<?php

namespace Esportsy;

class SeriesImport {

  public $mode;
  public $date;
  public $page = 1;

  public function run() {

    $api = new AbiosApi();
    $this->mode = $this->calcMode();

    // fetch last sync instance post
    $syncLast = SyncInstance::fetchLast('series', $this->mode);

    if( !$syncLast ) {
      $this->date = new \DateTime();
    } else {

      $today = new \DateTime();
      $this->date = \DateTime::createFromFormat( 'Y-m-d', $syncLast->dateImport );

      // advance to next day if all pages imported
      if( $syncLast->currentPage == $syncLast->lastPage ) {
        $this->date->modify('+1 day');
      } else {
        $page = $syncLast->currentPage +1;
      }

    }

    $range = $this->calcTimeRange();
    $response = $api->fetchSeriesByDateRange( $range['start'], $range['end'], $this->page );

    if( $response->code != 200 ) {
      return;
    }

    // insert each match as a post
    foreach( $response->data as $seriesData ) {
      $this->makeSeries( $seriesData );
    }

    // update tracker
    $this->updateTracker( $response );

  }

  public function calcMode() {

    $syncLast = SyncInstance::fetchLast('series', 'import');
    if( !$syncLast ) {
      return 'import';
    }

    $importRangeComplete = $this->importRangeComplete( $syncLast );
    if( $importRangeComplete ) {
      return 'refresh';
    }

    // range incomplete
    return 'import';

  }

  public function importRangeComplete( $syncLast ) {
    $today = new \DateTime();
    $importDate = \DateTime::createFromFormat( 'Y-m-d', $syncLast->dateImport );
    $daysDiff = $today->diff($importDate)->days; // 1
    if( $daysDiff >= 14 ) {
      return true;
    }
    return false;
  }

  public function calcTimeRange() {
    $range = [];
    $beginOfDay = clone $this->date;
    $beginOfDay->modify('today');
    $endOfDay = clone $beginOfDay;
    $endOfDay->modify('tomorrow');
    $endOfDateTimestamp = $endOfDay->getTimestamp();
    $endOfDay->setTimestamp($endOfDateTimestamp - 1);
    $range['start'] = $beginOfDay->format( 'Y-m-d\TH:i:s\Z' );
    $range['end'] = $endOfDay->format( 'Y-m-d\TH:i:s\Z' );
    return $range;
  }


  public function makeSeries( $seriesData ) {

    $series = new Series();

    $series->seriesId = $seriesData->id;
    $series->title = $seriesData->title;
    $series->start = $seriesData->start;
    if( !is_null($seriesData->end)) {
      $series->isOver = true;
    }
    $series->gameId = $seriesData->game->id;
    $series->gameTitle = $seriesData->game->title;
    $series->gameLogo = $seriesData->game->images->square;
    $series->tournamentId = $seriesData->tournament->id;
    $series->tournamentTitle = $seriesData->tournament->title;

    if( isset( $seriesData->rosters[0]->teams[0]->name )) {
      $series->teamA = $seriesData->rosters[0]->teams[0]->name;
    } else {
      $series->teamA = "TBD";
    }

    if( isset( $seriesData->rosters[1]->teams[0]->name )) {
      $series->teamB = $seriesData->rosters[1]->teams[0]->name;
    } else {
      $series->teamB = "TBD";
    }

    $series->save();

  }

  public function updateTracker( $response ) {
    $sync = new SyncInstance();
    $sync->routine = "series_" . $this->mode;
    $sync->timestamp = time();
    $sync->dateImport = $this->date->format('Y-m-d');
    $sync->lastPage = $response->last_page;
    $sync->currentPage = $response->current_page;
    $sync->save();
  }

}

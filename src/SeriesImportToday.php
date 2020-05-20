<?php

namespace Esportsy;

class SeriesImportToday extends SeriesImport {

  public function run() {

    $api = new AbiosApi();
    $this->mode = 'today';

    $this->date = new \DateTime();

    $syncLast = SyncInstance::fetchLast('series', $this->mode);
    if( $syncLast ) {
      $this->page = $syncLast->currentPage +1;
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

  public function calcTimeRange() {
    $range = [];
    $start = clone $this->date;
    $start->modify('-6 hours');
    $end = clone $this->date;
    $end->modify('+3 hours');
    $range['start'] = $start->format( 'Y-m-d\TH:i:s\Z' );
    $range['end'] = $end->format( 'Y-m-d\TH:i:s\Z' );
    return $range;
  }

}

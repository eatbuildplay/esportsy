<?php

namespace Esportsy;

class ShortcodeCalendar extends Shortcode {

  public $tag = 'espy-calendar';

  public function __construct() {
    $this->templateName = 'calendar';
    $this->templateData = [
      'calendarData' => $this->fetchCalendarData()
    ];
    parent::__construct();
  }

  public function fetchCalendarData() {
    $calendarData = new \stdClass;
    $api = new AbiosApi();

    // extract matches from series data
    $seriesList = $api->fetchSeriesList();

    $matches = [];
    foreach( $seriesList as $series ) {

      if( !empty($series->matches)) {

        foreach( $series->matches as $match ) {

          // add more data to the matches
          $match->series_title = $series->title;
          $match->tournament_title = $series->tournament->title;
          $matches[] = $match;
          
        }
      }
    }

    $calendarData->matches = $matches;
    $calendarData->games = $api->fetchGamesList();
    return $calendarData;
  }

}

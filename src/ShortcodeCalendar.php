<?php

namespace Esportsy;

class ShortcodeCalendar extends Shortcode {

  public $tag = 'espy-calendar';

  public function __construct() {

    add_action( 'wp_ajax_espy_calendar_draw', array( $this, 'jxDraw'));
    $this->templateName = 'calendar';
    parent::__construct();

  }

  public function loadData() {
    return [
      'calendarData' => $this->fetchCalendarData()
    ];
  }

  public function jxDraw() {

    $calendarData = $this->fetchCalendarData();

    $code = 200;
    $message = 'okay';

    $template = new Template;
    $template->name = 'calendar-match';
    $html = '';

    foreach( $calendarData->matches as $match ) {
      $template->data = [
        'match' => $match
      ];
      $html .= $template->get();
    }

    $response = array(
      'code'    => $code,
      'message' => $message,
      'html'    => $html
    );
    print json_encode( $response );

    wp_die();

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
          $match->game = $series->game;
          $match->start = $series->start;
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

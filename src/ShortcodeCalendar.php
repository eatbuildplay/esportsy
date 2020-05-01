<?php

namespace Esportsy;

class ShortcodeCalendar extends Shortcode {

  public $tag = 'espy-calendar';

  public function __construct() {

    add_action( 'wp_ajax_espy_calendar_draw', array( $this, 'jxDraw'));
    add_action( 'wp_ajax_nopriv_espy_calendar_draw', array( $this, 'jxDraw'));
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
    $template->name = 'calendar-series';
    $html = '';

    foreach( $calendarData->series as $series ) {
      $template->data = [
        'series' => $series
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

    $gameId = 2;

    $calendarData = new \stdClass;
    $calendarData->series = Series::fetch( $gameId );
    $calendarData->games = Game::fetchAll();
    return $calendarData;

  }

}

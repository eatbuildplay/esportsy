<?php

namespace Esportsy;

class Shortcodecalendar extends Shortcode {

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
    $calendarData->games = $api->fetchGamesList();
    return $calendarData;
  }

}

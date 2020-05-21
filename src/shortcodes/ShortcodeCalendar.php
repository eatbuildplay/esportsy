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
      'games' => Game::fetchAll()
    ];
  }

  public function jxDraw() {

    $params = $_POST['params'];
    $games = $params['filters']['games'];
    $schedule = $params['filters']['schedule'];
    $shortcode = $params['shortcode'];

    $seriesList = $this->fetchSeriesList( $games, $schedule );

    $code = 200;
    $message = 'okay';

    $template = new Template;

    if( $shortcode == 'home' ) {
      $template->name = 'calendar-home-series';
    } else {
      $template->name = 'calendar-series';
    }

    $html = '';

    foreach( $seriesList as $series ) {
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

  public function fetchSeriesList( $games, $schedule ) {

    $series = Series::fetch( $games, $schedule );
    return $series;

  }

}

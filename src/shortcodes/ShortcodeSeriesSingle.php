<?php

namespace Esportsy;

class ShortcodeSeriesSingle extends Shortcode {

  public $tag = 'espy-series-single';

  public function __construct() {

    $this->templateName = 'series-single';
    parent::__construct();

  }

  public function loadData() {

    global $post;
    $series = \Esportsy\Series::loadFromPost( $post );

    if( !$series->data ) {
      $api = new \Esportsy\AbiosApi();
      $series->data = $api->fetchSeries( $series->seriesId );
    }

    $series->loadStreams( $series->data->casters );

    return [
      'series' => $series
    ];

  }

}

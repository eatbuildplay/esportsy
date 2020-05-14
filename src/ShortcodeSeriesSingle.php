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

    $api = new \Esportsy\AbiosApi();
    $series->extra = $api->fetchSeries( $series->seriesId );

    return [
      'series' => $series
    ];

  }

}

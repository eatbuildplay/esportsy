<?php

namespace Esportsy;

class ShortcodeSeriesList extends Shortcode {

  public $tag = 'espy-series-list';

  public function __construct() {
    $this->templateName = 'series-list';
    $this->templateData = [
      'seriesList' => $this->fetchSeriesList()
    ];
    parent::__construct();
  }

  public function fetchseriesList() {
    $api = new AbiosApi();
    return $api->fetchSeriesList();
  }

}

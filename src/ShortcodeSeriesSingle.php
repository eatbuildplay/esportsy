<?php

namespace Esportsy;

class ShortcodeSeriesSingle extends Shortcode {

  public $tag = 'espy-series-single';

  public function __construct() {

    $this->templateName = 'series-single';
    parent::__construct();

  }

  public function loadData() {
    return [];
  }

}

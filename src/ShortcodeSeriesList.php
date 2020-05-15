<?php

namespace Esportsy;

class ShortcodeSeriesList extends Shortcode {

  public $tag = 'espy-series-list';

  public function __construct() {
    $this->templateName = 'series-list';
    $this->templateData = [];
    parent::__construct();
  }

}

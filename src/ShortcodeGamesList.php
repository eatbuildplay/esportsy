<?php

namespace Esportsy;

class ShortcodeGamesList extends Shortcode {

  public $tag = 'espy-games-list';

  public function __construct() {
    $this->templateName = 'games-list';
    $this->templateData = [
      'games' => $this->fetchGamesList()
    ];
    parent::__construct();
  }

  public function fetchGamesList() {
    $api = new AbiosApi();
    return $api->fetchGamesList();
  }

}

<?php

namespace Esportsy;

class ShortcodeGamesList extends Shortcode {

  public $tag = 'espy-games-list';
  public $templateName = 'games-list';

  public function __construct() {
    parent::__construct();
  }

  public function loadData() {
    return [
      'games' => $this->fetchGamesList()
    ];
  }

  public function fetchGamesList() {
    $api = new AbiosApi();
    return $api->fetchGamesList();
  }

}

<?php

namespace Esportsy;

class ShortcodeCalendarHome extends Shortcode {

  public $tag = 'espy-calendar-home';

  public function __construct() {

    $this->templateName = 'calendar-home';
    parent::__construct();

  }

  public function loadData() {
    return [
      'games' => Game::fetchAll()
    ];
  }

}

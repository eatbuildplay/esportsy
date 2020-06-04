<?php

namespace Esportsy;

class ShortcodeCalendarSidebar extends Shortcode {

  public $tag = 'espy-calendar-sidebar';

  public function __construct() {

    $this->templateName = 'calendar-sidebar';
    parent::__construct();

  }

  public function loadData() {
    return [
      'games' => Game::fetchAll()
    ];
  }

}

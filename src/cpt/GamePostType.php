<?php

namespace Esportsy;

class GamePostType extends PostType {

  public $menuPosition = 13000;
  public $showInMenu = false;

  public function getKey() {
    return 'game';
  }

}

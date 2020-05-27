<?php

namespace Esportsy;

class SeriesPostType extends PostType {

  public $menuPosition = 20;
  public $showInMenu = false;

  public function getKey() {
    return 'series';
  }

  public function getNamePlural() {
    return $this->getNameSingular();
  }

}

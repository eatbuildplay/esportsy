<?php

namespace Esportsy;

class LogPostType extends PostType {

  public $menuPosition = 13000;
  public $showInMenu = false;

  public function getKey() {
    return 'log';
  }

}

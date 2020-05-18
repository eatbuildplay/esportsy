<?php

namespace Esportsy;

class LogPostType extends PostType {

  public $menuPosition = 15000;

  public function getKey() {
    return 'log';
  }

}

<?php

namespace Esportsy;

class SeriesPostType extends PostType {

  public $menuPosition = 20;

  public function getKey() {
    return 'series';
  }

}

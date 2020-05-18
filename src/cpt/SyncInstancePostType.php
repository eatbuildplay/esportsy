<?php

namespace Esportsy;

class SyncInstancePostType extends PostType {

  public $menuPosition = 14000;

  public function getKey() {
    return 'sync_instance';
  }

}

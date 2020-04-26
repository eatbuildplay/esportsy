<?php

namespace Esportsy;

class Match {

  public $postId = 0;
  public $matchId = 0;
  public $seriesTitle;

  public function create() {

    $params = [

    ];
    $postId = wp_insert_post( $params );

  }

  public function update() {

  }

  public function save() {

  }

}

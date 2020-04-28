<?php

namespace Esportsy;

class Match {

  public $postId = 0;
  public $matchId = 0;

  public $seriesId = 0;
  public $seriesTitle;

  public $tournamentId = 0;
  public $tournamentTitle;

  public $gameId = 0;
  public $gameTitle;
  public $gameLogo;

  public function create() {

    $params = [
      'post_type'   => 'match',
      'post_title'  => $this->matchId
    ];
    $postId = wp_insert_post( $params );

  }

  public function update() {

  }

  public function save() {

  }

  public function fetch() {

    

  }

}

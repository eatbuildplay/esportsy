<?php

namespace Esportsy;

class Match {

  public $id = 0;
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
    return $postId;

  }

  public function update() {



  }

  public function save() {

    if( $this->id > 0 ) {
      $this->update();
    } else {
      $this->id = $this->create();
      if( !$this->id ) {
        return false;
      }
    }

    update_post_meta( $this->id, 'game_id', $this->gameId );
    update_post_meta( $this->id, 'match_id', $this->matchId );

  }

  public static function fetch( $gameId = 0 ) {

    $query = [
      'post_type' => 'match',
      'posts_per_page' => -1,
    ];

    if( $gameId ) {
      $query['meta_query'] = [
        [
          'key'   => 'game_id',
          'value' => $gameId
        ]
      ];
    }

    $matchPosts = get_posts( $query );

    $matches = [];
    foreach( $matchPosts as $matchPost ) {
      $match = new Match;
      $fields = get_post_meta( $matchPost->ID );
      $match->id = $matchPost->ID;
      $match->title = $matchPost->post_title;
      $match->matchId = get_post_meta( $matchPost->ID, 'match_id', 1 );
      $match->gameId = get_post_meta( $matchPost->ID, 'game_id', 1 );
      $matches[] = $match;
    }

    return $matches;

  }

}

<?php

namespace Esportsy;

class Game {

  public $id;
  public $abiosId;
  public $title;
  public $titleLong;
  public $imageSquare;

  public fetchAll() {

    $gamePosts = get_posts([
      'post_type' => 'game'
    ]);

    $games = [];
    foreach( $gamePosts as $gamePost ) {
      $game = new Game;
      $fields = get_fields( $gamePost );
      $game->id = $gamePost->ID;
      $game->abiosId = $fields['abios_id'];
      $game->title = $gamePost->post_title;
      $game->titleLong = $fields['titleLong'];
      $game->imageSquare = $fields['image_square'];
      $games[] = $game;
    }
    return $games;
    
  }

}

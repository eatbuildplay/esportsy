<?php

namespace Esportsy;

class Game {

  public $id;
  public $abiosId;
  public $title;
  public $titleLong;
  public $imageSquare;

  public static function fetchAll() {

    $gamePosts = get_posts([
      'post_type' => 'game',
      'posts_per_page' => -1
    ]);

    $games = [];
    foreach( $gamePosts as $gamePost ) {
      $game = new Game;
      $fields = get_fields( $gamePost );
      $game->id = $gamePost->ID;
      $game->abiosId = $fields['abios_id'];
      $game->title = $gamePost->post_title;
      $game->titleLong = $fields['long_title'];
      $game->imageSquare = $fields['image_square'];
      $games[] = $game;
    }
    return $games;

  }

}

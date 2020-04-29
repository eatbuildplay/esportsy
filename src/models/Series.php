<?php

namespace Esportsy;

class Series {

  public $id = 0;
  public $seriesId = 0;
  public $title;
  public $tournamentId = 0;
  public $tournamentTitle;
  public $gameId = 0;
  public $gameTitle;
  public $gameLogo;

  public function create() {

    $params = [
      'post_type'   => 'series',
      'post_title'  => $this->title,
      'post_status' => 'publish'
    ];
    $postId = wp_insert_post( $params );
    $this->id = $postId;
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
    update_post_meta( $this->id, 'series_id', $this->seriesId );

  }

  public static function fetch( $gameId = 0, $past = false ) {

    $query = [
      'post_type' => 'Series',
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

    if( $past ) {
      // add meta query for past current timestamp
    }

    $seriesPosts = get_posts( $query );

    $series = [];
    foreach( $seriesPosts as $seriesPost ) {
      $series = new Series;
      $fields = get_post_meta( $seriesPost->ID );
      $series->id = $seriesPost->ID;
      $series->title = $seriesPost->post_title;
      $series->seriesId = get_post_meta( $seriesPost->ID, 'series_id', 1 );
      $series->gameId = get_post_meta( $seriesPost->ID, 'game_id', 1 );
      $series[] = $series;
    }

    return $series;

  }

}
<?php

namespace Esportsy;

class Series {

  public $id = 0;
  public $seriesId = 0;
  public $title;
  public $start;
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

    update_post_meta( $this->id, 'series_id', $this->seriesId );
    update_post_meta( $this->id, 'title', $this->seriesId );
    update_post_meta( $this->id, 'start', $this->start );
    update_post_meta( $this->id, 'tournament_id', $this->tournamentId );
    update_post_meta( $this->id, 'tournament_title', $this->tournamentTitle );
    update_post_meta( $this->id, 'game_id', $this->gameId );
    update_post_meta( $this->id, 'game_title', $this->gameTitle );
    update_post_meta( $this->id, 'game_logo', $this->gameLogo );

  }

  public static function fetch( $games = [], $schedule = 'upcoming' ) {

    $query = [
      'post_type' => 'Series',
      'posts_per_page' => 10,
      'meta_query' => []
    ];

    if( is_array( $games )) {
      $query['meta_query'][] = [
        'key'     => 'game_id',
        'value'   => $games,
        'compare' => 'IN'
      ];
    }

    if( $schedule == 'upcoming' ) {
      $query['meta_query'][] = [
        'key'     => 'start',
        'value'   => date('Y-m-d h:i:s'),
        'compare' => '>='
      ];
    } elseif( $schedule == 'results' ) {
      $query['meta_query'][] = [
        'key'     => 'start',
        'value'   => date('Y-m-d h:i:s'),
        'compare' => '<='
      ];
    }

    $seriesPosts = get_posts( $query );

    $seriesList = [];
    foreach( $seriesPosts as $seriesPost ) {
      $series = new Series;
      $fields = get_post_meta( $seriesPost->ID );
      $series->id = $seriesPost->ID;
      $series->title = $seriesPost->post_title;
      $series->start = get_post_meta( $seriesPost->ID, 'start', 1 );
      $series->seriesId = get_post_meta( $seriesPost->ID, 'series_id', 1 );
      $series->gameId = get_post_meta( $seriesPost->ID, 'game_id', 1 );
      $series->tournamentTitle = get_post_meta( $seriesPost->ID, 'tournament_title', 1 );
      $series->gameLogo = get_post_meta( $seriesPost->ID, 'game_logo', 1 );
      $series->gameTitle = get_post_meta( $seriesPost->ID, 'game_title', 1 );
      $seriesList[] = $series;
    }

    return $seriesList;

  }

}

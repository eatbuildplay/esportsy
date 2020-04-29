<?php

namespace Esportsy;

class SyncInstance {

  public $id = 0;
  public $routine;
  public $timestamp;
  public $dateImport;
  public $lastPage;
  public $currentPage;

  public function __construct() {
    $this->timestamp = time();
  }

  public function save() {

    $args = [
      'post_type'   => 'sync_instance',
      'post_title'  => 'Series import ' . $this->timestamp,
      'post_status' => 'publish'
    ];
    $this->id = wp_insert_post( $args );

    update_post_meta( $this->id, 'timestamp', $this->timestamp );
    update_post_meta( $this->id, 'routine', $this->routine );
    update_post_meta( $this->id, 'date_import', $this->dateImport );
    update_post_meta( $this->id, 'last_page', $this->lastPage );
    update_post_meta( $this->id, 'current_page', $this->currentPage );

  }

  public static function fetchLast() {

    $syncInstancePosts = get_posts([
      'post_type' => 'sync_instance'
    ]);

    if( empty( $syncInstancePosts )) {
      return false;
    }

    $post = $syncInstancePosts[0];

    $syncInstance = new SyncInstance;

    $syncInstance->id = $post->ID;
    $syncInstance->timestamp = get_post_meta( $syncInstance->id, 'timestamp', 1 );
    $syncInstance->routine = get_post_meta( $syncInstance->id, 'routine', 1 );
    $syncInstance->dateImport = get_post_meta( $syncInstance->id, 'date_import', 1 );
    $syncInstance->lastPage = get_post_meta( $syncInstance->id, 'last_page', 1 );
    $syncInstance->currentPage = get_post_meta( $syncInstance->id, 'current_page', 1 );

    return $syncInstance;

  }

}

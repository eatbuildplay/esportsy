<?php

namespace Esportsy;

class ShortcodeTournamentList extends Shortcode {

  public $tag = 'espy-tournament-list';

  public function __construct() {
    $this->templateName = 'tournament-list';
    $this->templateData = [
      'tournaments' => $this->fetchTournamentList()
    ];
    parent::__construct();
  }

  public function fetchTournamentList() {
    $api = new AbiosApi();
    return $api->fetchTournamentList();
  }

}

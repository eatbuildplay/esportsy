<?php

namespace Esportsy;

class ShortcodeTeamsList extends Shortcode {

  public $tag = 'espy-teams-list';

  public function __construct() {
    $this->templateName = 'teams-list';
    $this->templateData = [
      'team' => $this->fetchTeamsList()
    ];
    parent::__construct();
  }

  public function fetchTeamsList() {
    $api = new AbiosApi();
    return $api->fetchTeamsList();
  }

}

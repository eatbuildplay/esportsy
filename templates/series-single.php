<?php

global $post;
$series = \Esportsy\Series::loadFromPost( $post );

$api = new \Esportsy\AbiosApi();
$seriesResponse = $api->fetchSeries( $series->seriesId );

?>

<div class="series-single">

  <img src="<?php print $series->gameLogo; ?>" />

  <h1 class="tournament-title">
    <?php print $series->tournamentTitle; ?>
  </h1>

  <h3><?php print $series->title; ?> / <?php print $series->start; ?></h3>

  <div class="matchup">
    <div class="team-a">
      <?php print $series->teamA; ?>
    </div>
    <div class="matchup-score">3 - 1</div>
    <div class="team-b">
      <?php print $series->teamB; ?>
    </div>
  </div>

</div>

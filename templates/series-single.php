<?php

global $post;
$series = \Esportsy\Series::loadFromPost( $post );

$api = new \Esportsy\AbiosApi();
$series->extra = $api->fetchSeries( $series->seriesId );

?>

<div class="series-single">

  <img src="<?php print $series->gameLogo; ?>" />

  <h1 class="tournament-title">
    <?php print $series->tournamentTitle; ?>
  </h1>

  <h3><?php print $series->title; ?> / Best of <?php print $series->extra->bestOf; ?> / <?php print $series->start; ?></h3>

  <div class="matchup">
    <div class="team-a">
      <?php print $series->teamA; ?>
      <img src="<?php print $series->extra->rosters[0]->teams[0]->images->default; ?>" />
    </div>
    <div class="matchup-score">
      <?php if( $series->extra->scores ) {
          $scores = (array) $series->extra->scores;
          $scores = array_values( $scores );
          print $scores[0] . ' - ' . $scores[1];
        } else {
          print 'Scores not available yet.';
        }

      ?>
    </div>
    <div class="team-b">
      <?php print $series->teamB; ?>
      <img src="<?php print $series->extra->rosters[1]->teams[0]->images->default; ?>" />
    </div>
  </div>

  <!-- Available Streams -->
  <h2>Available Streams</h2>
  <?php foreach( $series->extra->casters as $caster ): ?>
    <a href="<?php print $caster->url; ?>">
      <div>
        <img src="<?php print $caster->country->images->default; ?>" />
        <h2><?php print $caster->name; ?></h2>
      </div>
    </a>
  <?php endforeach; ?>

  <!-- Tournament Info -->
  <h2>Tournament Info</h2>
  <h5><?php print str_replace(' ', '', $series->extra->tournament->prizepool_string->total); ?></h5>
  <h3><?php print $series->extra->tournament->title; ?></h3>
  <img src="<?php print $series->extra->tournament->images->default; ?>" style="max-width: 200px;" />
  <h4><?php print $series->extra->tournament->start; ?> - <?php print $series->extra->tournament->end; ?></h4>
  <p><?php print $series->extra->tournament->description; ?></p>


</div>

<pre>
<?php
var_dump( $series->extra->rosters[1]->teams[0] );
?>
</pre>

<div class="series-single">

  <!-- series header -->
  <header>
    <div class="col-first">
      <?php $series->renderGameLogo(); ?>
    </div>
    <div>
      <h1 class="tournament-title">
        <?php print $series->tournamentTitle; ?>
      </h1>
    </div>
    <div class="col-third">
      <?php print $series->title; ?> / Best of <?php print $series->extra->bestOf; ?> / <?php print $series->start; ?>
    </div>
  </header>

  <!-- series matchup and result -->
  <div class="series-matchup">

    <div class="matchup-team team-a">
      <h3><?php print $series->teamA; ?></h3>
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

    <div class="matchup-team team-b">
      <h3><?php print $series->teamB; ?></h3>
      <img src="<?php print $series->extra->rosters[1]->teams[0]->images->default; ?>" />
    </div>
  </div>

  <!-- Available Streams -->
  <div class="series-streams">
    <h2 class="series-stream-title">Available Streams</h2>
    <?php foreach( $series->extra->casters as $caster ): ?>
      <a href="<?php print $caster->url; ?>">
        <div class="series-caster">
          <img src="<?php print $caster->country->images->default; ?>" />
          <h2><?php print $caster->name; ?></h2>
        </div>
      </a>
    <?php endforeach; ?>

    <?php

      $caster = $series->extra->casters[0];
      $casterStream = $caster->stream;

    ?>

    <!-- Twitch Embed -->
    <iframe
      src="https://player.twitch.tv/?channel=<?php print $casterStream->username; ?>"
      height="720"
      width="1280"
      frameborder="0"
      scrolling="no"
      allowfullscreen="true">
    </iframe>

  </div>

  <!-- Tournament Info -->
  <div class="series-tournament-info">

    <div class="col-first">
      <h2 class="series-tournament-info-title">Tournament Info</h2>
    </div>

    <div class="col-second">
      <img src="<?php print $series->extra->tournament->images->default; ?>" />
      <h3><?php print str_replace(' ', '', $series->extra->tournament->prizepool_string->total); ?></h3>
    </div>

    <div class="col-third">
      <h1><?php print $series->extra->tournament->title; ?></h1>
      <h4>From <?php print $series->extra->tournament->start; ?> to <?php print $series->extra->tournament->end; ?></h4>
      <p><?php print $series->extra->tournament->description; ?></p>
    </div>

  </div>

</div>

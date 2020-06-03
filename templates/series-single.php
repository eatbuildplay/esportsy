
<div class="series-single">

  <!-- series header -->
  <header class="main">
    <div class="col-first">
      <?php $series->renderGameLogo(); ?>
    </div>
    <div class="col-second">
      <h1 class="tournament-title">
        <?php print $series->tournamentTitle; ?>
      </h1>
    </div>
    <div class="col-third">
      <?php print $series->title; ?> / Best of <?php print $series->data->bestOf; ?> / <span class="datetime"><?php print $series->start; ?></span>
    </div>
  </header>

  <!-- series matchup and result -->
  <div class="series-matchup">

    <div class="matchup-team team-a">
      <h3><?php $series->renderTeamName('a'); ?></h3>
      <img src="<?php $series->renderTeamImage('a') ?>" />
    </div>

    <div class="matchup-score">
      <?php if( $series->data->scores ) {
          $scores = (array) $series->data->scores;
          $scores = array_values( $scores );
          print $scores[0] . ' - ' . $scores[1];
        } else {
          print 'Scores not available yet.';
        }
      ?>
    </div>

    <div class="matchup-team team-b">
      <h3><?php $series->renderTeamName('b'); ?></h3>
      <img src="<?php $series->renderTeamImage('b') ?>" />
    </div>
  </div>

  <!-- Betting -->
  <?php
    $oddsList = $series->data->sportsbook_odds;
    if( !empty( $oddsList )) :
  ?>
    <div class="series-odds series-section">

    <header>
      <h2>Place Your Bets</h2>
    </header>

    <div id="odds-switch">
      <h5>Decimal Odds</h5><input type="checkbox" id="switch" /><label for="switch">Decimal Odds</label>
    </div>


    <ul class="series-odds-list">
    <?php

      if( !empty( $oddsList )) :
        foreach( $oddsList as $odds ) :
          $moneyline = $odds->moneyline;
    ?>


      <li data-bet-url="<?php print $odds->link; ?>">
        <div class="odds-item"><?php print $moneyline->home; ?></div>
        <div class="sportbook-site"><?php print $odds->sportsbook; ?></div>
        <div class="odds-item"><?php print $moneyline->away; ?></div>
      </li>

    <?php endforeach; endif; ?>
    </ul>

  </div>
  <?php endif; // close check if any odds available ?>

  <!-- Available Streams -->
  <div class="series-streams series-section">
    <header>
      <h2 class="series-stream-title">Event Live Stream</h2>
    </header>
    <div class="stream-wrap">
      <?php $series->renderStream(); ?>
    </div>
  </div>

  <!-- Tournament Info -->
  <div class="series-tournament-info series-section">

    <header>
      <h2 class="series-tournament-info-title">Tournament Info</h2>
    </header>

    <div class="series-section-content">

      <div class="col-first">
        <img src="<?php print $series->data->tournament->images->default; ?>" />
      </div>

      <div class="col-second">
        <h1><?php print $series->data->tournament->title; ?></h1>
        <h3><?php print str_replace(' ', '', $series->data->tournament->prizepool_string->total); ?></h3>
        <h3>From <?php print $series->data->tournament->start; ?> to <?php print $series->data->tournament->end; ?></h3>
        <p><?php print $series->data->tournament->description; ?></p>
      </div>

    </div>

  </div>

</div>



<?php
  // var_dump( $series->data->rosters[1]->players[0] );
?>

<div class="series-single">

  <!-- series header -->
  <header class="main">
    <div class="col-first">
      <?php $series->renderGameLogo(); ?>
    </div>
    <div>
      <h1 class="tournament-title">
        <?php print $series->tournamentTitle; ?>
      </h1>
    </div>
    <div class="col-third">
      <?php print $series->title; ?> / Best of <?php print $series->extra->bestOf; ?> / <span class="datetime"><?php print $series->start; ?></span>
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

  <!-- Betting -->
  <div class="series-odds">

    <header>
      <h2>Place Your Bets</h2>
    </header>

    <?php
      $oddsList = $series->extra->sportsbook_odds;
    ?>

    <ul class="series-odds-list">
    <?php

      if( !empty( $oddsList )) :
        foreach( $oddsList as $odds ) :
          $moneyline = $odds->moneyline;
    ?>

      <li data-bet-url="<?php print $odds->link; ?>">
        <div><?php print $moneyline->home; ?></div>
        <div class="sportbook-site"><?php print $odds->sportsbook; ?></div>
        <div><?php print $moneyline->away; ?></div>
      </li>

    <?php endforeach; endif; ?>
    </ul>

    <?php

      // pass me $odds;

      // $odds['sportsbook'] uppercase it
      // $odds['link'] replace {affiliate_tag}
      // $moneyline = $odds['moneyline']
      // $moneyline['home']
      // $moneyline['home_bet_slip']
      // $moneyline['away']
      // $moneyline['away_bet_slip']

    ?>
  </div>

  <!-- Available Streams -->
  <div class="series-streams">
    <div class="col-first">
      <h2 class="series-stream-title">Event Live Stream</h2>
    </div>
    <div class="col-second">
      <?php $series->renderStream(); ?>
    </div>
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



<?php
  // var_dump( $series->extra );
?>

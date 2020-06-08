<article class="calendar-series" data-permalink="<?php print get_permalink( $series->id ); ?>">

  <div class="calendar-series-col">

    <div class="sidebar-col-1">
      <img src="<?php print $series->gameLogo; ?>" />
      <?php
        if( $series->live ) {
          print "<span class='live-flag'>LIVE NOW</span>";
        } else {
      ?>
        <span class="start-time"><?php print $series->start; ?></span>
      <?php } ?>
    </div>

    <div class="sidebar-col-2">
      <h5><?php print $series->tournamentTitle; ?></h5>
      <h1>
        <?php $series->renderTeamName('a') ?> <br />
          vs. <br /><?php print $series->renderTeamName('b') ?> &nbsp;
      </h1>
    </div>
    
  </div>

</article>

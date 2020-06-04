<div class="espy-calendar espy-calendar-home">

  <header>

    <div class="header-left">
      <ul class="top-menu">
        <li class="selected" data-schedule="upcoming">Upcoming</li>
        <li data-schedule="results">Results</li>
      </ul>
    </div>

    <div class="header-right">
      <ul class="filter-menu">
        <?php foreach( $games as $game ): ?>
          <li class="selected" data-game-id="<?php print $game->abiosId; ?>">
            <img src="<?php print $game->imageSquare; ?>" />
          </li>
        <?php endforeach; ?>
      </ul>
    </div>

  </header>

  <section id="calendar-canvas"></section>

  <a class="view-all-button" href="https://esportsy.com/calendar/">View All Matches</a>

</div>

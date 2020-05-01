<div class="espy-calendar">

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

</div>

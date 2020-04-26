<?php

/*
print '<pre>';
var_dump( $calendarData->matches[0]->rosters );
print '</pre>';
*/

?>


<div class="espy-calendar">

  <header>

    <div class="header-left">
      <ul class="top-menu">
        <li>Upcoming</li>
        <li>Results</li>
      </ul>
    </div>

    <div class="header-right">
      <ul class="filter-menu">
        <?php

          $supportedGames = [
            'Dota 2',
            'LoL',
            'CS:GO',
            'SCII',
            'OW',
            'Hearthstone',
            'FIFA'
          ];
          foreach( $calendarData->games as $game ):

            if( !in_array($game->title, $supportedGames) ) {
              continue;
            }

          ?>
          <li>
            <img src="<?php print $game->images->square; ?>" />
          </li>
        <?php endforeach; ?>
      </ul>
    </div>

  </header>

  <section id="calendar-canvas"></section>

</div>


<script>

(function($) {

  var calendar = {

    canvas: $('#calendar-canvas'),

    init: function() {

      $('.filter-menu li').on('click', function() {

        console.log('filtering...')

        calendar.draw();

      })

      $('.top-menu li').on('click', function() {

        console.log('switch...')

        calendar.draw();

      })

    },

    draw: function() {

      var params = {

      }

      data = {
        action: 'espy_calendar_draw',
        params: params
      }
      $.post( espy.ajaxurl, data, function( response ) {

        response = JSON.parse(response);
        console.log( response )

        calendar.canvas.html( response.html );

      });

    }

  }

  calendar.init()

})( jQuery );



</script>

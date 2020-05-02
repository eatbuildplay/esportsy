(function($) {

  var calendar = {

    canvas: $('#calendar-canvas'),

    init: function() {

      $(document).on('click', '.calendar-series', function() {

        window.location.href = espy.seriesBaseUrl + '/';

      });

      $('.filter-menu li').on('click', function() {

        // test if in show all mode with no filtering
        var showAll = false;
        var $gamesSelected = $('.filter-menu li.selected');
        var $gamesAll = $('.filter-menu li');
        if( $gamesSelected.length == $gamesAll.length ) {
          showAll = true;
        }

        // toggle selection of clicked game button
        var $gameBtn = $(this);
        if( $gameBtn.hasClass('selected')) {
          if( showAll ) {
            $gamesAll.removeClass('selected')
            $gameBtn.addClass('selected');
          } else {
            $gameBtn.removeClass('selected');
          }
        } else {
          $gameBtn.addClass('selected');
        }

        // if none selected after this choice, select all
        var $gamesSelected = $('.filter-menu li.selected');
        if( !$gamesSelected.length ) {
          $('.filter-menu li').addClass('selected')
        }

        calendar.draw();

      })

      /*
       * Schedule filter click handler
       */
      $('.top-menu li').on('click', function() {

        // set selected
        var $menuBtn = $(this);
        $('.top-menu li').removeClass('selected')
        $menuBtn.addClass('selected')

        calendar.draw();

      })

      /* init load */
      var calendarEl = $('.espy-calendar');
      if( calendarEl.length ) {
        calendar.draw();
      }

    },

    draw: function() {

      // setup games filter
      var gameFilter = [];
      var $gamesSelected = $('.filter-menu li.selected');
      $gamesSelected.each( function( index ) {
        gameFilter.push( $(this).data('game-id') )
      });

      // setup schedule filter
      var scheduleFilter = $('.top-menu li.selected').data('schedule');

      var params = {
        filters: {
          games: gameFilter,
          schedule: scheduleFilter
        }
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

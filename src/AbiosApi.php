<?php

namespace Esportsy;

class AbiosApi {

	public $baseUrl = 'https://api.abiosgaming.com/v2';
	public $token = '0a89946531255dec79cb5c26b0c2c2bc48862fc9008503bd202536fb6d02e7c4';

	/*
	 * Constructor sets the api key saved in settings
	 */
	public function __construct() {}

  public function fetchGamesList( $page = 1 ) {

		$token = $this->fetchToken();
		if( !$token ) {
			return;
		}

    $vars = [
			'access_token' 	=> $token,
			'page'					=> $page
		];

		$response = $this->call( '/games', 'get', $vars );

    if( $response->code == 200 ) {
			$games = $response->data;
      return $games;
    }

    return false;

  }

	public function fetchTeamsList() {
    $token = $this->fetchToken();
    $vars = [ 'access_token' => $token ];
    $response = $this->call( '/teams', 'get', $vars );
    $teams = $response->data->data;
    if( $response->code == 200 ) {
      return $teams;
    }
    return false;
  }

  public function fetchSeriesByDateRange( $start, $end, $page = 1 ) {

		$token = $this->fetchToken();

		// get list of games
		$gamePosts = get_posts([
			'post_type' => 'game',
			'posts_per_page' => -1
		]);
		$gameIds = [];
		foreach( $gamePosts as $gamePost ) {
			$abiosId = get_post_meta( $gamePost->ID, 'abios_id', 1 );
			$gameIds[] = $abiosId;
		}

    $vars = [
      'access_token' => $token,
      'with' => array(
				'matches',
				'tournament',
				'sportsbook_odds'
			),
			'games' => $gameIds,
			'starts_after' => $start,
			'starts_before' => $end,
			'page' => $page
    ];
    $response = $this->call( '/series', 'get', $vars );

    return $response;

  }

	public function fetchTournamentList() {
    $token = $this->fetchToken();
    $vars = [
      'access_token' => $token
    ];
    $response = $this->call( '/tournaments', 'get', $vars );
    $dataObjects = $response->data->data;
    if( $response->code == 200 ) {
      return $dataObjects;
    }
    return false;
  }

	public function fetchSeries( $seriesId ) {

		$token = $this->fetchToken();

    $vars = [
      'access_token' => $token,
			'with' => [
				'sportsbook_odds', 'matches', 'tournament', 'casters'
			]
    ];
    $response = $this->call( '/series/' . $seriesId, 'get', $vars );

    if( $response->code == 200 ) {
			return $response->data;
    }
    return false;

	}

	/*
	 * Make call to the Abios API
	*/
	public function call( $endpoint, $method = 'get', $vars = false ) {

    // set options for get to enable vars to be added
    if( $method == 'get' ) {

      $data = http_build_query($vars);
			$data = preg_replace('/%5B[0-9]+%5D/simU', '%5B%5D', $data);
      $url = $this->baseUrl . $endpoint . '?' . $data;
    } else {
      $url = $this->baseUrl . $endpoint;
    }

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
  		'User-Agent: PHP',
  		'Content-Type: application/x-www-form-urlencoded',
		));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		// set options for posts
		if( $method == 'post' ) {
			curl_setopt( $curl, CURLOPT_POST, 1 );
			curl_setopt( $curl, CURLOPT_POSTFIELDS, http_build_query($vars) );
		}

    // set options for put
		if( $method == 'put' ) {
			curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, "PUT");
			curl_setopt( $curl, CURLOPT_POSTFIELDS, http_build_query($vars) );
		}

		// set options for patch
		if( $method == 'patch' ) {
			curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, "PATCH");
			$varsJson = json_encode( $vars );
			curl_setopt( $curl, CURLOPT_POSTFIELDS, http_build_query($vars) );
		}

		// set options for delete
		if( $method == 'delete' ) {
			curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, "DELETE");
		}

		$response = new \stdClass;

		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

		$response->raw = curl_exec( $curl );
		$response->code = curl_getinfo( $curl, CURLINFO_HTTP_CODE );

		if( $response->code == 200 ) {

			$data = json_decode( $response->raw );
			if( isset( $data->id )) {

				// singular response
				$response->data = $data;

			} else {

				// list of objects
				$response->last_page = $data->last_page;
				$response->current_page = $data->current_page;
				$response->data = $data->data;

			}

		}

		return $response;

 	}

  public function fetchToken() {

		// return $this->token;

    $vars = [
      'grant_type' => 'client_credentials',
      'client_id'  => 'orilevi_340f7',
      'client_secret' => 'dde245ba-4b35-437a-bfe1-b879eb8fc537'
    ];

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $this->baseUrl . '/oauth/access_token' );
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
  		'User-Agent: PHP',
  		'Content-Type: application/x-www-form-urlencoded'
    ));
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt( $curl, CURLOPT_POST, 1 );
    $varsJson = json_encode( $vars );
    curl_setopt( $curl, CURLOPT_POSTFIELDS, http_build_query($vars) );

		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

		$response = new \stdClass;
    $response->raw = curl_exec( $curl );
    $response->data = json_decode( $response->raw );
    $response->code = curl_getinfo( $curl, CURLINFO_HTTP_CODE );

    if( $response->code == 200 ) {
      return $response->data->access_token;
    }

    return false;

  }

}

<?php

namespace Esportsy;

class AbiosApi {

	public $baseUrl = 'https://api.abiosgaming.com/v2';

	/*
	 * Constructor sets the api key saved in settings
	 */
	public function __construct() {}

	public function fetchMatches() {

    $seriesList = $this->fetchSeriesList();

    $matches = [];
    foreach( $seriesList as $series ) {

      if( !empty($series->matches)) {
        foreach( $series->matches as $match ) {

          // add series data to the matches
					$match->series = $series;
          $matches[] = $match;

        }
      }
    }

    return $matches;

  }

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

		var_dump( $response );

    $games = $response->data->data;
    if( $response->code == 200 ) {
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

  public function fetchSeriesList() {

    $token = $this->fetchToken();
    $vars = [
      'access_token' => $token,
      'with' => array(
				'matches',
				'tournament'
			),
			//'starts_after' => 'now',
			'page' => 2
    ];
    $response = $this->call( '/series', 'get', $vars );

    if( $response->code == 200 ) {
			$dataObjects = $response->data->data;
      return $dataObjects;
    }

    return false;

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
		$response->raw = curl_exec( $curl );
		$response->data = json_decode( $response->raw );
		$response->code = curl_getinfo( $curl, CURLINFO_HTTP_CODE );

		/*
     * Do logging here!
     */

		return $response;

 	}

  public function fetchToken() {

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

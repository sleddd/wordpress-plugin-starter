<?php namespace WpStarterPlugin\Base;

class API {
	
	/**
	 * API name.
	 *
	 * @var string
	 */
	public $name;


	/**
	 * Request url.
	 *
	 * @var string
	 */
	public $url;


	/**
	 * Authorization token.
	 *
	 * @var string
	 */
	private $token;


	/**
	 * Custom args.
	 *
	 * @link https://wp-kama.com/function/wp_remote_request
	 * @var  array
	 */
	private $args;


	/**
	 * Show errors.
	 *
	 * @var boolean
	 */
	private $show_errors;


	/**
	 * Cache response.
	 *
	 * @var boolean
	 */
	public $cache;


	/**
	 *  Cache timeout in minutes.
	 *
	 * @var int
	 */
	private $timeout;


	/**
	 * Constructor.
	 *
	 * @param string  $name
	 * @param string  $url
	 * @param string  $token
	 * @param array   $args
	 * @param boolean $show_errors
	 * @param boolean $cache
	 * @param int     $timeout
	 *
	 * @return void
	 */
	public function __construct( $name = '', $url, $token, $args, $show_errors = false, $cache = true, $timeout = 15 ) {
		$this->name        = $this->name = $name . '_api_request';
		$this->url    = $url;
		$this->token       = $token;
		$this->args        = $args;
		$this->show_errors = $show_errors;
		$this->cache       = $cache;
		$this->timeout    = $timeout;
	}

	/**
	 * Add auth header.
	 *
	 * @return array
	 */
	private function build_auth_headers() {
		return array_merge_recursive(
			array(
				'headers' => array(
					'Authorization' => 'Basic ' . $this->token,
				),
			),
			$this->args
		);
	}


	/**
	 * Make remote get request.
	 *
	 * @return array $response
	 */
	public function remote_get() {

		$response = false;

		// Build auth headers if token has been set.
		if ( $this->token ) {
			$this->build_auth_headers();
		}

		// Check cache first.
		if ( $this->cache ) {
			$response = get_transient( $this->name );
		}

		// Make request after checking cache.
		if ( ! $response ) {
			$response = wp_remote_request( $this->url, $this->args );
			$response = $this->handle_response( $response );
		}

		return $response;
	}


	/**
	 * Delete and set response transient.
	 *
	 * @param  array $response
	 * @return void
	 */
	private function cache_request( $response ) {
		delete_transient( $this->name );
		set_transient( $this->name, $response, $this->timeout * MINUTE_IN_SECONDS );
	}

	/**
	 * Handles received response.
	 *
	 * @param  array $response
	 * @return array $response
	 */
	private function handle_response( $response ) {
		if ( is_wp_error( $response ) ) {
			if ( $this->show_errors ) {
				throw new \ Exception( 'Request failed. ' . $response->errors['http_request_failed'][0] );
			}
			return false;
		}
		return $this->decode_response( $response );
	}

	/**
	 * Parses response JSON.
	 *
	 * @param  array $response
	 * @return array $decoded_response;
	 */
	private function decode_response( $response ) {

		$decoded_response = json_decode( $response['body'], true );

		if ( json_last_error() !== JSON_ERROR_NONE ) {
			$decoded_response = $response['body'];
		}

		if ( $this->cache ) {
			$this->cache_request( $decoded_response );
		}

		return $decoded_response;
	}
}

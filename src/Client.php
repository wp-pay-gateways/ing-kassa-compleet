<?php

/**
 * Title: ING Kassa Compleet client
 * Description:
 * Copyright: Copyright (c) 2005 - 2017
 * Company: Pronamic
 *
 * @author Reüel van der Steege
 * @version 1.0.0
 * @since 1.0.0
 */
class Pronamic_WP_Pay_Gateways_ING_KassaCompleet_Client {
	/**
	 * ING Kasse Compleet API endpoint URL
	 *
	 * @var string url
	 */
	const API_URL = 'https://api.kassacompleet.nl/v1/';

	//////////////////////////////////////////////////

	/**
	 * API Key
	 *
	 * @var string
	 */
	private $api_key;

	//////////////////////////////////////////////////

	/**
	 * Constructs and initalize an ING Kassa Compleet client object
	 */
	public function __construct( $api_key ) {
		$this->api_key = $api_key;
	}

	//////////////////////////////////////////////////

	/**
	 * Error
	 *
	 * @return WP_Error
	 */
	public function get_error() {
		return $this->error;
	}

	//////////////////////////////////////////////////

	/**
	 * Send request with the specified action and parameters
	 *
	 * @param string $endpoint
	 * @param string $method
	 * @param array  $data
	 */
	private function send_request( $endpoint, $method = 'POST', array $data = array() ) {
		$url = self::API_URL . $endpoint;

		$headers = array(
			'Authorization' => 'Basic ' . base64_encode( $this->api_key . ':' ),
		);

		if ( is_array( $data ) && ! empty( $data ) ) {
			$data = wp_json_encode( $data );

			$headers['Content-Type'] = 'application/json';
		}

		$return = wp_remote_request( $url, array(
			'method'    => $method,
			'headers'   => $headers,
			'body'      => $data,
		) );

		return $return;
	}

	/////////////////////////////////////////////////

	public function create_order( Pronamic_WP_Pay_Gateways_ING_KassaCompleet_OrderRequest $request ) {
		$result = null;

		$data = $request->get_array();

		$response = $this->send_request( 'orders/', 'POST', $data );

		$response_code = wp_remote_retrieve_response_code( $response );

		$body = wp_remote_retrieve_body( $response );

		// NULL is returned if the json cannot be decoded or if the encoded data is deeper than the recursion limit.
		$ing_result = json_decode( $body );

		if ( 201 === $response_code ) {
			if ( $ing_result && 'error' === $ing_result->status ) {
				$error_msg = $ing_result->transactions[0]->reason;
				$error     = $ing_result->transactions[0];
			} else {
				$result = $ing_result;
			}
		} else {
			$error_msg = '';
			$error     = '';

			if ( $ing_result ) {
				$error_msg = $ing_result->error->value;
				$error     = $ing_result->error;
			}

			if ( 401 === $response_code ) {
				// The default error message for an unauthorized API call does not mention the API key in any way.
				$error_msg .= ' Please check the API key.';
			}
		}

		if ( isset( $error_msg, $error ) ) {
			$this->error = new WP_Error( 'ing_kassa_compleet_error', $error_msg, $error );
		}

		return $result;
	}

	public function get_order( $order_id ) {
		$result = null;

		$response = $this->send_request( 'orders/' . $order_id . '/', 'GET' );

		$response_code = wp_remote_retrieve_response_code( $response );

		if ( 200 === $response_code ) {
			$body = wp_remote_retrieve_body( $response );

			// NULL is returned if the json cannot be decoded or if the encoded data is deeper than the recursion limit.
			$result = json_decode( $body );
		}

		return $result;
	}

	//////////////////////////////////////////////////

	/**
	 * Get issuers
	 *
	 * @return array
	 */
	public function get_issuers() {
		$issuers = false;

		$response = $this->send_request( 'ideal/issuers/', 'GET' );

		$response_code = wp_remote_retrieve_response_code( $response );

		if ( 200 === $response_code ) {
			$body = wp_remote_retrieve_body( $response );

			// NULL is returned if the json cannot be decoded or if the encoded data is deeper than the recursion limit.
			$result = json_decode( $body );

			if ( null !== $result ) {
				$issuers = array();

				foreach ( $result as $issuer ) {
					$id   = Pronamic_WP_Pay_XML_Security::filter( $issuer->id );
					$name = Pronamic_WP_Pay_XML_Security::filter( $issuer->name );

					$issuers[ $id ] = $name;
				}
			}
		} else {
			$body = wp_remote_retrieve_body( $response );

			$ing_result = json_decode( $body );

			$error_msg = $ing_result->error->value;

			if ( 401 === $response_code ) {
				// An unauthorized API call has nothing to do with the browser of the user in our case, remove to prevent confusion.
				$error_msg = str_replace( "You either supplied the wrong credentials (e.g. a bad password), or your browser doesn't understand how to supply the credentials required.", '', $error_msg );

				// The default error message for an unauthorized API call does not mention the API key in any way.
				$error_msg .= ' Please check the API key.';
			}

			$this->error = new WP_Error( 'ing_kassa_compleet_error', $error_msg, $ing_result->error );
		}

		return $issuers;
	}
}

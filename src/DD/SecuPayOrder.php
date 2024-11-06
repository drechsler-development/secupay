<?php

namespace DD;

use Exception;

class SecuPayOrder {

	const string API_URL_TEST      = 'https://connect-testing.secupay-ag.de/api/v2';
	const string API_AUTH_URL_TEST = 'https://connect-testing.secuconnect.com/oauth/token';

	const string API_URL_PROD      = 'https://connect.secucard.com/api/v2';
	const string API_AUTH_URL_PROD = 'https://connect.secuconnect.com/oauth/token';

	private string $apiURLAuth;
	private string $clientId;
	private string $clientSecret;
	private string $apiURL;
	private string $token;

	private static array $allowedStatusCodes = [200, 201, 204, 403];

	/**
	 * @param string $clientId
	 * @param string $clientSecret
	 * @param bool   $isTest
	 *
	 * @throws Exception
	 */
	public function __construct (string $clientId, string $clientSecret, bool $isTest = false) {

		if (session_status () === PHP_SESSION_NONE) {
			throw new Exception('A PHP Session must be started');
		}

		$this->clientId     = $clientId;
		$this->clientSecret = $clientSecret;
		$this->apiURL       = $isTest ? self::API_URL_TEST : self::API_URL_PROD;
		$this->apiURLAuth   = $isTest ? self::API_AUTH_URL_TEST : self::API_AUTH_URL_PROD;
		$this->GetToken ();

	}

	/**
	 * @param array $data
	 *
	 * @return array
	 * @throws Exception
	 */
	public function AddTransaction (array $data): array {

		$url = $this->apiURL . "/Smart/Transactions";

		return $this->SendCurlRequest ($url, true, $data);
	}

	/**
	 * @param string $id
	 *
	 * @return array
	 * @throws Exception
	 */
	public function GetTransaction (string $id) : array {

		$url = $this->apiURL . "/Smart/Transactions/". $id;

		return $this->SendCurlRequest ($url);

	}

	/**
	 * @param string $id
	 *
	 * @return array
	 * @throws Exception
	 */
	public function CaptureTransaction (string $id) : array {

		$url = $this->apiURL . "/Smart/Transactions/" . $id . "/start";

		return $this->SendCurlRequest($url, true);

	}

	/**
	 * @return void
	 * @throws Exception
	 */
	private function GetToken (): void {

		$url = $this->apiURLAuth;

		$data = [
			"grant_type"    => "client_credentials",
			"client_id"     => $this->clientId,
			"client_secret" => $this->clientSecret
		];

		$ch = curl_init ($url);

		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($ch, CURLOPT_POST, true);
		curl_setopt ($ch, CURLOPT_HTTPHEADER, [
			"Content-Type: application/json",
			"Accept: application/json"
		]);

		curl_setopt ($ch, CURLOPT_POSTFIELDS, json_encode ($data));

		$response = curl_exec ($ch);

		if (curl_errno ($ch)) {
			curl_close ($ch);
			throw new Exception(curl_error ($ch));
		} else {
			//Check if $response is valid JSON
			$response = json_decode ($response);
			if (json_last_error () === JSON_ERROR_NONE) {
				$this->token = $response->access_token;
			} else {
				curl_close ($ch);
				throw new Exception($response);
			}
		}

		curl_close ($ch);

	}

	/**
	 * @param string     $url
	 * @param bool       $isPost
	 * @param array|null $data
	 *
	 * @return array
	 * @throws Exception
	 */
	private function SendCurlRequest (string $url, bool $isPost = false, array $data = null) : array {

		$ch = curl_init ($url);

		$this->SetCurlHeadersAndOptions ($ch, $isPost, !is_null ($data));

		if($isPost && !is_null ($data)) {
			curl_setopt ($ch, CURLOPT_POSTFIELDS, json_encode ($data));
		}


		// Anfrage ausführen und Antwort erhalten
		$response = curl_exec ($ch);

		if (curl_errno ($ch)) {
			curl_close ($ch);
			throw new Exception(curl_error ($ch));
		} else {

			$httpStatus = curl_getinfo ($ch, CURLINFO_HTTP_CODE);
			if (!in_array ($httpStatus, self::$allowedStatusCodes)) {
				throw new Exception("Fehler: HTTP-Status $httpStatus<br>" . $response);
			}

			$returnArray = json_decode ($response, true);
			if (json_last_error () !== JSON_ERROR_NONE) {
				return ["error" => $response];
			}
		}

		curl_close ($ch);

		return $returnArray;

	}

	/**
	 * @param $ch
	 * @param bool $isPost
	 * @param bool $containsBody
	 *
	 * @return void
	 */
	private function SetCurlHeadersAndOptions ($ch, bool $isPost = false, bool $containsBody = false) : void {

		$headers = [
			"Authorization: Bearer $this->token",
			"Accept: application/json"
		];

		if($isPost && $containsBody){

			$headers[] = "Content-Type: application/json";

		}

		curl_setopt ($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);

		if ($isPost) {
			curl_setopt ($ch, CURLOPT_POST, true);
		}

	}



}

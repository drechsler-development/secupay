<?php

namespace DD;

class SecuPayAddress {
	public string $street;
	public string $street_number;
	public string $additional_address_data;
	public string $postal_code;
	public string $city;
	public string $country;

	public function __construct (
		string $street,
		string $street_number,
		string $additional_address_data,
		string $postal_code,
		string $city,
		string $country
	) {
		$this->street                  = $street;
		$this->street_number           = $street_number;
		$this->additional_address_data = $additional_address_data;
		$this->postal_code             = $postal_code;
		$this->city                    = $city;
		$this->country                 = $country;
	}
}

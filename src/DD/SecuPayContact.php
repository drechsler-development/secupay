<?php

namespace DD;

class SecuPayContact {
	public string  $forename;
	public string  $surname;
	public string  $phone;
	public string         $mobile;
	public SecuPayAddress $address;
	public string         $email;
	public string  $dob;

	public function __construct (
		string         $forename,
		string         $surname,
		string         $phone,
		string         $mobile,
		SecuPayAddress $address,
		string         $email,
		string         $dob
	) {
		$this->forename = $forename;
		$this->surname  = $surname;
		$this->phone    = $phone;
		$this->mobile   = $mobile;
		$this->address  = $address;
		$this->email    = $email;
		$this->dob      = $dob;
	}
}

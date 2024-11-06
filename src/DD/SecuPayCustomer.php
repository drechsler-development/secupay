<?php

namespace DD;

class SecuPayCustomer {
	public SecuPayContact $contact;

	public function __construct (SecuPayContact $contact) {
		$this->contact = $contact;
	}
}

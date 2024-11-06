<?php

namespace DD;

class SecuPayApplicationContext {
	public string $checkout_template;
	public string $language;
	public array  $return_urls;

	public function __construct (string $checkout_template, string $language, array $return_urls) {
		$this->checkout_template = $checkout_template;
		$this->language          = $language;
		$this->return_urls       = $return_urls;
	}
}

<?php

namespace DD;

class SecuPayBasket {
	/** @var SecuPayProduct[] $products */
	public array $products;

	public function __construct (array $products) {
		$this->products = $products;
	}
}

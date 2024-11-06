<?php

namespace DD;

class SecuPayProduct {

	public string  $id;
	public string  $desc;
	public float   $priceOne;
	public float   $tax;
	public float   $quantity;
	public ?string $item_type;

	/**
	 * @param string      $id
	 * @param string      $desc
	 * @param float       $priceOne net price of one item
	 * @param float       $tax tax rate in percent (e.g. 19.0)
	 * @param float       $quantity quantity of items
	 * @param string|null $item_type type of item (either null or 'shipping')
	 */
	public function __construct (
		string  $id,
		string  $desc,
		float   $priceOne,
		float   $tax,
		float   $quantity = 1,
		?string $item_type = null
	) {
		$this->id        = $id;
		$this->desc      = $desc;
		$this->priceOne  = $priceOne;
		$this->tax       = $tax;
		$this->quantity  = $quantity;
		$this->item_type = $item_type;
	}
}

<?php

namespace DD;

class SecuPayPaymentData {
	public bool               $is_demo;
	public array           $contract;
	public SecuPayCustomer $customer;
	public string          $intent;
	public SecuPayBasket $basket;
	public array         $basket_info;
	public SecuPayApplicationContext $application_context;
	public array                     $payment_context;

	public function __construct (
		bool                      $is_demo,
		array                     $contract,
		SecuPayCustomer           $customer,
		string                    $intent,
		SecuPayBasket             $basket,
		array                     $basket_info,
		SecuPayApplicationContext $application_context,
		array                     $payment_context
	) {
		$this->is_demo             = $is_demo;
		$this->contract            = $contract;
		$this->customer            = $customer;
		$this->intent              = $intent;
		$this->basket              = $basket;
		$this->basket_info         = $basket_info;
		$this->application_context = $application_context;
		$this->payment_context     = $payment_context;
	}
}

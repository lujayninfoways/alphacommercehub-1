<?php
namespace Dfe\AlphaCommerceHub\API;
use Dfe\AlphaCommerceHub\Method as M;
use Dfe\AlphaCommerceHub\Settings as S;
// 2017-12-02
final class Client extends \Df\API\Client {
	/**
	 * 2017-12-02
	 * «The AlphaHub API is available in XML and JSON formats»
	 * «API Integration Guide(Nov 2017)» → «Technical Integration Requirements» → «API Formats»
	 * http://developer.alphacommercehub.com.au/docs/api-integration-guidenov-2017#api-formats
	 * @override
	 * @see \Df\API\Client::_construct()
	 * @used-by \Df\API\Client::__construct()
	 */
	protected function _construct() {parent::_construct(); $this->reqJson(); $this->resJson();}

	/**
	 * 2017-12-02
	 * «API Integration Guide(Nov 2017)» → «API Reference»
	 * http://developer.alphacommercehub.com.au/docs/api-integration-guidenov-2017#api-reference
	 * @override
	 * @see \Df\API\Client::commonParams()
	 * @used-by \Df\API\Client::__construct()
	 * @param string $path
	 * @return array(string => mixed)
	 */
	protected function commonParams($path) {/** @var S $s */$s = dfps($this); return [
		// 2017-12-02 «Component Tag for Message Header», required.
		'Header' => [
			/**
			 * 2017-12-02
			 * «Merchant ID assigned by APC».
			 * String, required.
			 */
			'MerchantID' => $s->merchantID()
			/**
			 * 2017-12-02
			 * «The transaction being executed, for example `AuthPayment` for an Authorization».
			 * String, required.
			 */
			,'TransactionType' => $this->path()
			/**
			 * 2017-12-02
			 * «The ID for the user making the API request. Assigned by APC.
			 * A merchant can have different user ids assigned
			 * if they wish to separate permissions for example for refunds to their backend application.»
			 * String, required.
			 */
			,'UserID' => $s->privateKey()
			/**
			 * 2017-12-02
			 * «Version of the Hub API. Current version is 2.»
			 * String, required.
			 */
			,'Version' => '2'
		]
	];}

	/**
	 * 2017-12-02
	 * «To distinguish whether the message content is sent as XML or JSON
	 * please provide `Content-Type` as either `application/xml` or `application/json`»:
	 * «API Integration Guide(Nov 2017)» → «API Reference»
	 * http://developer.alphacommercehub.com.au/docs/api-integration-guidenov-2017#api-reference
	 * @override
	 * @see \Df\API\Client::headers()
	 * @used-by \Df\API\Client::__construct()
	 * @used-by \Df\API\Client::p()
	 * @return array(string => string)
	 */
	protected function headers() {return ['Content-Type' => 'application/json'];}

	/**
	 * 2017-12-02
	 * @override
	 * @see \Df\API\Client::responseValidatorC()
	 * @used-by \Df\API\Client::p()
	 * @return string
	 */
	protected function responseValidatorC() {return \Dfe\AlphaCommerceHub\API\Validator::class;}

	/**
	 * 2017-12-02
	 * @override
	 * @see \Df\API\Client::url()
	 * @used-by \Df\API\Client::_p()
	 * @return string
	 */
	protected function url() {return $this->urlBase();}

	/**
	 * 2017-12-02
	 * @override
	 * @see \Df\API\Client::urlBase()
	 * @used-by \Df\API\Client::__construct()
	 * @used-by url()
	 * @return string
	 */
	protected function urlBase() {$m = dfpm($this); /** @var M $m */ return $m->urlBase(false);}
}
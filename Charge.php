<?php
namespace Dfe\AlphaCommerceHub;
use Df\Payment\Init\Action;
/**
 * 2017-10-27
 * The charge parameters are described
 * in the «AlphaHPP» → «Paypage Request Reference» → «Request a Paypage Session» section
 * of the documentation: http://alpha.pwstaging.com.au/docs/alphahpp#request-a-paypage-session
 * @method Method m()
 * @method Settings s()
 */
final class Charge extends \Df\PaypalClone\Charge {
	/**
	 * 2017-10-27 «Amount of the payment», Numeric, 17 (14,3), required.
	 * @see \Dfe\AlphaCommerceHub\Method::amountFactor()
	 * @override
	 * @see \Df\PaypalClone\Charge::k_Amount()
	 * @used-by \Df\PaypalClone\Charge::p()
	 * @return string
	 */
	protected function k_Amount() {return 'Amount';}
	
	/**
	 * 2017-10-27 «The currency of the order. ISO-4217 A3 codes e.g. USD, GBP.», required.
	 * 2017-11-01 «Which currencies are allowed by each payment option?» https://mage2.pro/t/4820
	 * @override
	 * @see \Df\PaypalClone\Charge::k_Currency()
	 * @used-by \Df\PaypalClone\Charge::p()
	 * @return string
	 */
	protected function k_Currency() {return 'Currency';}

	/**
	 * 2017-10-27 «The customers email address», String(64), optional.
	 * @override
	 * @see \Df\PaypalClone\Charge::k_Email()
	 * @used-by \Df\PaypalClone\Charge::p()
	 * @return string
	 */
	protected function k_Email() {return 'EmailAddress';}

	/**
	 * 2017-10-27
	 * Note 1. «The merchant identifier assigned by ACH», String, required.
	 * Note 2.
	 * [AlphaCommerceHub] Will the `MerchantID` and `UserID` values be preserved
	 * for a particular merchant after switching its account from the test mode to the production one?
	 * https://mage2.pro/t/4798
	 * Note 3. [AlphaCommerceHub] Where can a merchant find his `MerchantID` and `UserID` values?
	 * https://mage2.pro/t/4799
	 * @override
	 * @see \Df\PaypalClone\Charge::k_MerchantId()
	 * @used-by \Df\PaypalClone\Charge::p()
	 * @return string
	 */
	protected function k_MerchantId() {return 'MerchantID';}
	
	/**
	 * 2017-10-27
	 * Note 1.
	 * «The merchants order id for the payment. Supported special characters are / and .». String, required.
	 * Note 2.
	 * [AlphaCommerceHub] Are any limitations on the «MerchantTxnID» parameter allowed characters or length?
	 * https://mage2.pro/t/4779
	 * 2017-11-01
	 * Which characters are considered «special» and «not special» for the AlphaHPP's `MerchantTxnID` parameter?
	 * https://mage2.pro/t/4842
	 * @override
	 * @see \Df\PaypalClone\Charge::k_RequestId()
	 * @used-by \Df\PaypalClone\Charge::p()
	 * @return string
	 */
	protected function k_RequestId() {return 'MerchantTxnID';}

	/**
	 * 2017-11-01
	 * AlphaCommerceHub does not use signatures.
	 * It requires a plain merchant password (`UserId`) instead.
	 * *) How is the APIs authentication security implemented? https://mage2.pro/t/4768
	 * *) Will the `MerchantID` and `UserID` values be preserved for a particular merchant
	 * after switching its account from the test mode to the production one? ttps://mage2.pro/t/4798
	 * *) Where can a merchant find his `MerchantID` and `UserID` values? https://mage2.pro/t/4799
	 * @override
	 * @see \Df\PaypalClone\Charge::k_Signature()
	 * @used-by \Df\PaypalClone\Charge::p()
	 * @return null
	 */
	protected function k_Signature() {return null;}

	/**
	 * 2017-10-27
	 * @override
	 * @see \Df\PaypalClone\Charge::pCharge()
	 * @used-by \Df\PaypalClone\Charge::p()
	 * @return array(string => mixed)
	 */
	protected function pCharge() {$s = $this->s(); $o = $s->options(); return [
		/**
		 * 2017-11-01
		 * «Y/N – Allows a merchant to flag that a transaction should be captured (settled) if the auth is».
		 * String(1), optional.
		 */
		'Capture' => Action::singleton($this->m())->preconfiguredToCapture() ? 'Y' : 'N'
		/**
		 * 2017-11-01
		 * Note 1. «AlphaHPP» → «Paypage Request Reference» → «Request a Paypage Session»
		 * «The language the customer selected on the merchants site.
		 * For example `EN` for English, `DE` for German.
		 * If not provided the page will default to English
		 * or if a language is provided that is not supported the page will default to.»
		 * String(2), optional.
		 * http://developer.alphacommercehub.com.au/docs/alphahpp-#request-a-paypage-session
		 * Note 2. «AlphaHPP» → «Paypage Request Reference» → «Supported Languages»
		 * http://developer.alphacommercehub.com.au/docs/alphahpp-#supported-languages
		 */
		,'Language' => df_lang(df_visitor_locale())
		/**
		 * 2017-11-01
		 * Note 1.
		 * «Allows the merchant to specify a statement descriptor for the transaction.
		 * Please note the narrative supported by different card schemes varies.»
		 * http://alpha.pwstaging.com.au/docs/alphahpp#request-a-paypage-session
		 * String(64), optional.
		 * Note 2.
		 * «[AlphaCommerceHub] Is the `MerchantDescriptor` parameter really accepts up to 64 characters?»
		 * https://mage2.pro/t/4800
		 * «The value is truncated depending on the provider of the payment processing.
		 * The Wikipedia article mainly focuses on card schemes,
		 * but as the platform supports many different payment methods worldwide
		 * this can be passed through to various providers where appropriate.
		 * The 64 character limit is simply what the hub will accept,
		 * not what is sent through to the end provider.»
		 * Note 3.
		 * «Are any unallowed characters for the `MerchantDescriptor` parameter values,
		 * and how does AlphaCommerceHub handle them
		 * (silently strip them, or reject the payment API request at whole)?»
		 * https://mage2.pro/t/4801
		 * Note 4.  We provide also `TxnDetails` for a long description.
		 */
		,'MerchantDescriptor' => $this->text($s->dsd(), 64)
		/**
		 * 2017-10-27
		 * Note 1.
		 * «The payment method to be used, default to `CC`.
		 * `ALL` will display all configured payment methods.
		 * Merchant can select a subset by delimiting.
		 * E.g. `CC,ID,AL` would display cards, iDeal, Alipay if supported by AlphaHPP.»
		 * String(14), required.
		 * Note 2. [AlphaCommerceHub] The payment method codes: https://mage2.pro/t/4809
		 * Note 3.
		 * [AlphaCommerceHub] Which codes are used for the
		 * Visa Checkout, UnionPay Online Payments, and ApplePay payment options?
		 * https://mage2.pro/t/4811
		 *
		 * 2017-11-01
		 * I have implemented it similar to @see \Dfe\AllPay\Charge::pChoosePayment():
		 * https://github.com/mage2pro/allpay/blob/1.10.0/Charge.php#L538-L562
		 */
		,'Method' => $this->m()->option() ?: (!$o->isLimited() ? 'ALL' : df_csv($o->allowed()))
		/**
		 * 2017-10-27
		 * Note 1. «User id for authentication», String, required.
		 * Note 2. [AlphaCommerceHub] How is the APIs authentication security implemented? https://mage2.pro/t/4768
		 * Note 3.
		 * [AlphaCommerceHub] Will the `MerchantID` and `UserID` values be preserved
		 * for a particular merchant after switching its account from the test mode to the production one?
		 * https://mage2.pro/t/4798
		 * Note 4. [AlphaCommerceHub] Where can a merchant find his `MerchantID` and `UserID` values?
		 * https://mage2.pro/t/4799
		 * Note 5. AlphaCommerceHub does not use signatures. `UserId` is really a merchant password.
		 */
		,'UserId' => $s->privateKey()
		/**
		 * 2017-10-27
		 * «The date and time of the transaction.
		 * If not provided defaults to ACH system timestamp (UTC).
		 * Format: DDMMYYYYHHMMSS»
		 * String(14), optional.
		 */
		,'TransactionTimestamp' => null
		/**
		 * 2017-11-01
		 * Note 1. «Information on the product/service being purchased». String(2000), optional.
		 * Note 2.
		 * We provide also `MerchantDescriptor` for a very short description:
		 * e.g., it is used as a dynamic statement descriptor for the bank card payment option:
		 * https://en.wikipedia.org/wiki/Billing_descriptor#Dynamic_descriptors
		 */
		,'TxnDetails' => $this->description()
	];}
}
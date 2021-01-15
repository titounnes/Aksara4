<?php namespace Aksara\Laboratory;
/**
 * Midtrans Payment Library
 * A connector to integrate payment withing Midtrans
 *
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 * @website			www.aksaracms.com
 * @since			version 4.0.0
 * @copyright		(c) 2021 - Aksara Laboratory
 */
//require_once ROOTPATH . 'vendor/autoload.php';

class Midtrans
{
	function __construct()
	{
		// Set your Merchant Server Key
		\Midtrans\Config::$serverKey = 'SB-Mid-server-M9QvOyuU_Fur2G3g48QlvfqL';
		// Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
		\Midtrans\Config::$isProduction = false;
		// Set sanitization on (default)
		\Midtrans\Config::$isSanitized = true;
		// Set 3DS transaction for credit card to true
		\Midtrans\Config::$is3ds = true;
	}
}

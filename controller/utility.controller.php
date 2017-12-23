<?php
class UtilityController extends Controller
{
	// ------------------------------ Attributes ------------------------------
	
	public $utility;
	// Admin Levels
	/*public $adminLevel = array(
		'fixmissingbatchguids' => 1
	);*/
	
	// ------------------------------- Methods --------------------------------
	
	public function generateRandomString($type = 'hexdec', $length = 8) {
		switch ($type) {
		case 'alnum':
			$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			break;
		case 'alpha':
			$pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			break;
		case 'hexdec':
			$pool = '0123456789abcdef';
			break;
		case 'hexdecupper':
			$pool = '0123456789ABCDEF';
			break;
		case 'numeric':
			$pool = '0123456789';
			break;
		case 'nozero':
			$pool = '123456789';
			break;
		case 'distinct':
			$pool = '2345679ACDEFHJKLMNPRSTUVWXYZ';
			break;
		case 'distinctlower':
			$pool = '12345679acdefhkmnprstwxyz';
			break;
		default:
			$pool = (string) $type;
			break;
		}
		$crypto_rand_secure = function ($min, $max) {
		$range = $max - $min;
		if ( $range < 0 ) return $min; // not so random...
		$log    = log( $range, 2 );
		$bytes  = (int) ( $log / 8 ) + 1; // length in bytes
		$bits   = (int) $log + 1; // length in bits
		$filter = (int) ( 1 << $bits ) - 1; // set all lower bits to 1
		do {
			$rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
			$rnd = $rnd & $filter; // discard irrelevant bits
		} while ($rnd >= $range);
			return $min + $rnd;
		};
		$token = "";
		$max   = strlen( $pool );
		for ( $i = 0; $i < $length; $i++ ) {
			$token .= $pool[$crypto_rand_secure( 0, $max )];
		}
		return $token;
	}
}
?>
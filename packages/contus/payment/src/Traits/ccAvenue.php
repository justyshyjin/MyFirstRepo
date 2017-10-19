<?php

/**
 * ccAvenue
 *
 * To manage the functionalities related to the Payment module from Payment Controller
 *
 * @vendor Contus
 * @package Payment
 * @version 1.0
 * @author Contus<developers@contus.in>
 * @copyright Copyright (C) 2016 Contus. All rights reserved.
 * @license GNU General Public License http://www.gnu.org/copyleft/gpl.html
 */
namespace Contus\Payment\Traits;

use Crypt;
use Contus\Payment\Models\PaymentMethod;

trait ccAvenue {
    /**
      * To redirect to various payment options.
      * param request to get filled details
      * return view
      */
    public function postccavRequestHandler($user,$package) {
        //amount, billing email, billing tel, userid, subscriptionid
        $payment =[
                'tid'=>time(),
                'merchant_id'=>96930,
                'order_id'=>time().$package['id'],
                'amount'=>$package['amount'],
                'redirect_url'=>'',
                'cancel_url'=>'',
                'billing_email'=>$user['email'],
                'billing_tel'=>$user['phone'],
                'merchant_param1'=>$user['id'],
                'merchant_param2'=>$package['id'],
        ];
        $working_key = 'CAB0231E8F567A457B74A2FB1AA2D658';
        $access_code = 'AVMS00EB89BP55SMPB';
        $merchant_data = '';
        foreach ( $payment as $key => $value ) {
            $merchant_data .= $key . '=' . $value . '&';
        }
        
        $encrypted_data = $this->encrypt($merchant_data, $working_key );
        return view ( 'payment::customer.payment.ccavRequestHandler' )->with ( 'encrypted_data', $encrypted_data )->with ( 'access_code', $access_code );
    }
    /**
     * To encrypt user provided data
     *
     * @param
     *          merchant_data & working_key
     * @return encrypted string
     */
    public function encrypt($plainText, $key) {
        $secretKey = $this->hextobin ( md5 ( $key ) );
        $initVector = pack ( "C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f );
        $openMode = mcrypt_module_open ( MCRYPT_RIJNDAEL_128, '', 'cbc', '' );
        $blockSize = mcrypt_get_block_size ( MCRYPT_RIJNDAEL_128, 'cbc' );
        $plainPad = $this->pkcs5_pad ( $plainText, $blockSize );
        if (mcrypt_generic_init ( $openMode, $secretKey, $initVector ) != - 1) {
            $encryptedText = mcrypt_generic ( $openMode, $plainPad );
            mcrypt_generic_deinit ( $openMode );
        }
        return bin2hex ( $encryptedText );
    }
    
    /**
     * To decrypt ccavenue response
     *
     * @param
     *          encrypted response & working_key
     * @return decrypted string
     */
    public function decrypt($encryptedText, $key) {
        $secretKey = $this->hextobin ( md5 ( $key ) );
        $initVector = pack ( "C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f );
        $encryptedText = $this->hextobin ( $encryptedText );
        $openMode = mcrypt_module_open ( MCRYPT_RIJNDAEL_128, '', 'cbc', '' );
        mcrypt_generic_init ( $openMode, $secretKey, $initVector );
        $decryptedText = mdecrypt_generic ( $openMode, $encryptedText );
        $decryptedText = rtrim ( $decryptedText, "\0" );
        mcrypt_generic_deinit ( $openMode );
        return $decryptedText;
    }
    
    /**
     * To handle large text making it in similer block size
     *
     * @param
     *          data & size of the block
     * @return view
     */
    public function pkcs5_pad($plainText, $blockSize) {
        $pad = $blockSize - (strlen ( $plainText ) % $blockSize);
        return $plainText . str_repeat ( chr ( $pad ), $pad );
    }
    
    /**
     * To convert Hexadecimal to Binary
     *
     * @param
     *          hexadecimal string
     * @return binary string
     */
    public function hextobin($hexString) {
        $length = strlen ( $hexString );
        $binString = "";
        $count = 0;
        while ( $count < $length ) {
            $subString = substr ( $hexString, $count, 2 );
            $packedString = pack ( "H*", $subString );
            if ($count == 0) {
                $binString = $packedString;
            }
    
            else {
                $binString .= $packedString;
            }
    
            $count += 2;
        }
        return $binString;
    }
  
}
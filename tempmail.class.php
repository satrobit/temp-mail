<?php
/**
 * PHP API Wrapper for temp-mail.ru service 
 *
 * Copyright (c) 2015 Amir Keshavarz <amirkekh@gmail.com>
 * 
 *
 * The MIT License (MIT)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense,
 * and/or sell copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following conditions:
 *
 * 1- The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 * 
 * 2- THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
 * DEALINGS IN THE SOFTWARE.
 *
 * @package    temp-mail
 * @author     Amir Keshavarz <amirkekh@gmail.com>
 * @copyright  2015 Amir Keshavarz
 * @license    http://opensource.org/licenses/mit-license.php The MIT License
 * @version    1.0
 */
class tempmail {
	public static $emailaddress = null; // Email address

	/**
	 * Doing request calls for api .
	 */
	protected function call($request, $addressid = null) {
		switch ($request) {
			case 'domains' :
				$target = 'http://api.temp-mail.ru/request/domains/format/json';
				break;
			
			default :
				$target = 'http://api.temp-mail.ru/request/' . $request . '/id/' . $addressid . '/format/json';
				break;
		}
		
		$handle = curl_init ( $target );
		curl_setopt ( $handle, CURLOPT_HEADER, false );
		curl_setopt ( $handle, CURLOPT_RETURNTRANSFER, true );
		$result = curl_exec ( $handle );
		curl_close ( $handle );
		
		return json_decode ( $result, true );
	}
	
	/**
	 * Setting an email address with a predefined address or a random address .
	 */
	public function setmail($email = null) {
		if (is_null ( $email )) {
			$domains = $this->domains ();
			$domain = $domains [array_rand ( $domains )];
			$email = $this->generateRandomString () . $domain;
		}
		
		self::$emailaddress = $email;
	}
	
	/**
	 * Requests for inbox . $email is optional if there is already an email address .
	 */
	public function getmails($email = null) {
		$email = ! is_null ( $email ) ? $email : self::$emailaddress;
		$mails = $this->call ( 'mail', md5 ( $email ) );
		return $mails;
	}
	
	/**
	 * Requests for sources . $email is optional if there is already an email address .
	 */
	public function getsources($email = null) {
		$email = ! is_null ( $email ) ? $email : self::$emailaddress;
		$sources = $this->call ( 'source', md5 ( $email ) );
		return $sources;
	}
	
	/**
	 * Deletes an email by the id assigned for each email .
	 */
	public function delete($emailid) {
		$delete = $this->call ( 'delete', md5 ( $emailid ) );
		
		if ($delete ['result'] != 'success') {
			return false;
		}
		
		return true;
	}
	
	/**
	 * Requests for available domains .
	 */
	public function domains() {
		$result = $this->call ( 'domains' );
		return $result;
	}
	
	/**
	 * Generating a random string to make an email address .
	 * http://stackoverflow.com/a/4356295
	 */
	protected function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
		$charactersLength = strlen ( $characters );
		$randomString = '';
		for($i = 0; $i < $length; $i ++) {
			$randomString .= $characters [rand ( 0, $charactersLength - 1 )];
		}
		return $randomString;
	}
}

?>

<?php

/**
 * Send sms via Twilio
 *
 * @package     iProd
 * @subpackage  Services
 * @category    Auth Service
 * @author      iProd Technologies Team
 * @version     1.0.0
 * @link        https://iprod.mr
*/

namespace App\Services;

use Illuminate\Http\Request;
use App\Contracts\SMSInterface;
use Twilio\Rest\Client;


class TwilioSms implements SMSInterface
{
	private $client, $verify;

	/**
     * Initialize Twillo credentials
     *
     * @return void
     */
	public function initialize()
	{
		$this->client = new Client(settings('twilio_sid'), settings('twilio_auth_token'));
		$this->verify = $this->client->verify->v2->services(settings('twilio_sms_service_sid'));
	}

	/**
     * Send OTP message
     *
     * @param String $phone_number
     * @return Array SMS Response
     */
	protected function sendOTP($phone_number)
	{
		try {
			$data = $this->verify->verifications->create($phone_number, "sms");
			return array('status_code' => 1,'message'=>'Success','status'=>true);
		}
		catch(\Exception $e) {
			return array('status_code' => 0,'message'=>$e->getMessage() ,'status'=>false);
		}
	}

	/**
     * Verify OTP number
     *
     * @param String $phone_number, String $verification_code
     * @return Array SMS Response
     */
	protected function verifyOTP($phone_number, $verification_code)
	{
		try {

			$verification = $this->verify->verificationChecks->create($verification_code, array('to'=>$phone_number));

			if($verification->valid)
				return array('status_code' => 1,'message'=>'Success','status'=>true);
			else
				return array('status_code' => 0,'message'=>__('messages.signup.wrong_otp'),'status'=>false);
		}
		catch(\Exception $e) {
			return array('status_code' => 0,'message'=>__('messages.signup.wrong_otp'),'status'=>false);
		}
	}

	/**
     * Send Text message to mobile
     *
     * @param String $phone_number, String $verification_code
     * @return Array SMS Response
     */
	public function send($phone_number, $text='', $verification_code=false)
	{
		$this->initialize();
		if($text){
			$result = $this->SendTextMessage($phone_number,$text);
		}
		else if($verification_code) {
			$result = $this->verifyOTP($phone_number, $verification_code);
		} else {
			$result = $this->sendOTP($phone_number);
		}
		return $result;
	}
	/**
     * Send Text message to mobile
     *
     * @param String $[to] user phone number
     * @param String $[text] [message to be send]
     * @return Array SMS Response
     */
	public function SendTextMessage($to,$text)
	{
		try {
			// Use the client to do fun stuff like send text messages!
			$response = $this->client->messages->create(
			    // the number you'd like to send the message to
			    $to,
			    [
			        // A Twilio phone number you purchased at twilio.com/console
			        'from' => settings('twilio_number'),
			        // the body of the text message you'd like to send
			        'body' => $text
			    ]
			);

			return array('status_code' => 1,'message'=>'Success','status'=>true);
		}

		catch(\Exception $e) {
			return array('status_code' => 0,'message'=>$e->getMessage(),'status'=>false);
		}
		return array('status_code' => 1,'message'=>'Success','status'=>true);

	}
}

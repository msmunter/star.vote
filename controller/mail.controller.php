<?php
// Outgoing mail using Mailgun's API
require 'vendor/autoload.php';
use Mailgun\Mailgun;

class MailController extends Controller
{
	private $apiKey;
	private $fromDomain;
	public $toFirstName;
	public $toLastName;
	public $toName;
	public $toAddress;
	public $msgSubject;
	public $msgText;
	public $msgArray = [];

	public function mailConfig() {
		$mailConfig = parse_ini_file('/srv/www/mailconfig/starvote_mail.ini');
		$this->apiKey = $mailConfig['apikey'];
		$this->fromDomain = $mailConfig['fromdomain'];
	}

	public function test() {
		$this->title = 'Mail Test';
	}

	public function verify() {
		$this->verified = false;
		$this->title = 'Email Verification: Error';
		if (ctype_alnum($this->URLdata)) {
			$verifyKey = $this->URLdata;
			$this->verified = $this->model->verifyEmail($verifyKey);
			if (array_key_exists('verifydate', $this->verified) && $this->verified->verifydate == null) {
				$this->verified->new = true;
				$this->verified->verifydate = $this->model->setVerifyDate($verifyKey, date("Y-m-d H:i:s"));
			} else {
				$this->verified->new = false;
			}
		}
		if ($this->verified) {
			$this->title = 'Email Verification: Success';
		}
	}

	public function ajaxsendemailverification() {
		// These are TEST ONLY
		$this->toFirstName = 'Steen';
		$this->toLastName = 'Munter';
		$this->toAddress = 'msmunter@gmail.com';
		// Not test only from here on out
		$return = [];
		// Ensure address exists to associate with verification key
		if (!$this->toAddress) {
			$return['error'] = 'Invalid email address';
		} else {
			// Verify 'to' address isn't already in DB
			$emailStatus = $this->model->emailStatus($this->toAddress);
			if ($emailStatus->verifykey) {
				$return['emailExists'] = true;
				if ($emailStatus->verifydate) {
					$return['verified'] = true;
				} else {
					$return['verified'] = false;
				}
			} else {
				$this->mailConfig();
				$this->msgSubject = 'Verify your star.vote registration';
				// Generate verification key
				$verifyKey = UtilityController::generateRandomString($type = 'distinctlower', $length = 16);
				$return['mailConfig'] = [
					'apiKey' => $this->apiKey,
					'fromDomain' => $this->fromDomain
				];
				$this->model->insertVerifyEmailKey($this->toAddress, $verifyKey);
				$this->msgText = 'Hi, '.$this->toFirstName.'! We\'re excited to have you be a part of star.vote. Please click the following link to verify your email address: https://star.vote/mail/verify/'.$verifyKey.'/';
				//$return['status'] = $this->sendMessage();
				$return['status'] = $this->sendMessage(true); // DEBUG
			}
		}
		echo htmlspecialchars(json_encode($return), ENT_NOQUOTES);
	}

	public function sendMessage($test) {
		$return = [];
		// Is necessary info all there?
		if (!$this->fromDomain) {
			$return['error'] = 'Invalid "from" domain';
		} else if (!$this->toAddress) {
			$return['error'] = 'Invalid "to" address';
		} else if (!$this->msgSubject) {
			$return['error'] = 'Invalid message subject';
		} else if (!$this->msgText) {
			$return['error'] = 'Invalid message text';
		} else if (!$this->apiKey) {
			$return['error'] = 'Invalid apiKey';
		} else {
			// Accumulate email header info
			if (!$this->toFirstName && !$this->toLastName) {
				$this->toFirstName = "Voter";
				$this->toName = $this->toFirstName;
			} else if (!$this->toFirstName) {
				$this->toFirstName = $this->toLastName;
				$this->toLastName = false;
				$this->toName = $this->toFirstName;
			} else if (!$this->toLastName) {
				$this->toName = $this->toLastName;
			} else {
				$this->toName = $this->toFirstName.' '.$this->toLastName;
			}
			$msgArray = [];
			$msgArray['from'] = 'Star.vote <server@'.$this->fromDomain.'>';
			$msgArray['to'] = $this->toName.' <'.$this->toAddress.'>';
			$msgArray['subject'] = $this->msgSubject;
			$msgArray['text'] = $this->msgText;
			// Send the message or return msgArray for testing
			if ($test) {
				$return = $msgArray;
			} else {
				// Make Mailgun instance, send mail, and return results
				$mg = new Mailgun($this->apiKey);
				$return = $mg->sendMessage($this->fromDomain, $msgArray);
			}
		}
		return $return;
	}
}
?>
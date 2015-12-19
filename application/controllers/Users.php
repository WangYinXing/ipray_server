<?php
defined('BASEPATH') OR exit('No direct script access allowed');


//require 'mailgun-php/vendor/autoload.php';
//use Mailgun\Mailgun;


class Users extends Api_Unit {

	function __construct(){
		parent::__construct();

		$this->load->model('Mdl_Users', '', TRUE);
		$this->load->library('Qbhelper');
	}

	public function index() {
		parent::initView('users', 'Manage iprayees for CRUDing',
			array()
		);

		parent::loadView();
	}

/*########################################################################################################################################################
	API Entries
########################################################################################################################################################*/

	/*--------------------------------------------------------------------------------------------------------
	User list for admin panel...
	_________________________________________________________________________________________________________*/
	public function api_entry_list() {
		$data = $this->Mdl_Users->get_list(
			$_POST['rp'],
			$_POST['page'],
			$_POST['query'],
			$_POST['qtype'],
			$_POST['sortname'],
			$_POST['sortorder']);

		echo json_encode(array(
			'page'=>$_POST['page'],
			'total'=>$this->Mdl_Users->get_length(),
			'rows'=>$data,
		));
	}

	/*--------------------------------------------------------------------------------------------------------
		Sign up...
	_________________________________________________________________________________________________________*/
	public function api_entry_signup() {
		parent::validateParams(array("username", "email", "password", "fullname", "church", "province", "city", "bday"));

		$qbToken = $this->qbhelper->generateSession();

		if ($qbToken == null || $qbToken == "")							parent::returnWithErr("Generating QB session has been failed.");

		$qbSession = $this->qbhelper->signupUser(
			$qbToken,
			$_POST['username'],
			$_POST['email'],
			QB_DEFAULT_PASSWORD
		);

		/*

		*/
		if ($qbSession == null)
			parent::returnWithErr($this->qbhelper->latestErr);

		$newUser = $this->Mdl_Users->signup(
			$_POST['username'],
			$_POST['email'],
			md5($_POST['password']),
			$_POST['fullname'],
			$_POST['church'],
			$_POST['province'],
			$_POST['city'],
			$_POST['bday'],
			$qbSession
		);

		if ($newUser == null) {
			parent::returnWithErr($this->qbhelper->latestErr);
		}

		/*
			Now we should register qb user at first.....
		*/
		parent::returnWithoutErr("User has been created successfully.", $newUser);
	}

	/*--------------------------------------------------------------------------------------------------------
		Sign in...
	_________________________________________________________________________________________________________*/
	public function api_entry_signin() {
		parent::validateParams(array('qbid', 'token', 'password'));

		$users = $this->Mdl_Users->getAll("qbid", $_POST["qbid"]);

		if (count($users) == 0)	parent::returnWithErr("User not found.");

		$user = $users[0];

		if ($user->password != md5($_POST["password"]))			parent::returnWithErr("Invalid password.");


		if (count($users) == 0)
			parent::returnWithErr("QBID is not valid. maybe not found corresponding user from qbid.");
		
		$user = $this->Mdl_Users->signin($_POST["qbid"], $_POST["token"]);

		parent::returnWithoutErr("Signin succeed.", $user);
	}

	/*--------------------------------------------------------------------------------------------------------
		Sign out...
	_________________________________________________________________________________________________________*/
	public function api_entry_signout() {
		parent::validateParams(array('user'));

		if (!$this->Mdl_Users->get($_POST["user"]))	parent::returnWithErr("User id is not valid.");
		
		$this->Mdl_Users->signout($_POST["user"]);

		//if ($user == null)			parent::returnWithErr("Invalidation token failed.");

		parent::returnWithoutErr("Signout succeed.");
	}

	/*--------------------------------------------------------------------------------------------------------
		Sign out...
	_________________________________________________________________________________________________________*/
	public function api_entry_forgotpassword() {
		parent::validateParams(array('email'));

		$users = $this->Mdl_Users->getAll("email", $_POST["email"]);

		if (!($user = $users[0]))	parent::returnWithErr("User email is not valid.");

		$hash = hash('tiger192,3', $user->username . date("y-d-m-h-m-s"));
		$baseurl = $this->config->base_url();

		$this->load->model('Mdl_Tokens');
		$this->Mdl_Tokens->create(array(
			"token" => $hash,
			"user" => $user->id
			));


		$content = '
		<html><head><base target="_blank">
				<style type="text/css">
				::-webkit-scrollbar{ display: none; }
				</style>
				<style id="cloudAttachStyle" type="text/css">
				#divNeteaseBigAttach, #divNeteaseBigAttach_bak{display:none;}
				</style>
				            <style type="text/css">
				                img {
				                    border: 0;
				                    height: auto;
				                    outline: none;
				                    text-decoration: none;
				                }

				                body {
				                    height: 100% !important;
				                    margin: 0;
				                    padding: 0;
				                    width: 100% !important;
				                }

				                img {
				                    -ms-interpolation-mode: bicubic;
				                }

				                .ReadMsgBody {
				                    width: 100%;
				                }

				                .ExternalClass {
				                    width: 100%;
				                }

				                body {
				                    -ms-text-size-adjust: 100%;
				                    -webkit-text-size-adjust: 100%;
				                }

				                .ExternalClass {
				                    line-height: 100%;
				                }

				                img {
				                    max-width: 100%;
				                }

				                body {
				                    -webkit-font-smoothing: antialiased;
				                    -webkit-text-size-adjust: none;
				                    width: 100% !important;
				                    height: 100%;
				                    line-height: 1.6;
				                }

				                body {
				                    background-color: #f3f3f3;
				                }

				                img {
				                    border-radius: 12px;
				                }

				                img {
				                    width: 100%;
				                }

				                _media screen and (min-width: 768px) {
				                    [class="emailContainer"] {
				                        width: 585px !important;
				                    }

				                    #emailLogo {
				                        max-width: 200px;
				                    }

				                    #emailPreview {
				                        max-width: 440px;
				                    }

				                    [class="flexibleColumn"] {
				                        width: 50% !important;
				                    }

				                    [class="flexibleGrid"] {
				                        width: 33% !important;
				                    }
				                
				                }

				                _media screen and (max-width: 768px) {
				                    [id="emailPreview"] {
				                        max-width: 100% !important;
				                        width: 100% !important;
				                    }

				                    [id="emailLogo"] {
				                        max-width: 100% !important;
				                        width: 100% !important;
				                    }

				                    [class="flexibleColumn"] {
				                        max-width: 50% !important;
				                        width: 100% !important;
				                    }

				                    [class="flexibleGrid"] {
				                        max-width: 33% !important;
				                    }

				                    [id="bodyTable"] {
				                        width: 100% !important;
				                    }

				                    [id="bodyCell"] {
				                        width: 100% !important;
				                    }

				                    [class="emailContainer"] {
				                        width: 100% !important;
				                    }

				                    [id="emailPreview"] {
				                        max-width: 100% !important;
				                        width: 100% !important;
				                    }

				                    [id="emailLogo"] {
				                        max-width: 100% !important;
				                        width: 100% !important;
				                    }

				                    [id="previewContent"] {
				                        text-align: center !important;
				                    }

				                    [id="logoContent"] {
				                        text-align: center !important;
				                    }

				                    [id="logo"] {
				                        text-align: center !important;
				                    }

				                    [class="cta-blue"] {
				                        padding: 0 !important;
				                    }

				                        [class="cta-blue"] a {
				                            padding: 10px 40px !important;
				                        }

				                    [class="cta-blue-gradient"] {
				                        padding: 0 !important;
				                    }

				                        [class="cta-blue-gradient"] a {
				                            padding: 15px 60px !important;
				                        }
				               span[class="spnText"] {display:block !important; word-wrap:break-word !important; width:245px !important; padding:0 7px !important; margin:0 auto !important;}
				                    span[class="spnText1"] {display:block !important; word-wrap:break-word !important; width:255px !important; padding:0 2px !important;margin:0 auto !important;}
				                    td[class="footer"] table {width: 320px !important; padding: 0 20px !important; }
				                }

				                _media only screen and (max-width: 480px) {
				                    body {
				                        width: 100% !important;
				                        min-width: 100% !important;
				                    }

				                    [id="emailPreheader"] .emailContainer td {
				                        padding-bottom: 0 !important;
				                    }

				                        [id="emailPreheader"] .emailContainer td.rightCol {
				                            padding: 10px 0 !important;
				                        }

				                    [class="flexibleColumn"] {
				                        max-width: 100% !important;
				                        width: 100% !important;
				                    }

				                        [class="flexibleColumn"] td {
				                            text-align: center !important;
				                            padding: 0 0 10px 0 !important;
				                        }

				                    [class="flexibleGrid"] {
				                        max-width: 50% !important;
				                    }

				                    [class="footerContent"] br {
				                        display: none !important;
				                        line-height:10px !important;
				                    }

				                    [id="emailPreview"] {
				                        display: none !important;
				                        visibility: hidden !important;
				                    }

				                    [class="headerButton"] {
				                        width: 50% !important;
				                        padding-bottom: 15px !important;
				                    }

				                    [class="headerButtonContent"] {
				                        font-size: 22px !important;
				                        padding: 0 !important;
				                    }

				                        [class="headerButtonContent"] a {
				                            padding: 20px !important;
				                        }

				                    [id="emailGrid"] .emailContainer {
				                        max-width: 80% !important;
				                    }

				                    [class="articleContent"] {
				                        text-align: center !important;
				                    }

				                        [class="articleContent"] h3 {
				                            text-align: center !important;
				                        }

				                        [class="articleContent"] h5 {
				                            text-align: center !important;
				                        }

				                    [class="articleButton"] {
				                        margin: 0 auto !important;
				                        width: 50% !important;
				                    }

				                    [class="articleButtonContent"] {
				                        font-size: 22px !important;
				                        padding: 0 !important;
				                    }

				                        [class="articleButtonContent"] a {
				                            padding: 20px !important;
				                        }
				                    span[class="spnText"] {display:block !important; word-wrap:break-word !important; width:245px !important; padding:0 7px !important; margin:0 auto !important;}
				                    span[class="spnText1"] {display:block !important;  width:258px !important; padding:0 2px !important;margin:0 auto !important;}
				                    td[class="footer"] table {width: 320px !important; padding: 0 20px !important; }
				                    span[class="resize"]{width:100% !important; display: inline-block !important;}
				                }
				            </style>
				            </head><body><center style="box-sizing: border-box; font-family: Helvetica Neue, Helvetica, Helvetica, Arial, sans-serif; font-size: 16px; margin: 0; padding: 0">
				                <table id="bodyTable" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; box-sizing: border-box; font-family: Helvetica Neue, Helvetica, Helvetica, Arial, sans-serif; font-size: 16px; height: 100% !important; margin: 0; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 0; table-layout: fixed; width: 100% !important" align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
				                    <tbody><tr style="box-sizing: border-box; font-family: Helvetica Neue, Helvetica, Helvetica, Arial, sans-serif; font-size: 16px; margin: 0; padding: 0">
				                        <td id="bodyCell" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; box-sizing: border-box; font-family: Helvetica Neue, Helvetica, Helvetica, Arial, sans-serif; font-size: 16px; height: 100% !important; margin: 0; mso-line-height-rule: exactly; padding: 0; vertical-align: top; width: 100% !important" align="center" valign="top">
				                            
				                            <table style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; box-sizing: border-box; font-family: Helvetica Neue, Helvetica, Helvetica, Arial, sans-serif; font-size: 16px; margin: 0; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 0; table-layout: fixed" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
				                                <tbody><tr style="box-sizing: border-box; font-family: Helvetica Neue, Helvetica, Helvetica, Arial, sans-serif; font-size: 16px; margin: 0; padding: 0">
				                                    <td id="emailPreheader" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; box-sizing: border-box; font-family: Helvetica Neue, Helvetica, Helvetica, Arial, sans-serif; font-size: 16px; margin: 0; mso-line-height-rule: exactly; padding: 0; vertical-align: top" align="center" valign="top">
				                                        <table class="emailContainer" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-bottom-color: #dcdcdc; border-bottom-style: solid; border-bottom-width: 2px; border-collapse: collapse; box-sizing: border-box; font-family: Helvetica Neue, Helvetica, Helvetica, Arial, sans-serif; font-size: 16px; margin: 0; max-width: 585px; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 0; table-layout: fixed" cellpadding="0" cellspacing="0" width="100%">
				                                            <tbody><tr style="box-sizing: border-box; font-family: Helvetica Neue, Helvetica, Helvetica, Arial, sans-serif; font-size: 16px; margin: 0; padding: 0">
				                                                <td style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; box-sizing: border-box; font-family: Helvetica Neue, Helvetica, Helvetica, Arial, sans-serif; font-size: 16px; margin: 0; mso-line-height-rule: exactly;  text-align: left; vertical-align: top" align="left" valign="top">
				                                                    
				                                                    <table class="flexibleColumn" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; box-sizing: border-box; font-family: Helvetica Neue, Helvetica, Helvetica, Arial, sans-serif; font-size: 16px; margin: 0; max-width: 320px; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 0; table-layout: fixed" align="left" border="0" cellpadding="0" cellspacing="0">
				                                                        <tbody><tr style="box-sizing: border-box; font-family: Helvetica Neue, Helvetica, Helvetica, Arial, sans-serif; font-size: 16px; margin: 0; padding: 0">
				                                                            <td style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; box-sizing: border-box; font-family: Helvetica Neue, Helvetica, Helvetica, Arial, sans-serif; font-size: 16px; margin: 0; mso-line-height-rule: exactly; padding: 15px 0 10px; text-align: left; vertical-align: top" align="left" valign="top">
				                                                               <font size="8" color="#3db01a" style="font-size:40px;"><b>DAF</b></font>
				                                                            </td>
				                                                        </tr>
				                                                    </tbody></table>
				                                                    
				                                                    <table class="flexibleColumn" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; box-sizing: border-box; font-family: Helvetica Neue, Helvetica, Helvetica, Arial, sans-serif; font-size: 16px; margin: 0; max-width: 320px; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 0; table-layout: fixed" align="right" cellpadding="0" cellspacing="0">
				                                                        <tbody><tr style="box-sizing: border-box; font-family: Helvetica Neue, Helvetica, Helvetica, Arial, sans-serif; font-size: 16px; margin: 0; padding: 0">
				                                                            <td class="rightCol" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; box-sizing: border-box; color: #999999; font-family: Helvetica Neue, Helvetica, Helvetica, Arial, sans-serif; font-size: 16px; margin: 0; mso-line-height-rule: exactly; padding: 25px 0 10px; text-align: right; vertical-align: top" align="right" valign="top">
				                                                                Reset&nbsp;your&nbsp;password.
				                                                            </td>
				                                                        </tr>
				                                                    </tbody></table>
				                                                    
				                                                </td>
				                                            </tr>
				                                        </tbody></table>
				                                    </td>
				                                </tr>

				                                <tr style="box-sizing: border-box; font-family: Helvetica Neue, Helvetica, Helvetica, Arial, sans-serif; font-size: 16px; margin: 0; padding: 0">
				                                    <td id="emailHeader" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; box-sizing: border-box; font-family: Helvetica Neue, Helvetica, Helvetica, Arial, sans-serif; font-size: 16px; margin: 0; mso-line-height-rule: exactly; padding: 0; vertical-align: top" align="center" valign="top">
				                                        <table class="emailContainer" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; box-sizing: border-box; font-family: Helvetica Neue, Helvetica, Helvetica, Arial, sans-serif; font-size: 16px; margin: 0; max-width: 585px; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 0; table-layout: fixed" align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
				                                            <tbody><tr style="box-sizing: border-box; font-family: Helvetica Neue, Helvetica, Helvetica, Arial, sans-serif; font-size: 16px; margin: 0; padding: 0">
				                                                <td class="content" style="-ms-text-size-adjust: 100%;  -webkit-text-size-adjust: 100%; box-sizing: border-box; display: block; font-family: Helvetica Neue, Helvetica, Helvetica, Arial, sans-serif; font-size: 16px; margin: 0 auto; max-width: 640px; mso-line-height-rule: exactly; padding: 20px 20px 0; text-align: center; vertical-align: top" align="center" valign="top">
				                                                    <h1 style="box-sizing: border-box; color: #39b449; display: block !important; font-family: Helvetica Neue, Helvetica, Arial, Lucida Grande, sans-serif; font-size: 22px; font-weight: bold; line-height: 1.2;  padding: 0 0 15px;">It'."'".'s&nbsp;OK&nbsp;to&nbsp;forget.</h1>
				                                                    <span class="spnText">Just&nbsp;click&nbsp;the&nbsp;link&nbsp;below&nbsp;<strong>within&nbsp;24&nbsp;hours&nbsp;</strong>to&nbsp;reset&nbsp;your&nbsp;password.</span>
				                                                    <br style="box-sizing: border-box; font-family: Helvetica Neue, Helvetica, Helvetica, Arial, sans-serif; font-size: 16px; margin: 0; padding: 0"><br style="box-sizing: border-box; font-family: Helvetica Neue, Helvetica, Helvetica, Arial, sans-serif; font-size: 16px; margin: 0; padding: 0">
				                                                    <table class="cta" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; border-collapse: collapse; box-sizing: border-box; font-family: Helvetica Neue, Helvetica, Helvetica, Arial, sans-serif; font-size: 16px; margin: 0 auto; mso-table-lspace: 0pt; mso-table-rspace: 0pt; padding: 0; table-layout: fixed" align="center" cellpadding="0" cellspacing="0">
				                                                        <tbody><tr style="box-sizing: border-box; font-family: Helvetica Neue, Helvetica, Helvetica, Arial, sans-serif; font-size: 16px; margin: 0; padding: 0">
				                                                            <td class="cta-blue-gradient" style="-moz-border-radius: 4px; -ms-text-size-adjust: 100%; -webkit-border-radius: 4px; -webkit-text-size-adjust: 100%; background: #1574bb linear-gradient(top, #2a87c7, #1a6599); border-radius: 4px; box-sizing: border-box; color: #FFFFFF; display: inline-block; font-family: Helvetica Neue, Helvetica, Helvetica, Arial, sans-serif; font-size: 22px; font-weight: normal; line-height: 22px; margin: 0; mso-line-height-rule: exactly; padding: 15px 60px; text-align: center; text-decoration: none; vertical-align: top" align="center" bgcolor="#1574bb" valign="top">
				                                                                <a target="_blank" href="'. $baseurl . '/adminlogin/forgotpassword?token='. $hash .'" style="-ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; box-sizing: border-box; color: #FFFFFF; display: block; font-family: Helvetica Neue, Helvetica, Helvetica, Arial, sans-serif; font-size: 16px; font-weight: bold; letter-spacing: 1px; margin: 0; mso-line-height-rule: exactly; padding: 0; text-decoration: none; text-shadow: 0px -1px 2px #333333">Reset&nbsp;Password</a>
				                                                            </td>
				                                                        </tr>
				                                                    </tbody></table>
				                                                    <br style="box-sizing: border-box; font-family: Helvetica Neue, Helvetica, Helvetica, Arial, sans-serif; font-size: 16px; margin: 0; padding: 0">
				                                                    <span class="spnText1">
				                                                        For&nbsp;your&nbsp;security,&nbsp;this&nbsp;link&nbsp;expires&nbsp;in<span class="resize">&nbsp;24&nbsp;hours.</span>
				                                                    </span>
				                                                </td>
				                                            </tr>
				                                        </tbody></table>
				                                    </td>
				                                </tr>

				                            </tbody></table>
				                            
				                        </td>
				                    </tr>
				                </tbody></table>
				            </center> 

				<style type="text/css">
				body{font-size:14px;font-family:arial,verdana,sans-serif;line-height:1.666;padding:0;margin:0;overflow:auto;white-space:normal;word-wrap:break-word;min-height:100px}
				td, input, button, select, body{font-family:Helvetica, "Microsoft Yahei", verdana}
				pre {white-space:pre-wrap;white-space:-moz-pre-wrap;white-space:-pre-wrap;white-space:-o-pre-wrap;word-wrap:break-word;width:95%}
				th,td{font-family:arial,verdana,sans-serif;line-height:1.666}
				img{ border:0}
				header,footer,section,aside,article,nav,hgroup,figure,figcaption{display:block}
				</style>

				<style id="ntes_link_color" type="text/css">a,td a{color:#064977}</style>
				</body></html>
		';

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, 'api:key-061710f7633b3b2e2971afade78b48ea');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($ch, CURLOPT_URL, 
		      'https://api.mailgun.net/v3/sandboxa8b6f44a159048db93fd39fc8acbd3fa.mailgun.org/messages');
		curl_setopt($ch, CURLOPT_POSTFIELDS, 
		        array('from' => 'Dwight Schrute <postmaster@sandboxa8b6f44a159048db93fd39fc8acbd3fa.mailgun.org>',
		              'to' => $user->username . ' <' . $user->email . '>',
		              'subject' => "You have forgot your passowrd.",
		              'text' => $content));
		$result = curl_exec($ch);
		curl_close($ch);
		print_r($result);
	}

	/*--------------------------------------------------------------------------------------------------------
		Submit device token, udid
	_________________________________________________________________________________________________________*/
	public function api_entry_subscribeAPN() {
		parent::validateParams(array('user', 'udid', 'devicetoken'));

		$users = $this->Mdl_Users->get($_POST["user"]);

		if (!$this->Mdl_Users->get($_POST["user"]))			parent::returnWithErr("User id is not valid.");

		$user = $this->Mdl_Users->update(array(
			'id' => $_POST["user"],
			'udid' => $_POST["udid"],
			'devicetoken' => $_POST["devicetoken"]
			));

		parent::returnWithoutErr("Subscription has been done successfully.", $user);
	}

	/*--------------------------------------------------------------------------------------------------------
		Make device token to void, udid
	_________________________________________________________________________________________________________*/
	public function api_entry_unsubscribeAPN() {
		parent::validateParams(array('user' ));

		$users = $this->Mdl_Users->get($_POST["user"]);

		if (!$this->Mdl_Users->get($_POST["user"]))			parent::returnWithErr("User id is not valid.");

		$user = $this->Mdl_Users->update(array(
			'id' => $_POST["user"],
			'udid' => '',
			'devicetoken' => ''
			));

		parent::returnWithoutErr("Unsubscription has been done successfully.", $user);
	}


	/*--------------------------------------------------------------------------------------------------------
		Get profile ..
	_________________________________________________________________________________________________________*/
	public function api_entry_getprofilefromqbid() {
		parent::validateParams(array('qbid'));

		$user = $this->Mdl_Users->getAll("qbid", $_POST["qbid"]);

		if (count($user) == 0  || $user[0] == null)
			parent::returnWithErr("QBID is not valid.");

		unset($user[0]->password);

		parent::returnWithoutErr("User profile fetched successfully.", $user[0]);
	}

	/*--------------------------------------------------------------------------------------------------------
		Get profile from qbid ..
	_________________________________________________________________________________________________________*/
	public function api_entry_getprofile() {
		parent::validateParams(array('user'));

		$user = $this->Mdl_Users->get($_POST["user"]);

		if ($user == null)
			parent::returnWithErr("User id is not valid.");

		unset($user->password);

		$this->load->model('Mdl_Requests');
		$this->load->model('Mdl_Prays');

		$user->ipray_praying_for_me = 0;
		$user->ipray_i_am_praying_for = 0;
		$user->ipray_request_attended = 0;

		$prays = $this->Mdl_Prays->getAll();
		$requests = array();

		foreach ($prays as $key => $val) {
			$request = $this->Mdl_Requests->get($val->request);
			$prayer = $this->Mdl_Users->get($val->prayer);

			if ($_POST["user"] == $request->host) {
				if ($val->status == 1)	$user->ipray_request_attended++;
				$user->ipray_praying_for_me++;
			}
			if ($_POST["user"] == $val->prayer) {
				$user->ipray_i_am_praying_for++;
			}
		}

		parent::returnWithoutErr("User profile fetched successfully.", $user);
	}

	/*--------------------------------------------------------------------------------------------------------
		Set profile ..
	_________________________________________________________________________________________________________*/
	public function api_entry_setprofile() {
		parent::validateParams(array('user'));

		$user = $this->Mdl_Users->get($_POST["user"]);

		if ($user == null)
			parent::returnWithErr("User id is not valid.");

		$arg = $this->safeArray(array('fullname', 'avatar', 'church', 'city', 'province', 'bday', 'mood'), $_POST);

		$arg['id'] = $_POST["user"];

		if (count($arg) == 1)
			parent::returnWithErr("You should pass the profile 1 entry at least to update.");

		$user = $this->Mdl_Users->update($arg);

		if ($user == null)
			parent::returnWithErr("Profile has not been updated.");

		parent::returnWithoutErr("Profile has been updated successfully.", $user);
	}


	/*--------------------------------------------------------------------------------------------------------
		Make friends ...
	_________________________________________________________________________________________________________*/
	public function api_entry_sendnotification() {
		parent::validateParams(array('sender', 'receiver', 'subject'));

		if(!$this->Mdl_Users->get($_POST['sender']))		parent::returnWithErr("Sender is not valid");
		if(!$this->Mdl_Users->get($_POST['receiver']))		parent::returnWithErr("Receiver is not valid");

		$sender = $this->Mdl_Users->get($_POST['sender']);
		$receiver = $this->Mdl_Users->get($_POST['receiver']);

		unset($sender->password);
		unset($receiver->password);

		if 		($_POST['subject'] == "ipray_sendinvitation") {
			$msg = $sender->username . " has invited you.";
		}
		else if ($_POST['subject'] == "ipray_acceptinvitation") {
			$msg = $sender->username . " has accepted your invitation.";

			// sender ---> receiver 
			$this->Mdl_Users->makeFriends($_POST["sender"], $_POST["receiver"]);
		}
		else if ($_POST['subject'] == "ipray_rejectinvitation") {
			$msg = $sender->username . " has rejected your invitation.";
		}
		else if ($_POST['subject'] == 'ipray_sendprayrequest') {
			parent::validateParams(array('request'));
		}
		else if ($_POST['subject'] == 'ipray_acceptprayrequest') {
			parent::validateParams(array('request'));

			
		}
		else if ($_POST['subject'] == 'ipray_rejectprayrequest') {
			parent::validateParams(array('request'));

			
		}
		else {
			parent::returnWithErr("Unknown subject is requested.");
		}

		if ($receiver->devicetoken == "" || !isset($receiver->devicetoken))
			parent::returnWithErr("User didn't subscribe.");

		$payload = array(
			'sound' => "default",
			'subject' => $_POST['subject'],
			'alert' => $msg,
			'sender' => $sender,
			'receiver' => $receiver
			);

		if (($failedCnt = $this->qbhelper->sendPN($receiver->devicetoken, json_encode($payload))) == 0) {
			$this->load->model('Mdl_Notifications');
			$this->Mdl_Notifications->create(array(
				'subject' => $_POST['subject'],
				'message' => $msg,
				'sender' => $sender->id,
				'receiver' => $receiver->id
				));

			parent::returnWithoutErr("Contact request has been sent successfully.");
		}
		else {
			parent::returnWithErr($failedCnt . " requests have not been sent.");
		}
		
	}

	/*--------------------------------------------------------------------------------------------------------
		Pray ...
	_________________________________________________________________________________________________________*/
	public function api_entry_pray() {
		parent::validateParams(array('prayer', 'subject', 'request'));

		$this->load->model('Mdl_Requests');
		$this->load->model('Mdl_Prays');


		if(!($prayer = $this->Mdl_Users->get($_POST['prayer'])))			parent::returnWithErr("Prayer is not valid");
		if(!($request = $this->Mdl_Requests->get($_POST['request'])))		parent::returnWithErr("Request id is not valid");
		if(!($host = $this->Mdl_Users->get($request->host)))				parent::returnWithErr("Unknown request host.");

		if ($request->type != "REQ_COMMON") 								parent::returnWithErr("Invalid request type. " . $request->type);

		unset($prayer->password);
		unset($host->password);

		if ($host->id == $prayer->id)
			parent::returnWithErr("You can't pray for yourself.");

		if 		($_POST['subject'] == 'ipray_sendprayrequest') {
			$msg = $prayer->username . " would like to pray for you.";

			$sender = $prayer;
			$receiver = $host;
			$status = 0;
		}
		else if ($_POST['subject'] == 'ipray_answerprayrequest') {
			$msg = $prayer->username . " accepted your pray request.";

			$sender = $host;
			$receiver = $prayer;
			$status = 1;
		}
		else {
			parent::returnWithErr("Unknown subject is requested.");
		}

		if ($receiver->devicetoken == "" || !isset($receiver->devicetoken))
			parent::returnWithErr("User didn't subscribe.");

		$pray = $this->Mdl_Prays->create(array(
				'request' => $request->id,
				'prayer' => $prayer->id,
				'status' => $status
				));

		$payload = array(
			'sound' => "default",
			'subject' => $_POST['subject'],
			'alert' => $msg,
			'sender' => $sender,
			'receiver' => $receiver,
			'request' => $request,
			'pray_id' => $pray['id'],
			'meta' => json_encode(array('request' => $request))
			);



		if (($failedCnt = $this->qbhelper->sendPN($host->devicetoken, json_encode($payload))) == 0) {
			$this->load->model('Mdl_Notifications');
			$this->Mdl_Notifications->create(array(
				'subject' => $_POST['subject'],
				'message' => $msg,
				'sender' => $sender->id,
				'receiver' => $receiver->id,
				'meta' => json_encode(array('request' => $request))
				));

			parent::returnWithoutErr("Contact request has been sent successfully.");
		}
		else {
			parent::returnWithErr($failedCnt . " requests have not been sent.");
		}
		
	}
}

?>
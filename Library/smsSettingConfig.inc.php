<?php
//-------------------------------------------------------
// Purpose: This will be included at the top of dbconfig.inc.php file. This file contains Global declaration of variables which are being used through out the application such as SMS variables.
//
// Author : Parveen Sharma
// Created on : (27.03.2011 )
// Copyright 2008-2009: syenergy Technologies Pvt. Ltd.
//--------------------------------------------------------

// SMS variables & max length detail
define('SMS_MAX_LENGTH','160');  // maximum length per sms
define('SMS_TEMPLATE_DISPLAY',0); //0 --> SMS Template not used, 1 --> SMS Template used
define('PROXY_ID','192.168.2.1:8080'); // Proxy IP with Port number_format
define('ENABLE_PROXY',1); // 0 --> IF PROXY disabled, 1 --> If proxy enabled


/*
define('SMS_GATEWAY_USER_VARIABLE','usr');
define('SMS_GATEWAY_PASS_VARIABLE','pwd');
define('SMS_GATEWAY_NUMBER_VARIABLE','ph'); // ph takes input as mobile no
define('SMS_GATEWAY_MESSAGE_VARIABLE','text'); // text is the message
define('SMS_GATEWAY_SNDR_VARIABLE','sndr'); // Sender variable
define('SMS_GATEWAY_SNDR_VALUE','db'); // Sender value
// SMS account detail
define('SMS_GATEWAY_USERNAME','13022');
define('SMS_GATEWAY_PASSWORD','syenergy');
define('SMS_GATEWAY_URL','http://70.36.96.162/send.php');  // Gateway URL that sends SMS
*/
define('SMS_GATEWAY_USER_VARIABLE','usr');
define('SMS_GATEWAY_PASS_VARIABLE','pwd');
define('SMS_GATEWAY_NUMBER_VARIABLE','ph'); // ph takes input as mobile no
define('SMS_GATEWAY_MESSAGE_VARIABLE','text'); // text is the message
define('SMS_GATEWAY_SNDR_VARIABLE','sndr'); // Sender variable
define('SMS_GATEWAY_SNDR_VALUE','-UNIVPD'); // Sender value
/*---------------------------------Airtel SMS SETTINGS-----------------------------------------------------------*/ 
//define('SMS_GATEWAY_USERNAME','23821');
//define('SMS_GATEWAY_PASSWORD','noesis');
//define('SMS_GATEWAY_URL','http://cp.smscindia.com/API/WebSMS/Http/v1.0a/index.php');  // Gateway URL that sends SMS
//define('SMS_GATEWAY_URL','http://SMS.NOESISINFOWAY.COM/lsend.php');  // Gateway URL that sends SMS


/*
SEND SMS --> http://SMS.NOESISINFOWAY.COM/send.php?usr=23821&pwd=< pass>&ph=< mob1,mob2,mob3>&sndr=< sender>&text=< msg>
FLASH SMS --> http://SMS.NOESISINFOWAY.COM/send.php?usr=23821&pwd=< pass>&flash=1&ph=< mob1,mob2,mob3>&sndr=< sender>&text=< msg>
LONG SMS --> http://SMS.NOESISINFOWAY.COM/lsend.php?usr=23821&pwd=< pass>&ph=< mob1,mob2,mob3>&sndr=< sender>&text=< msg>
Binary --> http://SMS.NOESISINFOWAY.COM/bsend.php?usr=23821&pwd=< pass>&ph=< mob1,mob2>&sndr=< sender>&udh=< udh>&text=< msg>
SCHEDULE   http://SMS.NOESISINFOWAY.COM/send.php?usr=23821&pwd=< pass>&ph=< mob1,Mob2>&sndr=< sender>&time=< UNIX TIME>&text=< msg>
Check Report-> http://SMS.NOESISINFOWAY.COM/rep.php?usr=23821&pwd=< pass>&msgid=< xx,xx2,xx3>
Check Bal-> http://SMS.NOESISINFOWAY.COM/bal.php?usr=23821&pwd=< pass>
Change PSWd-> http://SMS.NOESISINFOWAY.COM/pwd.php?usr=23821&pwd=< pass>&new=< NewPassowrd>
*/


/*---------------------------------Reliance SMS SETTINGS-----------------------------------------------------------*/

define('SMS_GATEWAY_USERNAME','18682');
define('SMS_GATEWAY_PASSWORD','123456');
define('SMS_GATEWAY_URL','http://144.76.77.197//lsend.php');  // Gateway URL that sends SMS 
define('SMS_GATEWAY_VERIFICATION_URL','NULL');

/*SEND SMS         http://144.76.77.197//send.php?usr=18682&pwd=< pass>&ph=< mob1,mob2,mob3>&sndr=< sender>&csndr=< Reliancesender>&text=< msg>
Check Report     http://144.76.77.197//rep.php?usr=18682&pwd=< pass>&msgid=< xx,xx2,xx3>
LONG SMS         http://144.76.77.197//lsend.php?usr=18682&pwd=< pass>&ph=< mob1,mob2,mob3>&sndr=< sender>&csndr=< Reliancesender>&text=< msg>
Check Balance   http://144.76.77.197//bal.php?usr=18682&pwd=< pass>
Change Password   http://144.76.77.197//pwd.php?usr=18682&pwd=< pass>&new=< NewPassowrd>
*/

?>

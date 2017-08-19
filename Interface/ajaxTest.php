<?php
/*
$fp = fopen("http://dipanjan", "r") or die("Could not contact");
$page_contents = "";

while ($new_text = fread($fp, 100)) {
 $page_contents .= $new_text;
}

echo $page_contents ;
*/

$username = "admin";

$password = "admin";

$cookie = getCookiesFolder($username);

$postData = "ltmpl=default&ltmplcache=2&continue=http%3A%2F%2Fmail.google.com%2Fmail%2F%3F&service=mail&rm=false&ltmpl=default&hl=en&ltmpl=default&Email=$username&Passwd=$password&rmShown=1&signIn=Sign+in&asts=";

//$postData = "ltmpl=default&ltmplcache=2&continue=http%3A%2F%2Fmail.google.com%2Fmail%2F%3F&service=mail&rm=false&ltmpl=default&hl=en&ltmpl=default&Email=$username&Passwd=$password&rmShown=1&signIn=Sign+in&asts=";

//$postData ="https://www.google.com/accounts/ServiceLogin?service=orkut&hl=en-US&rm=false&continue=http%3A%2F%2Fwww.orkut.com%2FRedirLogin.aspx%3Fmsg%3D0%26page%3Dhttp%253A%252F%252Fwww.orkut.com%252FHome.aspx%253Fhl%253Den%2526tab%253Dw0&cd=US&passive=true&skipvpage=true&sendvemail=false";


$postData ="continue=http%3A%2F%2Fwww.orkut.com%2FRedirLogin.aspx%3Fmsg%3D0&cd=IN&passive=true&skipvpage=true&sendvemail=false";

//$postData = "username=admin&password=admin&imgSubmit_x=1";
$url = "https://www.google.com/accounts/ServiceLoginAuth?service=mail";
//$url="http://dipanjan/HRMS/Interface/index.php";

http($url, $cookie, $postData, $timeoutInSeconcs = 25);

 

function getCookiesFolder($username) {

    $cookieFolder = ini_get('upload_tmp_dir') . '\\cookies\\';

    if (!file_exists($cookieFolder)) {

        mkdir($cookieFolder);

    }

 

    $cookie = $cookieFolder . sha1($username . time());

    return $cookie;

}

function http($url, $cookie, $postData = NULL, $timeoutInSeconcs = 25) {

   

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);

    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1');

    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);

    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    curl_setopt($ch, CURLOPT_TIMEOUT, $timeoutInSeconcs);    // The maximum number of seconds to allow cURL functions to execute.


/*For Gmail*/ 
/*

    if(strlen($referer)) {

         curl_setopt($ch, CURLOPT_REFERER, $referer);

    }

    else {

        curl_setopt($ch, CURLOPT_REFERER, '');

    }
*/
 

    if(isset($postData)) {

        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        if(!is_array($postData)) {

            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

        }

        else {

            curl_setopt($ch, CURLOPT_HTTPHEADER, Array());

        }

    }

    else {

        curl_setopt($ch, CURLOPT_POST, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, '');

        curl_setopt($ch, CURLOPT_HTTPHEADER, Array());

    }

 

    $result = curl_exec($ch);

    echo $result;

   

    print $curlErrorCode = curl_errno($ch);

    print $curlErrorMessage = curl_error($ch);

 

    //curl_close($ch);

    return true;

}
//done no changes
?>
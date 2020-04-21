<?php
// set the default timezone to use. Available since PHP 5.1
date_default_timezone_set("Asia/Tehran");
include('simple_html_dom.php');
const apiKey='4d01b936f4208cb1e57b7b859ce78c413e72761a971a1c69d92b84894a2aa8c6';
const receptor='09025149810';
const message='کد جدید پارس دیتا قرار گرفته است!';
const linenumber='2000235';
// --------------------------------------- Get Disscount Code and save it in $code Var ---------------
$html = file_get_html('https://www.parsdata.com/Default.aspx?ajax=1&sys=data&out=FDAjax&cul=fa-IR');
$code='';
foreach($html->find('.fdCode span') as $item)
{
   $code=($item)->innertext ;
}
// -------------------------------------- End  get Disscount Code and save it in $code Var ------------

//----------------------------------------- Get Now Date ---------------------------------------------

function nowDate(){
return $today = date("F j, Y, g:i a");   
}
//-------------------------------------- End Get Now Date ---------------------------------------------

//**-------------------------------Send voice Sms Function ---------------------------------------------- */

function sendSms(){
    $data = array(
        'receptor' => receptor,
        'message' => message,
    );
            
    $crl = curl_init('https://api.ghasedak.io/v2/voice/send/simple');
    curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($crl, CURLINFO_HEADER_OUT, true);
    curl_setopt($crl, CURLOPT_POST, true);
    curl_setopt($crl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($crl, CURLOPT_HTTPHEADER, array(
        'apikey: ' . apiKey)
    );
    $result = curl_exec($crl);
    if ($result === false) {
        $result_noti = 0; die();
    } else {
        $result_noti = 1; die();
    }
    // Close cURL session handle
    curl_close($crl);
}
//**-------------------------------Send voice Sms Function ---------------------------------------------- */


//**-------------------------------Send  Sms Function ---------------------------------------------- */
function sendSmsNumber(){
    $data = array(
        'receptor' => receptor,
        'message' => message,
        'linenumber'=>linenumber
    );
            
    $crl = curl_init('https://api.ghasedak.io/v2/sms/send/simple');
    curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($crl, CURLINFO_HEADER_OUT, true);
    curl_setopt($crl, CURLOPT_POST, true);
    curl_setopt($crl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($crl, CURLOPT_HTTPHEADER, array(
        'apikey: ' . apiKey)
    );
    $result = curl_exec($crl);
    if ($result === false) {
        $result_noti = 0; die();
    } else {
        $result_noti = 1; die();
    }
    // Close cURL session handle
    curl_close($crl);
}

//**-------------------------------Send  Sms Function ---------------------------------------------- */



//********************************Store $code in json file and check new code ********************************************


$json_data=array('code'=>$code);
$json_data=json_encode($json_data);
$string = file_get_contents("code.json");
$data=json_decode($string,true);
if($data['code']===$code){
$json_data=array('date'=>nowDate());
$json_data=json_encode($json_data);
file_put_contents('date.json', $json_data);
   echo 'code not changed !';
}
else{
   file_put_contents('code.json', $json_data);
   sendSms();
   sendSmsNumber();
   echo ' new code  changed !';
}
//********************************Store $code in json file and check new code ********************************************

?>
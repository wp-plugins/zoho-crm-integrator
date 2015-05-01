<?php
require_once('recaptchalib.php');
require('../../../wp-blog-header.php');
require_once('class.phpmailer.php');

$company=mysql_escape_string($_POST['company']);
$first_name=mysql_escape_string($_POST['first_name']);
$last_name=mysql_escape_string($_POST['last_name']);
$email=mysql_escape_string($_POST['email']);
$title=mysql_escape_string($_POST['title']);
$phone=mysql_escape_string($_POST['phone']);
$fax=mysql_escape_string($_POST['fax']);
$mobile=mysql_escape_string($_POST['mobile']);
$comments=mysql_escape_string($_POST['comments']);
$chkrecap=mysql_escape_string($_POST['recap']);
$sendemail=mysql_escape_string($_POST['sendemail']);

$form_data="";
if($company!="")
$form_data.='<FL val="Company">'.$company.'</FL>';

if($first_name!="")
$form_data.='<FL val="First Name">'.$first_name.'</FL>';

if($last_name!="")
$form_data.='<FL val="Last Name">'.$last_name.'</FL>';

if($email!="")
$form_data.='<FL val="Email">'.$email.'</FL>';

if($title!="")
$form_data.='<FL val="Title">'.$title.'</FL>';

if($phone!="")
$form_data.='<FL val="Phone">'.$phone.'</FL>';

if($fax!="")
$form_data.='<FL val="Fax">'.$fax.'</FL>';

if($mobile!="")
$form_data.='<FL val="Mobile">'.$mobile.'</FL>';

if($comments!="")
$form_data.='<FL val="Comments">'.$comments.'</FL>';

$options = get_option('my_option_name' );
$authtoken=$options['authtoken'];

if($chkrecap=="yes")
{
$recapoptions = get_option('zoho_crm_integrator_recaptcha_options' );
$privatekey=$recapoptions['private_key'];
$resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);
}
                                
                                


if ((!$resp->is_valid)&&($chkrecap=="yes")) {
    // What happens when the CAPTCHA was entered incorrectly
    die ("fail");
  } else {
    // Your code here to handle a successful verification
if($sendemail=="yes"){
$emailoptions = get_option('zoho_crm_integrator_email_options' );
$sendemailid=$emailoptions['emailid'];
$subject=$emailoptions['subject'];

$mail             = new PHPMailer();

$body             = '<table width="500" border="0">';

if($company!="")
$body.='<tr><td width="155" >Company :</td><td width="15">&nbsp;</td><td width="316">'.$company.'</td></tr>';

if($first_name!="")
$body.='<tr><td width="155" >First name :</td><td width="15">&nbsp;</td><td width="316">'.$first_name.'</td></tr>';

if($last_name!="")
$body.='<tr><td width="155" >Last name :</td><td width="15">&nbsp;</td><td width="316">'.$last_name.'</td></tr>';

if($email!="")
$body.='<tr><td width="155" >E-mail :</td><td width="15">&nbsp;</td><td width="316">'.$email.'</td></tr>';

if($title!="")
$body.='<tr><td width="155" >Title :</td><td width="15">&nbsp;</td><td width="316">'.$title.'</td></tr>';

if($phone!="")
$body.='<tr><td width="155" >Phone :</td><td width="15">&nbsp;</td><td width="316">'.$phone.'</td></tr>';

if($fax!="")
$body.='<tr><td width="155" >Fax :</td><td width="15">&nbsp;</td><td width="316">'.$fax.'</td></tr>';

if($mobile!="")
$body.='<tr><td width="155" >Mobile :</td><td width="15">&nbsp;</td><td width="316">'.$mobile.'</td></tr>';

if($comments!="")
$body.='<tr><td width="155" >Comments :</td><td width="15">&nbsp;</td><td width="316">'.$comments.'</td></tr>';


$body.='</table>';


$mail->SetFrom($email, $first_name.' '.$last_name);

$mail->AddReplyTo($email,$first_name.' '.$last_name);

$mail->Subject    = $subject;

$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

$mail->MsgHTML($body);


$mail->AddAddress($sendemailid);


if(!$mail->Send()) {
 // echo "Mailer Error: " . $mail->ErrorInfo;
} else {
 // echo "Message sent!";
}
}  



$url = 'https://crm.zoho.com/crm/private/xml/Leads/insertRecords';
$xmldata='<Leads>
<row no="1"><FL val="Lead Source">Web Download</FL>
'.$form_data.'
</row>
</Leads>';

$fields = array(
            'newFormat'=>1,
            'authtoken'=>$authtoken,
            'scope'=>'crmapi',            
			'xmlData'=>$xmldata
        );
$fields_string = NULL;
foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
$fields_string = rtrim($fields_string,'&');

	
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POST,1);
curl_setopt($ch, CURLOPT_POSTFIELDS,$fields_string);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
curl_setopt($ch, CURLOPT_HEADER      ,0);  // DO NOT RETURN HTTP HEADERS
curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);  // RETURN THE CONTENTS OF THE CALL


curl_exec($ch);

curl_close($ch);
}
?>
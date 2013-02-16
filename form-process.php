<?php
require('../../../wp-blog-header.php');

$company=mysql_escape_string($_POST['company']);
$first_name=mysql_escape_string($_POST['first_name']);
$last_name=mysql_escape_string($_POST['last_name']);
$email=mysql_escape_string($_POST['email']);
$title=mysql_escape_string($_POST['title']);
$phone=mysql_escape_string($_POST['phone']);
$fax=mysql_escape_string($_POST['fax']);
$mobile=mysql_escape_string($_POST['mobile']);


$options = get_option('my_option_name' );
$authtoken=$options['authtoken'];


$url = 'https://crm.zoho.com/crm/private/xml/Leads/insertRecords';
$xmldata='<Leads>
<row no="1">
<FL val="Lead Source">Web Download</FL>
<FL val="Company">'.$company.'</FL>
<FL val="First Name">'.$first_name.'</FL>
<FL val="Last Name">'.$last_name.'</FL>
<FL val="Email">'.$email.'</FL>
<FL val="Title">'.$title.'</FL>
<FL val="Phone">'.$phone.'</FL>
<FL val="Fax">'.$fax.'</FL>
<FL val="Mobile">'.$mobile.'</FL>
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

?>
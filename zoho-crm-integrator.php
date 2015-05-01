<?php
/*
Plugin Name: Zoho CRM Integrator
Plugin URI: 
Description: A simple wordpress plugin to integrate Wordpress with Zoho CRM. This plugin will help you to insert a form in any page or post by inserting a short code "[zoholead]". The form data will be used to insert a record into the Zoho CRM Lead. The form fields to display can be set through the shortcode. The plugin supports recaptcha in the form to stop spam and there is an option to send the form data to an email using the php's mail function. The settings page can be accessed on the left side bar.
Version: 1.2.0
Author: wp_candyman
Author URI: 
License: GPL2

Copyright 2013  Akhilesh  (email : )

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

require_once('recaptchalib.php');

function init_zoho_crm_integrator()
{
wp_enqueue_style('zoho-crm-integrator_css', plugins_url('/css/main.css', __FILE__));
wp_enqueue_script('jquery');
wp_enqueue_script('jquery-form');
wp_enqueue_script('zoho-crm-integrator_validate', plugins_url('/js/validate.js', __FILE__));
wp_enqueue_script('zoho-crm-integrator_form_submit',plugins_url('/js/ajax-submit-form.js', __FILE__));
}




function zohoLeadForm($atts)
{
$options = get_option('zoho_crm_integrator_recaptcha_options' );
$publickey=$options['public_key'];

extract( shortcode_atts( array(
		'recaptcha' => 'disable',
		'sendemail' => 'disable',
		'fields'	=> 'all',
	), $atts ) );

if($sendemail=="enable")
{
$emailhtml="<input type='hidden' name='sendemail' value='yes'/>";
}
else{
$emailhtml="";
}
if($recaptcha=="enable")
{
$recaptcha_html="<p><input type='hidden' name='recap' value='yes'/>";
$recaptcha_html.=recaptcha_get_html($publickey);
$recaptcha_html.="</p>";
}
else {
$recaptcha_html="";
}

$form_action=  plugins_url('/form-process.php',__FILE__);

if($fields=="all")
{
$form_string= "<p>
<label>Company : </label><br/>
<input type='text' name='company' id='company'/>
</p>

<p>
<label>First Name : </label><br/>
<input type='text' name='first_name' id='first_name'/>
</p>

<p>
<label>Last Name : </label><br/>
<input type='text' name='last_name' id='last_name'/>
</p>

<p>
<label>Email : </label><br/>
<input type='text' name='email' id='email'/>
</p>

<p>
<label>Title : </label><br/>
<input type='text' name='title' id='title'/>
</p>

<p>
<label>Phone : </label><br/>
<input type='text' name='phone' id='phone'/>
</p>

<p>
<label>Fax : </label><br/>
<input type='text' name='fax' id='fax'/>
</p>

<p>
<label>Mobile : </label><br/>
<input type='text' name='mobile' id='mobile'/>
</p>

<p>
<label>Comments : </label><br/>
<textarea rows='5' cols='40' name='comments' id='comments'></textarea>
</p>";
}
else{
$form_string="";
$fields_string=explode(",",$fields);
for($i=0;$i<count($fields_string);$i++)
{
if($fields_string[$i]=="company")
$form_string.="<p>
<label>Company : </label><br/>
<input type='text' name='company' id='company'/>
</p>";

if($fields_string[$i]=="first_name")
$form_string.="<p>
<label>First Name : </label><br/>
<input type='text' name='first_name' id='first_name'/>
</p>";

if($fields_string[$i]=="last_name")
$form_string.="<p>
<label>Last Name : </label><br/>
<input type='text' name='last_name' id='last_name'/>
</p>";

if($fields_string[$i]=="email")
$form_string.="<p>
<label>Email : </label><br/>
<input type='text' name='email' id='email'/>
</p>";

if($fields_string[$i]=="title")
$form_string.="<p>
<label>Title : </label><br/>
<input type='text' name='title' id='title'/>
</p>";

if($fields_string[$i]=="phone")
$form_string.="<p>
<label>Phone : </label><br/>
<input type='text' name='phone' id='phone'/>
</p>";

if($fields_string[$i]=="fax")
$form_string.="<p>
<label>Fax : </label><br/>
<input type='text' name='fax' id='fax'/>
</p>";

if($fields_string[$i]=="mobile")
$form_string.="<p>
<label>Mobile : </label><br/>
<input type='text' name='mobile' id='mobile'/>
</p>";

if($fields_string[$i]=="comments")
$form_string.="<p>
<label>Comments : </label><br/>
<textarea rows='5' cols='40' name='comments' id='comments'></textarea>
</p>";
}
}
$return_string= "<div class='lead_form_div'><form id='lead_form' accept-charset='UTF-8' method='POST' name='lead_form' action='$form_action' onsubmit='validate()'>"
.$form_string.$emailhtml.$recaptcha_html
."<p>
<input type='submit' name='submit' value='Submit'/>
</p>
</form></div>";
return $return_string;
}

function e_mail()
{
$update_action=  plugins_url('/options.php',__FILE__);
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo '<div class="wrap">';
	screen_icon('themes'); ?>
	<h2>Zoho CRM integrator options</h2>
	<form method="post" action="options.php"> 
	<?php
	settings_fields( 'zoho-crm-integrator_email_options_group' ); 
	do_settings_sections('email-settings');
	submit_button();
	echo '</form>';
	echo '</div>';
}

function email_settings_text()
{
echo '<p>Enter the E-mail to which the form data to be sent and the subject of the E-mail</p>';
}

function email_field()
{
$options = get_option('zoho_crm_integrator_email_options');
echo "<input id='emailid' name='zoho_crm_integrator_email_options[emailid]' size='40' type='text' value='{$options['emailid']}'/><br/>";
}

function subject_field()
{
$options = get_option('zoho_crm_integrator_email_options');
echo "<input id='subject' name='zoho_crm_integrator_email_options[subject]' size='40' type='text' value='{$options['subject']}'/><br/>";
}

function recaptcha()
{
$update_action=  plugins_url('/options.php',__FILE__);
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo '<div class="wrap">';
	screen_icon('themes'); ?>
	<h2>Zoho CRM integrator options</h2>
	<form method="post" action="options.php"> 
	<?php
	settings_fields( 'zoho-crm-integrator_recaptcha_options_group' ); 
	do_settings_sections('recaptcha-settings');
	submit_button();
	echo '</form>';
	echo '</div>';
}

function reCAPTCHA_settings_text(){
echo '<p>Enter the private key and public key from your re-CAPTCHA account at <a href="http://www.google.com/recaptcha" target="_blank">http://www.google.com/recaptcha</a> </p>';

}

function private_key_field(){
$options = get_option('zoho_crm_integrator_recaptcha_options');
echo "<input id='private_key' name='zoho_crm_integrator_recaptcha_options[private_key]' size='40' type='text' value='{$options['private_key']}'/><br/>";
}

function public_key_field(){
$options = get_option('zoho_crm_integrator_recaptcha_options');
echo "<input id='public_key' name='zoho_crm_integrator_recaptcha_options[public_key]' size='40' type='text' value='{$options['public_key']}'/><br/>";
}

function my_plugin_menu() {
	add_menu_page( 'Zoho CRM Integrator', 'Zoho CRM Integrator', 'activate_plugins', 'zoho-crm-integrator-main-menu','my_plugin_options');
	add_submenu_page( 'zoho-crm-integrator-main-menu', 'reCAPTCHA Settings', 'reCAPTCHA', 'activate_plugins', 'recaptcha-settings', 'recaptcha' );
	add_submenu_page( 'zoho-crm-integrator-main-menu', 'Mail Settings', 'E-mail', 'activate_plugins', 'mail-settings', 'e_mail' );
}

function my_plugin_options() {
$update_action=  plugins_url('/options.php',__FILE__);
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo '<div class="wrap">';
	screen_icon('themes'); ?>
	<h2>Zoho CRM integrator options</h2>
	<form method="post" action="options.php"> 
	<?php
	settings_fields( 'zoho-crm-integrator_options_group' ); 
	do_settings_sections('zoho-crm-integrator_option');
	submit_button();
	echo '</form>';
	echo '</div>';
}

function plugin_section_text() {
echo '<p>Enter the Auth Token for zoho CRM </p>';
echo '<p>You can get the Auth Token after logging into your CRM account and visiting <a href="https://accounts.zoho.com/apiauthtoken/create?SCOPE=ZohoCRM/crmapi" target="_blank">https://accounts.zoho.com/apiauthtoken/create?SCOPE=ZohoCRM/crmapi</a></p>';
}

function plugin_setting_authtoken() {
$options = get_option('my_option_name');
echo "<input id='authtoken' name='my_option_name[authtoken]' size='40' type='text' value='{$options['authtoken']}'/><br/>";
}


function register_my_setting() {
	register_setting( 'zoho-crm-integrator_options_group', 'my_option_name' ); 
	add_settings_section('plugin_main', 'Main Settings', 'plugin_section_text', 'zoho-crm-integrator_option');
	add_settings_field('authtoken', 'Auth Token', 'plugin_setting_authtoken', 'zoho-crm-integrator_option', 'plugin_main');
	
	register_setting( 'zoho-crm-integrator_recaptcha_options_group', 'zoho_crm_integrator_recaptcha_options' ); 
	add_settings_section('reCAPTCHA_settings', 'reCAPTCHA Settings', 'reCAPTCHA_settings_text', 'recaptcha-settings');
	add_settings_field('private_key', 'Private Key', 'private_key_field', 'recaptcha-settings', 'reCAPTCHA_settings');
	add_settings_field('public_key', 'Public Key', 'public_key_field', 'recaptcha-settings', 'reCAPTCHA_settings');
	
	register_setting( 'zoho-crm-integrator_email_options_group', 'zoho_crm_integrator_email_options' ); 
	add_settings_section('Email_settings', 'E-mail Settings', 'email_settings_text', 'email-settings');
	add_settings_field('emailid', 'Email Id', 'email_field', 'email-settings', 'Email_settings');
	add_settings_field('subject', 'E-mail Subject', 'subject_field', 'email-settings', 'Email_settings');
} 


if ( is_admin() ){ // admin actions
  add_action( 'admin_menu', 'my_plugin_menu' );
  add_action( 'admin_init', 'register_my_setting' );
} else {
  // non-admin enqueues, actions, and filters
  
add_action('init', 'init_zoho_crm_integrator');
add_shortcode('zoholead', 'zohoLeadForm'); 
}

?>
<?php
/*
Plugin Name: Zoho CRM Integrator
Plugin URI: 
Description: A simple wordpress plugin to integrate Wordpress with Zoho CRM. This plugin will help you to insert a pre-built form in any page or post by inserting a short code "[zoholead]". The form data will be used to insert a record into the Zoho CRM Lead. Currently the plugin supports only a pre-built form. The settings page can be accessed under the Wordpress settings menu on the left side bar.
Version: 1.0
Author: Akhilesh
Author URI: 
License: GPL2

Copyright 2013  Akhilesh  (email : akhileshk.biz@gmail.com)

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


function init_zoho_crm_integrator()
{
wp_enqueue_style('zoho-crm-integrator_css', plugins_url('/css/main.css', __FILE__));
wp_enqueue_script('jquery');
wp_enqueue_script('jquery-form');
wp_enqueue_script('zoho-crm-integrator_validate', plugins_url('/js/validate.js', __FILE__));
wp_enqueue_script('zoho-crm-integrator_form_submit',plugins_url('/js/ajax-submit-form.js', __FILE__));
}




function zohoLeadForm($form_action)
{
$form_action=  plugins_url('/form-process.php',__FILE__);
$return_string= "<div class='lead_form_div'><form id='lead_form' accept-charset='UTF-8' method='POST' name='lead_form' action='$form_action' onsubmit='validate()'>
<p>
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
<input type='submit' name='submit' value='Submit'/>
</p>
</form></div>";
return $return_string;
}



function my_plugin_menu() {
	add_options_page( 'Zoho crm integrator options', 'Zoho CRM integrator', 'manage_options', 'zoho-crm-integrator-options', 'my_plugin_options' );
}

function my_plugin_options() {
$update_action=  plugins_url('/options.php',__FILE__);
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo '<div class="wrap">';
	screen_icon(); ?>
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
echo '<p>You can get the Auth Token after logging into your CRM account and visiting https://accounts.zoho.com/apiauthtoken/create?SCOPE=ZohoCRM/crmapi</p>';
}

function plugin_setting_authtoken() {
$options = get_option('zoho-crm-integrator_options_group');
echo "<input id='authtoken' name='my_option_name[authtoken]' size='40' type='text' value='{$options['authtoken']}'/><br/>";
}


function register_my_setting() {
	register_setting( 'zoho-crm-integrator_options_group', 'my_option_name' ); 
	add_settings_section('plugin_main', 'Main Settings', 'plugin_section_text', 'zoho-crm-integrator_option');
	add_settings_field('authtoken', 'Auth Token', 'plugin_setting_authtoken', 'zoho-crm-integrator_option', 'plugin_main');
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
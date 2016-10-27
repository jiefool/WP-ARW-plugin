<?php
/*
Plugin Name: Modified Auto Responder Wizard
Plugin URI: http://autoresponderwizard.com
Description: Modified Auto Responder Wizard
Version: 1.0
Author: http://autoresponderwizard.com (modified by Jay Paul Aying)
Author URI: http://autoresponderwizard.com
*/
session_start();
include_once('arWizard.php');
$arp = new arWizard();
if (isset($arp))
{
	register_activation_hook( __FILE__, array($arp,'install') );
	register_deactivation_hook( __FILE__, array($arp,'deactivate') );
}
?>
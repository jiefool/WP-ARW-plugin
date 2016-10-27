<?php
if ( !class_exists('arWizard') ) :
	class arWizard
	{	
		var $plugin_url;
		var $plugin_image_url;
			
		function arWizard()
		{	
			global $table_prefix;
			
			$this->tablename = $table_prefix . "wptl";
			$this->plugin_url = trailingslashit( WP_PLUGIN_URL.'/'.dirname( plugin_basename(__FILE__) ));
			$this->plugin_image_url = trailingslashit( WP_PLUGIN_URL.'/'.dirname( plugin_basename(__FILE__) )).'images/';
			
			//actions
			add_action('admin_menu', array($this,'admin_menu'));
			add_action('admin_head', array($this,'arWizard_adminScripts'));			
		}
		
		function install(){
			global $wpdb;
			$arpSql = "CREATE TABLE IF NOT EXISTS `autoresponders` (
						  `autoresponder_id` int(11) NOT NULL AUTO_INCREMENT,
						  `autoresponder_name` varchar(100) NOT NULL,
						  `date_created` datetime NOT NULL,
						  PRIMARY KEY (`autoresponder_id`)
						) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";
			$wpdb->query($arpSql);
			
			$wpdb->insert('autoresponders',array('autoresponder_name'=>'Aweber','date_created'=>date('Y-m-d H:i:s',time())),array('%s','%s'));
			$wpdb->insert('autoresponders',array('autoresponder_name'=>'Get Response','date_created'=>date('Y-m-d H:i:s',time())),array('%s','%s'));
			$wpdb->insert('autoresponders',array('autoresponder_name'=>'iShoppingCart','date_created'=>date('Y-m-d H:i:s',time())),array('%s','%s'));
			$wpdb->insert('autoresponders',array('autoresponder_name'=>'Constant Contact','date_created'=>date('Y-m-d H:i:s',time())),array('%s','%s'));
			$wpdb->insert('autoresponders',array('autoresponder_name'=>'Mail Chimp','date_created'=>date('Y-m-d H:i:s',time())),array('%s','%s'));
			$wpdb->insert('autoresponders',array('autoresponder_name'=>'Infusionsoft','date_created'=>date('Y-m-d H:i:s',time())),array('%s','%s'));
			
			$cgvarSql = "CREATE TABLE IF NOT EXISTS `customer_global_vars` (
						  `cust_global_id` int(11) NOT NULL AUTO_INCREMENT,
						  `cust_seq_id` int(11) NOT NULL,
						  `var_name` varchar(200) NOT NULL,
						  `var_value` varchar(1000) NOT NULL,
						  `customer_id` int(11) NOT NULL,
						  `cust_var_official_name` varchar(200) NOT NULL,
						  PRIMARY KEY (`cust_global_id`)
						) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
			$wpdb->query($cgvarSql);
			
			/*$wpdb->query("INSERT INTO `customer_global_vars` (`cust_global_id`, `cust_seq_id`, `var_name`, `var_value`, `customer_id`, `cust_var_official_name`) VALUES
(1, 1, 'INTRO', '<p>dsfsdfsf</p>\n\n', 0, '{GLOBAL_INTRO} '),
(2, 1, 'CLOSE', '<p>dsfsdfsf</p>\n\n', 0, '{GLOBAL_CLOSE}'),
(3, 1, 'SIGNOFF', '<p>dsfsdfsf</p>\n\n', 0, '{GLOBAL_SIGNOFF}'),
(4, 1, 'FOOTER', '<p>dsfsdfsf</p>\n\n', 0, '{GLOBAL_FOOTER}');");*/


			$csSql = "CREATE TABLE IF NOT EXISTS `customer_sequences` (
					  `customer_sequence_id` int(11) NOT NULL AUTO_INCREMENT,
					  `user_id` int(11) NOT NULL,
					  `customer_sequence_name` varchar(255) NOT NULL,
					  `customer_id` int(11) NOT NULL,
					  `customer_auto_name` int(11) NOT NULL,
					  `customer_sequence_status` varchar(255) NOT NULL,
					  `customer_sequence_type` int(11) NOT NULL,
					  PRIMARY KEY (`customer_sequence_id`)
					) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
			$wpdb->query($csSql);
			
			$ctSql = "CREATE TABLE IF NOT EXISTS `customer_template` (
					  `cust_temp_id` int(200) NOT NULL AUTO_INCREMENT,
					  `customer_id` int(200) NOT NULL,
					  `template_id` int(200) NOT NULL,
					  `template_content` text NOT NULL,
					  `customer_sequence_id` int(11) NOT NULL,
					  `status` varchar(64) NOT NULL,
					  PRIMARY KEY (`cust_temp_id`)
					) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";
			$wpdb->query($ctSql);
			
			$cttSql = "CREATE TABLE IF NOT EXISTS `customer_tracker_table` (
						  `tracker_id` int(11) NOT NULL AUTO_INCREMENT,
						  `customer_id` int(11) NOT NULL,
						  `customer_sequence_id` int(11) NOT NULL,
						  `customer_template_name` varchar(200) NOT NULL,
						  `customer_template_status` varchar(64) NOT NULL,
						  `customer_template_id` int(11) NOT NULL,
						  PRIMARY KEY (`tracker_id`)
						) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
			$wpdb->query($cttSql);
			
			$gvSql = "CREATE TABLE IF NOT EXISTS `global_variables` (
					  `global_variable_id` int(11) NOT NULL AUTO_INCREMENT,
					  `global_variable_name` varchar(200) NOT NULL,
					  `global_flag` tinyint(1) NOT NULL DEFAULT '1',
					  `autoresponder_id` int(11) NOT NULL,
					  `global_variable_value` varchar(200) NOT NULL,
					  PRIMARY KEY (`global_variable_id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
			$wpdb->query($gvSql);
			$wpdb->query("INSERT INTO `global_variables` (`global_variable_name`, `global_flag`, `autoresponder_id`, `global_variable_value`) VALUES
							('{SEQ_AR_NAME}', 1, 1, '{!firstname}'),
							('{SEQ_AR_NAME}', 1, 2, '[[firstname]]'),
							('{SEQ_AR_EMAIL}', 1, 2, '[[email]]'),
							('{SEQ_AR_CITY}', 1, 2, '[[geo_city]]'),
							('{SEQ_AR_EMAIL}', 1, 1, '{!email}'),
							('{SEQ_AR_NAME}', 1, 3, '%$name$%'),
							('{SEQ_AR_EMAIL}', 1, 3, '%$email$%'),
							('{SEQ_AR_CITY}', 1, 3, '%$city$%'),
							('{SEQ_AR_CITY}', 1, 1, '{!geog_city}');");
			
			$gvvSql = "CREATE TABLE IF NOT EXISTS `global_variable_vars` (
					  `global_vars_id` int(11) NOT NULL AUTO_INCREMENT,
					  `global_vars_official_name` varchar(200) NOT NULL,
					  `global_vars_flag` tinyint(1) NOT NULL,
					  `global_vars_info` varchar(200) NOT NULL,
					  `global_vars_field` varchar(500) NOT NULL,
					  `global_vars_type` varchar(200) NOT NULL,
					  `global_vars_name` varchar(1000) NOT NULL,
					  `dropdown_values` text NOT NULL,
					  PRIMARY KEY (`global_vars_id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1";
			$wpdb->query($gvvSql);
			$wpdb->query("INSERT INTO `global_variable_vars` (`global_vars_id`, `global_vars_official_name`, `global_vars_flag`, `global_vars_info`, `global_vars_field`, `global_vars_type`, `global_vars_name`, `dropdown_values`) VALUES
(36, '{GLOBAL_INTRO} ', 0, 'Type your email introduction here. Something like Hello or Hey works great.', '<input type=\"text\" name=\"INTRO\" size=\"40\" class=\"required\" />', 'Textbox', 'INTRO', ''),
(37, '{GLOBAL_CLOSE}', 0, 'This is the signoff you would close your emails with. Enter something like To your success or Speak soon or Chat soon here. \r\n', '<input type=\"text\" name=\"CLOSE\" size=\"40\" class=\"required\" />', 'Textbox', 'CLOSE', ''),
(38, '{GLOBAL_SIGNOFF}', 0, 'This is your name, as it would appear at the bottom of your emails.\r\n', '<input type=\"text\" name=\"SIGNOFF\" size=\"39\" class=\"required\" />', 'Textbox', 'SIGNOFF', ''),
(39, '{GLOBAL_FOOTER}', 0, 'This is your footer. Put any links you want in all your emails here. Put information on how people can unsubscribe here, too.', '<textarea name=\"FOOTER\" rows=\"10\" cols=\"30\" class=\"required\" ></textarea>', 'Multiline', 'FOOTER', '');");
			
		
			
			$sSql = "CREATE TABLE IF NOT EXISTS `sequence` (
					  `sequence_id` int(11) NOT NULL AUTO_INCREMENT,
					  `sequence_name` varchar(64) NOT NULL,
					  `sequence_description` text NOT NULL,
					  `date_created` varchar(200) NOT NULL,
					  `sequence_category` varchar(200) NOT NULL,
					  `sequence_status` tinyint(4) NOT NULL DEFAULT '0',
					  PRIMARY KEY (`sequence_id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
			$wpdb->query($sSql);
			$wpdb->query("INSERT INTO `sequence` (`sequence_name`, `sequence_description`, `date_created`, `sequence_category`, `sequence_status`) VALUES
( '10 Email Affiliate Promo Sequence', '10 Email Affiliate Promo Sequence', '".date('Y-m-d H:i:s',time())."', 'Prospect sequence', 1),
( '2 Email Affiliate Mini Promo', '2 Email Affiliate Mini Promo', '".date('Y-m-d H:i:s',time())."', 'Prospect sequence', 1),
( '3 Email Affiliate Mini Promo', '3 Email Affiliate Mini Promo', '".date('Y-m-d H:i:s',time())."', 'Prospect sequence', 1),
( '3 Day Event Sequence', '3 Day Event Sequence', '".date('Y-m-d H:i:s',time())."', 'Event sequence', 1),
( '7 Day Event Sequence', '7 Day Event Sequence', '".date('Y-m-d H:i:s',time())."', 'Event sequence', 0),
( '7 Email Customer Consumption Sequence', '7 Email Customer Consumption Sequence', '".date('Y-m-d H:i:s',time())."', 'Customer sequence', 0),
( '7 Email Prospect Converter Followup ', '7 Email Prospect Converter Followup ', '".date('Y-m-d H:i:s',time())."', 'Prospect sequence', 0);");
			
			$tSql = "CREATE TABLE IF NOT EXISTS `template` (
					  `template_id` int(11) NOT NULL AUTO_INCREMENT,
					  `template_name` varchar(64) NOT NULL,
					  `template_content` text NOT NULL,
					  `date_created` varchar(200) NOT NULL,
					  `status` tinyint(1) NOT NULL DEFAULT '0',
					  `template_description` varchar(200) NOT NULL,
					  `sequence_id` int(100) NOT NULL,
					  PRIMARY KEY (`template_id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
			$wpdb->query($tSql);			
			$tSqlInsert = file_get_contents(dirname(__FILE__) . '/sql/template_insert.php');
			$wpdb->query($tSqlInsert);
			
			$vSql = "CREATE TABLE IF NOT EXISTS `variables` (
					  `variable_id` int(11) NOT NULL AUTO_INCREMENT,
					  `flag` tinyint(1) NOT NULL DEFAULT '1',
					  `variable_name` varchar(64) NOT NULL,
					  `variable_info` varchar(200) NOT NULL,
					  `field` varchar(2000) NOT NULL,
					  `sequence_id` int(11) NOT NULL,
					  `type` varchar(200) NOT NULL,
					  `dropdown_values` text NOT NULL,
					  PRIMARY KEY (`variable_id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
			$wpdb->query($vSql);	
			$vSqlInsert = file_get_contents(dirname(__FILE__) . '/sql/variable_insert.php');
			$wpdb->query($vSqlInsert);
			
			$myuploaddir = wp_upload_dir();
			$optdir = $myuploaddir['basedir']."/arw"; 
			
			if (!file_exists($optdir)) {
				mkdir($optdir, 0777);
			} 		
		}
		function deactivate(){
			global $wpdb;
			$wpdb->query('DROP TABLE autoresponders');
			$wpdb->query('DROP TABLE global_variables');
			$wpdb->query('DROP TABLE global_variable_vars');
			$wpdb->query('DROP TABLE sequence');
			$wpdb->query('DROP TABLE template');
			$wpdb->query('DROP TABLE variables');
		}
		
		function admin_menu(){
			$arWizard = add_menu_page('AR Wizard Member', 'AR Wizard Member', 'manage_options', 'arWizardSettings', array($this,'arWizard_admin'), $this->plugin_image_url."icon.png");
			add_action('admin_print_styles-'.$arWizard, array($this,'arWizard_style'));
			add_action('admin_print_scripts-'.$arWizard, array($this,'arWizard_scripts'));
			
			add_submenu_page('arWizardSettings', 'Settings', 'Settings', 'manage_options', 'arWizardSettings', array($this,'arWizard_admin'));	
			$addSequence = add_submenu_page('arWizardSettings', 'Add New Sequence', 'Add New Sequence', 'manage_options', 'arWizardAddSequence', array($this,'arWizard_add_sequence'));
			$bSequence = add_submenu_page('arWizardSettings', '', '', 'manage_options', 'arWizardFinalTemplate', array($this,'arWizard_final_template'));
			$cSequence = add_submenu_page('arWizardSettings', '', '', 'manage_options', 'arWizardSavedTemplates', array($this,'arWizard_saved_templates'));	
			$dSequence = add_submenu_page('arWizardSettings', '', '', 'manage_options', 'arWizardEditTemplates', array($this,'arWizard_edit_templates'));		
			$eSequence = add_submenu_page('arWizardSettings', '', '', 'manage_options', 'arWizardDuplicateSequence', array($this,'arWizard_duplicate_sequence'));		
			$fSequence = add_submenu_page('arWizardSettings', '', '', 'manage_options', 'arWizardDuplicateGlobal', array($this,'arWizard_duplicate_global'));
			$fSequence = add_submenu_page('arWizardSettings', '', '', 'manage_options', 'arWizardPublishSequence', array($this,'arWizard_publish_sequence'));
			$gSequence = add_submenu_page('arWizardSettings', '', '', 'manage_options', 'arWizardViewTemplate', array($this,'arWizard_view_template'));		
			$hSequence = add_submenu_page('arWizardSettings', '', '', 'manage_options', 'arWizardEditGlobal', array($this,'arWizard_edit_global'));		
			
			add_action('admin_print_scripts-'.$addSequence, array($this,'arWizard_scripts'));
			add_action('admin_print_styles-'.$bSequence, array($this,'arWizard_style'));
			add_action('admin_print_scripts-'.$bSequence, array($this,'arWizard_scripts'));
			add_action('admin_print_styles-'.$cSequence, array($this,'arWizard_style'));
			add_action('admin_print_scripts-'.$cSequence, array($this,'arWizard_scripts'));
			add_action('admin_print_styles-'.$dSequence, array($this,'arWizard_style'));
			add_action('admin_print_scripts-'.$dSequence, array($this,'arWizard_scripts'));
			add_action('admin_print_styles-'.$eSequence, array($this,'arWizard_style'));
			add_action('admin_print_scripts-'.$eSequence, array($this,'arWizard_scripts'));
			add_action('admin_print_styles-'.$fSequence, array($this,'arWizard_style'));
			add_action('admin_print_scripts-'.$fSequence, array($this,'arWizard_scripts'));
			add_action('admin_print_styles-'.$gSequence, array($this,'arWizard_style'));
			add_action('admin_print_scripts-'.$gSequence, array($this,'arWizard_scripts'));
			add_action('admin_print_styles-'.$hSequence, array($this,'arWizard_style'));
			add_action('admin_print_scripts-'.$hSequence, array($this,'arWizard_scripts'));
			
			
			
			$arWizard = add_menu_page('AR Wizard Admin', 'AR Wizard Admin', 'manage_options', 'arWizardAdmin', array($this,'arWizardAdmin'), $this->plugin_image_url."icon.png");
			
			add_submenu_page('arWizardAdmin', 'Manage Sequences', 'Manage Sequences', 'manage_options', 'arWizardAdmin', array($this,'arWizardAdmin'));
			$adminAddSequence = add_submenu_page('arWizardAdmin', 'Create Sequence', 'Create Sequence', 'manage_options', 'arWizardNewSequence', array($this,'arWizardNewSequence'));
			add_submenu_page('arWizardAdmin', '', '', 'manage_options', 'arWizardCompleteTemplate', array($this,'arWizardCompleteTemplate'));
			add_submenu_page('arWizardAdmin', '', '', 'manage_options', 'arWizardAllTemplate', array($this,'arWizardAllTemplate'));
			add_submenu_page('arWizardAdmin', '', '', 'manage_options', 'arWizardTemplateEdit', array($this,'arWizardTemplateEdit'));
			
			add_action('admin_print_scripts-'.$adminAddSequence, array($this,'arWizard_scripts'));
			add_action('admin_head',array($this,'arWizard_style'));			
			
			add_submenu_page('arWizardAdmin', 'Create Autoresponder', 'Create Autoresponder', 'manage_options', 'arWizardNewAutoresponder', array($this,'arWizardNewAutoresponder'));		
			add_submenu_page('arWizardAdmin', 'Manage Autoresponder', 'Manage Autoresponder', 'manage_options', 'arWizardManageAutoresponder', array($this,'arWizardManageAutoresponder'));
			add_submenu_page('arWizardAdmin', '', '', 'manage_options', 'arWizardAutoresponderEdit', array($this,'arWizardAutoresponderEdit'));
			
			add_submenu_page('arWizardAdmin', '', '', 'manage_options', 'arWizardAllVariables', array($this,'arWizardAllVariables'));
			
			add_submenu_page('arWizardAdmin', 'Create Global Variable', 'Create Global Variable', 'manage_options', 'arWizardCreateGlobalVars', array($this,'arWizardCreateGlobalVars'));
			add_submenu_page('arWizardAdmin', 'Manage Global Variables', 'Manage Global Variables', 'manage_options', 'arWizardManageGlobalVars', array($this,'arWizardManageGlobalVars'));
		}
		
		/********* ARW Admin *********/
		
		function arWizardAdmin(){
			global $wpdb;
			
			include_once('arWizard.php');			
			$arp = new arWizard(); 
			
			include_once('admin/viewSequences.php');	
		}
		
		function arWizardNewSequence(){
			global $wpdb;
			
			include_once('arWizard.php');			
			$arp = new arWizard(); 
			
			include_once('admin/newSequence.php');
		}
		
		function arWizardCompleteTemplate(){
			global $wpdb;
			
			include_once('arWizard.php');			
			$arp = new arWizard(); 
			
			include_once('admin/completeTemplate.php');
		}
		
		function arWizardAllTemplate(){
			global $wpdb;
			
			include_once('arWizard.php');			
			$arp = new arWizard(); 
			
			include_once('admin/allTemplates.php');
		}
		
		function arWizardTemplateEdit(){
			global $wpdb;
			
			include_once('arWizard.php');			
			$arp = new arWizard(); 
			
			include_once('admin/templateEdit.php');
		}
		
		function arWizardNewAutoresponder(){
			global $wpdb;
			
			include_once('arWizard.php');			
			$arp = new arWizard(); 
			
			include_once('admin/newAutoresponder.php');
		}
		
		function arWizardManageAutoresponder(){
			global $wpdb;
			
			include_once('arWizard.php');			
			$arp = new arWizard(); 
			
			include_once('admin/viewAutoresponders.php');
		}
		
		function arWizardAutoresponderEdit(){
			global $wpdb;
			
			include_once('arWizard.php');			
			$arp = new arWizard(); 
			
			include_once('admin/autoresponderEdit.php');
		}
		
		function arWizardAllVariables(){
			global $wpdb;
			
			include_once('arWizard.php');			
			$arp = new arWizard(); 
			
			include_once('admin/allVariables.php');
		}
		
		function arWizardCreateGlobalVars(){
			global $wpdb;
			
			include_once('arWizard.php');			
			$arp = new arWizard(); 
			
			include_once('admin/createGlobalVars.php');
		}
		
		function arWizardManageGlobalVars(){
			global $wpdb;
			
			include_once('arWizard.php');			
			$arp = new arWizard(); 
			
			include_once('admin/manageGlobalVars.php');
		}
		
		/********** Members ************/
		
		function arWizard_admin(){
			global $wpdb;
			
			include_once('arWizard.php');
			$arp = new arWizard();
			include_once('adminSettings.php');
		}
		
		function arWizard_add_sequence(){
			global $wpdb;
			
			include_once('arWizard.php');			
			$arp = new arWizard(); 			
			include_once('addSequence.php');
		}
		
		function arWizard_final_template(){
			global $wpdb;
			
			include_once('arWizard.php');
			$arp = new arWizard();
			include_once('finalTemplate.php');
		}
		
		function arWizard_edit_templates(){
			global $wpdb;
			
			include_once('arWizard.php');
			$arp = new arWizard();
			include_once('editTemplate.php');
		}
		
		function arWizard_saved_templates(){
			global $wpdb;
			
			include_once('arWizard.php');
			$arp = new arWizard(); 
			include_once('savedTemplate.php');
		}
		
		function arWizard_duplicate_sequence(){
			global $wpdb;
			
			include_once('arWizard.php');
			$arp = new arWizard();
			include_once('duplicateSequence.php');
		}
		
		function arWizard_duplicate_global(){
			global $wpdb;
			
			include_once('arWizard.php');
			$arp = new arWizard();
			include_once('duplicateGlobal.php');
		}
		
		function arWizard_publish_sequence(){
			global $wpdb;
			
			include_once('arWizard.php');
			$arp = new arWizard();
			include_once('publishSequence.php');
		}
		
		function arWizard_view_template(){
			global $wpdb;
			
			include_once('arWizard.php');
			$arp = new arWizard(); 
			include_once('viewTemplate.php');
		}
		
		function arWizard_edit_global(){
			global $wpdb;
			
			include_once('arWizard.php');
			$arp = new arWizard();
			include_once('editGlobal.php');
		}
		
		function arWizard_style(){
			wp_register_style( 'arwStylesheet', $this->plugin_url . 'css/arw.css' );
			wp_enqueue_style('arwStylesheet');
		}
		
		function arWizard_scripts(){
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'jquery-validate',	$this->plugin_url . 'js/jquery.validate.js', array('jquery'));
			wp_enqueue_script( 'qtip',	$this->plugin_url . 'js/jquery.qtip-1.0.0-rc3.min.js', array('jquery'));
		}
		
		function arWizard_adminScripts(){
			wp_enqueue_script( 'arw',	$this->plugin_url . 'js/arwScript.js', array('jquery'));
		}
		
		function stri_replace( $find, $replace, $string ) {
			$parts = explode( strtolower($find), strtolower($string) );
			$pos = 0;			
			foreach( $parts as $key=>$part ){
				$parts[ $key ] = substr($string, $pos, strlen($part));
				$pos += strlen($part) + strlen($find);
			}			
			return( join( $replace, $parts ) );
		}

		function txt2html($txt) {
			while( !( strpos($txt,'  ') === FALSE ) ) $txt = str_replace('  ',' ',$txt);
			  $txt = str_replace(' >','>',$txt);
			  $txt = str_replace('< ','<',$txt);
			
			  //Transforms accents in html entities.
			  $txt = htmlentities($txt);
			
			  //We need some HTML entities back!
			  $txt = str_replace('&quot;','"',$txt);
			  $txt = str_replace('&lt;','<',$txt);
			  $txt = str_replace('&gt;','>',$txt);
			  $txt = str_replace('&amp;','&',$txt);
			
			  //Ajdusts links - anything starting with HTTP opens in a new window
			  $txt = $this->stri_replace("<a href=\"http://","<a target=\"_blank\" href=\"http://",$txt);
			  $txt = $this->stri_replace("<a href=http://","<a target=\"_blank\" href=http://",$txt);
			
			  //Basic formatting
			  $eol = ( strpos($txt,"\r") === FALSE ) ? "\n" : "\r\n";
			  $html = '<p>'.str_replace("$eol$eol","</p><p>",$txt).'</p>';
			  $html = str_replace("$eol","<br />\n",$html);
			  $html = str_replace("</p>","</p>\n\n",$html);
			  $html = str_replace("<p></p>","<p>&nbsp;</p>",$html);
			
			  //Wipes <br> after block tags (for when the user includes some html in the text).
			  $wipebr = Array("table","tr","td","blockquote","ul","ol","li");
			
			  for($x = 0; $x < count($wipebr); $x++) {
			
				$tag = $wipebr[$x];
				$html = $this->stri_replace("<$tag><br />","<$tag>",$html);
				$html = $this->stri_replace("</$tag><br />","</$tag>",$html);
			
			  }
			
			  return $html;
		}
	
function create_zip($files = array(),$destination = '',$overwrite = false) {
  //if the zip file already exists and overwrite is false, return false
  if(file_exists($destination) && !$overwrite) { return false; }
  //vars
  $valid_files = array();
  //if files were passed in...
  if(is_array($files)) {
    //cycle through each file
    foreach($files as $file) {
      //make sure the file exists
      if(file_exists($file)) {
        $valid_files[] = $file;
      }
    }
  }
  //if we have good files...
  if(count($valid_files)) {
    //create the archive
    $zip = new ZipArchive();
    if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
      return false;
    }
    //add the files
    foreach($valid_files as $file) {
      $zip->addFile($file,$file);
    }
    //debug
   // echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
    
    //close the zip -- done!
    $zip->close();
    
    //check to make sure the file exists
    return file_exists($destination);
  }
  else
  {
    return false;
  }
}	
	}
else :
	exit ("Class arWizard already declared!");
endif;
?>
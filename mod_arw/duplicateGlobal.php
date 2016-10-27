<style>
.wrap a{
	text-decoration:none;
}
.wrap a:hover{
	text-decoration:underline;
}
.wrap .clear{
	clear:both;
}
.wrap h2{
	line-height: 38px;
}
.wrap h2 img{
	padding-right:5px;
}
.wrap h3{
	font-size:20px;
}
.wrap .addSequence{
	padding:30px 60px;
	border:1px solid #e0dede;
	border-radius: 9px;
	-moz-border-radius: 9px;
	-webkit-border-radius: 9px;
	background: #ececec;
	color:#888;
}
.wrap .addSequence strong{
	font-size: 12px;
}
.wrap .emptySequence{
	
	border:1px solid #e0dede;
	border-radius: 9px;
	-moz-border-radius: 9px;
	-webkit-border-radius: 9px;
	background: #ececec;
	color:#888;
}
.addSequence .field{
	padding: 20px 0;
}
.addSequence .field label{
	width:170px;
	display: block;
	float: left;
	line-height:40px;
	font-weight: bold;
}
.wrap input[type="text"]{
	width:300px;
	padding: 10px;
}
.wrap textarea{
	width:300px;
	padding: 10px;
}
.addSequence .field input[type="text"]{
	width:300px;
	padding: 10px;
}
.addSequence .field select{
	width: 300px;
	height: 36px;
	padding: 0 0 0 10px;
}
.addSequence .field input[type="submit"]{
	padding: 10px 50px;
	font-size:16px;
	font-weight: bold;
}

.wrap .red{
	color: #cc0000;
}
.wrap p.error{
	margin-left:170px;
	color:red;
}
.wrap .hide{
	display:none;
}
.wrap .updated{
	padding:10px;
}
.wrap .button{
	background: #7e7b7b;
	border-radius:6px;
	-moz-border-radius:6px;
	-webkit-border-radius: 6px;
	padding: 5px 10px;
	color: #fff;
}
.wrap td{ height:30px; vertical-align:middle; padding-left:20px;}
.qtip-content{word-wrap: break-word;}
</style>
<?php 
global $wpdb;
//$error = false;
include_once('arWizard.php');
$arp = new arWizard(); 

if(isset($_POST['save']))
{
	$duplicate_sequence_id=$_SESSION['duplicate_sequence_id'];
	
	$sequence_id = $_GET['sequence_id'];
	
	$seq_array = $wpdb->get_row("select * from customer_sequences where customer_sequence_id='$sequence_id'");
			 
	$sequence_type=$seq_array->customer_sequence_type; 	
	
	$ex_qry_global = $wpdb->get_results("select * from global_variable_vars");
	foreach($ex_qry_global as $fetch){
		$var_name = $fetch->global_vars_name;
		$var_value = $arp->txt2html($_POST[$var_name]);
		
		$var_official_name=$fetch->global_vars_official_name;
		$wpdb->insert('customer_global_vars',array('cust_seq_id'=>$duplicate_sequence_id,'var_name'=>$var_name,'var_value'=>$var_value,'cust_var_official_name'=>$var_official_name),array('%d','%s','%s','%s'));
		
	}
	
	
	
	
	//$qry =mysql_query("update customer_sequences set closing_snippet= '$closing_snippet' where customer_sequence_id = '".$duplicate_sequence_id."' 
	//and customer_id='".$_SESSION['customer_id']."'") or die(mysql_error());
	
	
	
	$count_qry=$wpdb->get_results("select * from template where sequence_id ='".$sequence_type."' ");
	foreach($count_qry as $fetch_count){
		$template_name = $fetch_count->template_name;
		$template_id = $fetch_count->template_id;
		$wpdb->insert('customer_tracker_table',array('customer_sequence_id'=>$duplicate_sequence_id,'customer_template_name'=>$template_name,'customer_template_status'=>'inactive','customer_template_id'=>$template_id),array('%d','%s','%s','%d'));
		
	}
	
	
	$true_msg="successful";
	
	$_SESSION['sequence_id'] = $duplicate_sequence_id;
	
	unset($_SESSION['duplicate_sequence_id']);

}

?>


<div class="wrap">

    <h2><img src="<?php echo $arp->plugin_image_url;?>icon-32.png" alt="Autoresponder Wizard" align="left" /> Autoresponder Wizard </h2>
    <div class="hide updated fade">
    	
    </div>
    
    	<script type="text/javascript">
			jQuery(function(){
				jQuery('#globalVariablesForm').validate();
			});
		</script>
        <h3> Global Variables &raquo; </h3>
        
        <p>These settings define several global "variables" that will be utilised accross several emails in this sequence(like your name for example, or your disclaimers)...please fill in the details below.Use the "help" tooltip next to each option if you need more information.</p>
         <?php 

if($error)
		{	
?>		
<div class="notification warning"> <span class="strong">ERROR!</span> <?php echo $error_msg ;?> </div>
<?PHP 
		}
if($true_msg)
		{	
?>		
<script language="javascript">
location.href="admin.php?page=arWizardAddSequence&step=3";
</script>

<?PHP 
		}



?>
        <form name="globalVariablesForm" id="globalVariablesForm" method="post" action="">
            <div class="addSequence">
            
            <div class="field">
                <label>INTRO <span class="red">*</span></label>       
                <span class="help" title="Type your email introduction here. Something like Hello or Hey works great.">
                	<input type="text" name="intro" id="intro" value="" class="required" />
                </span>
            </div>
            
            <div class="field">
                <label>CLOSE <span class="red">*</span></label>
                <span class="help" title="This is the signoff you would close your emails with. Enter something like To your success or Speak soon or Chat soon here.">
                	<input type="text" name="close" id="close" value="" class="required" />
                </span>
            </div>
            
            <div class="field">
                <label>SIGNOFF <span class="red">*</span></label>
                <span class="help" title="This is your name, as it would appear at the bottom of your emails.">
                	<input type="text" name="signoff" id="signoff" value="" class="required" />
                </span>
            </div>
            
            <div class="field">
                <label>FOOTER <span class="red">*</span></label>
                <span class="help" title="This is your footer. Put any links you want in all your emails here. Put information on how people can unsubscribe here, too.">
                	<textarea name="footer" class="required"></textarea>
                </span>
                <div class="clear"></div>
            </div>
            
            <div class="field">
                <label>&nbsp;</label><input type="submit" name="save" value="Save" />
            </div>
        </div>
        </form>
    
   
    
</div>
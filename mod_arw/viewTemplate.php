<?php 	
if($_POST) {
	$tracker_query = $wpdb->update('customer_tracker_table',array('customer_template_status'=>'inactive'),
	array('customer_sequence_id'=>$_GET['sequence_id'],'customer_template_id'=>$_GET['temp_id']),array('%s'));
	$template_query= $wpdb->update('customer_template',array('status'=>'notfinal'),
	array('customer_sequence_id'=>$_GET['sequence_id'],'template_id'=>$_GET['temp_id']),array('%s'));
	$status_update= $wpdb->update('customer_sequences',array('customer_sequence_status'=>'saved'),
	array('customer_sequence_id'=>$_GET['sequence_id']),array('%s'));	 
?>
<script type="text/javascript">
	location.href="admin.php?page=arWizardEditTemplates&sequence_id=<?php echo $_GET['sequence_id'];?>&temp_id=<?php echo $_GET['temp_id'];?>";
</script>
<?php
}
?>
<div class="wrap">
    <h2><img src="<?php echo $arp->plugin_image_url;?>icon-32.png" alt="Autoresponder Wizard" align="left" /> Autoresponder Wizard </h2>
    <div class="hide updated fade"></div>
    <?php
    if($true_msg) {
    ?>
          <div class="true" style="padding-left:20px;"><?php echo "<ul>$true_msg</ul>";?></div>
		  <script language="javascript">
			location.href="admin.php?page=arWizardFinalTemplate";
		  </script>
		  
          <?php

		  		}
				
		if($error_msg)
		{
		?>
		<div class="row" style="background-color: #DDDDDD;"><?php echo $error_msg;?></div>
				<?php
		}?>
        
        <div class="emptySequence" style="padding:20px;">
		<?php
		$template_array = $wpdb->get_row("select * from customer_template where customer_sequence_id = '".$_GET['sequence_id']."' and template_id='".$_GET['temp_id']."'");

$content=$template_array->template_content;
$template_id=$template_array->template_id;

$get_array=$wpdb->get_row("select customer_template_name from customer_tracker_table where customer_template_id = '$template_id'");

$template_name=$get_array->customer_template_name;

$get_seq_array=$wpdb->get_row("select customer_sequence_name from customer_sequences where customer_sequence_id='".$_GET['sequence_id']."'");

$seq_name=$get_seq_array->customer_sequence_name;

$final_id=$_SESSION['template_id_customized'];

	function curPageURL() {
	 $pageURL = 'http';
	 if ($_SERVER["HTTPS"] == "on") 
	 {$pageURL .= "s";}
	 $pageURL .= "://";
	 if ($_SERVER["SERVER_PORT"] != "80") {
	  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	 } else {
	  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	 }
	 return $pageURL;
	}
?>
     <div class="box">

          <?php
		  	if($true_msg)
				{
		  ?>
          <div class="true" style="padding-left:20px;"><?php echo "<ul>$true_msg</ul>";?></div>
		  <script language="javascript">
			location.href="listing_templates.php";
		  </script>
		  
          <?php
		  		}
				
		  ?>	
        <form method="post" action="" name="form_template">
            <div class="row"><u><b>
            	<?php echo ucwords($template_name);?></b></u>
            </div> 
            <div class="row">
            	<?php echo stripslashes($content);?>
            </div>
            <div class="row" align="right" >
                <label></label>
                <input type="submit" value="Edit Again" class="button medium" name="edit_again"/>            
            </div>
        </form>
	</div>           
</div>
</div>
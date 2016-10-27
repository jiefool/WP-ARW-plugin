<?php
if($_POST)
	{
		extract($_POST);
		if(empty($autoresponder_name))
		{
		$error = '1';
		$error_autoresponder_name = "Please enter autoresponders name";
		echo "	<script>
						jQuery(function(){
							jQuery('.hide').html('Please enter autoresponders name.');
							jQuery('.hide').css('color','red');
							jQuery('.hide').show().fadeOut(5000);
						});
					</script>";
		}
		
		if($error!='1')
		{
		$date_created=$_POST['date_created'];;
		$date_created=date('Y-m-d G:i:s', strtotime($date_created));
		 
        $qry = $wpdb->update('autoresponders',array('autoresponder_name'=>$autoresponder_name, 'date_created'=>$date_created), array('autoresponder_id'=>$_GET['auto_id']),array('%s','%d'));
		//$true_msg = 'Update successful';
		echo "	<script>
						jQuery(function(){
							jQuery('.hide').html('Update Successful.');
							jQuery('.hide').show().fadeOut(5000);
						});
					</script>";
		}
		//echo "<meta http-equiv='refresh' content='2;url=index.php?mode=add_question'>";	
		
	}
?>
<script language='JavaScript'>	  
	  jQuery(function(){
				jQuery('#arpName').validate();
			});
</script>
<div class="wrap">
	<h2><img src="<?php echo $arp->plugin_image_url;?>icon-32.png" alt="Autoresponder Wizard" align="left" /> Autoresponder Wizard </h2>
    <div class="hide updated fade">
    	
    </div>
    
	<h3> Manage Template <span style="float:right;"><a href="admin.php?page=arWizardManageAutoresponder">&laquo; Back to List</a></span></h3>
    
	<div class="emptySequence" style="padding:15px;">
		<div class="grid_7" style="width:100%">

<?php
	$row = $wpdb->get_row("select * from autoresponders where autoresponder_id='".$_GET['auto_id']."'");
			       
?>
				 
	
	
          
          <form method="post" action="" name="arpName" id="arpName" class="form">
		  
		  			
				
				<div class="field">
                <label>Autoresponder Name</label>
                <input type="text" name="autoresponder_name" value="<?php echo $row->autoresponder_name;?>" size="60" class="required" />
              </div>
			  
			  
			  <?php
						
						$date=explode(' ',$row->date_created);
					
                         $date[0]=date('F d, Y', strtotime($date[0]));
					     $date[1]=date('g:ia',strtotime($date[1]));
					
					     $date=$date[0].','.$date[1];
						 
						 ?>
						 
			  <div class="field">
                <label>Created Date</label>
                <input type="text" name="date_created" value="<?php echo $date;?>" size="60" />
              </div>

				
				
				

			  <?php
			  if($_GET['page_no']=='')
			  {
			  ?>
			    <div class="field">
                <label>&nbsp;</label>
                <input type="submit" value="Update" class="button medium" />
				     <a href="admin.php?page=arWizardManageAutoresponder"><input type="button" value="Cancel" class="button medium" /></a>
              </div>
			  
			  <?php
			  }
			  else{
			  ?>
              <div class="field">
                <label>&nbsp;</label>
                <input type="submit" value="Update" class="button medium" />
				     <a href="admin.php?page=arWizardManageAutoresponder&page_no=<?php echo $_GET['page_no'];?>"><input type="button" value="Cancel" class="button medium" /></a>
              </div>
			  <?php
			  }
			  ?>
			  

            </form>
		<div class="clear"></div>
	</div>
</div>
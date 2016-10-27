<?php 
if(isset($_GET['mode']) && $_GET['mode']=='delete'){	
	$sequence_id=$_GET['id'];
	$wpdb->query("delete from customer_template where customer_sequence_id='".$sequence_id."'");			  
	$wpdb->query("delete from customer_tracker_table where customer_sequence_id='".$sequence_id."'");			  
	$wpdb->query("delete from customer_sequences where customer_sequence_id='".$sequence_id."'");			  
	$wpdb->query("delete from customer_global_vars where cust_seq_id='".$sequence_id."'");
	echo "	<script>
						jQuery(function(){
							jQuery('.hide').html('Sequence Deleted Successfully.');
							jQuery('.hide').show().fadeOut(5000);
						});
					</script>";	  		
}

?>
<script type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->

</script>
<div class="wrap">
<h2><img src="<?php echo $arp->plugin_image_url;?>icon-32.png" alt="Autoresponder Wizard" align="left" /> Autoresponder Wizard </h2>

<h3> My Sequences &raquo; </h3>

<div class="emptySequence">

<?php
				 $currentUserId = get_current_user_id();
			   $sequenceCount = $wpdb->get_var( "SELECT COUNT(*) FROM customer_sequences WHERE user_id=$currentUserId;" );
			       // include("includes/class.paging.php");
			   	
					$sql = "select * from customer_sequences where user_id=$currentUserId";
					
					$res=$wpdb->get_results($sql);
					
					?>



                    <?php /*?><div class="error" align="center">
                    <b><?php //echo 'Welcome '.ucwords($firstname.' '.$lastname);?></b></div>
					  
					  <div><br /></div>
					  
					  <div class="paging"><?php //echo $PAGING->show_paging("account.php",'')?></div>
					  <?php */?>
					  <?php
					  if($sequenceCount=='0')
					  {
					   ?>
                       <div style="width:100%; padding:10px; text-align:center">
                       <span class="red"><strong>Welcome to Autoresponder Wizard!</strong>
    						<br/><br/>
                            You have no Sequence yet</span>
                            <br/><br/>
                            <b><a href="admin.php?page=arWizardAddSequence">Click Here</a> to Create a New Sequence</b>	
                            </div>					 
						 <?php   
					  }					  
					  else{
					  ?>
               
		 
 
            <form name="account" action="account.php" method="post">
              <input type="hidden" name="action" value="process" />
			  
            <table cellspacing="0" width="100%" class="widefat sortable resizable editable" style="float:left;">
             <thead>
			 
			    <tr>
                  <th>Name</th>
                  <th align="center">Category</th>
                  <th align="center">Status</th>	
				  <th align="center">Progress</th>
				  <th align="center">Options</th>
				  
               </tr>
			   </thead>
			   <?php

			  // $query=mysql_query("select * from customer_sequences where customer_id='".$_SESSION['customer_id']."'");
			   //while($templates=mysql_fetch_array($res))
			   foreach($res as $templates){
			      $sequence_id=$templates->customer_sequence_id;
				  $customer_seq_type=$templates->customer_sequence_type;
				  
				  $query1=$wpdb->get_results("select * from customer_tracker_table where customer_sequence_id='".$sequence_id."'");
				  
				  $fetch = $wpdb->get_row("select sequence_category from sequence where sequence_id='".$customer_seq_type."'");			
				 
				  $category = $fetch->sequence_category;
				 
				$total = $wpdb->get_var( "select count(*) from customer_tracker_table where customer_sequence_id='".$sequence_id."'" );
					
			 
			
			 
			 $active=$wpdb->get_var( "select count(*) from customer_tracker_table where customer_sequence_id =
			  '".$sequence_id."' and customer_template_status='active'");
			
			
			 
			 ?>

			<tr>
                  <td><b><?php echo stripslashes($templates->customer_sequence_name);?></b></td>
				  <td><b><?php echo $category;?></b></td>
                  <td><b><?php echo $templates->customer_sequence_status;?></b></td>
				  
				


                  <td align="center"><b><?php echo $active .' of total ' .$total .' saved' ;?></b></td>
				  <td align="center">

				  
				  <a href="admin.php?page=arWizardSavedTemplates&sequence_id=<?php echo $templates->customer_sequence_id;?>" class="button">
				  Edit
				  </a>
				
				  <?php
				    if($active==$total)
					{
					?>
					
					<a href="admin.php?page=arWizardDuplicateSequence&sequence_id=<?php echo $templates->customer_sequence_id;?>" class="button">
				  Duplicate
				  </a>
	
					<?php  
				   $content=$templates->customer_sequence_id;?>
	
<a href="admin.php?page=arWizardPublishSequence&sequence_id=<?php echo $templates->customer_sequence_id;?>" class="button">
				 Publish</a>
	<?php /*?></span><?php */?>
				  
				  
				  
		
				  <?php
					}
				  ?>
				  
				  <a href='admin.php?page=arWizardSettings&id=<?php echo $templates->customer_sequence_id;?>&mode=delete' style="cursor:pointer;" onClick="confirmDelete();" class="button">
				  	Delete
				  </a>
				  
			 </td>
				  
				  
	
               </tr>
			   <?php
			   
			   }
			   
			   ?>

			 
			 
			 </table>
			
			</form>

<?php
}
?>

<div class="clear"></div>
</div>
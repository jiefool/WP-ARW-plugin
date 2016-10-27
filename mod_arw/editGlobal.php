<?php 	
if(isset($_POST['update_form'])) {
	$qry = "select * from global_variable_vars";
	$ex_qry = $wpdb->get_results($qry);
	foreach($ex_qry as $fetch) {
		$var_name = $fetch->global_vars_name;	
		if(empty($_POST[$var_name])) {
			$error = '1';
			$error_msg = "Please enter all the details";
		}
	}
		

		print_r($_POST);exit;	

	if($error != 1)
			{
			         $sequence_id=$_GET['sequence_id'];
					 
					 $qry_global = "select * from customer_global_vars where cust_seq_id = '$sequence_id'";
		             $ex_qry_global = $wpdb->get_results($qry_global);
                     foreach($ex_qry_global as $fetch)
		           {
				     $var_name = $fetch->var_name;
					 $var_value=$arp->txt2html($_POST[$var_name]);
					 
					// $var_official_name=$fetch['global_vars_official_name'];
					$wpdb->update('customer_global_vars',array('var_value'=>$var_value), array('seq_id'=>$sequence_id, 'var_name'=>$var_name));
					
					
					}
			

				echo "	<script>
						jQuery(function(){
							jQuery('.hide').html('Updated Successfuly!');
							jQuery('.hide').show().fadeOut(5000, function(){});
						});
					</script>";
			}
}

if(isset($_POST['save']))
{



		//$closing_snippet = addslashes($_POST['closing_snippet']);



	if($error != 1)
			{
					 
					
					$globalvars = $wpdb->get_results('select * from global_variable_vars');
		
		foreach($globalvars as $gv){
			
			$wpdb->insert('customer_global_vars',array('cust_seq_id'=>$_GET['sequence_id'],'var_name'=>$gv->global_vars_name,'var_value'=>$arp->txt2html($_POST[strtolower($gv->global_vars_name)]),'cust_var_official_name'=>$gv->global_vars_official_name),array('%d','%s','%s','%s'));
		}
					
				//$qry =mysql_query("update customer_sequences set closing_snippet= '$closing_snippet' where customer_sequence_id = '".$_SESSION['sequence_id']."' 
				//and customer_id='".$_SESSION['customer_id']."'") or die(mysql_error());
				 
				echo "	<script>
						jQuery(function(){
							jQuery('.hide').html('Added Successfuly!');
							jQuery('.hide').show().fadeOut(5000, function(){});
						});
					</script>";

				
			}
}
	?>
	<div class="wrap">

    <h2><img src="<?php echo $arp->plugin_image_url;?>icon-32.png" alt="Autoresponder Wizard" align="left" /> Autoresponder Wizard </h2>
    <div class="hide updated fade">
    	
    </div>
    
   <?php 
		$sequence_id=$_GET['sequence_id'];
		$get_array = $wpdb->get_row("select customer_sequence_name from customer_sequences where customer_sequence_id='$sequence_id'");	
		$seq_name=$get_array->customer_sequence_name;
	?>
		<script type="text/javascript">
			jQuery(function(){
				jQuery('#update_form').validate();
				$ = jQuery.noConflict();
				$('span.help').qtip({
				   show: 'mouseover',
				   hide: 'mouseout'
				})
			});
		</script>
        
		<h3> Global Variables &raquo; </h3>
        
        <a href="admin.php?page=arWizardSavedTemplates&sequence_id=<?php echo $_GET['sequence_id'];?>">&laquo; Back to Template List</a>
        
        <p>These settings define several global "variables" that will be utilised accross several emails in this sequence(like your name for example, or your disclaimers)...please fill in the details below.Use the "help" tooltip next to each option if you need more information.</p>
        
       <?php  
	   	$query=$wpdb->get_results("select * from customer_global_vars c ,global_variable_vars g where c.cust_seq_id = '$sequence_id' and g.global_vars_official_name = c.cust_var_official_name ") ;
		$num = $wpdb->get_var( "select count(*) from customer_global_vars c ,global_variable_vars g where c.cust_seq_id = '$sequence_id' and g.global_vars_official_name = c.cust_var_official_name");
		 
		 
		 
		 if($num=='0')
		 {?>
        <form name="globalVariablesForm" id="globalVariablesForm" method="post" action="">
            <div class="addSequence">
            
            <?php
		 $query=$wpdb->get_results("select * from global_variable_vars");
		 
		 foreach($query as $fetch) { 
		 ?>
      <div class="field">
        <label><?php echo $fetch->global_vars_name;?>:</label>
        <?php 
		  
		   $tooltip=$fetch->global_vars_info;
		   $tooltip = str_replace(' ','&nbsp;',$fetch->global_vars_info);
		  
		   $tooltip=explode("\n",$tooltip);
		 
		    $n=sizeof($tooltip);
		 
            foreach($tooltip as $value)
		   {
			$tool123.= trim($value);
			 
		   }	

		  
		  $d="<span class='help' title='".$tool123."'>".$fetch->global_vars_field."</span>";		  
		  echo $d;
		  $tool123="";

		  ?>
        <!--<input type="text" name="name" value="" size="50" style=" padding: 7px 2px 0;" />--> 
      </div>
      <?php
		 
		 }
		 
		 ?>
            
          <?php /*?>  <div class="field">
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
            </div><?php */?>
            
            <div class="field">
                <label>&nbsp;</label><input type="submit" name="save" value="Save" />
            </div>
        </div>
        </form>
		<?php
		 
		 }
		 
		 else{
		 
		  
		 ?><form name="update_form" id="update_form" method="post" action="" >
		 <div class="addSequence">
		 <?php
		 
		 foreach($query as $fetch) {
		   

		 ?>
		 <div class="field">
          <label><?php echo $fetch->var_name;?><span class="red">*</span>:</label>
		  
		  <?php 
		  $fetch->global_vars_info = str_replace(' ','&nbsp;',$fetch->global_vars_info);
		  
		  $type=$fetch->global_vars_type;
		  $var_name=$fetch->var_name;
		  $var_value=strip_tags($fetch->var_value);
		  $tooltip=$fetch->global_vars_info;
		   $tooltip = str_replace(' ','&nbsp;',$fetch->global_vars_info);
		  
		   $tooltip=explode("\n",$tooltip);
		 
		    $n=sizeof($tooltip);
		 
            foreach($tooltip as $value)
		   {
			$tool123.= trim($value);
			 $tool123.='<br/>';
		   }	
		
 //echo $tool123 =htmlspecialchars($tool123,ENT_QUOTES);
	
	
	      //die();
		  
		  
		  
		 
		  if($type=='Textbox')
		  {
		     $field='<input type="text" name="'.$var_name.'" class="required" size="39" value="'.$var_value.'"/>';

		  }
		  elseif($type=='Multiline')
		  {
		      $field='<textarea name="'.$var_name.'" rows="10" class="required"  cols="30" >'.$var_value.'</textarea>';
		  }
		  echo '<span class="help" title="'.$tool123.'">'.$field.'</span>';
		   $tool123="";
		   

		  ?>
          <!--<input type="text" name="name" value="" size="50" style=" padding: 7px 2px 0;" />-->
         </div>
		 
		 
		 <?php
		 
		 }
		 ?>
		 
		 		 <div class="field" >
                <label>&nbsp;</label>
                <input type="submit" value="Save" class="button medium" name="update_form"/>
              </div>
			 
              </div>
			  
			  </form>
         
        <?php }?>
</div>
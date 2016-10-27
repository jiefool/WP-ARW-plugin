<?php 	
	if($_POST)
	{
	if($error != 1){
			
			// replace variables to the posted values//
			$content=$wpdb->get_row("select template_content from template where template_id='".$_GET['temp_id']."'");
			
			$template_content=$content->template_content;
			
			$seqarray=$wpdb->get_row("select * from customer_sequences where customer_sequence_id='".$_GET['sequence_id']."'");		  
			$seqname=$seqarray->customer_sequence_name;
			  
			$seqid=$seqarray->customer_sequence_type;
			
			$vars_query=$wpdb->get_results("select * from variables where sequence_id='".$seqid."'");
			foreach($vars_query as $vars_array){
			     $var_name=$vars_array->variable_name;
			     $template_content = str_replace($var_name , $_POST[$var_name] , $template_content);
			}
            // replace variables to the posted values//
			
			// replace autoresponder-variables to the values//
			$fetch=$wpdb->get_row("select * from customer_sequences where customer_sequence_id='".$_GET['sequence_id']."'");
		   	
		   $autoresponder=$fetch->customer_auto_name;
		   
		   $fetcharr=$wpdb->get_results("select * from global_variables where autoresponder_id='".$autoresponder."'");
		   
		   foreach($fetcharr as $fetch_array){
				$vars_name = $fetch_array->global_variable_name;
				$vars_value = $fetch_array->global_variable_value;
				$template_content = str_replace($vars_name,$vars_value,$template_content);
			}
			
		 
		 // replace autoresponder-variables to the values//
		 
		 //replace the global variables//
		 
		 $var_query=$wpdb->get_results("select * from customer_global_vars where cust_seq_id='".$_GET['sequence_id']."'");
		 
		 foreach($var_query as $var_array){
		   $global_vars_official_name=$var_array->cust_var_official_name;
		   $global_var_value= $var_array->var_value;
		   $template_content = str_replace($global_vars_official_name,$global_var_value,$template_content);
		   
		   
		 }
		 
//replace the global variables//
		 

		 
		 
		   $template_content=addslashes($arp->txt2html($template_content));
		   
		  
		  
		  
		  // insert into table//
		   
		   
            $qry = mysql_query("insert into customer_template(customer_id,template_id,template_content,customer_sequence_id,status) values('".$_SESSION['customer_id']."',
			'".$_GET['temp_id']."', '".$template_content."','".$_GET['sequence_id']."','notfinal')") or die(mysql_error());
			
			$final_id=mysql_insert_id();
			$template_id=$_GET['temp_id'];
			
			
		   // insert into table//

			
			
			$_SESSION['template_id_customized']=$final_id;
			
			
			$true_msg="Template succesfuly customized";
		}
	
		
	
	}
	?>
	<div class="wrap">

    <h2><img src="<?php echo $arp->plugin_image_url;?>icon-32.png" alt="Autoresponder Wizard" align="left" /> Autoresponder Wizard </h2>
    <div class="hide updated fade">
    	
    </div>
    
   
	<?php	
		if(isset($_GET['undoid']) && !empty($_GET['undoid'])){
			$wpdb->query('delete from customer_template where cust_temp_id = '.$_GET['undoid']);
		}
	?>
		
         <?php
		  	if($true_msg)
				{
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
			$template_array=$wpdb->get_row("select * from template where template_id='".$_GET['temp_id']."'");
			
			$temp_name=$template_array->template_name;
        ?>
         <div class="breadcrumb"><a href="admin.php?page=arWizardSavedTemplates&sequence_id=<?php echo $_GET['sequence_id'];?>"><?php echo 'Edit Templates';?></a> &raquo; <span class="grey" style="color: #999999;font-size: 13px;"><?php echo $temp_name;?></span></div>
		 <div><br /></div>

	
	  
<script type="text/javascript">
			jQuery(function(){
				//jQuery('#globalVariablesForm').validate();
				$ = jQuery.noConflict();
				$('span.help').qtip({
				   style: { 
      					width: 500,
						background: '#FFFBCC'
				   },
				   show: 'mouseover',
				   hide: 'mouseout'
				})
			});
		</script>


	     <?php
		  	if($true_msg)
				{
		  ?>
          <div class="true" style="padding-left:20px;"><?php echo "<ul>$true_msg</ul>";?></div>
		  <script language="javascript">
			location.href="final_template.php";
		  </script>
		  
          <?php

		  		}
				
		if($error_msg)
		{
		?>
		<div class="row" style="background-color: #DDDDDD;"><?php echo $error_msg;?></div>
				<?php
		}
				
		  $closing_array=$wpdb->get_row("select * from customer_sequences where customer_sequence_id='".$_GET['sequence_id']."'");
        
		  
		  $seq_name=$closing_array->customer_sequence_name;
		  
		  $customer_sequence_type=$closing_array->customer_sequence_type;
		
		  $temp_id= $_GET['temp_id'];
		
		  
		 ?>

		
		
       <form method="post" action="" name="form_template">
	   
  


		   <?php
//replace template variables with their corresponding controls//
		   
		   $variables=$wpdb->get_results("select * from variables where sequence_id='".$customer_sequence_type."'");
		   
		   foreach($variables as $var)
		   {
				$variable_info=$var->variable_info;
				
				if($variable_info!=''){
					$tooltip=$variable_info;
					$tooltip = str_replace(' ','&nbsp;',$variable_info);
					$tooltip=explode("\n",$tooltip);
					
					$n=sizeof($tooltip);
					
					foreach($tooltip as $value)
					{
					 $tool123.= trim($value);
					 $tool123.='<br/>';
					}
						
					//echo "<span class='help' title='".$tool123."' >".$var->field."</span>";exit;
					$d = "<span class='help' title='".$tool123."' >".$var->field."</span>";
					
					$template_array->template_content= str_replace($var->variable_name,$d,$template_array->template_content);
					
				}
				else{
					$var2=$var_array['field'];
					$template_array->template_content= str_replace($var->variable_name,$var2,$template_array->template_content);				
				}
				
				 $tool123="";
           }
		   
//replace template variables with their corresponding controls//


		   
//replace autoresponder variables with their merger codes//
		   		   
		   $query=$wpdb->get_row("select * from customer_sequences where customer_sequence_id='".$_GET['sequence_id']."'");
		   
		   $autoresponder=$query->customer_auto_name;
		   
		   $auto_query=$wpdb->get_results("select * from global_variables where autoresponder_id='".$autoresponder."'");
		   foreach($auto_query as $arr)
		 {
		    $vars_name = $arr->global_variable_name;
			$vars_value = $arr->global_variable_value;
			$template_array->template_content = str_replace($vars_name,$vars_value,$template_array->template_content);
			
		 }
		 
//replace autoresponder variables with their merger codes//


		 
		 //replace the global variables//
		 

		 
		 $var_query=$wpdb->get_results("select * from customer_global_vars where cust_seq_id='".$_GET['sequence_id']."'");
		 
		foreach($var_query as $varq)
		 {
		   $global_vars_official_name=$varq->cust_var_official_name;
		   $global_var_value= $varq->var_value;
		   $template_array->template_content = str_replace($global_vars_official_name,$global_var_value,$template_array->template_content);
		   
		   
		 }
		 
		 
		 
		 
		 
		 
		 //replace the global variables//

		     
		 $template_array->template_content = $arp->txt2html($template_array->template_content);
	
		  $closing_snippet_query=$wpdb->get_row("select closing_snippet from customer_sequences where customer_sequence_id='".$_GET['sequence_id']."'");
		  $closing_snippet=$closing_snippet_query->closing_snippet;
		  
		  if($closing_snippet!='')
		  {
		  
		       $template_array->template_content=$template_array->template_content.$closing_snippet;
			   echo $template_array->template_content;
		  }
		  
		  else{
		      echo $template_array->template_content;
		  }
		  
		  
		  
		  ?>

		  
		  <div class="row" >
                <label></label>
                <input type="submit" value="Save" name="templateSave" class="button medium" />
              </div>
			 
			 
			 </form></div>
             <?php

	?>
        </div>
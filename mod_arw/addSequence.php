<?php 
/*
Step 1: Create New Sequence 
*/
if(isset($_POST['create']) && !empty($_POST['sequenceName'])) {
	$currentUserId = get_current_user_id();
	$wpdb->insert('customer_sequences',array('user_id'=>$currentUserId,'customer_sequence_name'=>$_POST['sequenceName'],'customer_auto_name'=>$_POST['autoResponder'],
	'customer_sequence_status'=>'saved','customer_sequence_type'=>$_POST['sequenceTemplate']),array('%d', '%s','%d','%s','%d'));
	if($wpdb->insert_id) {
		$_SESSION['sequence_id'] = $wpdb->insert_id;		
		$count_qry = $wpdb->get_results("select * from template where sequence_id ='".$_POST['sequenceTemplate']."' order by date_created desc");
	
		foreach($count_qry as $fetch_count) {
			$template_name=$fetch_count->template_name;
			$template_id=$fetch_count->template_id;
			$wpdb->insert('customer_tracker_table',array('customer_sequence_id'=>$_SESSION['sequence_id'],'customer_template_name'=>$template_name,
			'customer_template_status'=>'inactive','customer_template_id'=>$template_id),array('%d','%s','%s','%d'));
		}
		
		echo "	<script>
					jQuery(function(){
						jQuery('.hide').html('New Sequence created successfully');
						jQuery('.hide').show().fadeOut(5000);
					});
				</script>";
	} else {
		echo "	<script>
					jQuery(function(){
						jQuery('.hide').html('Problem creating sequence. Please try again.');
						jQuery('.hide').show().fadeOut(5000);
					});
				</script>";
		$_GET['step'] =  1;
	}
}

/*
Step 2: Save Global variables 
*/
if(isset($_POST['save']) && !empty($_POST['INTRO'])) {
	$globalvars = $wpdb->get_results('select * from global_variable_vars');
	
	foreach($globalvars as $gv) {		
		$wpdb->insert('customer_global_vars',array('cust_seq_id'=>$_SESSION['sequence_id'],'var_name'=>$gv->global_vars_name,
		'var_value'=>$arp->txt2html($_POST[$gv->global_vars_name]),'cust_var_official_name'=>$gv->global_vars_official_name),
		array('%d','%s','%s','%s'));
	}
}
/*
Step 3: Template Handling
*/	
if(isset($_POST['templateSave']) && !empty($_GET['temp_id'])) {		
	if($error != 1) {
		$content = $wpdb->get_row("select template_content from template where template_id='".$_GET['temp_id']."'");			
		$template_content = $content->template_content;			
		$vars_query = $wpdb->get_results("select * from variables where sequence_id='".$_GET['sequence_id']."'");
		foreach($vars_query as $vars_array) {
			 $var_name = $vars_array->variable_name;
			 $template_content = str_replace($var_name , $_POST[$var_name] , $template_content);
		}
		
		$fetch=$wpdb->get_row("select * from customer_sequences where customer_sequence_id='".$_SESSION['sequence_id']."'");
		$autoresponder=$fetch->customer_auto_name;
		$fetcharr=$wpdb->get_results("select * from global_variables where autoresponder_id='".$autoresponder."'");
		foreach($fetcharr as $fetch_array){
			$vars_name = $fetch_array->global_variable_name;
			$vars_value = $fetch_array->global_variable_value;
			$template_content = str_replace($vars_name,$vars_value,$template_content);
		}
		 
		$var_query=$wpdb->get_results("select * from customer_global_vars where cust_seq_id='".$_SESSION['sequence_id']."'");
		foreach($var_query as $var_array) {
			$global_vars_official_name=$var_array->cust_var_official_name;
			$global_var_value= $var_array->var_value;
			$template_content = str_replace($global_vars_official_name,$global_var_value,$template_content);
		}
		$template_content = addslashes($arp->txt2html($template_content));
		$wpdb->insert('customer_template',array('customer_id'=>$_SESSION['customer_id'],'template_id'=>$_GET['temp_id'],'template_content'=>$template_content,'customer_sequence_id'=>$_SESSION['sequence_id'],'status'=>'notfinal'));
		$final_id = $wpdb->insert_id;
		$template_id = $_GET['temp_id'];
		$_SESSION['template_id_customized'] = $final_id;
		$true_msg = "Template succesfuly customized";
	}
}
?>
<div class="wrap">
    <h2><img src="<?php echo $arp->plugin_image_url;?>icon-32.png" alt="Autoresponder Wizard" align="left" /> Autoresponder Wizard </h2>
    <div class="hide updated fade"></div>
	<?php 
	if(isset($_GET['temp_id']) && isset($_GET['sequence_id']) && !empty($_GET['temp_id']) && !empty($_GET['sequence_id'])) {	
		if(isset($_GET['undoid']) && !empty($_GET['undoid'])){
			$wpdb->query('delete from customer_template where cust_temp_id = '.$_GET['undoid']);
		}
		
		if($true_msg) {
	?>
            <div class="true" style="padding-left:20px;"><?php echo "<ul>$true_msg</ul>";?></div>
            <script language="javascript">
                location.href="admin.php?page=arWizardFinalTemplate";
            </script>
	<?php 
		}	
					
		if($error_msg)
			echo '<div class="row" style="background-color: #DDDDDD;">'.$error_msg.'</div>';
	?>
  <div class="emptySequence" style="padding:20px;">
    <?php
			$customer_id = $_SESSION['customer_id'];
			$template_array=$wpdb->get_row("select * from template where template_id='".$_GET['temp_id']."'");
			
			$temp_name=$template_array->template_name;
        ?>
    <div class="breadcrumb"><a href="admin.php?page=arWizardAddSequence&step=3"><?php echo 'Edit Templates';?></a> &raquo; <span class="grey" style="color: #999999;font-size: 13px;"><?php echo $temp_name;?></span></div>
    <div><br />
    </div>
    <script type="text/javascript">
			jQuery(function(){
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
				
		 $temp_id= $_GET['temp_id'];?>
    <form method="post" action="admin.php?page=arWizardAddSequence&sequence_id=<?php echo $_GET['sequence_id'];?>&temp_id=<?php echo $temp_id;?>" name="form_template">
      <?php
		   $variables=$wpdb->get_results("select * from variables where sequence_id='".$_GET['sequence_id']."'");
		   
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
		   $query=$wpdb->get_row("select * from customer_sequences where customer_sequence_id='".$_SESSION['sequence_id']."'");
		   
		   $autoresponder=$query->customer_auto_name;
		   
		   $auto_query=$wpdb->get_results("select * from global_variables where autoresponder_id='".$autoresponder."'");
			foreach($auto_query as $arr) {
				$vars_name = $arr->global_variable_name;
				$vars_value = $arr->global_variable_value;
				$template_array->template_content = str_replace($vars_name,$vars_value,$template_array->template_content);			
			}
		 
		 $var_query=$wpdb->get_results("select * from customer_global_vars where cust_seq_id='".$_SESSION['sequence_id']."'");
		 
		foreach($var_query as $varq)
		 {
		   $global_vars_official_name=$varq->cust_var_official_name;
		   $global_var_value= $varq->var_value;
		   $template_array->template_content = str_replace($global_vars_official_name,$global_var_value,$template_array->template_content);		   
		 }
		     
		 $template_array->template_content = $arp->txt2html($template_array->template_content);
	
		  $closing_snippet_query=$wpdb->get_row("select closing_snippet from customer_sequences where customer_sequence_id='".$_SESSION['sequence_id']."'");
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
    </form>
  </div>
  <?php

	}
	
	elseif(isset($_GET['step']) && $_GET['step']==3){ ?>
  <h3> Edit Templates &raquo; </h3>
  <div class="emptySequence">
    <table cellspacing="0" width="100%" class="widefat sortable resizable editable" align="center">
      <thead>
        <tr><th width="33%" valign="top">Name</td>
              <th width="33%" valign="top">Status</td>
              <th width="33%" valign="top">Edit</td>
            </tr></th>
          
        </tr>
        </th>
        <?php
				   $sequence_id=$_SESSION['sequence_id'];
				   
				   $seq_query = $wpdb->get_row("select * from customer_sequences where customer_sequence_id = '".$sequence_id."'");
				   
				   $customer_sequence_type=$seq_query->customer_sequence_type;
				   
				   $templates=$wpdb->get_results("select * from template where sequence_id='".$customer_sequence_type."' and status='1'");
				   
				   foreach($templates as $t){
					   $template_id=$t->template_id;
					   $template_name=$t->template_name;	   
			   ?>
        
            <tr>
              <td width="33%"><b><?php echo ucwords($template_name);?></b></td>
              <td width="33%"><b><?php echo 'Unsaved';?></b></td>
              <td width="33%"><a href="admin.php?page=arWizardAddSequence&temp_id=<?php echo $template_id;?>&sequence_id=<?php echo $customer_sequence_type;?>" style="text-decoration:none;" class="button"> Edit </a>
            </tr>
         
        <?php
			   	  }
			   
			   ?>
      
    </table>
  </div>
  <?php }
	
	elseif(isset($_GET['step']) && $_GET['step']==2){ ?>
  <script type="text/javascript">
			jQuery(function(){
				jQuery('#globalVariablesForm').validate();
				$ = jQuery.noConflict();
				$('span.help').qtip({
				   show: 'mouseover',
				   hide: 'mouseout'
				})
			});
		</script>
  <h3> Global Variables &raquo; </h3>
  <p>These settings define several global "variables" that will be utilised accross several emails in this sequence(like your name for example, or your disclaimers)...please fill in the details below.Use the "help" tooltip next to each option if you need more information.</p>
  <form name="globalVariablesForm" id="globalVariablesForm" method="post" action="admin.php?page=arWizardAddSequence&step=3">
    <div class="addSequence">
      
      <div class="row"> </div>
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
      <div class="field">
        <label>&nbsp;</label>
        <input type="submit" name="save" value="Save" />
      </div>
    </div>
  </form>
  <?php } 
	
	else {?>
  <script type="text/javascript">
			jQuery(function(){
				jQuery('#addSequence').validate();
			});
		</script>
  <h3> Add New Sequences &raquo; </h3>
  <form name="addSequence" id="addSequence" method="post" action="admin.php?page=arWizardAddSequence&step=2">
    <div class="addSequence"> <strong>Enter your sequence and autoresponder details and click create to begin editing your new email sequence!</strong><br/>
      <br/>
      <div class="field">
        <label>Sequence Name <span class="red">*</span></label>
        <input type="text" name="sequenceName" id="sequenceName" value="" class="required" />
      </div>
      <div class="field">
        <label>Select Template <span class="red">*</span></label>
        <select name="sequenceTemplate" id="sequenceTemplate" class="required">
          <option value=""> Select </option>
          <?php 
                        $result = $wpdb->get_results("select * from sequence where sequence_status='1'");
                        foreach($result as $res){
                            echo '<option value="'.$res->sequence_id.'">'.$res->sequence_name.'</option>';
                        }
                    ?>
        </select>
      </div>
      <div class="field">
        <label>Autoresponder <span class="red">*</span></label>
        <select name="autoResponder" id="autoResponder" class="required">
          <option value=""> Select </option>
          <?php 
                        $autoresponders = $wpdb->get_results("select * from autoresponders");
                        $c = 0;
                        foreach($autoresponders as $arp){
                            echo '<option value="'.$arp->autoresponder_id.'">'.$arp->autoresponder_name	.'</option>';
                        }
                    ?>
        </select>
      </div>
      <div class="field">
        <label>&nbsp;</label>
        <input type="submit" name="create" value="Create" />
      </div>
    </div>
  </form>
  <?php } ?>
</div>

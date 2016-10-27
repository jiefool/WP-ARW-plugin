<?php 
if($_POST) { 
	extract($_POST);
		
	if(empty($variable_name)) {
		$error = '1';
		$error_variable_name = "Please enter global variable's name";
	}
		
	if($tag=='Select') {
		$error = '1';
		$error_tag = "Please select a type";
	}
		
	if($tag=='Dropdown') {
		if($variable_dropdown_values=='') {
		       $error = '1';
		       $error_dropdown_values = "Please enter options for the dropdown";
		} else {
			$pick_all_up=$_POST['variable_dropdown_values'];
			$variable_dropdown_values=explode(',' , $_POST['variable_dropdown_values']);
			$n=sizeof($variable_dropdown_values);
			$make_dropdown='<select name="'.$variable_name.'" class="required" >';
			
			foreach($variable_dropdown_values as $left => $right) {
				$make_dropdown.= '<option value="'.$right.'">'.$right.'</option>';
			}
			
			$make_dropdown.='</select>';
			}
		}
		
		if($error!=1) { 
			if($_POST['tag']=='Textbox') {
				$make_field='<input type="text" name="'.$variable_name.'" size="39" class="required" />';
			  }
			  elseif($_POST['tag']=='Multiline')
			  {
				 $make_field='<textarea name="'.$variable_name.'" rows="10" cols="30" class="required" ></textarea>';
			  }
			  elseif($_POST['tag']=='Dropdown')
			  {
				  $make_field=$make_dropdown;
			  }
			  $auto_id=$_GET['auto_id'];
			  
			
			 $variable_official_name='{GLOBAL_'.strtoupper($variable_name).'}';

		 
		 if($tag=='Dropdown'){
			$wpdb->insert('global_variable_vars',array('global_vars_name'=>$variable_name,'global_vars_official_name'=>$variable_official_name,'global_vars_info'=>$variable_info,'global_vars_field'=>$make_field,'global_vars_type'=>$tag,'dropdown_values'=>$pick_all_up),array('%s','%s','%s','%s','%s','%s'));
		 }
		 else{
		 	$wpdb->insert('global_variable_vars',array('global_vars_name'=>$variable_name,'global_vars_official_name'=>$variable_official_name,'global_vars_info'=>$variable_info,'global_vars_field'=>$make_field,'global_vars_type'=>$tag),array('%s','%s','%s','%s','%s'));
		 }
		 
		 $true_msg = 'Global variable(s) added successfully';
		 
}
		
		
	
}
?>
<?php
	if($error == '1'){
		echo "	<script>
					jQuery(function(){
						jQuery('.hide').html('".$error_variable_name."');
						jQuery('.hide').show().fadeOut(5000);
					});
				</script>";
		}

	if($error_tag){
		echo "	<script>
					jQuery(function(){
						jQuery('.hide').html('".$error_tag."');
						jQuery('.hide').show().fadeOut(5000);
					});
				</script>";
		}
	if($error_dropdown_values)
		{
			echo "	<script>
						jQuery(function(){
							jQuery('.hide').html('".$error_dropdown_values."');
							jQuery('.hide').show().fadeOut(5000);
						});
					</script>";
		}
		
		
	if($true_msg)
		{
			echo "	<script>
						jQuery(function(){
							jQuery('.hide').html('".$true_msg."');
							jQuery('.hide').show().fadeOut(5000);
						});
					</script>";
 }
  ?>
<div class="wrap">
	<h2><img src="<?php echo $arp->plugin_image_url;?>icon-32.png" alt="Autoresponder Wizard" align="left" /> Autoresponder Wizard </h2>
    <div class="hide updated fade">
    	
    </div>
	<h3> Global Variables </h3>
    
	<div class="emptySequence" style="padding:20px;">
		<?php 
			$sql = "select * from sequence order by sequence_id desc";			
			$res=$wpdb->get_results($sql);
		?>
        <form method="post" action="" id="myform" class="form">
			<fieldset>
						<div class="field">
                          <label>Variable Name</label>
                            <input type="text" name="variable_name" size="40" />
                        </div>
                        <div class="field">
                        	<label>ToolTip</label>
                            <textarea rows="4" class="30" name="variable_info" cols="45"></textarea>
                        </div>				
			           <div class="field">
                        	<label>Variable Type</label>
                            <select name="tag">
                               <option>Select</option>
                               <option>Textbox</option>
                               <option>Multiline</option>
							   <option>Dropdown</option>
                            </select>
                        </div>						
						
						<div class="row" style="color:red;font-weight:bold">
						Note:This is only for dropdown values.Enter here option values seperated with commas
						</div>
						
						
						<div class="field">
                        	<label>Dropdown Values</label>
							<?php
						$tooltip='Enter here option values seperated with commas';
							?>
                            <textarea rows="4" class="30" name="variable_dropdown_values" cols="45"
							 onmouseover="Tip('<?php echo $tooltip;?>');" onMouseOut="UnTip();" ></textarea>
                        </div>						
						<input type="hidden" name="hid"  id="hid" value="1"/>
			</fieldset>
			

              <div class="field">
                <label>&nbsp;</label>
                <input type="submit" value="Submit" class="button medium" />
				
              </div>
            </form>

		<div class="clear"></div>
	</div>
</div>
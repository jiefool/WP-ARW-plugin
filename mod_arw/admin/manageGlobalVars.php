<?php 
/*
Global Variable Deletion
*/

if($_POST['submt']){
	
	$id = $_POST['check'];
	$action_up = $_POST['action_up'];
	
	if($action_up == 'Select' || $action_up == ''){
		$action = $_POST['action'];
		if($action == 'Select' || $action == ''){	
			$error = '1';				
		}
	}
	
	$url = $_POST['url'];
	
	if(empty($id))
		$error = '1';
		
	if($error != '1'){
		
		foreach($id as $u_id){
			
			if(($action_up == 'Delete') || ($action == 'Delete')){
				$query = "delete from global_variable_vars where global_vars_id='$u_id'";
				$wpdb->query($query);
				$display_msg = "Deletion Successful" ;
				echo "<script>
						jQuery(function(){
							jQuery('.hide').html('Deletion Successful.');
							jQuery('.hide').show().fadeOut(5000);
						});
					 </script>";
			}
		}
	}
}

/* 
Global Variable Update 
*/

if(isset($_POST['variableUpdate'])){	
		extract($_POST);
		$global_variable_official_name=$_POST['global_variable_official_name'];
		$global_variable_name=$_POST['global_variable_name'];
		$global_variable_info=$_POST['global_variable_info'];
		$global_variable_type=$_POST['global_variable_type'];
		
		/*
		Error Check starts
		*/
		if(empty($global_variable_official_name)){
			$error="1";
			$empty_variable_name = "Please enter variable's official name";
        }
		
		if(empty($global_variable_name)){
			$error="1";
			$empty_variable_name = "Please enter variable name";
        }
		
        if($global_variable_type == 'Select'){
			$error="1";
			$empty_variable_type = "Please select variable type";
        }
		/*
		Error Check stops
		*/
		
		if($error !='1'){		
			if($_POST['global_variable_type']=='Textbox')
		    	$field='<input type="text" name="'.$global_variable_name.'" size="40" class="required" />';
				
			elseif($_POST['global_variable_type']=='Multiline')
			 	$field='<textarea name="'.$global_variable_name.'" rows="10" cols="30" class="required" ></textarea>';
				
			elseif($_POST['global_variable_type']=='Dropdown'){
				$pick_all_up=$_POST['variable_dropdown_values'];
				$variable_dropdown_values=explode(',' , $_POST['variable_dropdown_values']);				
				$make_dropdown='<select name="'.$global_variable_name.'" class="required" >';
				
				foreach($variable_dropdown_values as $left => $right)
					$make_dropdown.= '<option value="'.$right.'">'.$right.'</option>';
				$make_dropdown.='</select>';
				$field=$make_dropdown;
		 	}
			
		  	/*
			Update Variable
			*/
			if($_POST['global_variable_type']=='Dropdown'){			
				$qry = $wpdb->update('global_variable_vars', array('global_vars_official_name'=>$global_variable_official_name,
				'global_vars_name'=>$global_variable_name,'global_vars_info'=>$global_variable_info,'global_vars_field'=>$field,
				'global_vars_type'=>$global_variable_type,'dropdown_values'=>$pick_all_up),array('global_vars_id'=>$_GET['var_id']), 
				array('%s','%s','%s','%s','%s','%s'));
			} else {
				$qry = $wpdb->update('global_variable_vars', array('global_vars_official_name'=>$global_variable_official_name,
				'global_vars_name'=>$global_variable_name,'global_vars_info'=>$global_variable_info,'global_vars_field'=>$field,
				'global_vars_type'=>$global_variable_type),array('global_vars_id'=>$_GET['var_id']), array('%s','%s','%s','%s','%s'));
			}
			$true_msg = 'Update successful';
		}
}
?>

<script language='JavaScript'>
	checked = false;
	function checkedAll () {
		if (checked == false){ checked = true } else { checked = false}
		for (var i = 0; i < document.getElementById('myform').elements.length; i++) {
			document.getElementById('myform').elements[i].checked = checked;
		}
	}
	
	jQuery(function(){
		jQuery('#addSequence').validate();
	});
</script>

<div class="wrap">
	<h2><img src="<?php echo $arp->plugin_image_url;?>icon-32.png" alt="Autoresponder Wizard" align="left" /> Autoresponder Wizard </h2>
    <div class="hide updated fade"></div>
    
	<h3 style="float:left;"> Edit Variable </h3>
	<?php 
		if(isset($_GET['var_id']) && !empty($_GET['var_id'])){ ?>
			<div class="emptySequence" style="padding:20px;">
          		<h3>Manage Variables <a href="admin.php?page=arWizardManageGlobalVars" style="float:right;">&laquo; Back to List</a> </h3>
          
                <div class="box table">
                <form method="post" action="" class="form">
					<?php
                    $sql = "select * from global_variable_vars where global_vars_id='".$_GET['var_id']."'";
					$res=$wpdb->get_results($sql); 
                    foreach($res as $row){ ?>
                        <div class="field">
                            <label>Variable Official Name</label>
                            <input type="text" name="global_variable_official_name" value="<?php echo $row->global_vars_official_name;?>" size="60" />
                        </div>
                        <div class="field">
                            <label>Variable Name</label>
                            <input type="text" name="global_variable_name" value="<?php echo $row->global_vars_name;?>" size="60" />
                        </div>
                        <div class="field">
                            <label>ToolTip</label>
                            <textarea name="global_variable_info" rows="10" cols="40"><?php echo $row->global_vars_info;?></textarea>
                        </div>
						<?php
							$type = $row->global_vars_type;
							if($type == "Textbox")
							   $type_selected1="selected";
							else
								$type_selected1="";
								
							if($type=="Multiline")
							   $type_selected2="selected";
							else	
								$type_selected2="";
								
							if($type=="Dropdown")
								$type_selected3="selected";
							else
								$type_selected3="";
                       		if($type_selected1 == "selected"){ ?>
                                <div class="field">
                                    <label>Variable type</label>
                                    <select name="global_variable_type">
                                        <option>Select</option>
                                        <option selected>Textbox</option>
                                        <option>Multiline</option>
                                        <option>Dropdown</option>
                                    </select>
                                </div>
						  <?php
							} elseif ( $type_selected2 == "selected" ) { ?>
                                <div class="field">
                                    <label>Variable type</label>
                                    <select name="global_variable_type">
                                        <option>Select</option>
                                        <option>Textbox</option>
                                        <option selected>Multiline</option>
                                        <option>Dropdown</option>
                                    </select>
                                </div>
							  <?php
                              }
                              elseif($type_selected3 == "selected")
                              {
                              ?>
                                <div class="field">
                                <label>Variable type</label>
                                <select name="global_variable_type">
                                    <option>Select</option>
                                    <option>Textbox</option>
                                    <option>Multiline</option>
                                    <option selected>Dropdown</option>
                                </select>
                         
                              </div>
                              
                              <?php
                              }
						  if($type=='Dropdown'){
						  $dropdown_contents=$row->dropdown_values; ?>
						  
						  <div class="field">
							<label>Dropdown Values</label>
							<textarea rows="4" class="30" name="variable_dropdown_values" cols="45" onmouseover="Tip('Enter here option values seperated with commas')" onMouseOut="UnTip();" ><?php echo $dropdown_contents;?></textarea>
						  </div>
						  
						  <?php
							}
						  }
        
        
                      if($_GET['page_no']=='')
                      {
                      ?>
                        <div class="field">
                        <label></label>
                        <input type="submit" value="Update" class="button medium" name="variableUpdate" />
                             <a href="index.php?mode=global_vars"><input type="button" value="Cancel" class="button medium" /></a>
                      </div>
                      
                      <?php
                      } else {
                      ?>
                      <div class="field">
                        <label></label>
                        <input type="submit" value="Update" class="button medium" name="variableUpdate" />
                        <a href="index.php?mode=global_vars&page_no=<?php echo $_GET['page_no'];?>"><input type="button" value="Cancel" class="button medium" /></a>
                      </div>
                      <?php
                      }
                      ?>
                      
        
                    </form> 
                  
                </div>
        	</div>
    <?php } else { ?>
    
        <form action="" method="post" id="myform">
        <div style="float:right">Action &nbsp;&nbsp;&nbsp;&nbsp;
                            <select name="action_up" >
                              <option>Select</option>
                              <option>Delete</option>
                            </select>
                            <input type="hidden" name="url" value="<?php echo $url?>">    	   
                            <input type="submit" name="submt" value="Action" /> </div>
                            <div class="clear"></div>
                            <br/>
                            <div class="emptySequence">
            <table cellspacing="0" width="100%" class="widefat sortable resizable editable">
                <thead>
					<?php
                    $sql = "select * from global_variable_vars order by global_vars_id";
                    $res = $wpdb->get_results($sql); ?>
                    
                    <tr style="font-weight:bold;">
                        <th style="width:40px;"><input type="checkbox" name="check_all" onclick="checkedAll();"></th>
                        <th style="width:200px;">Variable Official Name</th>
                        <th style="width:200px;">Variable Name</th>
                        <th style="width:350px;">ToolTip</th>
                        <th style="width:200px;">Type</th>	
                        <th style="width:200px;">Edit</th>
                    </tr>
                </thead>
                <tbody>
                <?php 	
                    foreach($res as $row) { ?>
                        <tr>
                            <td style="width:40px;"><input type="checkbox" name="check[]" value="<?php echo $row->global_vars_id;?>"></td>
                            <td style="width:200px;"> <?php echo $row->global_vars_official_name;?> </td>
                            <td style="width:200px;"> <?php echo $row->global_vars_name;?> </td>
                            <td style="width:350px;"> <?php echo $row->global_vars_info;?> </td>
                            <td style="width:200px;">
							<?php
								$type = $row->global_vars_type;
								if($type == "Textbox")
									$type_selected1="selected";
								else
									$type_selected1="";
							
								if($type=="Multiline")
									$type_selected2="selected";
								else
									$type_selected2="";
							
								if($type=="Dropdown")
									$type_selected3="selected";
								else
									$type_selected3="";
                        
								if( $type_selected1 == "selected" ) { ?>
                                    <select name="variable_type">
                                        <option>Select</option>
                                        <option selected>Textbox</option>
                                        <option>Multiline</option>
                                        <option>Dropdown</option>
                                    </select>
                        		<?php
                        		} elseif ( $type_selected2 == "selected" ) { ?>
                                    <select name="variable_type">
                                        <option>Select</option>
                                        <option>Textbox</option>
                                        <option selected>Multiline</option>
                                        <option>Dropdown</option>
                                    </select>
                        <?php
                        } elseif ( $type_selected3 == "selected" ){ ?>
                                    <select name="variable_type">
                                        <option>Select</option>
                                        <option>Textbox</option>
                                        <option>Multiline</option>
                                        <option selected>Dropdown</option>
                                    </select>
                        <?php
                        } ?>
                        </td>
                        <td style="width:200px;">
                        	<a href="admin.php?page=arWizardManageGlobalVars&var_id=<?php echo $row->global_vars_id;?>&<?php echo $page_no;?>">  
                        	<input type="button" value="Edit Variable" class="button medium" style="width:auto;"/></a>
                        </td>
                     </tr>
                <?php
                    } ?>
                    
                </tbody>
       		</table>
            <div class="clear"></div>
	</div><br/>
            <div style="float:right;"><b>Action:</b> &nbsp;&nbsp;&nbsp;&nbsp;
                <select name="action">
                    <option>Select</option>
                    <option>Delete</option>
                </select>
                <input type="submit" name="submt" value="Action" />
                <input type="hidden" name="url" value="<?php echo $url;?>"> 
            </div>
        </form> 
		
    <?php }?>
</div>
<?php
$p_id = $_GET['pid'];
$u_id = $_GET['uid'];
$page_no = $_GET['page_no'];
if($page_no)
	$page_no = "page_no=$page_no";
else
	$page_no = "";

if($p_id) {
	$class_one = $wpdb->update('sequence',array('sequence_status'=>1),array('sequence_id' => $p_id),array('%d'));
	$class_two = $wpdb->update('template',array('status'=>1),array('sequence_id' => $p_id),array('%d'));
	header("location:index.php?page=arWizardAdmin");
}
if($u_id) {
	$class_one = $wpdb->update('sequence',array('sequence_status'=>0),array('sequence_id' => $u_id),array('%d'));
	$class_two = $wpdb->update('template',array('status'=>0),array('sequence_id' => $p_id),array('%d'));
	header("location:admin.php?page=arWizardAdmin");
}

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
	
	if(empty($id)){
		$error = '1';			
	}
	
	if($error != '1'){
		foreach($id as $u_id){
			if(($action_up == 'Delete') || ($action == 'Delete')){
				$query = "delete from sequence where sequence_id='$u_id'";
				$wpdb->query($query);
				
				$get_templates=$wpdb->get_results("select template_id from template where sequence_id ='$u_id' ");
				foreach($get_templates as $array){
					$template_id = $array->template_id;
					$delete_templates = $wpdb->query("delete from template where sequence_id ='$u_id' and template_id='$template_id'");
					$delete_vars = $wpdb->query("delete from variables where sequence_id='$u_id'");				
				}
				
				echo "	<script>
						jQuery(function(){
							jQuery('.hide').html('Sequence Deleted Successfully.');
							jQuery('.hide').show().fadeOut(5000);
						});
					</script>";	  
			}
		}
	} else {
		echo "	<script>
						jQuery(function(){
							jQuery('.hide').html('Error in Process. Please Try again.');
							jQuery('.hide').css('color','red');
							jQuery('.hide').show().fadeOut(5000);
						});
					</script>";	 
	}
	
}
?>
<div class="wrap">
	<h2><img src="<?php echo $arp->plugin_image_url;?>icon-32.png" alt="Autoresponder Wizard" align="left" /> Autoresponder Wizard </h2>
    <div class="hide updated fade">
    	
    </div>
	<h3 style="float:left;"> Sequences </h3>
    <form action="" method="post" id="myform">
	<div style="float:right">
    	<b>Action:</b> &nbsp;&nbsp;&nbsp;&nbsp;
        <select name="action_up" >
          <option>Select</option>
          <option>Delete</option>
        </select>
        <input type="hidden" name="url" value="<?php echo $url?>">    	   
        <input type="submit" name="submt" value="Action" /> 
    </div> 
    <div class="clear"></div>
	<div class="emptySequence">
		<?php 
			$sql = "select * from sequence order by sequence_id desc";			
			$res=$wpdb->get_results($sql);
		?>
        
            <input type="hidden" name="action" value="process" />
                
            <table cellspacing="0" width="100%" class="widefat sortable resizable editable" style="float:left;">
                <thead>                
                    <tr>
                    	<th style="width:40px;"><input type="checkbox" name="check_all" onclick="checkedAll();"></th>
                        <th>Sequence Name</th>
                        <th>Sequence Description</th>
                        <th>Status</th>	
                        <th align="center">Date Created</th>
                        <th align="center">Edit</th>                        
                    </tr>
                </thead>                
				<?php 
                	foreach($res as $templates){
                ?>
                        <tr>
                        	<td style="width:40px;"><input type="checkbox" name="check[]" value="<?php echo $templates->sequence_id;?>"><?php //echo $row['sequence_id'];?></td>
                            <td><b><?php echo stripslashes($templates->sequence_name);?></b></td>
                            <td><b><?php echo substr($templates->sequence_description,0,50);?></b></td>
                            <td><b>
                                <?php
                                    if($templates->sequence_status == '1'){
                                ?>
                                    <a href="admin.php?page=arWizardAdmin&uid=<?php echo $templates->sequence_id;?>&<?php echo $page_no;?>">Active</a>
                                <?php
                                    } else {
                                ?>
                                    <a href="admin.php?page=arWizardAdmin&pid=<?php echo $templates->sequence_id;?>&<?php echo $page_no;?>">Inactive</a>
                               <?php 		
                                    }	
                                ?>  
                                </b>
                            </td>
                            <?php 
                                $date=explode(' ',$templates->date_created);
                                $date[0]=date('F d, Y', strtotime($date[0]));
                                $date[1]=date('g:ia',strtotime($date[1]));
                                $date=$date[0].','.$date[1];                  
                            ?>
                            <td align="center"><b><?php echo $date;?></b></td>
                            <td align="center">                   
                                <a href="admin.php?page=arWizardAllTemplate&sequence_id=<?php echo $templates->sequence_id;?>"> 
                                <input type="button" value="Edit Templates" class="button medium" style="width:auto;"/></a>
                            </td>
                        </tr>
				<?php               
                	}
                ?>
            </table>
		<div class="clear"></div>
	</div>
    <div style="float:right; margin-top:15px;" class="clear;">
    	<b>Action:</b> &nbsp;&nbsp;&nbsp;&nbsp;
        <select name="action" >
          <option>Select</option>
          <option>Delete</option>
        </select>
        <input type="hidden" name="url" value="<?php echo $url?>">    	   
        <input type="submit" name="submt" value="Action" /> 
    </div> 
    </form>
</div>

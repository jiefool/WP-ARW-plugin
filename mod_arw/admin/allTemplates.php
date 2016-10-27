<?php
if($_POST['submt']) {	
	$id = $_POST['check'];
	
	$action_up = $_POST['action_up'];
	if($action_up == 'Select' || $action_up == '') {
		$action = $_POST['action'];
		if($action == 'Select' || $action == '')	
				$error = '1';
	}
	
	$url = $_POST['url'];
	
	if(empty($id))
			$error = '1';
	if($error != '1') {
		foreach($id as $u_id) {
			if(($action_up == 'Inactive') || ($action == 'Inactive')){
				$wpdb->update('template',array('status'=>0), array('template_id'=>$u_id),array('%d'));
				echo "	<script>
					jQuery(function(){
						jQuery('.hide').html('Status Successfuly Updated');
						jQuery('.hide').show().fadeOut(5000);
					});
				</script>";
			}
			if(($action_up == 'Active') || ($action == 'Active')){
				$wpdb->update('template',array('status'=>1), array('template_id'=>$u_id),array('%d'));
				echo "	<script>
					jQuery(function(){
						jQuery('.hide').html('Status Successfuly Updated');
						jQuery('.hide').show().fadeOut(5000);
					});
				</script>";
			}
			if(($action_up == 'Delete') || ($action == 'Delete')){
				$query = "delete from template where template_id='$u_id'";
				$rs = $wpdb->query($query);
				echo "	<script>
					jQuery(function(){
						jQuery('.hide').html('Deletion Successful');
						jQuery('.hide').show().fadeOut(5000);
					});
				</script>";
			}
		}					
	}
}
	
/***********/
$p_id = $_GET['pid'];
$u_id = $_GET['uid'];
$page_no = $_GET['page_no'];
$sequence_id=$_GET['sequence_id'];
if($page_no)
	$page_no = "page_no=$page_no";
else
	$page_no = "";

if($p_id) {
	$wpdb->update('template',array('status'=>1),array('template_id' => $p_id),array('%d'));
	header("location:admin.php?page=arWizardAllTemplate&sequence_id=$sequence_id");
}
if($u_id) {
	$wpdb->update('template',array('status'=>0),array('template_id' => $u_id),array('%d'));
	header("location:admin.php?page=arWizardAllTemplate&sequence_id=$sequence_id");
}

function curPageURL() {
	$pageURL = 'http';
	if ($_SERVER["HTTPS"] == "on") {
		$pageURL .= "s";
	}
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80") {
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	} else {
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	return $pageURL;
}
	
$url = curPageURL();
$sql = "select * from template where sequence_id='".$_GET['sequence_id']."'order by template_id desc";
$res = $wpdb->get_results("select * from template where sequence_id='".$_GET['sequence_id']."'order by template_id desc");					
$sequence_array = $wpdb->get_row("select sequence_name from sequence where sequence_id='".$_GET['sequence_id']."'");					
$seq_name=$sequence_array->sequence_name;
?>
<div class="wrap">
    <h2><img src="<?php echo $arp->plugin_image_url;?>icon-32.png" alt="Autoresponder Wizard" align="left" /> Autoresponder Wizard </h2>
    <div class="hide updated fade"></div>
    
        <h3 style="float:left;">Templates</h3>          
        <div class="box table">
            <form action="" method="post" id="myform">
                <?php if(!empty($display_msg)) {
                    echo '<div Sclass="true" style="color: red;float:left;">' . $display_msg . '</div>';
                    unset($display_msg);
                }?>
                <div style="float:right">	
                    Action &nbsp;&nbsp;&nbsp;&nbsp;
                    <select name="action_up" >
                        <option>Select</option>
                        <option>Active</option>
                        <option>Inactive</option>
                        <option>Delete</option>
                    </select>
                    <input type="hidden" name="url" value="<?php echo $url?>">    	   
                    <input type="submit" name="submt" value="Action" /> 
                </div> 
                <br /><br /><br />
                <div style="float:right;"> 
                    <a href="admin.php?page=arWizardCompleteTemplate&sequence_id=<?php echo $_GET['sequence_id'];?>">
                        <b>Add template to this sequence &raquo;</b>
                    </a><br/><br/>
                </div>                  
                <div class="emptySequence clear">               
                <table cellspacing="0" width="100%" class="widefat">
                    <thead>                
                        <tr>
                            <th style="width:40px;"><input type="checkbox" name="check_all" onclick="checkedAll();"></th>
                            <th style="width:200px;">Template Name</th>
                            <th style="width:500px;">Template Content</th>
                            <th style="width:150px;text-align:center;">Status</th>
                            <th style="width:250px;">Date Created</th>	
                            <th style="width:100px;">Edit</th>
                        </tr>
                    </thead> 
              		<tbody>
					<?php 	
                        foreach($res as $row) {
                    ?>
                <tr>
                  <td style="width:40px;"><input type="checkbox" name="check[]" value="<?php echo $row->template_id;?>"><?php //echo $row['template_id'];?></td>
                  <td style="width:200px;"> <?php echo $row->template_name;?> </td>
                  <td style="width:500px;"><?php echo substr($row->template_content,0,200)?> </td>
                   <td style="width:150px;text-align:center;">
                   <?php
                        if($row->status == '1')
                            {
                    ?>
                        <a href="admin.php?page=arWizardAllTemplate&uid=<?php echo $row->template_id;?>&sequence_id=<?php echo $_GET['sequence_id'];?>&<?php echo $page_no;?>">Active						
                        	   
                        </a>
                      <?php
                            }
                        else
                            {
                     ?>
                        <a href="admin.php?page=arWizardAllTemplate&pid=<?php echo $row->template_id;?>&sequence_id=<?php echo $_GET['sequence_id'];?>&<?php echo $page_no;?>">Inactive
                       	  
                        </a>
                       <?php 		
                            }	
                        ?>                   </td>
                        
                        <?php
                        
                        $date=explode(' ',$row->date_created);
                    
                         $date[0]=date('F d, Y', strtotime($date[0]));
                         $date[1]=date('g:ia',strtotime($date[1]));
                    
                         $date=$date[0].','.$date[1];
                         
                         ?>
                        
                        <td style="width:200px;"><?php echo $date;?></td>
                        
                        <td style="width:100px;">
                        <a href="admin.php?page=arWizardTemplateEdit&sequence_id=<?php echo $_GET['sequence_id'];?>&temp_id=<?php echo $row->template_id;?>&<?php echo $page_no;?>">  
                        <input type="button" value="Edit Template" class="button medium" style="width:auto;"/></a>               
              </td>
              
              
              
                        
                </tr>
                <?php
                    }
                ?></tbody></table>
                </div>
                        
                <div style="float:right; margin-top:10px;">
                    Action &nbsp;&nbsp;&nbsp;&nbsp;
                    <select name="action" >
                        <option>Select</option>
                        <option>Active</option>
                        <option>Inactive</option>
                        <option>Delete</option>
                    </select>
                    <input type="submit" name="submt" value="Action" />
                    <input type="hidden" name="url" value="<?php echo $url;?>">              
                </div>
                <div class="clear"></div>
            </form>           
     	
	</div>
</div>

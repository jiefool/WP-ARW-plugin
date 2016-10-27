<?php 
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
<script language='JavaScript'>
      checked = false;
      function checkedAll () {
        if (checked == false){checked = true}else{checked = false}
	for (var i = 0; i < document.getElementById('myform').elements.length; i++) {
	  document.getElementById('myform').elements[i].checked = checked;
	}
      }
</script>
<div class="wrap">
	<h2><img src="<?php echo $arp->plugin_image_url;?>icon-32.png" alt="Autoresponder Wizard" align="left" /> Autoresponder Wizard </h2>
    <div class="hide updated fade"></div>
	<h3 style="float:left;"> Autoresponder </h3>
    <form action="" method="post" id="myform">
		<div style="float:right">
            <strong>Action: </strong>&nbsp;&nbsp;&nbsp;&nbsp;
            <select name="action_up" >
              <option>Select</option>
              <option>Delete</option>
            </select>
            <input type="hidden" name="url" value="<?php echo $url?>">    	   
            <input type="submit" name="submt" value="Action" /> 
        </div> 
    <div class="clear"></div>
    <br/>
	<div class="emptySequence">
		<?php 
			$sql = "select * from autoresponders order by autoresponder_id desc";			
			$res=$wpdb->get_results($sql);
		?>
        
            <input type="hidden" name="action" value="process" />
			  
                  


                   
                
            <table cellspacing="0" width="100%" class="widefat sortable resizable editable" style="float:left;">
                <thead>                
                    <tr bgcolor="#cccccc" style="font-weight:bold;">
                    	<th style="width:150px;" align="center"><input type="checkbox" name="check_all" onclick="checkedAll();" style="float:left;">Autoresponder Id</th>
                        <th align="center">Autoresponder Name</th>
                          <th align="center">Date Created</th>	
                          <th align="center">Edit</th>                        
                    </tr>
                </thead>                
				<?php 
                	foreach($res as $row){
                ?>
                        <tr>
                        	<td style="width:40px;" align="center"><input type="checkbox" name="check[]" value="<?php echo $row->autoresponder_id;?>" style="float:left;"><?php echo $row->autoresponder_id;?></td>
                            <td align="center"><b><?php echo $row->autoresponder_name?></b></td>
                            
                            
                            <?php
						
						$date=explode(' ',$row->date_created);
					
                         $date[0]=date('F d, Y', strtotime($date[0]));
					     $date[1]=date('g:ia',strtotime($date[1]));
					
					     $date=$date[0].','.$date[1];
						 
						 ?>
                            <td align="center"><b><?php echo $date;?></b></td>
                            <td align="center">                   
                                <a href="admin.php?page=arWizardAutoresponderEdit&auto_id=<?php echo $row->autoresponder_id;?>&<?php echo $page_no;?>">  
                        <input type="button" value="Edit Autoresponder" class="button medium" style="width:auto;"/></a>
				 
				 <a href="admin.php?page=arWizardAllVariables&auto_id=<?php echo $row->autoresponder_id;?>&<?php echo $page_no;?>"> 
				<input type="button" value="Variables in this responder" class="button medium" style="width:auto;"/></a>
                            </td>
                        </tr>
				<?php               
                	}
                ?>
            </table>
            
		<div class="clear"></div>
	</div>
        <div style="float:right;" class="clear">
        <br/>
        <strong>Action: </strong> &nbsp;&nbsp;&nbsp;&nbsp;
            <select name="action" >
              <option>Select</option>
              <option>Delete</option>
            </select>
            <input type="submit" name="submt" value="Action" />
            <input type="hidden" name="url" value="<?php echo $url;?>">  
        </div>
    </form>
</div>
<script language='JavaScript'>
      checked = false;
      function checkedAll () {
        if (checked == false){checked = true}else{checked = false}
	for (var i = 0; i < document.getElementById('myform').elements.length; i++) {
	  document.getElementById('myform').elements[i].checked = checked;
	}
      }
</script>
<?php
if(isset($_POST['variableUpdate'])){
	
		extract($_POST);
		$global_variable_name=$_POST['global_variable_name'];
		$global_variable_value=$_POST['global_variable_value'];

		
		
		if(empty($global_variable_name))
        {
			$error="1";
			$empty_variable_name = "Please enter variable name";
        }

		if(empty($global_variable_name)){
			$error="1";
			$empty_variable_value = "Please select variable value";
		}
		
		if($error !='1')
		{
			$wpdb->update('global_variables',array('global_variable_name'=>$global_variable_name,'global_variable_value'=>$global_variable_value),array('autoresponder_id'=>$_GET['auto_id'],'global_variable_id'=>$_GET['var_id']),array('%s','%s'));
	
			$true_msg = 'Update successful';
		}
	
		
	
	
}
if($_POST['submt'])
	{
		$id = $_POST['check'];
		
		$action_up = $_POST['action_up'];
		if($action_up == 'Select' || $action_up == '')
			{
				$action = $_POST['action'];
				if($action == 'Select' || $action == '')
					{	
						$error = '1';				
					}
			}
		$url = $_POST['url'];
	
	
	if(empty($id))
		{
			$error = '1';
			
		}
	
	if($error != '1')
	
		{
				foreach($id as $u_id)
					{
						if(($action_up == 'Delete') || ($action == 'Delete'))
							{
							    $query = "delete from global_variables where global_variable_id='$u_id'";
								$wpdb->query($query);
								$display_msg = "Deletion Successful" ;
								echo "	<script>
											jQuery(function(){
												jQuery('.hide').html('Deletion Successful.');
												jQuery('.hide').show().fadeOut(5000);
											});
										</script>";
							}
					}
		}
	}
	


	function curPageURL() {
		 $pageURL = 'http';
		 if ($_SERVER["HTTPS"] == "on") 
		 {$pageURL .= "s";}
		 $pageURL .= "://";
		 if ($_SERVER["SERVER_PORT"] != "80") {
		  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		 } else {
		  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
		 }
		 return $pageURL;
	}
	
					$url = curPageURL();
					
					$auto = $wpdb->get_row("select * from autoresponders where autoresponder_id='".$_GET['auto_id']."'");
	
				?>
<div class="wrap">

    <h2><img src="<?php echo $arp->plugin_image_url;?>icon-32.png" alt="Autoresponder Wizard" align="left" /> Autoresponder Wizard </h2>
    <div class="hide updated fade">
    </div>
    <?php if(isset($_GET['var_id']) && !empty($_GET['var_id'])){
			$sql = "select * from autoresponders where autoresponder_id='".$_GET['auto_id']."'";
			$auto = $wpdb->get_row($sql);
	
			$sql1 = "select * from global_variables where global_variable_id='".$_GET['var_id']."' and autoresponder_id='".$_GET['auto_id']."'";
			$row = $wpdb->get_row($sql1);
		?>
    		<div class="emptySequence" style="padding:20px;">
		
          <h3>Edit Variable <a href="admin.php?page=arWizardAllVariables&auto_id=<?php echo $_GET['auto_id'];?>" style="float:right;">&laquo; Back to List</a> </h3>
          
          <div class="box table">
          <?php if($error == '1')
				{
		  ?>
          <div class="error">
          	<?php echo "$empty_variable_name"."<br>"."$empty_variable_value"."<br>"; 
			?>
          </div>
		  <?php
		  }
		  	if($true_msg)
				{
		  ?>
          <div class="true" style="padding-left:20px;"><?php echo "<ul>$true_msg</ul>";?></div>
          <?php
		  		}
		  ?>
          <form method="post" action="" class="form">


              

				
			<div class="field">
                <label>Variable Name</label>
                <input type="text" name="global_variable_name" value="<?php echo $row->global_variable_name;?>" size="60" />
              </div>
			  
			  
			 <div class="field">
                <label>Variable Value</label>
            <input type="text" name="global_variable_value" value="<?php echo $row->global_variable_value;?>" size="60" />
              </div>
			  

<?php

			  if($_GET['page_no']=='')
			  {
			  ?>
			    <div class="field">
                <label>&nbsp;</label>
                <input type="submit" name="variableUpdate" value="Update" class="button medium" />
				     <a href="admin.php?page=arWizardAllVariables&auto_id=<?php echo $_GET['auto_id'];?>"><input type="button" value="Cancel" class="button medium" /></a>
              </div>
			  
			  <?php
			  }
			  else{
			  ?>
              <div class="field">
                <label>&nbsp;</label>
                <input type="submit" name="variableUpdate" value="Update" class="button medium" />
				<a href="admin.php?page=arWizardAllVariables&auto_id=<?php echo $_GET['auto_id'];?>&page_no=<?php echo $_GET['page_no'];?>"><input type="button" value="Cancel" class=                     "button medium" /></a>
              </div>
			  <?php
			  }
			  ?>
			  

            </form> 
          
        </div>
        </div>
    <?php } else { ?>
		
        
		
          <h3 style="float:left;">Autoresponder Variable Listing </h3>
          
          <div class="box table">
          <form action="" method="post" id="myform">
           
			 <?php
					$res = $wpdb->get_results("select * from global_variables where autoresponder_id = '".$_GET['auto_id']."' order by global_variable_id");
					
				?>
              
      
                  <div style="float:right"><?php //echo $PAGING->show_paging("index.php?mode=auto_vars&auto_id=".$_GET['auto_id']);?></div>
<div style="float:right"><b>Action:</b> &nbsp;&nbsp;&nbsp;&nbsp;
                    <select name="action_up" >
                          <option>Select</option>
                          <option>Delete</option>
                    </select>
                    <input type="hidden" name="url" value="<?php echo $url?>">    	   
                    <input type="submit" name="submt" value="Action" /> </div>
					<br /><br /><br />
					<div style="float:right;"> 
			<a href="admin.php?page=arWizardNewAutoresponder&auto_id=<?php echo $_GET['auto_id'];?>">Add variables to this autoresponder</a>
            <br/><br/>
</div>

           <div class="emptySequence clear">
               <table cellspacing="0" width="100%" class="widefat sortable resizable editable" style="border: 1px solid #E0DEDE; border-radius:9px; -moz-border-radius:9px; -webkit-border-radius:9px;">
             <thead>
                <tr style="font-weight:bold;">
                    <th class="tc"><input type="checkbox" name="check_all" onclick="checkedAll();"> Variable Id</th>
                  <th>Variable Name</th>
                 <!-- <td>Variable Info</td>-->
                  <th>Variable Value</th>
				  <!--<td>Type</td>	-->
				  <th style="text-align:center;">Edit</th>
               </tr>
              </thead>
              <tbody>
              
                
                <?php 	
					foreach($res as $row)
					{
					
				?>
                
                <tr>
                  <td class="tc"><input type="checkbox" name="check[]" value="<?php echo $row->global_variable_id;?>"> <?php echo $row->global_variable_id;?></td>
				  <td> <?php echo $row->global_variable_name?> </td>
                  <!--<td> <?php echo $row->global_info;?> </td>-->
                  <td>	<?php echo $row->global_variable_value;?> </td> 
			  
			  

              
						
						<td style="text-align:center;">
						<a href="admin.php?page=arWizardAllVariables&auto_id=<?php echo $_GET['auto_id'];?>&var_id=<?php echo $row->global_variable_id;?>&<?php echo $page_no;?>">  
                        <input type="button" value="Edit Variable" class="button medium" style="width:auto;"/></a>
              </td>
			  
						
                </tr>
                <?php
					}
				?></tbody></table>
                                  </div>          
                                  <div style="float:right; clear:both;"><?php //echo $PAGING->show_paging("index.php?mode=variable_edit&auto_id=".$_GET['auto_id'])?></div>
                                  <br/>
	                                    <div style="float:right;"><b>Action:</b> &nbsp;&nbsp;&nbsp;&nbsp;
    	                            <select name="action">
    	                              <option>Select</option>
                                      <option>Delete</option>
  	                                </select>
									<input type="submit" name="submt" value="Action" />
                                    <input type="hidden" name="url" value="<?php echo $url;?>">    	   
                                    <!--<input type="submit" name="submt" value="Action" /> --></div>
                                    <br class="clear"/></td>
                               
           </form> 
          
        </div>
        
        
        <?php }?>
        </div>
<?php
if($_POST['create'] == 'Create')
	{

		extract($_POST);
		
		
		
		if(empty($template_name))
		{
		$error = '1';
		$error_template_name = "Please Enter Template name";
		}
		
		if(empty($template_content))
		{
		$error = '1';
		$error_template_content = "Please Enter Template content";
		}
		
		$template_content=addslashes($_POST['template_content']);
		

		if($error!=1)
		{
		$sequence_id=$_GET['sequence_id'];
		$qry = $wpdb->insert('template',array('template_name'=>$template_name,'template_description'=>$template_description,'template_content'=>$template_content,'date_created'=>date('Y-m-d H:i:s',time()),'sequence_id'=>$sequence_id),array('%s','%s','%s','%s','%d'));
		
		$template_id = $wpdb->insert_id; 
		$true_msg = 'Template created successfully';
		 
		}
	}


if($_POST['submit'] == 'Submit')
	{


		extract($_POST);
		
		if(empty($variable_name))
		{
		$error = '1';
		$error_variable_name = "Please enter variable name";
		}
		

		if($tag=='Select')
		{
		$error = '1';
		$error_tag = "Please select a type";
		}
		
		if($tag=='Dropdown')
		{
		   if($variable_dropdown_values=='')
		   {
		       $error = '1';
		       $error_dropdown_values = "Please enter options for the dropdown";
		   }
		   else{
		       $pick_all=$_POST['variable_dropdown_values'];
		       $variable_dropdown_values=explode(',' , $_POST['variable_dropdown_values']);
			   $n=sizeof($variable_dropdown_values);
               $make_dropdown='<select name="'.$variable_name.'">';

			      foreach($variable_dropdown_values as $left => $right)
				  {
			        $make_dropdown.= '<option value="'.$right.'">'.$right.'</option>';
                  }
				  
                $make_dropdown.='</select>';
                 }
		}

		if($error!=1)
		{


		$variable_name='{SEQ_'.strtoupper($variable_name).'}'; 
		$sequence_id=$_GET['sequence_id'];
		if($_POST['tag']=='Textbox')
		  {
		    $make_field='<input type="text" name="'.$variable_name.'"/>';
		  }
		  elseif($_POST['tag']=='Multiline')
		  {
		     $make_field='<textarea name="'.$variable_name.'"></textarea>';
		  }
		  elseif($_POST['tag']=='Dropdown')
		  {
		      $make_field=$make_dropdown;
		  }
		

		 $hid_value=$_POST['hid'];
		 if($hid_value > 1)
		 {
		 
		 
		    for($i=1; $i < $hid_value ; $i++)
			{
			 $name = $_POST['name'.$i];
			$desc = $_POST['desc'.$i];
			 $cmb = $_POST['cmb'.$i];
			 $type = $_POST['type'.$i];
			 $pick_all_up=$_POST['dropdown_values'.$i];
			 $dropdown_values=explode(',' , $_POST['dropdown_values'.$i]);
			 
			 $name='{SEQ_'.strtoupper($name).'}';
			
			$num=sizeof($dropdown_values);
				
            $make_dropdown_dynamic='<select name="'.$name.'">';

			      foreach($dropdown_values as $key => $value)
				  {
			        $make_dropdown_dynamic.= '<option value="'.$value.'">'.$value.'</option>';
                  }
				  
                $make_dropdown_dynamic.='</select>';
		           		     

		  
		  if($_POST['type'.$i]=='Textbox')
		  {
		    $field='<input type="text" name="'.$name.'"/>';
		  }
		  elseif($_POST['type'.$i]=='Multiline')
		  {
		     $field='<textarea name="'.$name.'"></textarea>';
		  }
		  
		 elseif($_POST['type'.$i]=='Dropdown')
		  {
		    $field=$make_dropdown_dynamic;
		  }
		  
		   if($_POST['type'.$i]=='Dropdown')
		  {
		
			
		  // $str="insert into variables(variable_name, variable_info,field,sequence_id,type,dropdown_values) values('$name',  
		    //     '$desc','$field','$sequence_id','$type','$pick_all_up')";
		     $qry = $wpdb->insert('variables',array('variable_name'=>$name,'variable_info'=>$desc,'field'=>$field,'sequence_id'=>$sequence_id,'type'=>$type,'dropdown_values'=>$pick_all_up),array('%s','%s','%s','%d','%s','%s'));
		  }
		  
		  else{
		  //$str="insert into variables(variable_name, variable_info,field,sequence_id,type) values('$name',  
		    //     '$desc','$field','$sequence_id','$type')";
		     $qry = $wpdb->insert('variables',array('variable_name'=>$name,'variable_info'=>$desc,'field'=>$field,'sequence_id'=>$sequence_id,'type'=>$type),array('%s','%s','%s','%d','%s'));
			}

			 
		}
		
		if($tag=='Dropdown')
			{
			
			
			
			//$query="insert into variables(variable_name, variable_info,field,sequence_id,type,dropdown_values) values('$variable_name',  
		  //'$variable_info','$make_field','$sequence_id','$tag','$pick_all')";
		  $ex_query = $wpdb->insert('variables',array('variable_name'=>$variable_name,'variable_info'=>$variable_info,'field'=>$make_field,'sequence_id'=>$sequence_id,'type'=>$tag,'dropdown_values'=>$pick_all),array('%s','%s','%s','%d','%s','%s'));
			}
			
			else{
			
			
			
			//$query="insert into variables(variable_name, variable_info,field,sequence_id,type) values('$variable_name',  
		 // '$variable_info','$make_field','$sequence_id','$tag')";
		  $ex_query = $wpdb->insert('variables',array('variable_name'=>$variable_name,'variable_info'=>$variable_info,'field'=>$make_field,'sequence_id'=>$sequence_id,'type'=>$tag),array('%s','%s','%s','%d','%s'));
			
			}



		 }
		 else{
		 
		  if($tag=='Dropdown')
			{
		//$str="insert into variables(variable_name, variable_info,field,sequence_id,type,dropdown_values) values('$variable_name',  
		//'$variable_info','$make_field','$sequence_id','$tag','$pick_all')";
		$qry = $wpdb->insert('variables',array('variable_name'=>$variable_name,'variable_info'=>$variable_info,'field'=>$make_field,'sequence_id'=>$sequence_id,'type'=>$tag,'dropdown_values'=>$pick_all),array('%s','%s','%s','%d','%s','%s'));
		 }
		 else{
		$str="insert into variables(variable_name, variable_info,field,sequence_id,type) values('$variable_name',  
		'$variable_info','$make_field','$sequence_id','$tag')";
		$qry = $wpdb->insert('variables',array('variable_name'=>$variable_name,'variable_info'=>$variable_info,'field'=>$make_field,'sequence_id'=>$sequence_id,'type'=>$tag),array('%s','%s','%s','%d','%s'));
		 }
		 


		}
		$true_msg1 = 'Variable added successfully';
		}
	}
?>
<script language='JavaScript'>
      
	  
	  jQuery(function(){
				jQuery('#template_form').validate();
			});
</script>
<script language="javascript">
function confirmDelete(delUrl) {
  if (confirm("Are you sure you want to delete")) {
    document.location = delUrl;
  }
}
</script>

<script type = "text/javascript">

function insertAtCursor(myField,mybuttonid) {
//IE support
var test='txt'+mybuttonid;

var myValue=document.getElementById(test).value;
if (document.selection) {
myField.focus();
sel = document.selection.createRange();
sel.text = myValue;
}
//MOZILLA/NETSCAPE support
else if (myField.selectionStart || myField.selectionStart == '0') {
var startPos = myField.selectionStart;
var endPos = myField.selectionEnd;
myField.value = myField.value.substring(0, startPos)
+ myValue
+ myField.value.substring(endPos, myField.value.length);
} else {
myField.value += myValue;
}
}

</script>

<script type="text/javascript" language="javascript">

function createDiv()
{

var i= document.getElementById('hid').value;
var divTag = document.createElement("div");

//var dname= "div" + i;

divTag.id = "div" + i;

divTag.setAttribute("align","left");



divTag.className ="field";

/*divTag.innerHTML = "This HTML Div tag created using Javascript DOM dynamically.";*/

document.getElementById('test').appendChild(divTag);

var lab = document.createElement("label");
lab.id="lab" + parseInt(i);
lab.className ="label_adjust";
document.getElementById('test').appendChild(lab);
document.getElementById('lab' + parseInt(i)).innerHTML ="Variable Name";

var txt = "name" + i ;

txt = document.createElement("input");

txt.id = "name" + i;

txt.name = "name" + i;

txt.size = "40";

txt.className ="template_adjust";

document.getElementById('test').appendChild(txt);
var lab1 = document.createElement("label");
lab1.id="lab1" + parseInt(i);
lab1.className ="template1_adjust";
document.getElementById('test').appendChild(lab1);
document.getElementById('lab1' + parseInt(i)).innerHTML ="ToolTip";


var desc = "desc" + i;
 
desc  = document.createElement("textarea");

desc.id = "desc" + i;

desc.name = "desc" + i ;

desc.rows = "4";

desc.cols = "45";

desc.className ="template2_adjust";

document.getElementById('test').appendChild(desc);
var lab3 = document.createElement("label");
lab3.id="lab3" + parseInt(i);
lab3.className ="label_type_adjust";
document.getElementById('test').appendChild(lab3);
document.getElementById('lab3' + parseInt(i)).innerHTML ="Type";

var type = "type" + i ;

type = document.createElement("select");

type.id = "type" + i;

type.name = "type" + i;

type.className ="adjust3";

document.getElementById('test').appendChild(type);

var type_opt = document.createElement("option");
		  // Add an Option object to Drop Down/List Box
        document.getElementById("type" + i).options.add(type_opt);
		 // Assign text and value to Option object
        type_opt.text = "select";
        type_opt.value = "select";
var type_opt1 = document.createElement("option");
		  // Add an Option object to Drop Down/List Box
        document.getElementById("type" + i).options.add(type_opt1);
		 // Assign text and value to Option object
        type_opt1.text = "Textbox";
        type_opt1.value = "Textbox";		
		
var type_opt2 = document.createElement("option");
		  // Add an Option object to Drop Down/List Box
        document.getElementById("type" + i).options.add(type_opt2);
		 // Assign text and value to Option object
        type_opt2.text = "Multiline";
        type_opt2.value = "Multiline";
		
var type_opt3 = document.createElement("option");
		  // Add an Option object to Drop Down/List Box
        document.getElementById("type" + i).options.add(type_opt3);
		 // Assign text and value to Option object
        type_opt3.text = "Dropdown";
        type_opt3.value = "Dropdown";
		
		
var lab4 = document.createElement("label");
lab4.id="lab4" + parseInt(i);
lab4.className ="label_dropdown_adjust";
document.getElementById('test').appendChild(lab4);
document.getElementById('lab4' + parseInt(i)).innerHTML ="Dropdown Values";


var dropdown_values = "dropdown_values" + i;
 
dropdown_values  = document.createElement("textarea");

dropdown_values.id = "dropdown_values" + i;

dropdown_values.name = "dropdown_values" + i ;

dropdown_values.rows = "4";

dropdown_values.cols = "45";

dropdown_values.className ="dropdown_adjust";

document.getElementById('test').appendChild(dropdown_values);


		
		
var msg = "<h3>variable"+(parseInt(i)+1)+"</h3><hr width='400px;' align='left'>";


document.getElementById("div" + i).innerHTML = msg;



i= parseInt(i)+1;
document.getElementById('hid').value = i;




}


</script>



<div class="wrap">
	<h2><img src="<?php echo $arp->plugin_image_url;?>icon-32.png" alt="Autoresponder Wizard" align="left" /> Autoresponder Wizard </h2>
    <div class="hide updated fade">
    	
    </div>
    
	<h3> New Sequence </h3>
    
	<div class="emptySequence">
		<div class="grid_7" style="width:100%">

<?php
					$sequence_array = $wpdb->get_row("select sequence_name from sequence where sequence_id='".$_GET['sequence_id']."'");
					$seq_name = $sequence_array->sequence_name;
					
					?>
				 
	
	
	<br/>
	
          <h3 class="marginleft20"> Complete Template </h3>
          <?php
		  	if($error == '1')
				{
		  ?>
          <div class="error" style="padding-left:20px;"><?php echo "<ul>$error_template_name"."<br>"."$error_template_content</ul>"?></div>
          <?php
		  		}
				
			if($true_msg)
				{
		  ?>
          <div class="true" style="padding-left:20px;"><?php echo "<ul>$true_msg</ul>"?></div>
		  
		  
          <?php
		  		}
				
				
		  ?>
          <div class="box">
		  <table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr>
					<td width="70%" style="vertical-align:top;">
            <form method="post" action="" name="template_form" class="form" id="template_form">
	
						<div class="field">
                        	<label>Template Name</label>
                            <input type="text" name="template_name" size="40">
                        </div>
						
						<div class="field">
                        	<label>Template Description</label>
                            <input type="text" name="template_description" size="40">
                        </div>
						
						<div class="field">
                        	<label>Template Content</label>
                            <textarea name="template_content" rows="20" cols="60"></textarea>
                        </div>
						

              <div class="field">
                <label>&nbsp;</label>
                <input type="submit" value="Create" class="button medium" name="create"/>
              </div>
            </form>
			</td>
			
			
			<td width="30%" valign="top" style="vertical-align:top;" bgcolor="#cccccc" >
			<?php
			
			if($_POST['new_variable'] == 'Add new template variable')
			{
			?>

			<div class="field">
			   <form method="post" action="" id="myform" class="form">
			<fieldset style="width:400px; margin-top:20px;">
						 <div class="field">
                          <label>Variable Name</label>
                            <input type="text" name="variable_name" size="40"></textarea>
                        </div>
						
						
			            <div class="field">
                        	<label>ToolTip</label>
                            <textarea rows="4" class="30" name="variable_info" cols="45"></textarea>
                        </div>
						
						
	<!--		           <div class="row">
                        	<label>Status</label>
                            <select name="status">
                               <option>Select</option>
                               <option>Customized</option>
                            </select>
                        </div>-->
						
												
			           <div class="field">
                        	<label>Type</label>
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
                            <textarea rows="4" class="30" name="variable_dropdown_values" cols="45"
							 onmouseover="Tip('Enter here option values seperated with commas')" onMouseOut="UnTip();" ></textarea>
                        </div>
						
						<input type="hidden" name="hid"  id="hid" value="1"/>
						
			</fieldset>
			
			<fieldset id="test" style="width:400px;">
			
			
			
			</fieldset>
			</div>
						
						
						

              <div class="row" style="margin-bottom:20px;">
                <label></label><input type="button" name="more" value="add more" class="button medium" onClick="createDiv();"/>
                <input type="submit" value="Submit" class="button medium" name="submit"/>
              </div>
            </form>
		
			
			<?php
			
			}
			
			elseif($_GET['mode'] == 'edit_variable_now')
				{
				
				if($_POST['update']=='Update')
				{

if($_POST)
	{
		extract($_POST);
		$variable_name=$_POST['variable_name'];
		$variable_info=$_POST['variable_info'];
		//$variable_status=$_POST['variable_status'];
		$variable_type=$_POST['variable_type'];
		
		
		if(empty($variable_name))
         {
		$error="1";
		$empty_variable_name = "Please enter variable name";

        }

/*         if($variable_status == 'Select')
        {
		$error="1";
		$empty_variable_status = "Please select variable status";

        }*/

         if($variable_type == 'Select')
        {
		$error="1";
		$empty_variable_type = "Please select variable type";

        }
		
		if($error !='1')
		{
		
		if($_POST['variable_type']=='Textbox')
		  {
		    $field='<input type="text" name="'.$variable_name.'"/>';
		  }
		  elseif($_POST['variable_type']=='Multiline')
		  {
		     $field='<textarea name="'.$variable_name.'"></textarea>';
		  }
		  
		   elseif($_POST['variable_type']=='Dropdown')
		  {
		  		
			$pick_all_up=$_POST['variable_dropdown_values'];
		    $variable_dropdown_values=explode(',' , $_POST['variable_dropdown_values']);
			
			$make_dropdown='<select name="'.$variable_name.'">';

			  foreach($variable_dropdown_values as $left => $right)
				  {
			        $make_dropdown.= '<option value="'.$right.'">'.$right.'</option>';
                  }
				  
                $make_dropdown.='</select>';
				
				
			$field=$make_dropdown;
		  }
		  
		  		  if($_POST['variable_type']=='Dropdown')
		  {
		
	 $qry = $wpdb->update('variables',array('variable_name'=>$variable_name,'variable_info'=>$variable_info,'field'=>$field, 'type'=>$variable_type,'dropdown_values'=>$pick_all_up),array('sequence_id'=>$_GET['sequence_id']),array('%s','%s','%s','%s','%s'));
		 }
		 else{
		 
	$qry = $wpdb->update('variables',array('variable_name'=>$variable_name,'variable_info'=>$variable_info,'field'=>$field, 'type'=>$variable_type),array('sequence_id'=>$_GET['sequence_id'],'variable_id'=>$_GET['var_id']),array('%s','%s','%s','%s'));
	
		 }
		 

		
		$true_msg2 = 'Update successful';
		}
	
		
	}
	}
?>
<div class="grid_7" style="width:100%">
          <h3> Manage Variable </h3>
         
		            <div class="box" style="background:none;"> <?php

		  	if($error == '1')
				{
		  ?>
          <div class="error">
          	<?php echo "$empty_variable_name"."<br>"."$empty_variable_type"."<br>"; 
			?>
          </div>
		  <?php
		  }
		  	if($true_msg2)
				{
		  ?>
          <div class="true" style="padding-left:20px;"><?php echo "$true_msg2";?></div>
          <?php
		  		}
		  ?>		
					
            <form method="post" action="" name="variables_form" class="form">
		  
		  			 <?php
					$sql = "select * from variables where variable_id='".$_GET['var_id']."'";
					$res=mysql_query($sql) or die($sql." - ".mysql_error());
				?>

              
                
                <?php 	
					while($row=mysql_fetch_array($res))
					{
					
					
				?>
				
			<div class="field">
                <label><b>Variable Name</b></label>
                <input type="text" name="variable_name" value="<?php echo $row['variable_name'];?>" size="60" />
              </div>
			  
			  	<div class="field">
                <label><b>ToolTip</b></label>
                <textarea name="variable_info" rows="10" cols="40"><?php echo $row['variable_info'];?></textarea>
              </div>
			  
			 
			  
			  
			  
			  		  <?php
			  
			    $type = $row['type'];
				if($type == "Textbox")
				{
				   $type_selected1="selected";
				}
				else{
				    $type_selected1="";
				}
				
				if($type=="Multiline")
				{
				   $type_selected2="selected";
				
				}
				else{
				$type_selected2="";
				}
				
				if($type=="Dropdown")
				{
				   $type_selected3="selected";
				
				}
				else{
				$type_selected3="";
				}
               
			   if($type_selected1 == "selected")
			   {
				
				?>
				<div class="field">
                <label><b>Variable type</b></label>
                <select name="variable_type">
                <option>Select</option>
                <option selected>Textbox</option>
                <option>Multiline</option>
				<option>Dropdown</option>
                </select>
         
              </div>
			  <?php
			  }
			  elseif($type_selected2 == "selected")
			  {
			  ?>
				<div class="field">
                <label><b>Variable type</b></label>
                <select name="variable_type">
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
                <label><b>Variable type</b></label>
                <select name="variable_type">
                <option>Select</option>
                <option>Textbox</option>
                <option>Multiline</option>
				<option selected>Dropdown</option>
                </select>
         
              </div>
			  
			  <?php
			  }
			  
			   if($type=='Dropdown')
			  {
			  $dropdown_contents=$row['dropdown_values']; 			  
			  ?>
			  
			  <div class="field">
                <label><b>Dropdown Values</b></label>
				
	<textarea rows="4" class="30" name="variable_dropdown_values" cols="45" onmouseover="Tip('Enter here option values seperated with commas')" onMouseOut="UnTip();" >
	<?php echo $dropdown_contents;?></textarea>
              </div>
			  
			  <?php
			  }

}


			  if($_GET['page_no']=='')
			  {
			  ?>
			    <div class="field">
                <label></label>
                <input type="submit" value="Update" class="button medium" name="update"/>
				<a href="admin.php?page=arWizardCompleteTemplate&sequence_id=<?php echo $_GET['sequence_id'];?>"><input type="button" value="Cancel" class="button medium" /></a><br/><br/>
              </div>
			  
			  <?php
			  }
			  else{
			  ?>
              <div class="row">
                <label></label>
                <input type="submit" value="Update" class="button medium" name="update"/>
				<a href="index.php?mode=complete_template&sequence_id=<?php echo $_GET['sequence_id'];?>"><input type="button" value="Cancel"                 class= "button medium" /></a>
              </div>
			  <?php
			  }
			  ?>
			  

            </form>
			
			         </div>
        </div>
		
		<?php
		}
			
			else{
			?>
			<form method="post" action=""  name="variables_listing" class="form">
			<div style="height:100px; margin-top:20px; overflow-y:scroll;">
			
			<div class="row">
                <label><b>Autoresponder Variables</b></label>
				</div>
				
				<div class="row">
				<?php
				$auto_vars=mysql_query("select global_variable_name from global_variables group by global_variable_name");
				while($auto_array=mysql_fetch_array($auto_vars))
				{
				  echo $auto_array['global_variable_name']."<br>";
				}
				
				?>
				
			</div>
			</div>
			
			<div>
			<br />
			</div>
			
	
			
			
			<div style="height:400px; overflow-y:scroll;">
			<div class="row">
                <label><b>Template Variables</b></label>
				</div>
				
				
				
				<div class="row">
				<!--<textarea name="text" rows="30" cols="50">-->
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
					
				
				<?php
				$i=0;
				$auto_vars=$wpdb->get_results("select * from variables where sequence_id='".$_GET['sequence_id']."'");
				foreach($auto_vars as $auto_array){
				?>
                    <tr><td width="100%" valign="top" style="border:none;"><?php echo $auto_array->variable_name;?></td></tr>
                    <tr><td width="100%" valign="top"><?php echo $auto_array->variable_info;?></td></tr>
                    <tr style="padding:0px;margin:0px;">
                    <td>
				

				<a href="admin.php?page=arWizardCompleteTemplate&mode=edit_variable_now&sequence_id=<?php echo $_GET['sequence_id'];?>&var_id=<?php echo $auto_array->variable_id;?>&<?php echo $page_no;?>">  
                        <input type="button" value="Edit" class="button medium" style="width:auto;"/></a>
						
				<input type="button" value="Delete" class="button medium" 
				onclick="confirmDelete('admin.php?page=arWizardCompleteTemplate&sequence_id=<?php echo $_GET['sequence_id'];?>
				&var_id=<?php echo $auto_array->variable_id;?>')"/>
				
				<input type="hidden" name="txt<?php  echo $i;?>" value="<?php echo $auto_array->variable_name;?>" id="txt<?php echo $i;?>"/>
			    <input type = "button" value = "Add Variable" id="<?php  echo $i ?>" 
			   onclick = "insertAtCursor(document.template_form.template_content,this.id)" class="button medium">
				
				
              </td></tr>
			  
			  <tr>
			  <td width="100%">
			  <hr/>

			  </td>
			  </tr>
<?php
$i++;
				}
				
				?>
				</table>
				<!--</textarea>-->
				
				</div>

				
				<div>
			<br />
			</div>
				</div>
				
				
	<div class="row">
				<input type="submit" name="new_variable" value="Add new template variable" class="button medium"/>

				</div>
				
				
				</div>
			 </form>
			<?php
			}
			?>
			
			</td>
			
			</tr>
			</table>
          </div>
		<div class="clear"></div>
	</div>
</div>
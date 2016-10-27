<?php 
if($_POST['addVariable']){
	extract($_POST);
	$hid_value=$_POST['hid'];
	if(empty($variable_name)){
		$error = '1';
		$error_variable_name = "Please enter global variable's name";
	}
	
	for($i=1; $i < $hid_value ; $i++){
		 
		 if(($_POST['name'.$i] =='') || ($_POST['cmb'.$i]==''))
		 {
			$error.$i='1';
			$error='1';
		 }

		 
		 }
	
	

	if($error!=1)
	{


	  
	 // elseif($_POST['type']=='3')
	  //{
			   //     <select name="type">
					//       <option>Select</option>
					 //      <option value="1">Textbox</option>
					 //      <option value="2">Multiline</option>
					//	   <option value="3">Dropdown</option>
					//    </select>
		 //$field='<select name="'.$variable_name.'">';
	 // }


	  $auto_id=$_GET['auto_id'];

	
	 if($hid_value > 1)
	 {
	 
	 
		for($i=1; $i < $hid_value ; $i++)
		{
		 $name = $_POST['name'.$i];
		 $cmb = $_POST['cmb'.$i];


	   $str=$wpdb->insert('global_variables',array('global_variable_name'=>$name,'global_variable_value'=>$cmb, 'autoresponder_id'=>$auto_id), array('%s','%s','%d'));
		 
	}

		
		
		$query=$wpdb->insert('global_variables',array('global_variable_name'=>$name,'global_variable_value'=>$cmb, 'autoresponder_id'=>$auto_id), array('%s','%s','%d'));
	
		

	 }
	 else{
	 
	 $str=$wpdb->insert('global_variables',array('global_variable_name'=>$variable_name,'global_variable_value'=>$variable_value, 'autoresponder_id'=>$auto_id), array('%s','%s','%d'));

	}
	//$true_msg = 'Global variable(s) added successfully to this autoresponder';
	echo "	<script>
						jQuery(function(){
							jQuery('.hide').html('Global variable(s) added successfully to this autoresponder');
							jQuery('.hide').show().fadeOut(5000);
						});
					</script>";
	}
	
}

if(isset($_POST['submitbtn']))
	{
		extract($_POST);
		
		if(empty($autoresponder_name))
		{
		$error = '1';
		$error_name = "Please enter Autoresponders name";
		}
		
		//$content = addslashes($content);
		//echo "update cms set title = '$title', content = '$content', page_title = '$page_title', keyword = '$keyword', description = '$description'  where id = '$page_id'";
		if($error!=1)
		{
		$qry = $wpdb->insert('autoresponders',array('autoresponder_name'=>$autoresponder_name,'date_created'=>date('Y-m-d H:i:s',time())),array('%s','%s'));
		
		$true_msg = 'Autoresponder added successfully';	
		$autoresponder_id=$wpdb->insert_id;
		}
	}
?>


<div class="wrap">
	<h2><img src="<?php echo $arp->plugin_image_url;?>icon-32.png" alt="Autoresponder Wizard" align="left" /> Autoresponder Wizard </h2>
    <div class="hide updated fade">
    	
    </div>
     <?php
	$auto = $wpdb->get_row("select * from autoresponders where autoresponder_id='".$_GET['auto_id']."'");
	
					
?>
	<h3> <?php echo isset($_GET['auto_id'])?'Autoresponder Variables':'New Autoresponder';?> </h3>
    <?php if($error==1 && !empty($_GET['auto_id'])){?>
    <div class="error" style="padding-left:20px;"><?php echo "<ul>$error_variable_name</ul>"?></div>
	<?php }
		  	elseif($error == '1')
				{
		  ?>
          <div class="error" style="padding-left:20px;"><?="<ul>$error_name</ul>"?></div>
          <?php
		  		}
				
				if($error_tag)
				{
		  ?>
          <div class="error" style="padding-left:20px;"><?php echo "<ul>$error_tag</ul>"?></div>
          <?php
		  		}
				
				if($true_msg && !empty($_GET['auto_id'])){
					?>
                    <div class="true" style="padding-left:20px;"><?php echo "<ul>$true_msg</ul>"?></div>
                    <?php 
				}
		  	elseif($true_msg)
				{
		  ?>
          <div class="true" style="padding-left:20px;"><?="<ul>$true_msg</ul>"?></div>
		  <?php
          header('Location:admin.php?page=arWizardNewAutoresponder&auto_id='.$autoresponder_id);?>
		  <?php
		  		}

		  ?>
          
          
	<div class="emptySequence" style="padding:20px;">
    	<?php if(!isset($_GET['auto_id']) && empty($_GET['auto_id'])){?>
		
        <form method="post" action="" class="form">
			<fieldset>
			 <div class="field">
             <label>Autoresponder name</label>
             <input type="text" size="60" name="autoresponder_name" value="" />
             </div>
			 </fieldset>
			  
	
				
              <div class="field">
                <label>&nbsp;</label>
                <input type="submit" name="submitbtn" value="Submit" class="button medium" />
				
              </div>
            </form>
            
            <?php } else {?>
            <script type="text/javascript" language="javascript">

function createDiv()
{

var i= document.getElementById('hid').value;
var divTag = document.createElement("div");

//var dname= "div" + i;

divTag.id = "div" + i;

divTag.setAttribute("align","left");

divTag.style.padding ="20px 0 0";
divTag.style.clear = "both";
divTag.style.width = "900px";

divTag.className ="row";

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

txt.className ="adjust";

document.getElementById('test').appendChild(txt);


var lab2 = document.createElement("label");
lab2.id="lab2" + parseInt(i);
lab2.className ="label_responder_adjust";
document.getElementById('test').appendChild(lab2);

document.getElementById('lab2' + parseInt(i)).innerHTML ="Variable Value";

var cmb = "cmb" + i ;

cmb = document.createElement("input");

cmb.id = "cmb" + i;

cmb.name = "cmb" + i;

cmb.size = "40";

cmb.className ="responder_adjust2";

document.getElementById('test').appendChild(cmb);



		
		
var msg = "<h3>variable"+(parseInt(i)+1)+"</h3><hr>";


document.getElementById("div" + i).innerHTML = msg;



i= parseInt(i)+1;
document.getElementById('hid').value = i;




}


</script>
				<form method="post" action="" id="myform" class="form">
			<?php //echo $_GET['temp_id'];?>
			<fieldset>
						 <div class="field">
                          <label>Variable Name</label>
                            <input type="text" name="variable_name" size="40"></textarea>
                        </div>
						
												
						<div class="field">
                          <label>Variable Value</label>
                            <input type="text" name="variable_value" size="40"></textarea>
                        </div>
						<input type="hidden" name="hid"  id="hid" value="1"/>
						
			</fieldset>
			
			<fieldset id="test">
			
			
			
			</fieldset>
						
						<input type="button" name="more" value="add more" class="button medium" onClick="createDiv();"/>
						

              <div class="field">
                <label>&nbsp;</label>
                <input type="submit" value="Submit" name="addVariable" class="button medium" />
              </div>
            </form>
            <?php }?>
		<div class="clear"></div>
	</div>
</div>
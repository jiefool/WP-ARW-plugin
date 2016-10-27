<?php 
if(isset($_POST['btnSubmitSequence'])){
	extract($_POST);
	
	if(empty($sequence_name)){
		$error = '1';
		$error_sequence_name = "Please enter sequence name";
	}
	
	if($category=='Select'){
		$error = '1';
		$error_sequence_category = "Please select a category";
	}
	
	if($error!=1)
	{
		$qry = $wpdb->insert('sequence',array('sequence_name'=>$sequence_name, 'sequence_description'=>$sequence_description, 'date_created'=>date('Y-m-d H:i:s',time()), 'sequence_category'=>$category),array('%s','%s','%s','%s'));
		
		$sequence_id = $wpdb->insert_id;
		echo "	<script>
						jQuery(function(){
							jQuery('.hide').html('Sequence created successfully.');
							jQuery('.hide').show().fadeOut(5000);
						});
					</script>";	
		header('Location:admin.php?page=arWizardCompleteTemplate&sequence_id='.$sequence_id);  	 
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
	  
	  jQuery(function(){
				jQuery('#addSequence').validate();
			});
</script>

<div class="wrap">
	<h2><img src="<?php echo $arp->plugin_image_url;?>icon-32.png" alt="Autoresponder Wizard" align="left" /> Autoresponder Wizard </h2>
    <div class="hide updated fade">
    	
    </div>
	<h3> New Sequence </h3>
    
	<div class="emptySequence">
		<?php 
			$sql = "select * from sequence order by sequence_id desc";			
			$res=$wpdb->get_results($sql);
		?>
        <form id="addSequence" method="post" action="" class="addSequence" style="padding:20px 40px;">			
            <div class="field">
                <label>Sequence Name</label>
                <input type="text" name="sequence_name" class="required">
            </div>                
            <div class="field">
                <label>Sequence Description</label>
                <input type="text" name="sequence_description" class="required">
            </div>              
            <div class="field">
                <label>Sequence Category</label>
                <select name="category"  class="required">
                    <option value="">Select</option>
                    <option>Prospect sequence</option>
                    <option>Customer sequence</option>
                    <option>Event sequence</option>
                </select>
            </div>
            <div class="field">
                <label>&nbsp;</label>
                <input type="submit" name="btnSubmitSequence" value="Create" class="button medium" />
            </div>
        </form>

		<div class="clear"></div>
	</div>
</div>
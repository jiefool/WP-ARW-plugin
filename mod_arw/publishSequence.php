<?php 
include_once('arWizard.php');
$arp = new arWizard();
include_once("html2text.php");
$tyuploaddir = wp_upload_dir();
$optdir = $tyuploaddir['basedir']."/arw"; 
if (!file_exists($optdir)) {
	mkdir($optdir, 0777);
} 
?>
<script type="text/JavaScript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>

<?php
$myuploaddir = wp_upload_dir();
 	 $date=date("mdy : H:i:s") ;	
	 $sequence_id=$_GET['sequence_id'];
	 $get_array=$wpdb->get_row("select customer_sequence_name from customer_sequences where customer_sequence_id ='$sequence_id'");	 
	 $sequence_name= $myuploaddir['basedir'].str_replace(array(' ','#',':','\''),(array('_','_','_','_')),stripslashes(stripslashes('/arw/'.$get_array->customer_sequence_name.$date.'.zip')));
	 $seq_name = stripslashes(stripslashes($get_array->customer_sequence_name));	
	 $wpdb->update('customer_sequences', array('customer_sequence_status'=>'published'),array('customer_sequence_id'=>$sequence_id),array('%s'));
	 $ex_qry=$wpdb->get_results("select * from customer_template where customer_sequence_id='$sequence_id' order by template_id asc");
	 
	 foreach($ex_qry as $fetch){		 
		 $template_array=$wpdb->get_row("select template_name from template where template_id='".$fetch->template_id."'");		 
		 $template_name=$template_array->template_name;	
	     $ourFileName = str_replace(array(' ','#',':'),(array('_','_','_')),$template_name.$date.".txt");
         $ourFileHandle = fopen($ourFileName, 'w+') or die("can't open file");		 
		 $content=$fetch->template_content;		 
         $htmlToText = new Html2Text ($content,500);
         $text = stripslashes(stripslashes($htmlToText->convert()));		 
		 $text=str_replace('&nbsp;',' ',$text);		 
		 $text=str_replace('<br  />','',$text);		 
		 fwrite($ourFileHandle,$text);		 
         fclose($ourFileHandle);		 
		 $files_to_zip[] = $ourFileName;
	 }
	 $result = $arp->create_zip($files_to_zip,$sequence_name);
	 $file = $myuploaddir['baseurl'].str_replace(array(' ','#',':','\''),(array('_','_','_','_')),stripslashes(stripslashes('/arw/'.$seq_name.$date.'.zip')));
?>
	  <div class="wrap">

    <h2><img src="<?php echo $arp->plugin_image_url;?>icon-32.png" alt="Autoresponder Wizard" align="left" /> Autoresponder Wizard </h2>
    <div class="hide updated fade">
    	
    </div>
    <br/><br/>
    <a href="admin.php?page=arWizardSettings">&laquo; Back to List</a>
    <p id="show" style="display:none;">Templates for Sequence <?php echo $seq_name;?> download complete.</p>
    </div>
	  <script type="text/javascript">
	  jQuery('#show').fadeIn(2000);
	  location.href="<?php echo $file;?>";
	  </script>
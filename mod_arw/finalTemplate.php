<style>
.wrap a{
	text-decoration:none;
}
.wrap a:hover{
	text-decoration:underline;
}
.wrap .clear{
	clear:both;
}
.wrap h2{
	line-height: 38px;
}
.wrap h2 img{
	padding-right:5px;
}
.wrap h3{
	font-size:20px;
}
.wrap .addSequence{
	padding:30px 60px;
	border:1px solid #e0dede;
	border-radius: 9px;
	-moz-border-radius: 9px;
	-webkit-border-radius: 9px;
	background: #ececec;
	color:#888;
}
.wrap .addSequence strong{
	font-size: 12px;
}
.wrap .emptySequence{
	
	border:1px solid #e0dede;
	border-radius: 9px;
	-moz-border-radius: 9px;
	-webkit-border-radius: 9px;
	background: #ececec;
	color:#888;
}
.addSequence .field{
	padding: 20px 0;
}
.addSequence .field label{
	width:170px;
	display: block;
	float: left;
	line-height:40px;
	font-weight: bold;
}
.wrap input[type="text"]{
	width:300px;
	padding: 10px;
}
.wrap textarea{
	width:300px;
	padding: 10px;
}
.addSequence .field input[type="text"]{
	width:300px;
	padding: 10px;
}
.addSequence .field select{
	width: 300px;
	height: 36px;
	padding: 0 0 0 10px;
}
.addSequence .field input[type="submit"]{
	padding: 10px 50px;
	font-size:16px;
	font-weight: bold;
}

.wrap .red{
	color: #cc0000;
}
.wrap p.error{
	margin-left:170px;
	color:red;
}
.wrap .hide{
	display:none;
}
.wrap .updated{
	padding:10px;
}
.wrap .button{
	background: #7e7b7b;
	border-radius:6px;
	-moz-border-radius:6px;
	-webkit-border-radius: 6px;
	padding: 5px 10px;
	color: #fff;
}
.wrap td{ height:30px; vertical-align:middle; padding-left:20px;}
.qtip-content{word-wrap: break-word;}
</style>
<?php 
	global $wpdb;
	
	
if((isset($_POST)) && ($_POST['final'] == 'Finalize'))
{             
             $final_id=$_SESSION['template_id_customized'];
             $template_array=$wpdb->get_row("select * from customer_template where cust_temp_id = '".$final_id."'");
             
             $content=$template_array->template_content;
             $template_id=$template_array->template_id;
			 $sequence_id=$template_array->customer_sequence_id;
			
			 $update_qry = $wpdb->query("update customer_tracker_table set customer_template_status='active' where customer_template_id='".$template_id."' and customer_sequence_id='".$sequence_id."'") ;
			 
			 $qry = $wpdb->query("update customer_template set status ='final' where cust_temp_id = '".$final_id."'");
			 
			 unset($_SESSION['sequence_id']);
			 unset($_SESSION['template_id_customized']);
			 ?>
			 
			 <script language="javascript">
			location.href="admin.php?page=arWizardSettings";
		    </script>  
			
			<?php

			

}

if((isset($_POST)) && ($_POST['undo'] == 'Undo'))
{             
             $final_id=$_SESSION['template_id_customized'];
			 
			 $get_array=$wpdb->get_row("select * from customer_template where cust_temp_id = '".$final_id."'");
			 
			 $sequence_id=$get_array->customer_sequence_id;
			 
			 $sequencetype = $wpdb->get_row("select * from customer_sequences where customer_sequence_id = ".$sequence_id);
			 $template_id=$get_array->template_id;
			 
			 $tracker_query=mysql_query("update customer_tracker_table set customer_template_status='inactive' where customer_sequence_id ='".$sequence_id."' and 
		     customer_template_id= '".$template_id."'");
		 
             $template_query="update customer_template set status='notfinal' where customer_sequence_id ='".$sequence_id."' and template_id
             = '".$template_id."'" ;
 
             $ex_qry=mysql_query($template_query);
			 
			// $template_query=mysql_query("select template_id from customer_template where cust_temp_id = '".$final_id."' and customer_id='".$_SESSION['customer_id']."'");
            // $template_array=mysql_fetch_array($template_query);
			 //$template_id=$template_array['template_id'];
			// $sequence_id_or=$template_array['customer_sequence_id'];
			 			 
            // $template_query=mysql_query("delete from customer_template where cust_temp_id = '".$final_id."' and customer_id='".$_SESSION['customer_id']."'") or die(mysql_error());
			
			 echo".";
			 
			 ?>
			 	<form name="undo1" action="admin.php?page=arWizardAddSequence&sequence_id=<?php echo $sequencetype->customer_sequence_type;?>&temp_id=<?php echo $template_id;?>&undoid=<?php echo $final_id;?>" method="post" id="undo1">
					 </form>
			 
			 <script language="javascript">
			 document.undo1.submit();
			 </script>
			 


<?php

}
?><?php
		  $final_id=$_SESSION['template_id_customized'];

$template_array=$wpdb->get_row("select * from customer_template where cust_temp_id = '".$final_id."'");
$content=$template_array->template_content;
$template_id=$template_array->template_id;
$customer_sequence_id=$template_array->customer_sequence_id;

$get_array=$wpdb->get_row("select template_name from template where template_id='$template_id'");
$template_name=$get_array->template_name;


$get_sq_array=$wpdb->get_row("select customer_sequence_id,customer_sequence_name from customer_sequences where customer_sequence_id='$customer_sequence_id'");

$seq_name=$get_sq_array->customer_sequence_name;
?>
<div class="wrap">
		 <div class="emptySequence" style="padding:20px;">
         	<?php echo stripslashes($template_array->template_content);?>
         </div>
</div>
<form method="post" action="" name="form_template">
<input type="submit" value="Finalize" class="button medium" name="final"/>
				<input type="submit" value="Undo" class="button medium" name="undo"/>
                </form>
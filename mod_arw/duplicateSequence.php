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
.wrap .duplicateSequence{
	padding:30px 60px;
	border:1px solid #e0dede;
	border-radius: 9px;
	-moz-border-radius: 9px;
	-webkit-border-radius: 9px;
	background: #ececec;
	color:#888;
}
.wrap .duplicateSequence strong{
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
.duplicateSequence .field{
	padding: 20px 0;
}
.duplicateSequence .field label{
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
.duplicateSequence .field input[type="text"]{
	width:300px;
	padding: 10px;
}
.duplicateSequence .field select{
	width: 300px;
	height: 36px;
	padding: 0 0 0 10px;
}
.duplicateSequence .field input[type="submit"]{
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
	//$error = false;
	include_once('arWizard.php');
	$arp = new arWizard();
if(isset($_POST['duplicate_btn']))
{

    $sequence_name = $_POST['sequence_name'];
	//$sequence_type = $_POST['sequence_type'];
	$responder = $_POST['autoresponder'];
    //$closing_snippet = addslashes($_POST['closing_snippet']);


	if(empty($sequence_name))
		{
			$error="1";
			$sequence_name_error = "Please enter sequence name";
		}

/*	if($sequence_type=='Select')
		{
			$error="1";
			$sequence_type_error = "Please enter sequence type";
		}*/

	if($responder =='Select')
		{
			$error="1";
			$responder_error = "Please select a responder";
		}

	if($error != 1)
			{
				 $sequence_id=$_GET['sequence_id'];

				 //immitate customer sequence table//


				 $seq_array = $wpdb->get_row("select * from customer_sequences where customer_sequence_id='$sequence_id'");

				 $autoresponder_old= $seq_array->customer_auto_name;
				 $snippet_old=$seq_array->closing_snippet;

				$seq_type = $seq_array->customer_sequence_type;


				  $currentUserId = get_current_user_id();
				  $wpdb->insert('customer_sequences',array('user_id'=>$currentUserId,'customer_sequence_name'=>$sequence_name,'customer_auto_name'=>$responder,'customer_sequence_status'=>'saved','customer_sequence_type'=>$seq_type),array('%d','%s','%d','%s','%d'));

				 //immitate customer sequence table//


				 //immitate customer tracker table//


				 $new_sequence_id=mysql_insert_id();

				 $_SESSION['duplicate_sequence_id']=$new_sequence_id;



				 ?>
				<script language="javascript">
					location.href="admin.php?page=arWizardDuplicateGlobal&sequence_id=<?php echo $_GET['sequence_id'];?>";
				</script>
<?php


			}
}
?>


<div class="wrap">

    <h2><img src="<?php echo $arp->plugin_image_url;?>icon-32.png" alt="Autoresponder Wizard" align="left" /> Autoresponder Wizard </h2>
    <div class="hide updated fade">

    </div>

    	<script type="text/javascript">
			jQuery(function(){
				jQuery('#duplicateSequence').validate();
			});
		</script>
        <h3> Duplicate Sequence &raquo; </h3>

        <form id="duplicateSequence" method="post" action="">
        <div class="duplicateSequence">
            <div class="field">
                <label>Sequence Name</label>
                <input type="text" name="sequence_name" value="" size="40" class="required" /> *
            </div>

		 <?php if($sequence_name_error){?>
			<div class="error" style="padding-left:220px;"><?php echo $sequence_name_error?></div>
		<?php }?>




	   <div class="field">
                <label>Autoresponder</label>
					<?php
				$pull_down_responder= '<select name="autoresponder" >';
				$pull_down_responder.= '<option>Select</option>';

				$qry=mysql_query("select * from autoresponders");
				while($fetch=mysql_fetch_array($qry))
				{
					$pull_down_responder.='<option value="'.$fetch['autoresponder_id'].'">'.$fetch['autoresponder_name'].'</option>';
				}



                    $pull_down_responder.='</select>';
					echo $pull_down_responder. ' *';
					?>
       </div>


	   <?php
         if($responder_error)
		{
?>
<div class="error" style="padding-left:220px;"><?php echo $responder_error?> </div>
<?php
		}
?>





			 <div class="field" align="left" style="padding-right:350px;">
                <label>&nbsp;</label>
                <input name="duplicate_btn" type="submit" value="Create" class="button medium" />
              </div>
		</div>

			  </form>



</div>

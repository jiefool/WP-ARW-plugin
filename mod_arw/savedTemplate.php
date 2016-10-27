<div class="wrap">

    <h2><img src="<?php echo $arp->plugin_image_url;?>icon-32.png" alt="Autoresponder Wizard" align="left" /> Autoresponder Wizard </h2>
    <div class="hide updated fade">
    	
    </div>
    
    <h3> Edit Templates &raquo; </h3>       
        <div class="emptySequence">
        <table cellspacing="0" width="100%" class="widefat sortable resizable editable" align="center">
			<thead>		 
                <tr width="100%">
                    <th width="33%" valign="top">Name</th>
                    <th width="33%" valign="top">Status</th>	
                    <th width="33%" valign="top">Edit</th>          
                </tr>
                 </thead>
							   
			   <?php
				   $sequence_id=$_GET['sequence_id'];
				   
				   $seq_query = $wpdb->get_row("select * from customer_sequences where customer_sequence_id = '".$sequence_id."'");
				   
				   $customer_sequence_type=$seq_query->customer_sequence_type;
				   
				   $templates=$wpdb->get_results("select * from customer_tracker_table where customer_sequence_id='".$_GET['sequence_id']."' order by customer_template_id");
				   foreach($templates as $t){
					   $template_id=$t->customer_template_id;
					   $template_name=$t->customer_template_name;
					   $template_status = $t->customer_template_status;
					   if($template_status=='inactive')
			   {

			   
			   ?>
			   	 <tr width="100%">
               		
                            <td width="33%"><b><?php echo ucwords($template_name);?></b></td>
                            <td width="33%"><b><?php echo 'Draft';?></b></td>
                            <td width="33%">
                            <a href="admin.php?page=arWizardEditTemplates&temp_id=<?php echo $template_id;?>&sequence_id=<?php echo $sequence_id;?>" style="text-decoration:none;" class="button">
                            Edit
                            </a>
                            
                             <a href="admin.php?page=arWizardEditGlobal&sequence_id=<?php echo $sequence_id;?>" style="text-decoration:none;" class="button">
                            Global Vars
                            </a>
                         
				</tr>
                 
                 
			   <?php
			   }
			   
			   else{
			   ?>
			   	 <tr width="100%">
               		
                            <td width="33%"><b><?php echo ucwords($template_name);?></b></td>
                            <td width="33%"><b><?php echo 'Saved';?></b></td>
                            <td width="33%">
                            <a href="admin.php?page=arWizardViewTemplate&temp_id=<?php echo $template_id;?>&sequence_id=<?php echo $sequence_id;?>" style="text-decoration:none;" class="button">
                            View
                            </a>
                         
				</tr>
			   
			<?php   
			   }
			   ?>
               
               
			   	
			   <?php
			   	  }
			   
			   ?>

			 </table>
        </div>
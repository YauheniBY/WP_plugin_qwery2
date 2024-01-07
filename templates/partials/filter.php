<div class="wrapper filter_form">
    <?php       
      $qazproperty = new qazProperty();

      $options = get_option('qazproperty_settings_options');
       if(isset($options['filter_title'])){
        
            echo $options['filter_title'];
       }
      
    ?>    
    <form method="post" action="<?php get_post_type_archive_link('property'); ?>">
        <input type="submit" value="<?php esc_attr_e('Filter');?>" name="submit"/>
        
        <select name="qazproperty_location" id="qazproperty_location">
            <option value=""><?php esc_html_e('Select Location'); ?></option>
           
            <?php
            if(isset($_POST['qazproperty_location']) && ($_POST['qazproperty_location'] != '') ){
                $current_location = $_POST['qazproperty_location'];
            } else {
                $current_location ='';
            }
                echo $qazproperty->get_terms_hierarchical('location', $current_location); 
            ?> 
        </select>
        <select name="qazproperty_offer_type" id="qazproperty_offer_type">
            <option value=""><?php esc_html_e('Select Offers Type'); ?></option>
           
            <?php
            if(isset($_POST['qazproperty_offer_type']) && ($_POST['qazproperty_offer_type'] != '') ){
                $current_offer_type = $_POST['qazproperty_offer_type'];
            } else {
                $current_offer_type ='';
            }
                echo $qazproperty->get_terms_hierarchical('property-type', $current_offer_type); 
            ?> 
        </select>
        
        <input type="text" value="<?php if(isset($_POST['qazproperty_price'])){esc_attr_e($_POST['qazproperty_price']);}?>" name="qazproperty_price" placeholder="Max price"/>
        
        <select name="qazproperty_type" id="qazproperty_type">
        <option value="" <?php if(isset($_POST['qazproperty_type']) && ($_POST['qazproperty_type'] == '')){ echo 'selected';}  ?>><?php esc_html_e('Select Type','qazproperty'); ?></option>
            <option value="sale" <?php if(isset($_POST['qazproperty_type']) && ($_POST['qazproperty_type'] == 'sale')){ echo 'selected';}  ?>><?php esc_html_e('For Sale','qazproperty'); ?></option>
            <option value="rent" <?php if(isset($_POST['qazproperty_type']) && ($_POST['qazproperty_type'] == 'rent')){ echo 'selected';}  ?>><?php esc_html_e('For Rent','qazproperty'); ?></option>
            <option value="sold" <?php if(isset($_POST['qazproperty_type']) && ($_POST['qazproperty_type'] == 'sold')){ echo 'selected';}  ?>><?php esc_html_e('Sold','qazproperty'); ?></option>
        </select>

        <select name="qazproperty_agent" id="qazproperty_agent">
            <option value="" ><?php esc_html_e('Select Agent','qazproperty'); ?></option>
            <?php $agents = get_posts(array('post_type'=>'agent', 'numberposts'=>'-1')); 
                $agent_id = '';
                if(isset($_POST['qazproperty_agent'])){ 
                    $agent_id = $_POST['qazproperty_agent'];
                }
                foreach($agents as $agent){ 
                ?>
                    <option value="<?php echo($agent->ID);?>" <?php echo(selected($agent->ID, $agent_id, false));?>><?php echo($agent->post_title); ?></option>
                <?php }
            ?>
           </select>
    </form>
</div>
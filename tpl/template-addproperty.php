<?php
/**
 * Template Name: Add Property
 */ 

 function qazproperty_image_validation($file_name){
    $valide_extensions = array('jpg','jpeg','gif','png');
    $exploded_array = explode('.',$file_name);
    if(!empty($exploded_array) && is_array($exploded_array)){
        $ext = array_pop($exploded_array);
        return in_array($ext, $valide_extensions);
    } else {
        return false;
    }
 }

 function qazproperty_insert_attachment($file_handler,$post_id, $setthumb=false){
    if($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK ) __return_false();
    
    require_once(ABSPATH . "wp-admin" ."/includes/image.php");
    require_once(ABSPATH . "wp-admin" ."/includes/file.php");
    require_once(ABSPATH . "wp-admin" ."/includes/media.php");

    $attach_id = media_handle_upload($file_handler, $post_id);

    if($setthumb){
        update_post_meta($post_id, '_thumbnail_id', $attach_id);
    }

    return $attach_id;


}  

$success = '';

if(isset($_POST['action']) && is_user_logged_in()){
    if(wp_verify_nonce($_POST['property_nonce'], 'submit_property' )){

        $qazproperty_item = array();

        $qazproperty_item ['post_title'] = sanitize_text_field($_POST['property_title']);
        $qazproperty_item ['post_content'] = sanitize_textarea_field($_POST['property_description']);
        $qazproperty_item ['post_type'] = 'property';

        global $current_user; 
        wp_get_current_user();
        $qazproperty_item ['post_author'] = $current_user->ID;
        $qazproperty_action = $_POST['action'];

        if($qazproperty_action == 'qazproperty_add_property'){
            $qazproperty_item ['post_status'] = 'pending';
            $qazproperty_item_id = wp_insert_post($qazproperty_item);
            
            if($qazproperty_item_id > 0){
                do_action('wp_insert_post','wp_insert_post'); 
                $success = 'property successefull published';
            
            }
        } elseif ($qazproperty_action == 'qazproperty_edit_property' ){
            $qazproperty_item ['post_status'] = 'pending';            
            $qazproperty_item ['ID'] = intval($_POST['property_id']);
            $qazproperty_item_id = wp_update_post($qazproperty_item);
            $success = 'property successefull updated';
        }
// matabox, taxonomy, featured image

        if($qazproperty_item_id > 0){
            // Metabox
            if(isset($_POST['property_offer']) && ($_POST['property_offer'] != '')){
                update_post_meta($qazproperty_item_id, 'qazproperty_type',trim($_POST['property_offer']));
            }
            if(isset($_POST['property_price'])){
                update_post_meta($qazproperty_item_id, 'qazproperty_price',trim($_POST['property_price']));
            }
            if(isset($_POST['property_period'])){
                update_post_meta($qazproperty_item_id, 'qazproperty_period',trim($_POST['property_period']));
            }
            if(isset($_POST['property_agent']) &&  $_POST['property_agent'] != 'disable'){
                update_post_meta($qazproperty_item_id,'qazproperty_agent',trim($_POST['property_agent']));
            }

            // taxonomy

            if(isset($_POST['property_location'])){
                wp_set_object_terms($qazproperty_item_id, intval($_POST['property_location']), 'location');
            }
            if(isset($_POST['property_type'])){
                wp_set_object_terms($qazproperty_item_id, intval($_POST['property_type']), 'property-type');
            }
            // featured image

            if($_FILES){
                foreach($_FILES as $submitted_file => $file_array){
                    if (qazproperty_image_validation($_FILES[$submitted_file]['name'])){
                        $size = intval($_FILES[$submitted_file]['size']);
                        if($size > 0){
                            qazproperty_insert_attachment($submitted_file,$qazproperty_item_id, true );
                        }
                    }
                }
            }
        }

             
    }

}

// }
    get_header();?>

    
    

    <div class="wrapper">

    <?php 

    if ( have_posts() ) {

        // Load posts loop.
        while ( have_posts() ) {
            the_post(); 
    ?>
        
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>           
            <!-- <h2>
                <?php 
                // the_title(); 
                ?>
            </h2>    -->
            <div class="description"> <?php the_content(); ?></div>            
            </article>

            <?php 
            if(is_user_logged_in()){

                if($success){
                    echo esc_html__($success);
                } else{
                        if(isset($_GET['edit']) && !empty($_GET['edit'])){

                            $property_id_edit = intval(trim($_GET['edit']));

                            $qaz_edit_property = get_post($property_id_edit);

                            if(!empty($qaz_edit_property) && ($qaz_edit_property->post_type == 'property')){


                                global $current_user; 
                                wp_get_current_user();
                                

                                if($current_user->ID == $qaz_edit_property->post_author){
                                    $qaz_metadata = get_post_custom($qaz_edit_property->ID);
                                    ?>  
                                    
                                    <h2>Edit property</h2> 
                                        <div class="add_form">
                                            <form enctype="multipart/form-data" method="post" id="add_property">
                                                <p>
                                                    <label for="property_title">Enter the Title</label>
                                                    <input type="text" id="property_title" name="property_title" placeholder="Add the Title" value="<?php echo esc_attr($qaz_edit_property->post_title); ?>" required tabindex="1" />
                                                </p>
                                                <p>
                                                <label for="property_description">Write the Description</label>
                                                    <textarea id="property_description" name="property_description" required tabindex="2"><?php echo esc_html($qaz_edit_property->post_content); ?></textarea>
                                                </p>
                                                <p>
                                                <label for="property_image">Feature the Image</label>
                                                    <input type="file" id="property_image" name="property_image" tabindex="3" />
                                                </p>

                                                <p>
                                                <label for="property_location">Select the Location</label>
                                                    <select  id="property_location" name="property_location" required tabindex="4">
                                                        <?php 
                                                            $current_term_id  = 0;
                                                            $tax_terms = get_the_terms($qaz_edit_property->ID,'location');


                                                            if(!empty($tax_terms)){
                                                                foreach($tax_terms as $tax_term){

                                                                   $current_term_id =  $tax_term->term_id;
                                                                   break;
                                                                }
                                                            }
                                                            $current_term_id = intval($current_term_id);

                                                            $locations = get_terms(array('location'),array('hide_empty'=>false));
                                                            
                                                            if(!empty($locations)){
                                                                foreach($locations as $location){
                                                                    $selected = '';
                                                                    if( $current_term_id == $location->term_id){$selected = 'selected';}
                                                                    echo'<option '.$selected.' value='.$location->term_id.'>'.$location->name.'</option>';
                                                                }
                                                            }
                                                        ?>
                                                    </select>
                                                </p>
                                                <p>
                                                <label for="property_type">Select the Type</label>
                                                    <select  id="property_type" name="property_type" required tabindex="5">
                                                        <?php
                                                        $curren_property_type_term_id  = 0;
                                                        $tax_property_type_terms = get_the_terms($qaz_edit_property->ID,'property-type');


                                                        if(!empty($tax_property_type_terms)){
                                                            foreach($tax_property_type_terms as $tax_term){

                                                               $curren_property_type_term_id = $tax_term->term_id;
                                                               break;
                                                            }
                                                        }
                                                        $curren_property_type_term_id = intval($curren_property_type_term_id);

                                                            $types = get_terms(array('property-type'),array('hide_empty'=>false));
                                                            if(!empty($types)){
                                                                foreach($types as $type){
                                                                    $selected = '';
                                                                    if( $curren_property_type_term_id == $type->term_id){$selected = 'selected';}
                                                                    echo'<option '.$selected.' value='.$type->term_id.'>'.$type->name.'</option>';
                                                                }
                                                            }
                                                        ?>
                                                    </select>
                                                </p>
                                                <p>
                                                <label for="property_offer">Select the Offer</label>
                                                    <select  id="property_offer" name="property_offer" required tabindex="6">
                                                        <option value="">Not Selected</option>
                                                        <option <?php if(get_post_meta($qaz_edit_property->ID, 'qazproperty_type', true) == 'sale'){ echo 'selected';} ?> value="sale"><?php esc_html_e('For Sale', 'qazproperty'); ?></option>
                                                        <option <?php if(get_post_meta($qaz_edit_property->ID, 'qazproperty_type', true) == 'sold'){ echo 'selected';} ?> value="sold"><?php esc_html_e('Sold', 'qazproperty'); ?></option>
                                                        <option <?php if(get_post_meta($qaz_edit_property->ID, 'qazproperty_type', true) == 'rent'){ echo 'selected';} ?> value="rent"><?php esc_html_e('For Rent', 'qazproperty'); ?></option>
                                                    </select>
                                                </p>
                                                <p>
                                                    <label for="property_price">Enter the price</label>
                                                    <input type="text" id="property_price" name="property_price" placeholder="Enter the price" value="<?php echo esc_attr(get_post_meta($qaz_edit_property->ID, 'qazproperty_price', true)); ?>" required tabindex="7" />
                                                </p>
                                                <p>
                                                    <label for="property_period">Enter the period</label>
                                                    <input type="text" id="property_period" name="property_period" placeholder="Enter the period" value="<?php echo esc_attr(get_post_meta($qaz_edit_property->ID, 'qazproperty_period', true)); ?>" required tabindex="8" />
                                                </p>
                                                <p>
                                                    <?php global $current_user; wp_get_current_user(); ?>
                                                <label for="property_agent">Select the Agent</label>
                                                    <select  id="property_agent" name="property_agent" required tabindex="9">
                                                        <option value="disable">Disable Agent. Use user</option>
                                                    <?php
                                                        $current_agent = get_post_meta($qaz_edit_property->ID, 'qazproperty_agent', true);
                                                            echo $current_agent;
                                                        $agents = get_posts(array('post_type'=>'agent','numberposts'=>-1 ));
                                                        if(!empty($agents)){
                                                            foreach($agents as $agent){
                                                                $selected = '';
                                                                if($current_agent == $agent->ID){ 
                                                                    $selected = 'selected';
                                                                }
                                                                echo'<option '.$selected.' value="'.$agent->ID.'">'.$agent->post_title.'</option>';
                                                            }
                                                        }
                                                    ?>                        
                                                    </select>
                                                </p>
                                                <p>
                                                    <?php 
                                                    wp_nonce_field('submit_property','property_nonce');  
                                                    ?>
                                                    <input type="submit" id="property_submit" name="submit"  value="Edit the Property" tabindex="10" />
                                                    <input type="hidden" id="property_action" name="action"  value="qazproperty_edit_property"  />
                                                    <input type="hidden" id="property_id" name="property_id"  value="<?php echo esc_attr($qaz_edit_property->ID); ?>" />
                                                </p>

                                            </form>  
                                        </div>

                                    <?php
                                
                                }   

                            }
                        } else {
                
            ?>

                            
                                <h2>Add new Property</h2> 
                                <div class="add_form">
                                <form enctype="multipart/form-data" method="post" id="add_property">
                                    <p>
                                        <label for="property_title">Enter the Title</label>
                                        <input type="text" id="property_title" name="property_title" placeholder="Add the Title" value="" required tabindex="1" />
                                    </p>
                                    <p>
                                    <label for="property_description">Write the Description</label>
                                        <textarea id="property_description" name="property_description"placeholder="Add the Description" required tabindex="2"></textarea>
                                    </p>
                                    <p>
                                    <label for="property_image">Feature the Image</label>
                                        <input type="file" id="property_image" name="property_image" tabindex="3" />
                                    </p>

                                    <p>
                                    <label for="property_location">Select the Location</label>
                                        <select  id="property_location" name="property_location" required tabindex="4">
                                            <?php 
                                                $locations = get_terms(array('location'),array('hide_empty'=>false));
                                                if(!empty($locations)){
                                                    foreach($locations as $location){
                                                        echo'<option value='.$location->term_id.'>'.$location->name.'</option>';
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </p>
                                    <p>
                                    <label for="property_type">Select the Type</label>
                                        <select  id="property_type" name="property_type" required tabindex="5">
                                            <?php 
                                                $types = get_terms(array('property-type'),array('hide_empty'=>false));
                                                if(!empty($types)){
                                                    foreach($types as $type){
                                                        echo'<option value='.$type->term_id.'>'.$type->name.'</option>';
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </p>
                                    <p>
                                    <label for="property_offer">Select the Offer</label>
                                        <select  id="property_offer" name="property_offer" required tabindex="6">
                                            <option value="">Not Selected</option>
                                            <option value="sale"><?php esc_html_e('For Sale', 'qazproperty'); ?></option>
                                            <option value="sold"><?php esc_html_e('Sold', 'qazproperty'); ?></option>
                                            <option value="rent"><?php esc_html_e('For Rent', 'qazproperty'); ?></option>
                                        </select>
                                    </p>
                                    <p>
                                        <label for="property_price">Enter the price</label>
                                        <input type="text" id="property_price" name="property_price" placeholder="Enter the price" value="" required tabindex="7" />
                                    </p>
                                    <p>
                                        <label for="property_period">Enter the period</label>
                                        <input type="text" id="property_period" name="property_period" placeholder="Enter the period" value="" required tabindex="8" />
                                    </p>
                                    <p>
                                        <?php global $current_user; wp_get_current_user(); ?>
                                    <label for="property_agent">Select the Agent</label>
                                        <select  id="property_agent" name="property_agent" required tabindex="9">
                                            <option value="disable">Disable Agent. Use user</option>
                                        <?php 
                                            $agents = get_posts(array('post_type'=>'agent','numberposts'=>-1 ));
                                            if(!empty($agents)){
                                                foreach($agents as $agent){
                                                    echo'<option value="'.$agent->ID.'">'.$agent->post_title.'</option>';
                                                }
                                            }
                                        ?>                        
                                        </select>
                                    </p>
                                    <p>
                                        <?php 
                                        wp_nonce_field('submit_property','property_nonce');  
                                        ?>
                                        <input type="submit" id="property_submit" name="submit"  value="Add New Property" tabindex="10" />
                                        <input type="hidden" id="property_action" name="action"  value="qazproperty_add_property"  />
                                    </p>

                                </form>  
                            </div>
                <?php
                        }
                    } 
                } else {
                    echo '<p>You can not see fields unless you get autorisation on the site!</p>';
            }
                ?>
    <?php
            } 

    }    
    ?>

    </div>
    
    <?php
    get_footer();
?>
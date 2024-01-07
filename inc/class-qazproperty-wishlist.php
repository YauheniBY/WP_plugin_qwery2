<?php 
 class qazProperty_Wishlist {
    function register(){
        add_action('wp_ajax_qazproperty_add_wishlist', [$this,'qazproperty_add_wishlist']);
        add_action('wp_ajax_qazproperty_remove_wishlist', [$this,'qazproperty_remove_wishlist']);


    }

    public function qazproperty_add_wishlist(){

        // qaz_property_id
        //  qaz_user_id
        if(isset($_POST['qaz_property_id']) && isset($_POST['qaz_user_id']) ){
            $qaz_user_id = intval($_POST['qaz_user_id']);
            $qaz_property_id = intval($_POST['qaz_property_id']);
            
            if($qaz_user_id > 0 && $qaz_property_id > 0){
                if(add_user_meta($qaz_user_id,'qazproperty_wishlist_properties', $qaz_property_id)){
                    esc_html_e('successefull added to wishlist', 'qazproperty');
                } else {
                    esc_html_e('Failed and it is not added to wishlist', 'qazproperty');
                }

            }
        
        }
        wp_die();

    }

    public function qazproperty_remove_wishlist(){
            
        if(isset($_POST['qaz_property_id']) && isset($_POST['qaz_user_id']) ){
            $qaz_user_id = intval($_POST['qaz_user_id']);
            $qaz_property_id = intval($_POST['qaz_property_id']);
            
            if($qaz_user_id > 0 && $qaz_property_id > 0){
                if(delete_user_meta($qaz_user_id,'qazproperty_wishlist_properties', $qaz_property_id)){
                    echo '3'; //ok
                } else{
                    echo '2';//failed
                }

            } else{
                echo '1';//bad
            }
        
        } else{
            echo '1'; //bad
        }
        wp_die();


    }

    public function qazproperty_in_wishlist($user_id, $property_id){
        global $wpdb;
        $result = $wpdb->get_results("SELECT * FROM  $wpdb->usermeta WHERE meta_key = 'qazproperty_wishlist_properties' AND meta_value = ".$property_id." AND user_id =".$user_id." " );
        if(isset($result[0]->meta_value) && ($result[0]->meta_value) == $property_id ){
           return true; 
        } else {
            return false;
        }

    }


 }

 $qazProperty_Wishlist = new qazProperty_Wishlist();
 $qazProperty_Wishlist->register();

?>
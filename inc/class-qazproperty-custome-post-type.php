<?php 

if(!class_exists('qazProperty_Cpt')){

    class qazProperty_Cpt {
        public function register(){
            add_action('init', [$this, 'custom_post_type']);

            add_action('add_meta_boxes', [$this, 'add_metabox_property']);
            add_action('save_post', [$this, 'save_metabox'], 10, 2);           
            
            add_action('manage_property_posts_columns',[$this, 'custom_columns_for_property']);
            add_action('manage_property_posts_custom_column',[$this, 'custom_property_columns_data'],10, 2);
            add_filter('manage_edit-property_sortable_columns',[$this, 'custom_property_columns_sort']);
            add_action('pre_get_posts',[$this, 'custom_property_order']);



        }

        public function add_metabox_property(){
            add_meta_box(
               'qazproperty_settings',
               'Property Settings',
               [$this, 'metabox_property_html'],
               'property',
               'normal',
               'default',
            );
        }

        public function save_metabox($post_id, $post){
            if(!isset($_POST['_qazproperty']) || !wp_verify_nonce($_POST['_qazproperty'], 'qazpropertyfields')){
                return $post_id;
            }

            if(defined('DOING_AUTOCAVE') && DOING_AUTOCAVE){
                return $post_id;
            }

            if(($post->post_type) != 'property'){
                return $post_id;
            }
            $post_type = get_post_type_object($post->post_type);

            if(!current_user_can($post_type->cap->edit_post,$post_id)){
                return $post_id;
            }

            if(is_null($_POST['qazproperty_price'])){
                delete_post_meta($post_id,'qazproperty_price');
            } else {
                update_post_meta($post_id,'qazproperty_price',sanitize_text_field(intval($_POST['qazproperty_price'])));
            }

            if(is_null($_POST['qazproperty_period'])){
                delete_post_meta($post_id, 'qazproperty_period');
            } else {
                update_post_meta($post_id,'qazproperty_period',sanitize_text_field($_POST['qazproperty_period']));
            }

            if(is_null($_POST['qazproperty_type'])){
                delete_post_meta($post_id,'qazproperty_type');
            } else {
                update_post_meta($post_id,'qazproperty_type',sanitize_text_field($_POST['qazproperty_type']));
            }

            if(is_null($_POST['qazproperty_agent'])){
                delete_post_meta($post_id,'qazproperty_agent');
            } else {
                update_post_meta($post_id,'qazproperty_agent',sanitize_text_field($_POST['qazproperty_agent']));
            }
            return $post_id;
        }

        public function metabox_property_html($post){
            $price = get_post_meta($post->ID, 'qazproperty_price', true);
            $period = get_post_meta($post->ID, 'qazproperty_period', true);
            $type = get_post_meta($post->ID, 'qazproperty_type', true);
            $agent_meta = get_post_meta($post->ID, 'qazproperty_agent', true);

            wp_nonce_field('qazpropertyfields','_qazproperty');

            echo '
            <p>
                <label for="qazproperty_price">'.esc_html__('Price (USD)','qazproperty').'</label>
                <input type="number" name="qazproperty_price" id="qazproperty_price" value="'.esc_html($price).'"></input>
            </p>
            <p>
                <label for="qazproperty_period">'.esc_html__('Period','qazproperty').'</label>
                <input type="text" name="qazproperty_period" id="qazproperty_period" value="'.esc_html($period).'"></input>
            </p>
            <p>
                <label for="qazproperty_type">'.esc_html__('Type','qazproperty').'</label>
                <select name="qazproperty_type" id="qazproperty_type">
                    <option value="">'.esc_html__('Select Type','qazproperty').'</option>
                    <option value="sale" '.selected('sale',$type,false).'>'.esc_html__('For Sale','qazproperty').'</option>
                    <option value="rent" '.selected('rent',$type,false).'>'.esc_html__('For Rent','qazproperty').'</option>
                    <option value="sold" '.selected('sold',$type,false).'>'.esc_html__('Sold','qazproperty').'</option>
                </select>
            </p>
            ';
            $agents = get_posts(array('post_type' => 'agent','numberposts'=>-1 ));
            if($agents){
                echo '
                <p>
                <label for="qazproperty_agent">'.esc_html__('Agent','qazproperty').'</label>
                <select name="qazproperty_agent" id="qazproperty_agent">     
                <option value="">Select Agent</option>
                ';

                foreach($agents as $agent){
                    echo '
                    <option value="'.$agent->ID.'" '.selected($agent->ID,$agent_meta,false).'>'.$agent->post_title.'</option>
                    ';
                }

                echo '
                    </select>
                <p>
                ';

            }
            
            

        }

        public function custom_post_type(){
            register_post_type('property', 
            array(
                'public' => true,
                'has_archive' => true,
                'rewrite' => array('slug' => 'properties'),
                'label' => esc_html__('Property','qazproperty'),
                'supports' => array('title', 'editor', 'thumbnail')
            ));

            register_post_type('agent', 
            array(
                'public' => true,
                'has_archive' => true,
                'rewrite' => array('slug' => 'agents'),
                'label' => esc_html__('Agents','qazproperty'),
                'supports' => array('title', 'editor', 'thumbnail'),
                'show_in_rest' => true,
            ));

            $labels = array(
                'name'              => esc_html_x('Locations','taxonomy general name','qazproperty'),
                'singular_name'     => esc_html_x ('Location','taxonomy singular name','qazproperty'),
                'search_items'      => esc_html__('Search Locations','qazproperty'), 
                'all_items'         => esc_html__('All Locations','qazproperty'),
                'view_item '        => esc_html__('View Location','qazproperty'),
                'parent_item'       => esc_html__('Parent Location','qazproperty'),
                'parent_item_colon' => esc_html__('Parent Location:','qazproperty'),
                'edit_item'         => esc_html__('Edit Location','qazproperty'),
                'update_item'       => esc_html__('Update Location','qazproperty'),
                'add_new_item'      => esc_html__('Add New Location','qazproperty'),
                'new_item_name'     => esc_html__('New Location Name','qazproperty'),
                'menu_name'         => esc_html__('Location','qazproperty'),
                'back_to_items'     => esc_html__('← Back to Location','qazproperty'),
            );

            $args = array(
                'hierarchical'=> true,
                'show_ui' => true,
                'show_admin_column'=> true,
                'query_var'=> true,
                'rewrite' => array('slug'=>'properties/location'),
                'labels'=>$labels,
            );

            register_taxonomy('location', 'property', $args);

            unset($args);
            unset($labels);

            $labels = array(
                'name'              => esc_html_x('Types','taxonomy general name','qazproperty'),
                'singular_name'     => esc_html_x ('Type','taxonomy singular name','qazproperty'),
                'search_items'      => esc_html__('Search Types','qazproperty'), 
                'all_items'         => esc_html__('All Types','qazproperty'),
                'view_item '        => esc_html__('View Type','qazproperty'),
                'parent_item'       => esc_html__('Parent Type','qazproperty'),
                'parent_item_colon' => esc_html__('Parent Type:','qazproperty'),
                'edit_item'         => esc_html__('Edit Type','qazproperty'),
                'update_item'       => esc_html__('Update Type','qazproperty'),
                'add_new_item'      => esc_html__('Add New Type','qazproperty'),
                'new_item_name'     => esc_html__('New Type Name','qazproperty'),
                'menu_name'         => esc_html__('Type','qazproperty'),
                'back_to_items'     => esc_html__('← Back to Type','qazproperty'),
            );

            $args = array(
                'hierarchical'=> true,
                'show_ui' => true,
                'show_admin_column'=> true,
                'query_var'=> true,
                'rewrite' => array('slug'=>'properties/type'),
                'labels'=>$labels,
            );
            register_taxonomy('property-type', 'property', $args);
        }

        public function custom_property_columns_data($column, $post_id){
            
            $price = get_post_meta($post_id, 'qazproperty_price', true);
            $offer = get_post_meta($post_id, 'qazproperty_type', true);
            $agent_id = get_post_meta($post_id, 'qazproperty_agent', true);
            if($agent_id){
                $agent = get_the_title($agent_id);
            } else {
                $agent = 'No agent';
            }

            switch($column){
                case 'price':
                    echo esc_html($price);
                    break;
                    case 'offer':
                        echo esc_html($offer);
                        break;
                    case 'agent':
                        echo esc_html($agent);
                        break;
            }

        }

        public function custom_property_columns_sort($colunms){
            
            $columns['price'] = 'price';
            $columns['offer'] = 'offer';
            $columns['agent'] = 'agent';



            
            return $columns;
        }  

        public function custom_property_order($query){

            if(!is_admin()){
                return;
            }

            $orderby = $query->get('orderby');
            
            if('price' == $orderby){
                $query->set('meta_key','qazproperty_price');
                $query->set('orderby','meta_value_num');

            }

            if('offer' == $orderby){
                $query->set('meta_key','qazproperty_type');
                $query->set('orderby','meta_value');

            }
        }  
        

        public function custom_columns_for_property($columns){
           

            $title = $columns['title'];
            $date = $columns['date'];
            $location = $columns['taxonomy-location'];
            $type = $columns['taxonomy-property-type'];


            $columns['title'] = $title;
            $columns['date'] = $date;
            $columns['taxonomy-location'] = $location;
            $columns['taxonomy-property-type'] = $type;
            $columns['price'] = esc_html('Price', 'qazproperty');
            $columns['offer'] = esc_html('Offer', 'qazproperty');
            $columns['agent'] = esc_html('Agent', 'qazproperty');

           
           
            return $columns;
        }
         
    }

}

if(class_exists('qazProperty_Cpt')){
    $qazPropertyCpt = new qazProperty_Cpt();
    $qazPropertyCpt->register();
}  

?>
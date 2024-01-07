<?php 

    class qazProperty_Filter_Widget extends WP_Widget {
        
        function __construct() {
            parent::__construct('qazproperty_filter_widget', esc_html__('Filter', 'qazproperty'), array('description'=>esc_html__('Filter form', 'qazproperty')));

        }

        public function widget($args, $instance) {
            extract($args);
            if(isset($instance['title']) && ($instance['title']) != ''){
                $title = apply_filters('widget_title',$instance['title'] );
            } else {
                $title = apply_filters('widget_title','' );
            }

            echo $before_widget;

            if($title){
                echo $before_title. esc_html($title).$after_title;
            }

            $fields = '';

            if(isset($instance['location']) && ($instance['location']) != ''){
                $fields .= ' location="1" ';
            }

            if(isset($instance['type']) && ($instance['type']) != ''){
                $fields .= ' type="1" ';
            }

            if(isset($instance['price']) && ($instance['price']) != ''){
                $fields .= ' price="1" ';
            }

            if(isset($instance['offer']) && ($instance['offer']) != ''){
                $fields .= ' offer="1" ';
            }

            if(isset($instance['agent']) && ($instance['agent']) != ''){
                $fields .= ' agent="1" ';
            }



            echo do_shortcode('[qazproperty_filter ' .$fields.']');

            echo $after_widget;
        }

        public function form($instance) {

            if(isset($instance['title'])){
                $title = $instance['title'];
                $check_variable ='1';
            } else {
                $instance['title'] = '';
                $title ='';
                $check_variable ='2';
            }

            if(isset($instance['location'])){
                $location = $instance['location'];
            } else {
                $location ='';
            }

            if(isset($instance['price'])){
                $price = $instance['price'];
            } else {
                $price ='';
            }

            if(isset($instance['offer'])){
                $offer = $instance['offer'];
            } else {
                $offer ='';
            }

            if(isset($instance['type'])){
                $type = $instance['type'];
            } else {
                $type ='';
            }

            if(isset($instance['agent'])){
                $agent = $instance['agent'];
            } else {
                $agent ='';
            }

            
                echo 'TITLE:'.$check_variable.$title;

            ?>
            
            <p>
                <label for="<?php  echo $this->get_field_id('title');?>">Title</label>
                <input class="widefat" type="text" id="<?php  echo $this->get_field_id('title');?>" name="<?php  echo $this->get_field_name('title');?>" value="<?php echo esc_attr($title);?>">
            </p>

            <p>
                <label for=" <?php  echo $this->get_field_id('location');?>"> <?php echo esc_html__('Location', 'qazproperty'); ?> </label>
                <input type="checkbox" name="<?php  echo $this->get_field_name('location');?>"  <?php  checked($location, 'on');?> id="<?php  echo $this->get_field_id('location');?>">
            </p>

            <p>
                <label for="<?php  echo $this->get_field_id('type');?>"> <?php echo esc_html__('Type', 'qazproperty'); ?> </label>
                <input type="checkbox" name="<?php  echo $this->get_field_name('type');?>"  <?php  checked($type, 'on');?> id="<?php  echo $this->get_field_id('type');?>">
            </p>

            <p>
                <label for="<?php  echo $this->get_field_id('price');?>"> <?php echo esc_html__('Price', 'qazproperty'); ?> </label>
                <input type="checkbox" name="<?php  echo $this->get_field_name('price');?>"  <?php  checked($price, 'on');?> id="<?php  echo $this->get_field_id('price');?>">
            </p>

            <p>
                <label for="<?php  echo $this->get_field_id('offer');?>"> <?php echo esc_html__('Offer type', 'qazproperty'); ?> </label>
                <input type="checkbox" name="<?php  echo $this->get_field_name('offer');?>"  <?php  checked($offer, 'on');?> id="<?php  echo $this->get_field_id('offer');?>">
            </p>

            <p>
                <label for="<?php  echo $this->get_field_id('agent');?>"> <?php echo esc_html__('Agent', 'qazproperty'); ?> </label>
                <input type="checkbox" name="<?php  echo $this->get_field_name('agent');?>"  <?php  checked($agent, 'on');?> id="<?php  echo $this->get_field_id('agent');?>">
            </p>

         <?php   
        }        
        
        public function update($new_instance, $old_instance) {
            $instance = $old_instance;

            if(isset($instance['title']) && isset($new_instance['title'])){
                $instance['title'] = strip_tags($new_instance['tilte']);
            }
            
            if(isset($new_instance['location'])){
                $instance['location'] = strip_tags($new_instance['location']);
            }
            if(isset($new_instance['offer'])){
                $instance['offer'] = strip_tags($new_instance['offer']);
            }
            if(isset($new_instance['type']) ){
                $instance['type'] = strip_tags($new_instance['type']);
            }
            
            if(isset($new_instance['agent'])){
                $instance['agent'] = strip_tags($new_instance['agent']);
            }
            
            if(isset($new_instance['price'])){
                $instance['price'] = strip_tags($new_instance['price']);
            }
            return $instance;
        }
    }

?>
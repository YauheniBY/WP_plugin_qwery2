<?php 


class qazProperty_Shortcodes {
    public $qazProperty;
    public $agents;

    public function register(){
        add_action('init', [$this,'register_shortcode']);
    }

    public function register_shortcode(){
        add_shortcode('qazproperty_filter', [$this,'filter_shortcode' ]);
    }

    public function filter_shortcode($atts = array()){
        
        extract(shortcode_atts(array(
            'location'=>'0',
            'type'=>'0',
            'offer' => '0',
            'price' => '0',
            'agent' => '0',

        ),$atts));

        $this->qazProperty = new qazProperty();
        $this->agents = get_posts(array('post_type'=>'agent', 'numberposts'=>'-1')); 
        $agents_list = '';
        foreach($this->agents as $agent_for_shortcode){
            $agents_list .= '<option value="'. $agent_for_shortcode->ID .'">'. esc_html__($agent_for_shortcode->post_title,'qazproperty') .'</option>';
         }


        $output = '';
        $output .= '<div class="filter wrapper">';
        $output .= '<form method="post" action="'. get_post_type_archive_link('property') .'">';
        $output .= ' <input type="submit" value="'.  esc_attr__('Filter') .'" name="submit"/>';
        

        if($location){
            $output .= '<select name="qazproperty_location" id="qazproperty_location">
        <option value="">'. esc_html__('Select Location','qazproperty') .'</option>       
        '. $this->qazProperty->get_terms_hierarchical('location', '') .'</select>';
        }

        if($offer){
            $output .= '<select name="qazproperty_offer_type" id="qazproperty_offer_type">
            <option value="">'. esc_html__('Select Offers Type','qazproperty') .'</option>
            '. $this->qazProperty->get_terms_hierarchical('property-type', '') .'</select>';
        }        
       
        if($type){
            $output .= '<select name="qazproperty_type" id="qazproperty_type">
                <option value="">'. esc_html__('Types','qazproperty') .'</option>
                <option value="sale">'. esc_html__('For Rent','qazproperty') .'</option>
                <option value="rent">'. esc_html__('For Sale','qazproperty') .'</option>
                <option value="sold">'. esc_html__('Sold','qazproperty') .'</option>
            </select>';            
        }

        if($price){
            $output .= '<input type="text" value="" name="qazproperty_price" placeholder="Max price"/>';
        }       
        

        if( $agent){
            
            $output .= '<select name="qazproperty_agent" id="qazproperty_agent">
            <option value="" >'. esc_html__('Select Agent','qazproperty') .'</option>
            '. $agents_list .'</select>';
        }
    


        $output .= '</form> </div>';


        return $output;
    }
}

$qazProperty_Shortcodes = new qazProperty_Shortcodes();
$qazProperty_Shortcodes->register();
?>
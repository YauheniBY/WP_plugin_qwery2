<?php 
/*
Plugin Name: qazProperty
Plugin URI: https://geniuscourses.com/
Description: First Plugin For Practice
Version: 1.0
Author: YAUHENIBY
Author URI: https://geniuscourses.com/
Licence: GPLv2 or later
Text Domain: qazproperty
Domain Path: /lang
*/

if(!defined('ABSPATH')){
    die;
}

define('QAZPROPERTY_PATH',plugin_dir_path(__FILE__));

if(!class_exists('qazProperty_Cpt')){
    require QAZPROPERTY_PATH.'inc/class-qazproperty-custome-post-type.php';
}

if(!class_exists('Gamajo_Template_Loader')){
    require QAZPROPERTY_PATH.'inc/class-gamajo-template-loader.php';
}
require QAZPROPERTY_PATH.'inc/class-qazproperty-template-loader.php';

require QAZPROPERTY_PATH.'inc/class-qazproperty-shortcodes.php';
require QAZPROPERTY_PATH.'inc/class-qazproperty-filter-widget.php';
require QAZPROPERTY_PATH.'inc/class-qazproperty-elementor.php';
require QAZPROPERTY_PATH.'inc/class-qazproperty-bookingform.php';
require QAZPROPERTY_PATH.'inc/class-qazproperty-wishlist.php';


class qazProperty {
   

    function register(){
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_front']);
       
        add_action('plugin_loaded', [$this, 'load_text_domain']);
        
        add_action('widgets_init',[$this,'register_widget']);
        
        add_action('admin_menu',[$this,'add_menu_item']);
        add_filter('plugin_action_links_'.plugin_basename(__FILE__),[$this,'add_plugin_setting_link']);
        
        add_action('admin_init',[$this,'settings_init']);
    
    }

    public function settings_init(){
        register_setting('qazproperty_settings', 'qazproperty_settings_options');
        
        add_settings_section('qazproperty_settings_section', esc_html__('Settings','qazproperty'),[$this, 'qazproperty_settings_section_html'],'qazproperty_settings');
        
        add_settings_field('filter_title',esc_html__('Title for Filter','qazproperty'),[$this, 'filter_title_html'],'qazproperty_settings','qazproperty_settings_section');
        add_settings_field('archive_title',esc_html__('Title for Archive','qazproperty'),[$this, 'archive_title_html'],'qazproperty_settings','qazproperty_settings_section');

    
    }

    
    public function qazproperty_settings_section_html(){
        esc_html_e('Settings for qazproperty Plugin','qazproperty');
    }


    public function filter_title_html(){
        $options = get_option('qazproperty_settings_options');
    ?>    

        <input type="text" name="qazproperty_settings_options[filter_title]" value="<?php echo isset($options['filter_title']) ? $options['filter_title'] : "";  ?>"/>

    <?php
        
    }


    public function archive_title_html(){
        $options = get_option('qazproperty_settings_options');
    ?>    

        <input type="text" name="qazproperty_settings_options[archive_title]" value="<?php echo isset($options['archive_title']) ? $options['archive_title'] : "";  ?>"/>

    <?php
        
    }

    
    public function add_plugin_setting_link($link){
        $qazproperty_link = '<a href="admin.php?page=qazproperty_settings">'.esc_html__('Settings Page','qazproperty').'</a>';
        array_push($link,$qazproperty_link);
        return $link;
    }


    public function add_menu_item(){
        add_menu_page(
            esc_html__('qazPrpoperty Settings Page','qazproperty'),
            esc_html__('qazPrpoperty','qazproperty'),
            'manage_options',
            'qazproperty_settings',
            [$this, 'main_admin_page'],
            'dashicons-welcome-view-site',
            150,
        );
    }



    public function main_admin_page(){
        require_once QAZPROPERTY_PATH.'admin/welcome.php'; 
    }



    public function register_widget(){
        register_widget('qazproperty_filter_widget');
    }

    public function get_terms_hierarchical($tax_name,$current_term = ''){
        $taxonomy_terms = get_terms($tax_name, ['hide_empty'=>false, 'parent'=>0]);
      
        $html = '';
        if(!empty($taxonomy_terms)){
            foreach($taxonomy_terms as $term){
                if($current_term == $term->term_id){
                    $html .= '<option value="'.$term->term_id.'" selected>'.$term->name.'</option>';
                } else {
                    $html .= '<option value="'.$term->term_id.'">'.$term->name.'</option>';
                }
                $child_terms = get_terms($tax_name, ['hide_empty'=>false, 'parent'=>$term->term_id]);
               

                foreach($child_terms as $child_term){
                    if($current_term == $child_term->term_id){
                        $html .= '<option value="'.$child_term->term_id.'" selected> - '.$child_term->name.'</option>';                 
                    } else {
                        $html .= '<option value="'.$child_term->term_id.'"> - '.$child_term->name.'</option>';
                    }
                }
            }
        }
        return $html;
    }

    function load_text_domain(){
        load_plugin_textdomain('qazproperty', false, dirname(plugin_basename(__FILE__)).'/lang');
    }

    public function enqueue_admin(){
        wp_enqueue_style('qazProperty_stytle_admin',plugins_url('/assets/css/admin/style.css',__FILE__));
        wp_enqueue_script('qazProperty_sscript_admin',plugins_url('/assets/js/admin/scripts.js',__FILE__), array('jquery'),'1.0',true);
    }

    public function enqueue_front(){
        wp_enqueue_style('qazProperty_stytle',plugins_url('/assets/css/front/style.css',__FILE__));
        wp_enqueue_script('qazProperty_script',plugins_url('/assets/js/front/scripts.js',__FILE__), array('jquery'),'1.0',true);
        wp_enqueue_script('jquery-form');
        // wp_enqueue_script('qazProperty_sscript',plugins_url('/assets/js/front/scripts.js',__FILE__), array('jquery'),'1.0',true);
    }
  

    static function activation(){
        flush_rewrite_rules();
    }

    static function deactivation(){
        flush_rewrite_rules();
    }
}


if(class_exists('qazProperty')){
    $qazProperty = new qazProperty();
    $qazProperty->register();
    register_activation_hook(__FILE__, array($qazProperty, 'activation'));
    register_deactivation_hook(__FILE__, array($qazProperty, 'deactivation'));
  
}  


?>
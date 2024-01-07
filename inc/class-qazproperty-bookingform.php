<?php 

class qazProperty_Bookingform {
    public function __construct(){
        add_action('wp_enqueue_scripts',[$this,'enqueue' ]);
        add_action('init', [$this, 'qazproperty_booking_shortcode'] );
        add_action('wp_ajax_booking_form', [$this, 'booking_form'] );
        add_action('wp_ajax__nopriv_booking_form', [$this, 'booking_form'] );
        
    
    }

    public function enqueue (){
        wp_enqueue_script('qazproperty_bookingform', plugins_url('qazproperty/assets/js/front/bookingform.js'),array('jquery'), '1.0', true);
        wp_localize_script('qazproperty_bookingform','qazproperty_bookingform_var',array(
            'ajaxurl'=> admin_url('admin-ajax.php'),
            'nonce'=>wp_create_nonce('_wpnonce'),
            'title'=> esc_html__('Booking Form','qazproperty'),

        ));
    }

    public function qazproperty_booking_shortcode (){
        add_shortcode('qazproperty_booking',[$this,'booking_form_html']);
    }

    public function booking_form_html ($atts, $content){
        extract(shortcode_atts(array(
            'location'=>'',
            'type'=>'',
            'offer' => '',
            'price' => '',
            'agent' => '',

        ),$atts));
        
        
        echo'<div id="qazproperty_result"></div>
        
        <form method="post">
        <p>
            <input  type="text" name="name" id="qazproperty_name" />
        </p>
        <p>
            <input  type="text" name="email" id="qazproperty_email"/>
        </p>
        <p>
            <input  type="text" name="phone" id="qazproperty_phone"/>
        </p>';
        if($price != ''){
            echo '
                <p>
                    <input  type="hidden" name="price" id="qazproperty_bookingform_price" value="'.esc_html($price).'"/>
                </p>
                    ';
        }
        if($location != ''){
            echo '
                <p>
                    <input  type="hidden" name="location" id="qazproperty_bookingform_location" value="'.esc_html($location).'"/>
                </p>
                    ';
        }
        if($agent != ''){
            echo '
                <p>
                    <input  type="hidden" name="location" id="qazproperty_bookingform_agent" value="'.esc_html($agent).'"/>
                </p>
                    ';
        }
        echo '<p>
            <input  type="submit" name="submit" id="qazproperty_bookingform_submit" />
        </p>
        </form>  
        ';
    }

    function booking_form (){

       check_ajax_referer('_wpnonce', 'nonce');
       if(!empty($_POST)){
        print_r($_POST);

            if(!empty($_POST['name'])){
                $name = sanitize_text_field($_POST['name']);
            } else {
                $name = '';
            }

            if(!empty($_POST['email'])){
                $email = sanitize_text_field($_POST['email']);
            } else {
                $email = '';
            }
            if(!empty($_POST['phone'])){
                $phone = sanitize_text_field($_POST['phone']);
            } else {
                $phone = '';
            }

            if(!empty($_POST['price'])){
                $bookingform_price = sanitize_text_field($_POST['price']);
            } else {
                $bookingform_price = '';
            }
            if(!empty($_POST['location'])){
                $bookingform_location = sanitize_text_field($_POST['location']);
            } else {
                $bookingform_location = '';
            }
            if(!empty($_POST['agent'])){
                $bookingform_agent = sanitize_text_field($_POST['agent']);
            } else {
                $bookingform_agent = '';
            }

            //email admin

            $data_message = 'Data_message - ';
            $data_message .= 'Name: '.esc_html($name).'<br>';
            $data_message .= 'Email: '.esc_html($email).'<br>';
            $data_message .= 'Phone: '.esc_html($phone).'<br>';
            $data_message .= 'Price: '.esc_html($bookingform_price).'<br>';
            $data_message .= 'Location: '.esc_html($bookingform_location).'<br>';
            $data_message .= 'Agent: '.esc_html($bookingform_agent).'<br>';
            echo  $data_message;
            $result_admin = wp_mail(get_option('admin_email'), ('New reservation'), $data_message);
           
            if($result_admin){
                echo 'All right';
            } else {
                echo 'DIDNT SENT';
            }
            //email client

            $message =  esc_html__('Thank you for your reservation. We will contact you soon!', 'qazproperty');

            wp_mail($email, esc_html__('Booking', 'qazproperty'), $message);
       } else {
        echo ('smth wrong');
       }
        wp_die();
    }
}

$booking_form = new qazProperty_Bookingform();

?>
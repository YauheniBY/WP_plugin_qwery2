<?php 
get_header();
?>
<div class="filter wrapper"><?php
    $qazProperty_Template->get_template_part('partials/filter');
 ?>
</div>
<div class="wrapper archive_property">
    <?php
    if(!empty($_POST['submit'])){


        $args = array(
            'post_type' => 'property',
            'post_per_page' => -1,
            'meta_query' => array( 'relation' => 'AND'),
            'tax_query' => array( 'relation' => 'AND'),

        );

        if(isset($_POST['qazproperty_type']) && ($_POST['qazproperty_type'] != '')){
            
            array_push($args['meta_query'], array(
            'key' => 'qazproperty_type',
            'value' => esc_attr($_POST['qazproperty_type']),
            ));
        }
        if(isset($_POST['qazproperty_location']) && ($_POST['qazproperty_location'] != '')){
            
            array_push($args['tax_query'], array(
            'taxonomy' => 'location',
            'terms' => esc_attr($_POST['qazproperty_location']),
            ));
        }
        if(isset($_POST['qazproperty_offer_type']) && ($_POST['qazproperty_offer_type'] != '')){
            
            array_push($args['tax_query'], array(
            'taxonomy' => 'property-type',
            'terms' => esc_attr($_POST['qazproperty_offer_type']),
            ));
        }
        

        if(isset($_POST['qazproperty_price']) && ($_POST['qazproperty_price'] != '')){
            
            array_push($args['meta_query'], array(
            'key' => 'qazproperty_price',
            'value' => esc_attr($_POST['qazproperty_price']),
            'type' => 'numeric',
            'compare' => '<=',
            ));
        }

        if(isset($_POST['qazproperty_agent']) && ($_POST['qazproperty_agent'] != '')){
            
            array_push($args['meta_query'], array(
            'key' => 'qazproperty_agent',
            'value' => esc_attr($_POST['qazproperty_agent']),
            ));
        }

        $properties = new WP_Query($args);
        if( $properties->have_posts()){

            while ( $properties->have_posts() ) {
                $properties->the_post(); 
                $qazProperty_Template->get_template_part('partials/content'); 
            }  
        } else {
            echo '<p>'.esc_html__('No properties!', 'qazproperty').'</p>';
        }

    } else {
        if ( have_posts() ) {

            // Load posts loop.
            while ( have_posts() ) {
                the_post(); 
                $qazProperty_Template->get_template_part('partials/content');
            }
        
            // Previous/next page navigation.
            twenty_twenty_one_the_posts_navigation();
            // post_nav_link();
        
        } else {
            echo '<p>'.esc_html__('No properties!', 'qazproperty').'</p>';
        }

    }
    
   
    
    
?>


</div>
<?php
get_footer();
?>
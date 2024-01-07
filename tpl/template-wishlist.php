<?php
/**
 * Template Name: Template Wishlist
 */ 

get_header();


?>
    <div class="wrapper archive_property">

        <?php  

        if ( have_posts() ) {

            // Load posts loop.
            while ( have_posts() ) {
                the_post(); 
                ?> <div class="wishlist_content"> <?php
                    the_content();
                ?> </div> <?php
                // $qazProperty_Template->get_template_part('partials/content');
            }
        
        } else {
        echo '<p>'.esc_html__('No properties!', 'qazproperty').'</p>';
        }

        if(is_user_logged_in()){
            $user_id = get_current_user_id();
            $wishlist_items = get_user_meta($user_id, 'qazproperty_wishlist_properties');
            
            if(count($wishlist_items)>0){
                $args = array(
                    'post_type' => 'property',
                    'posts_per_page' => -1,
                    'post__in' => $wishlist_items,
                    'orderby' => 'post__in',
                );
                
                $properties = new WP_Query($args);
    
    
                if ( $properties->have_posts() ) {
    
                    // Load posts loop.
                    while ( $properties->have_posts() ) {
                        $properties->the_post();
    
                        $qazProperty_Template->get_template_part('partials/content');
                    }
                
                }

            } else {
                esc_html_e('No property in your Wishlist');
            }
            
        }
        ?>
    </div>
<?php
get_footer();
?>
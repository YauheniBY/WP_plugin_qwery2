<?php 
get_header();
?>
<div class="wrapper single_property">
    <?php 
    if ( have_posts() ) {

        // Load posts loop.
        while ( have_posts() ) {
            the_post(); 
    ?>
           
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <?php if(get_the_post_thumbnail(get_the_ID(),'large')){
            echo get_the_post_thumbnail(get_the_ID(),'large');
        }

        //Price

        $price = esc_html__(get_post_meta(get_the_ID(), 'qazproperty_price',true));
        
        //Agents
        $agent = get_post_meta(get_the_ID(), 'qazproperty_agent',true);
        if($agent != ''){
            $agent_name  = get_post($agent)->post_title;
        } else {
            $agent_name = '';
        }
        
        //Locations
        $qaz_location = '';
        $locations = get_the_terms(get_the_ID(), 'location');
        foreach($locations as $location){
            $qaz_location .= ' '.esc_html__($location->name);
        }

        do_shortcode('[qazproperty_booking location="'.esc_html($qaz_location).'" price="'.esc_html($price).'" agent="'.esc_html($agent_name).'" ]');

        ?>
        <h2><?php the_title(); ?></h2>   
        <div class="description"> <?php the_content(); ?></div>
        <div class="property_info">
            <span class="location"><?php esc_html_e('Location', 'qazproperty');
               
                    echo esc_html($qaz_location);
            ?>
            </span>
            <span class="type"><?php esc_html_e('Type', 'qazproperty');
                $types = get_the_terms(get_the_ID(), 'property-type');                
                if($types != 0){
                    foreach($types as $type){
                        echo ' '.esc_html__($type->name);
                    }
                } else {
                    echo ' '.esc_html__('-');
                }
             ?>
             </span>
            <span class="price"><?php esc_html_e('Price', 'qazproperty'); echo': '.esc_html($price).' $ / '.esc_html__(get_post_meta(get_the_ID(), 'qazproperty_period',true)); ?></span>
            <span class="offer"><?php esc_html_e('Offer', 'qazproperty'); echo': '.esc_html__(get_post_meta(get_the_ID(), 'qazproperty_type',true)); ?></span>
            <span class="agent"><?php esc_html_e('Agent', 'qazproperty');
             
            if($agent != ''){  
                $agent_name  = get_post($agent)->post_title;             
                echo': '.esc_html__($agent_name);
            } else {
                echo': -';
            }
                
            ?>
            </span>
        </div>
        </article>
     <?php
        }
    
    }
    
    
     ?>


</div>
<?php
get_footer();
?>
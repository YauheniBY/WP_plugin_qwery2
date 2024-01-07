<?php
/**
 * Template Name: List Personal Properties
 */ 
get_header();

$edit_property = 'http://localhost/qwery2/add-property/';
?>


 <div class="wrapper">

 <?php 

    if ( have_posts() ) {

        // Load posts loop.
        while ( have_posts() ) {
            the_post(); 
    ?>
        
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>           
            <h2><?php the_title(); ?></h2>   
            <div class="description"> <?php the_content(); ?></div>            
            </article>
    <?php 
        } 
    }
    ?>

    <div class="list">
        <?php 
            if(is_user_logged_in()){
                
            global $current_user; 
            wp_get_current_user();


                $args =array(
                    'post_type' => 'property',
                    'post_status' => array('publish', 'pending', 'draft', 'future'),
                    'post_per_page' => -1,
                    'post_author' => $current_user->ID,

                );

                $listing = new WP_Query($args);
                if($listing->have_posts()) : 
                    while($listing->have_posts()) :
                        $listing->the_post();

                        if($edit_property){
                            $new_edit_property = add_query_arg('edit',$post->ID, $edit_property);
                        }
                        
                ?>
                    <div class="property">
                        <h3><?php echo the_title(); ?></h3>
                        <a href="<?php echo the_permalink(); ?>">Read more</a>
                        <a href="<?php echo $new_edit_property; ?>">Edit Property</a>
                    </div>
                <?php
                    endwhile;
                endif;
            }
        ?>
    </div>
     
 </div>
<?php
get_footer();

 ?>


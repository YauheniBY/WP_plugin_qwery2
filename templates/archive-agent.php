<?php 
get_header();
?>
<div class="wrapper archive_property archive_agent">
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
        ?>
        <h2><?php the_title(); ?></h2>   
        <div class="description"> <?php the_excerpt(); ?></div>
        <a href="<?php the_permalink(); ?>"><?php esc_html_e('Open this agent', 'qazproperty'); ?></a>
        </article>
     <?php
        }
    
        // Previous/next page navigation.
        twenty_twenty_one_the_posts_navigation();
        // post_nav_link();
    
    } else {
        echo '<p>'.esc_html__('No agents!', 'qazproperty').'</p>';
    
    }
    
    
     ?>


</div>
<?php
get_footer();
?>
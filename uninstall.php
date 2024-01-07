<?php 
    $proprties = get_posts(array('post_type'=>['property','agent'], 'numberposts'=>-1));
foreach($proprties as $property){
    wp_delete_post($property->ID, true);
}
?>
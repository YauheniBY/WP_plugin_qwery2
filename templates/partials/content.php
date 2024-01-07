<?php ?>
 <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
 <?php if(get_the_post_thumbnail(get_the_ID(),'large')){
     echo get_the_post_thumbnail(get_the_ID(),'large');
 }
 ?>
 <h2><?php the_title(); ?></h2>   
 <div class="description"> <?php the_excerpt(); ?></div>
 <div class="property_info">
    <?php 
        $locations = get_the_terms(get_the_ID(), 'location');
        if(!empty($locations)){
    ?>
        <span class="location"><?php esc_html_e('Location', 'qazproperty');
            
            
            foreach($locations as $location){
                echo ' '.esc_html__($location->name,  'qazproperty');
            }
        ?>
        </span>
        <?php   
        }
     ?>
     <span class="type"><?php esc_html_e('Type', 'qazproperty');
         $types = get_the_terms(get_the_ID(), 'property-type');                
         if($types != 0){
             foreach($types as $type){
                 echo ' '.esc_html__($type->name,  'qazproperty');
             }
         } else {
             echo ' -';
         }
      ?>
      </span>
     <span class="price"><?php esc_html_e('Price', 'qazproperty'); echo': '.esc_html__(get_post_meta(get_the_ID(), 'qazproperty_price',true));echo' $ / '.esc_html__(get_post_meta(get_the_ID(), 'qazproperty_period',true)); ?></span>
     <span class="offer"><?php esc_html_e('Offer', 'qazproperty'); echo': '.esc_html__(get_post_meta(get_the_ID(), 'qazproperty_type',true)); ?></span>
     <span class="agent"><?php esc_html_e('Agent', 'qazproperty');
     $agent = get_post_meta(get_the_ID(), 'qazproperty_agent',true);
     if($agent != ''){
         $agent_name  = get_post($agent)->post_title;
         echo': '.esc_html__($agent_name,  'qazproperty');
     } else {
         echo': -';
     }
         
     ?>
     </span>
 </div>
 <a href="<?php the_permalink(); ?>"><?php esc_html_e('Open the property', 'qazproperty'); ?></a><br>
 <?php 
    if(is_user_logged_in()){ 
        $property_id = get_the_ID();
        $user_id = get_current_user_id();
        $wishlist = new qazProperty_Wishlist();
        if($wishlist->qazproperty_in_wishlist($user_id,$property_id)){
            if(is_page_template('tpl/template-wishlist.php')){ ?>
                <a href="<?php echo admin_url('admin-ajax.php'); ?>" class="qazproperty_remove_from_wishlist" data-user-id="<?php echo $user_id; ?>" data-property-id="<?php echo $property_id; ?>"><?php esc_html_e('Remove from Wishlist', 'qazproperty'); ?></a>
            <?php
            } else{
                esc_html_e('Already Added', 'qazprperty');
            }
            
        } else { ?>

            <form action="<?php echo admin_url('admin-ajax.php'); ?>" method="post" id="qazproperty_add_to_wishlist_form_<?php echo $property_id; ?>" >
            <input type="hidden"  name="qaz_user_id"  value="<?php echo esc_attr($user_id); ?>"  />
            <input type="hidden"  name="qaz_property_id"  value="<?php echo esc_attr($property_id); ?>"  />
            <input type="hidden"  name="action"  value="qazproperty_add_wishlist"  />

            </form>
            <a href="#" data-property-id="<?php echo $property_id; ?>" class="qazproperty_add_to_wishlist"><?php esc_html_e('Add to Wishlist', 'qazproperty'); ?></a>
            <span class="successfull_added" style="display:none;">Already added to wishList</span>
        
        <?php  }        
   }
  ?>
 </article>

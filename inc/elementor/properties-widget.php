<?php

class Elementor_Properties_Widget extends \Elementor\Widget_Base {

	protected $qazProperty_Template;
	protected $qazLocations = array(''=>'Select Something');
	public function get_name() {
		return 'qazproperties';
	}

	public function get_title() {
		return esc_html__( 'Properties list', 'qazproperty' );
	}

	public function get_icon() {
		return 'eicon-code';
	}

	// public function get_custom_help_url() {
	// 	return 'https://developers.elementor.com/docs/widgets/';
	// }

	public function get_categories() {
		return [ 'qazproperty' ];
	}

	// public function get_keywords() {
	// 	return [ 'oembed', 'url', 'link' ];
	// }
    
	protected function register_controls() {

		$temp_locations = get_terms('location');
		foreach($temp_locations as $location){
			$this -> qazLocations[$location->term_id] = $location->name;
		}

		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', 'qazproperty' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'count',
			[
				'label' => esc_html__( 'Posts count', 'qazproperty' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 3,
            ]
		);
		$this->add_control(
			'offer',
			[
				'label' => esc_html__( 'Offer', 'qazproperty' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					'' => esc_html__( '-', 'qazproperty' ),
					'sale' => esc_html__( 'For Sale', 'qazproperty' ),
					'rent'  => esc_html__( 'For Rent', 'qazproperty' ),
					'sold' => esc_html__( 'Sold', 'qazproperty' ),
				],
			]
		);

		$this->add_control(
			'location',
			[
				'label' => esc_html__( 'Location', 'qazproperty' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options'=> $this->qazLocations,
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
        $settings = $this->get_settings_for_display();
		$args  = array(
            'post_type'=>'property',
            'posts_per_page'=> $settings['count'],
			'meta_query' => array( 'relation' => 'AND'),
            'tax_query' => array( 'relation' => 'AND'),
        );

		if(isset($settings['offer']) && $settings['offer'] != ''){
			array_push($args['meta_query'], array(
				'key' => 'qazproperty_type',
				'value' => esc_attr($settings['offer']),
				));

		}

		if(isset($settings['location']) && ($settings['location'] != '')){
            
            array_push($args['tax_query'], array(
            'taxonomy' => 'location',
            'terms' => esc_attr($settings['location']),
            ));
        }


        $properties = new WP_Query($args);
        $this->qazProperty_Template = new qazProperty_Template_Loader();

		
        if ( $properties->have_posts() ) {

            echo '<div class="wrapper archive_property">';

            while ( $properties->have_posts() ) {
                $properties->the_post(); 
                $this->qazProperty_Template->get_template_part('partials/content');
            }        
            echo '</div>';
		}
		wp_reset_postdata();

    }

}

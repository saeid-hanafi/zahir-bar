<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Group_Control_Image_Size;
use \Elementor\Group_Control_Typography;
use \Elementor\Group_Control_Css_Filter;
use \Elementor\Scheme_Typography;
use \Elementor\Widget_Base;
use \Elementor\Repeater;

class WBC_Testimonial_Carousel extends Widget_Base {

	public function get_name() {
        return 'wbc_testimonial_carousel';
    }
    
    public function get_title() {
        return esc_html__( 'Testimonail Carousel', 'wbc907-core' );
    }

    public function get_icon() {
        return 'eicon-slides wbc-icon-widget';
    }
    
    public function get_categories() {
        return [ 'wbc-ninezeroseven' ];
    }

    public function get_keywords() {
		return [ '907', 'ninezeroseven','logos','client','carousel','testimonial'];
	}



	public function get_portfolio_categories(){
		$results = array();
		$terms = get_terms( 'portfolio-categories' );

		if($terms && count($terms) > 0){
			foreach ($terms as $key => $value) {
				$results[$value->name] = $value->name;
			}
		}

		return $results;
	}


	protected function get_repeater_defaults() {
		$placeholder_image_src = \Elementor\Utils::get_placeholder_image_src();

		return [
			[
				'name' => __( 'Bill |Freddricks|', 'elementor-pro' ),
				'position' => __( 'Customer', 'elementor-pro' ),
				'testimonail_content' => '“Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.”',
				'logo_image' => [
					'url' => $placeholder_image_src,
				],
				'link' => [
					'url' => 'https://themeforest.net/item/907-responsive-multipurpose-wordpress-theme/4087140'
				]
			],
			[
				'name' => __( 'Mark |Sampson|', 'elementor-pro' ),
				'position' => __( 'Customer', 'elementor-pro' ),
				'testimonail_content' => '“Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.”',
				'logo_image' => [
					'url' => $placeholder_image_src,
				],
				'link' => [
					'url' => 'https://themeforest.net/item/907-responsive-multipurpose-wordpress-theme/4087140'
				]
			],
			[
				'name' => __( 'John |Wright|', 'elementor-pro' ),
				'position' => __( 'Customer', 'elementor-pro' ),
				'testimonail_content' => '“Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.”',
				'logo_image' => [
					'url' => $placeholder_image_src,
				],
				'link' => [
					'url' => 'https://themeforest.net/item/907-responsive-multipurpose-wordpress-theme/4087140'
				]
			],
		];
	}

	protected function _register_controls() {
        // Start Image        
			$this->start_controls_section( 
				'logos_section',
	            [
	                'label' => esc_html__( 'Logos', 'wbc907-core'),
	            ]
	        );

	       

		$repeater = new Repeater();

		$repeater->add_control(
			'name',
			[
				'label' => __( 'Name', 'elementor-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Bill Watkins', 'elementor-pro' ),
			]
		);

		$repeater->add_control(
			'position',
			[
				'label' => __( 'Position', 'elementor-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Bill Watkins', 'elementor-pro' ),
			]
		);

		$repeater->add_control(
			'testimonial_content',
			[
				'label' => esc_html__( 'Content', 'elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'rows' => '10',
				'default' => esc_html__( '“Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.”', 'elementor' ),
			]
		);

		$repeater->add_control(
			'image',
			[
				'label' => __( 'Image', 'elementor' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);
			


		$this->add_control(
			'testimonials',
			[
				'label' => __( 'Testimonials', 'elementor-pro' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ name }}}',
				'default' => $this->get_repeater_defaults(),
				'separator' => 'after',
			]
		);
		
		$this->end_controls_section();
        // /.End Image
     
			$this->start_controls_section( 
				'carousel_settings',
	            [
	                'label' => esc_html__('Settings', 'wbc907-core'),
	            ]
	        );


	        $this->add_control(
				'item_speed',
				[
					'label' => __( 'Speed', 'elementor' ),
					'type' => Controls_Manager::TEXT,
					'default' => '',
					'title' => 'Changes width of items',
					'description'=>'Changes the speed/duration between entires.',
					'placeholder' => 'Use numbers only i.e 8000',
					'label_block' => true,
				]
			);

			$this->add_control(
			'item_height',
				[
					'label' => __( 'Make Same Height?', 'wbc907-core' ),
					'description' =>'Enable this will make all items same height',
					'type' => Controls_Manager::SWITCHER,
					'default' => '',
					'label_off' => __( 'No', 'wbc907-core' ),
					'label_on' => __( 'Yes', 'wbc907-core' ),
				]
			);

			


			$this->end_controls_section();

		

	}

	protected function render($instance = []) {
		$atts = $this->get_settings_for_display();
		
		include WBC_INCLUDES_DIRECTORY.'elementor/includes/templates/testimonial-carousel/index.php';
			
	}

	protected function content_template() {}	

}
	\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \WBC_Testimonial_Carousel() );
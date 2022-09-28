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

class WBC_Logo_Carousel extends Widget_Base {

	public function get_name() {
        return 'wbc_logo_carousel';
    }
    
    public function get_title() {
        return esc_html__( 'Logos Carousel', 'wbc907-core' );
    }

    public function get_icon() {
        return 'eicon-slides wbc-icon-widget';
    }
    
    public function get_categories() {
        return [ 'wbc-ninezeroseven' ];
    }

    public function get_keywords() {
		return [ '907', 'ninezeroseven','logos','client','carousel'];
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
				'name' => __( 'Company Name #1', 'elementor-pro' ),
				'logo_image' => [
					'url' => $placeholder_image_src,
				],
				'link' => [
					'url' => 'https://themeforest.net/item/907-responsive-multipurpose-wordpress-theme/4087140'
				]
			],
			[
				'name' => __( 'Company Name #2', 'elementor-pro' ),
				'logo_image' => [
					'url' => $placeholder_image_src,
				],
				'link' => [
					'url' => 'https://themeforest.net/item/907-responsive-multipurpose-wordpress-theme/4087140'
				]
			],
			[
				'name' => __( 'Company Name #3', 'elementor-pro' ),
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
				'label' => __( 'Title', 'elementor-pro' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'CEO', 'elementor-pro' ),
			]
		);

		$repeater->add_control(
			'link',
			[
				'label' => __( 'Link', 'elementor' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => __( 'https://your-link.com', 'elementor' ),
			]
		);

		$repeater->add_control(
			'logo_image',
			[
				'label' => __( 'Choose Image', 'elementor' ),
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
			'logos',
			[
				'label' => __( 'Slides', 'elementor-pro' ),
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
				'item_width',
				[
					'label' => __( 'Items Width', 'elementor' ),
					'type' => Controls_Manager::TEXT,
					'default' => '',
					'title' => 'Changes width of items',
					'placeholder' => 'Enter width i.e 400',
					'label_block' => true,
				]
			);

			$this->add_control(
				'item_scroll',
				[
					'label' => __( 'Scroll Amount', 'elementor' ),
					'type' => Controls_Manager::TEXT,
					'default' => '',
					'title' => 'How many to scroll when animating',
					'placeholder' => 'i.e 2',
					'label_block' => true,
				]
			);

			$this->add_control(
				'item_min',
				[
					'label' => __( 'Min Items Shown', 'elementor' ),
					'type' => Controls_Manager::TEXT,
					'default' => '',
					'title' => 'Min amount of items to be shown',
					'placeholder' => 'i.e 5',
					'label_block' => true,
				]
			);

			$this->add_control(
				'item_max',
				[
					'label' => __( 'Max Items Shown', 'elementor' ),
					'type' => Controls_Manager::TEXT,
					'default' => '',
					'title' => 'Min amount of items to be shown',
					'placeholder' => 'i.e 8',
					'label_block' => true,
				]
			);

			


			$this->end_controls_section();


	    // Start Image Style
			$this->start_controls_section( 
				'section_style_image',
	            [
	                'label' => esc_html__('Image', 'wbc907-core'),
	                'tab' => Controls_Manager::TAB_STYLE,
	            ]
	        );

			$this->start_controls_tabs( 'thumbnail_effects_tabs' );

		$this->start_controls_tab( 'normal',
			[
				'label' => __( 'Normal', 'elementor-pro' ),
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'thumbnail_filters',
				'selector' => '{{WRAPPER}} .wbc-logo img',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'hover',
			[
				'label' => __( 'Hover', 'elementor-pro' ),
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'thumbnail_hover_filters',
				'selector' => '{{WRAPPER}} .wbc-logo:hover img',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		

	}

	protected function render($instance = []) {
		$atts = $this->get_settings_for_display();
		
		include WBC_INCLUDES_DIRECTORY.'elementor/includes/templates/logo-carousel/index.php';
			
	}

	protected function content_template() {}	

}
	\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \WBC_Logo_Carousel() );
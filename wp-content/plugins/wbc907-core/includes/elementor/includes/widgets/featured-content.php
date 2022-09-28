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

class WBC_Featured_Content extends Widget_Base {

	public function get_name() {
        return 'wbc_featured_content';
    }
    
    public function get_title() {
        return esc_html__( 'Featured Content', 'wbc907-core' );
    }

    public function get_icon() {
        return 'eicon-featured-image wbc-icon-widget';
    }
    
    public function get_categories() {
        return [ 'wbc-ninezeroseven' ];
    }

    public function get_keywords() {
		return [ '907', 'image','featured image','featured', 'single'];
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
				'featured_content_section',
	            [
	                'label' => esc_html__( 'Image', 'wbc907-core'),
	            ]
	        );


	        $this->add_group_control(
				Group_Control_Image_Size::get_type(),
				[
					'name' => "img",
					'exclude' => [ 'custom' ],
					'default' => 'large',
				]
			);

			$this->end_controls_section();

			//Image Style
			$this->start_controls_section( 
				'section_style_image',
	            [
	                'label' => esc_html__('Image', 'wbc907-core'),
	                'tab' => Controls_Manager::TAB_STYLE,
	            ]
	        );


	        $this->add_control(
				'image_overlay_color',
				[
					'label' => esc_html__( 'Overlay Color', 'wbc907-core' ),
					'type' => Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .wbc-image-wrap .item-link-overlay' => 'background-color: {{VALUE}}',
					],
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
					'selector' => '{{WRAPPER}} .post-featured img',
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
					'selector' => '{{WRAPPER}} .post-featured:hover img',
				]
			);

			$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->end_controls_section();
	}

	protected function render($instance = []) {
		$atts = $this->get_settings_for_display();
		
		include WBC_INCLUDES_DIRECTORY.'elementor/includes/templates/featured-content/index.php';
			
	}

	protected function content_template() {}	

}
	\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \WBC_Featured_Content() );
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

class WBC_Animated_Chart extends Widget_Base {

	public function get_name() {
        return 'wbc_chart';
    }
    
    public function get_title() {
        return esc_html__( 'Animated Chart', 'wbc907-core' );
    }

    public function get_icon() {
        return 'eicon-skill-bar wbc-icon-widget';
    }
    
    public function get_categories() {
        return [ 'wbc-ninezeroseven' ];
    }

    public function get_keywords() {
		return [ '907', 'ninezeroseven','chart','progress'];
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
				'name' => __( 'Company Name #1', 'wbc907-core' ),
				'logo_image' => [
					'url' => $placeholder_image_src,
				],
				'link' => [
					'url' => 'https://themeforest.net/item/907-responsive-multipurpose-wordpress-theme/4087140'
				]
			],
			[
				'name' => __( 'Company Name #2', 'wbc907-core' ),
				'logo_image' => [
					'url' => $placeholder_image_src,
				],
				'link' => [
					'url' => 'https://themeforest.net/item/907-responsive-multipurpose-wordpress-theme/4087140'
				]
			],
			[
				'name' => __( 'Company Name #3', 'wbc907-core' ),
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
				'chart_section',
	            [
	                'label' => esc_html__( 'Chart', 'wbc907-core'),
	            ]
	        );


	        $this->add_control(
				'percent',
				[
					'label' => __( 'Value', 'wbc907-core' ),
					'type' => Controls_Manager::TEXT,
					'default' => '',
					'title' => 'Changes width of items',
					'placeholder' => 'Enter width i.e 86',
					'label_block' => true,
				]
			);

			$this->add_control(
				'ending',
				[
					'label' => __( 'Ending Character', 'wbc907-core' ),
					'type' => Controls_Manager::TEXT,
					'default' => '',
					'title' => 'Changes width of items',
					'placeholder' => 'Optional ending character',
					'label_block' => true,
				]
			);

			$this->end_controls_section();


	    // Start Image Style
		$this->start_controls_section( 
			'chart_style',
            [
                'label' => esc_html__('Styling', 'wbc907-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

		$this->add_control(
			'size',
			[
				'label' => __( 'Chart Size', 'wbc907-core' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'title' => 'Changes width of progress line',
				'placeholder' => 'Enter width i.e 150',
				'label_block' => true,
			]
		);

        $this->add_control(
			'line_width',
			[
				'label' => __( 'Line Width', 'wbc907-core' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'title' => 'Changes width of progress line',
				'placeholder' => 'Enter width i.e 7',
				'label_block' => true,
			]
		);

		$this->add_control(
			'track_color',
			[
				'label' => esc_html__( 'Track Color', 'wbc907-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
			]
		);

		$this->add_control(
			'bar_color',
			[
				'label' => esc_html__( 'Bar Color', 'wbc907-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
			]
		);

		$this->add_control(
			'backing_size',
			[
				'label' => __( 'Backing Size', 'wbc907-core' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'title' => 'Changes width of progress line',
				'placeholder' => 'Enter width i.e 150',
				'label_block' => true,
			]
		);
		$this->add_control(
			'backing_color',
			[
				'label' => esc_html__( 'Backing Color', 'wbc907-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
						'{{WRAPPER}} .wbc-pie-chart .percent-backing' => 'background-color: {{VALUE}}',
					]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'chart_font',
				'label' => esc_html__( 'Typography', 'wbc907-core' ),
				'selector' => '{{WRAPPER}} .wbc-pie-chart',
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => esc_html__( 'Text Color', 'wbc907-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
						'{{WRAPPER}} .wbc-pie-chart' => 'color: {{VALUE}}',
					]
			]
		);

		$this->end_controls_section();

		

	}

	protected function render($instance = []) {
		$atts = $this->get_settings_for_display();
		
		include WBC_INCLUDES_DIRECTORY.'elementor/includes/templates/animated-chart/index.php';
			
	}

	protected function content_template() {}	

}
	\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \WBC_Animated_Chart() );
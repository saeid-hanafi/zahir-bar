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

class WBC_Progress_Bar extends Widget_Base {

	public function get_name() {
        return 'wbc_progress_bar';
    }
    
    public function get_title() {
        return esc_html__( '907 Progress Bar', 'wbc907-core' );
    }

    public function get_icon() {
        return 'eicon-skill-bar wbc-icon-widget';
    }
    
    public function get_categories() {
        return [ 'wbc-ninezeroseven' ];
    }

    public function get_keywords() {
		return [ '907', 'ninezeroseven','chart','progress','bar'];
	}


	protected function _register_controls() {      
			$this->start_controls_section( 
				'bar_section',
	            [
	                'label' => esc_html__( 'Progress Bar', 'wbc907-core'),
	            ]
	        );


			$this->add_control(
				'title',
				[
					'label' => __( 'Title', 'wbc907-core' ),
					'type' => Controls_Manager::TEXT,
					'default' => 'Your Skill',
					'title' => 'Title to appear above progress bar',
					'placeholder' => 'Webdesign, skills, etc',
					'label_block' => true,
				]
			);

			$this->add_control(
				'percent',
				[
					'label' => esc_html__( 'Percentage', 'elementor' ),
					'type' => Controls_Manager::SLIDER,
					'default' => [
						'size' => 50,
						'unit' => '%',
					],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'dynamic' => [
						'active' => true,
					],
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
		'type_title',
			[
				'label' => esc_html__( 'Title Styles', 'elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Text Color', 'wbc907-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
						'{{WRAPPER}} .wbc-progress-title' => 'color: {{VALUE}}',
					]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_font',
				'label' => esc_html__( 'Typography', 'wbc907-core' ),
				'selector' => '{{WRAPPER}} .wbc-progress-title',
			]
		);

		$this->add_control(
		'type_percent',
			[
				'label' => esc_html__( 'Percentage Styles', 'elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'percent_type_color',
			[
				'label' => esc_html__( 'Color', 'wbc907-core' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
						'{{WRAPPER}} .wbc-progress-percent' => 'color: {{VALUE}}',
					]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'percent_type_font',
				'label' => esc_html__( 'Typography', 'wbc907-core' ),
				'selector' => '{{WRAPPER}} .wbc-progress-percent',
			]
		);
		//////
		$this->add_control(
		'bar_title_styles',
			[
				'label' => esc_html__( 'Progress Bar', 'elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'bar_color',
			[
				'label' => esc_html__( 'Color', 'wbc907-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .wbc-progress' => 'background-color:{{VALUE}}',
					]
			]
		);

		$this->add_control(
			'bar_height',
			[
				'label' => esc_html__( 'Height', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'dynamic' => [
					'active' => true,
				],
				'selectors' => [
						'{{WRAPPER}} .wbc-progress' => 'height: {{SIZE}}{{UNIT}}',
					]
			]
		);


		$this->add_control(
		'backing_title',
			[
				'label' => esc_html__( 'Backing', 'elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

			$this->add_control(
			'backing_color',
			[
				'label' => esc_html__( 'Color', 'wbc907-core' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
						'{{WRAPPER}} .wbc-progress-back' => 'background-color:{{VALUE}}',
					]
			]
		);

		$this->add_control(
			'bar_padding',
			[
				'label' => esc_html__( 'Padding', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'dynamic' => [
					'active' => true,
				],
				'selectors' => [
						'{{WRAPPER}} .wbc-progress-back' => 'padding: {{SIZE}}{{UNIT}}',
					]
			]
		);

		$this->add_control(
			'bar_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'dynamic' => [
					'active' => true,
				],
				'selectors' => [
						'{{WRAPPER}} .wbc-progress-back' => 'border-radius: {{SIZE}}{{UNIT}}',
					]
			]
		);


		$this->end_controls_section();

		

	}

	protected function render($instance = []) {
		$atts = $this->get_settings_for_display();

		include WBC_INCLUDES_DIRECTORY.'elementor/includes/templates/progress-bar/index.php';
			
	}

	protected function content_template() {}	

}
	\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \WBC_Progress_Bar() );
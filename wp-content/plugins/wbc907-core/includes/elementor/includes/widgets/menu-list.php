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

class WBC_Menu_List extends Widget_Base {

	public function get_name() {
        return 'wbc_menu_list';
    }
    
    public function get_title() {
        return esc_html__( 'Menu Price List', 'wbc907-core' );
    }

    public function get_icon() {
        return 'eicon-price-list wbc-icon-widget';
    }
    
    public function get_categories() {
        return [ 'wbc-ninezeroseven' ];
    }

    public function get_keywords() {
		return [ '907', 'ninezeroseven','pricing','price','menu list'];
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

	protected function _register_controls() {
       $this->start_controls_section(
			'section_list',
			[
				'label' => __( 'List', 'wbc907-core' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'price',
			[
				'label' => __( 'Price', 'wbc907-core' ),
				'type' => Controls_Manager::TEXT,
				// 'dynamic' => [
				// 	'active' => true,
				// ],
			]
		);

		$repeater->add_control(
			'title',
			[
				'label' => __( 'Title', 'wbc907-core' ),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'label_block' => 'true',
				// 'dynamic' => [
				// 	'active' => true,
				// ],
			]
		);

		$repeater->add_control(
			'item_description',
			[
				'label' => __( 'Description', 'wbc907-core' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => '',
				// 'dynamic' => [
				// 	'active' => true,
				// ],
			]
		);

		$this->add_control(
			'price_list',
			[
				'label' => __( 'List Items', 'wbc907-core' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'title' => __( 'First item on the list', 'wbc907-core' ),
						'item_description' => __( 'Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'wbc907-core' ),
						'price' => '$20',
					],
					[
						'title' => __( 'Second item on the list', 'wbc907-core' ),
						'item_description' => __( 'Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'wbc907-core' ),
						'price' => '$9',
					],
					[
						'title' => __( 'Third item on the list', 'wbc907-core' ),
						'item_description' => __( 'Lorem ipsum dolor sit amet consectetur adipiscing elit dolor', 'wbc907-core' ),
						'price' => '$32',
					],
				],
				'title_field' => '{{{ title }}}',
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_list_style',
			[
				'label' => __( 'List', 'wbc907-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'heading__title',
			[
				'label' => __( 'Title & Price', 'wbc907-core' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'heading_color',
			[
				'label' => __( 'Color', 'wbc907-core' ),
				'type' => Controls_Manager::COLOR,
				// 'global' => [
				// 	'default' => Global_Colors::COLOR_PRIMARY,
				// ],
				'selectors' => [
					'{{WRAPPER}} .wbc-price-list-info' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'heading_typography',
				// 'global' => [
				// 	'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				// ],
				'selector' => '{{WRAPPER}} .wbc-price-list-info',
			]
		);

		$this->add_control(
			'heading_item_description',
			[
				'label' => __( 'Description', 'wbc907-core' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'description_color',
			[
				'label' => __( 'Color', 'wbc907-core' ),
				'type' => Controls_Manager::COLOR,
				// 'global' => [
				// 	'default' => Global_Colors::COLOR_TEXT,
				// ],
				'selectors' => [
					'{{WRAPPER}} .wbc-price-list-content' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				// 'global' => [
				// 	'default' => Global_Typography::TYPOGRAPHY_TEXT,
				// ],
				'selector' => '{{WRAPPER}} .wbc-price-list-content',
			]
		);

		$this->add_control(
			'heading_separator',
			[
				'label' => __( 'Separator', 'wbc907-core' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'separator_style',
			[
				'label' => __( 'Style', 'wbc907-core' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'solid' => __( 'Solid', 'wbc907-core' ),
					'dotted' => __( 'Dotted', 'wbc907-core' ),
					'dashed' => __( 'Dashed', 'wbc907-core' ),
					'double' => __( 'Double', 'wbc907-core' ),
					'none' => __( 'None', 'wbc907-core' ),
				],
				'default' => 'dotted',
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .wbc-price-list-info-sep' => 'border-bottom-style: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'separator_weight',
			[
				'label' => __( 'Weight', 'wbc907-core' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 10,
					],
				],
				'condition' => [
					'separator_style!' => 'none',
				],
				'selectors' => [
					'{{WRAPPER}} .wbc-price-list-info-sep' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
				],
				'default' => [
					'size' => 2,
				],
			]
		);

		$this->add_control(
			'separator_color',
			[
				'label' => __( 'Color', 'wbc907-core' ),
				'type' => Controls_Manager::COLOR,
				// 'global' => [
				// 	'default' => Global_Colors::COLOR_SECONDARY,
				// ],
				'selectors' => [
					'{{WRAPPER}} .wbc-price-list-info-sep' => 'border-bottom-color: {{VALUE}};',
				],
				'condition' => [
					'separator_style!' => 'none',
				],
			]
		);

		$this->add_control(
			'separator_spacing',
			[
				'label' => __( 'Spacing', 'wbc907-core' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 40,
					],
				],
				'condition' => [
					'separator_style!' => 'none',
				],
				'selectors' => [
					'{{WRAPPER}} .wbc-price-list-info-sep' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_item_style',
			[
				'label' => __( 'Item', 'wbc907-core' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'row_gap',
			[
				'label' => __( 'Rows Gap', 'wbc907-core' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 50,
					],
					'em' => [
						'max' => 5,
						'step' => 0.1,
					],
				],
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .wbc-price-list li:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
				'default' => [
					'size' => 20,
				],
			]
		);

		$this->end_controls_section();

		

	}

	protected function render($instance = []) {
		$atts = $this->get_settings_for_display();
		
		include WBC_INCLUDES_DIRECTORY.'elementor/includes/templates/menu-list/index.php';
			
	}

	protected function content_template() {}	

}
	\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \WBC_Menu_List() );
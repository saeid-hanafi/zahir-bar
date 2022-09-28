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

class WBC_Portfolio_Carousel extends Widget_Base {

	public function get_name() {
        return 'wbc_portfolio_carousel';
    }
    
    public function get_title() {
        return esc_html__( 'Portfolio Carousel', 'wbc907-core' );
    }

    public function get_icon() {
        return 'eicon-slider-push wbc-icon-widget';
    }
    
    public function get_categories() {
        return [ 'wbc-ninezeroseven' ];
    }

    public function get_keywords() {
		return [ '907', 'ninezeroseven','post','portfolio','carousel','gallery' ];
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
        // Start Image        
			$this->start_controls_section( 
				'section_layout',
	            [
	                'label' => esc_html__( 'Layout', 'wbc907-core'),
	            ]
	        );

			$this->add_control(
				'show_post',
				[
					'label' => __( 'Posts Per Page', 'wbc907-core' ),
					'type' => Controls_Manager::NUMBER,
					'default' => 6,
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


	        $this->add_control(
			'hide_title',
				[
					'label' => __( 'Hide Title?', 'wbc907-core' ),
					'type' => Controls_Manager::SWITCHER,
					'default' => '',
					'label_off' => __( 'No', 'wbc907-core' ),
					'label_on' => __( 'Yes', 'wbc907-core' ),
				]
			);

			$this->add_control(
			'hide_popup_link',
				[
					'label' => __( 'Hide Popup Link?', 'wbc907-core' ),
					'type' => Controls_Manager::SWITCHER,
					'default' => '',
					'label_off' => __( 'No', 'wbc907-core' ),
					'label_on' => __( 'Yes', 'wbc907-core' ),
				]
			);

			$this->add_control(
			'hide_page_link',
				[
					'label' => __( 'Hide Page Link?', 'wbc907-core' ),
					'type' => Controls_Manager::SWITCHER,
					'default' => '',
					'label_off' => __( 'No', 'wbc907-core' ),
					'label_on' => __( 'Yes', 'wbc907-core' ),
				]
			);

			$this->add_control(
			'link_overlay',
				[
					'label' => __( 'Link Overlay?', 'wbc907-core' ),
					'type' => Controls_Manager::SWITCHER,
					'default' => '',
					'label_off' => __( 'No', 'wbc907-core' ),
					'label_on' => __( 'Yes', 'wbc907-core' ),
				]
			);

					
	        $this->end_controls_section();
        // /.End Image
     
			$this->start_controls_section( 
				'section_query',
	            [
	                'label' => esc_html__('Query', 'wbc907-core'),
	            ]
	        );


	        $this->add_control(
				'post_in',
				[
					'label' => __( 'Include Post Only', 'elementor' ),
					'type' => Controls_Manager::TEXT,
					'default' => '',
					'title' => 'Must be numbers seperated by commas.',
					'placeholder' => 'i.e 12,34,53',
					'label_block' => true,
				]
			);

			$this->add_control(
				'post_not_in',
				[
					'label' => __( 'Exclude Post', 'elementor' ),
					'type' => Controls_Manager::TEXT,
					'default' => '',
					'title' => 'Must be numbers seperated by commas.',
					'placeholder' => 'i.e 12,34,53',
					'label_block' => true,
					'separator' => 'after',
				]
			);

		    $this->add_control(
			    'order_by',
			    [
				    'label' => esc_html__( 'Order By', 'wbc907-core' ),
				    'type' => Controls_Manager::SELECT,
				    'default' => 'date',
				    'options' => [
							'ID'    =>	esc_html__( 'ID', 'ninezeroseven' ),
							'title' =>	esc_html__( 'Title', 'ninezeroseven' ),
							'name'  =>	esc_html__( 'Name', 'ninezeroseven' ),
							'date'  =>	esc_html__( 'Date', 'ninezeroseven' ),
							'rand'  =>	esc_html__( 'Random', 'ninezeroseven' ),
				    ],
			    ]
		    );

		    $this->add_control(
			    'order_dir',
			    [
				    'label' => esc_html__( 'Order', 'wbc907-core' ),
				    'type' => Controls_Manager::SELECT,
				    'default' => 'DESC',
				    'options' => [
							'ASC'    =>	esc_html__( 'Ascending', 'ninezeroseven' ),
							'DESC' =>	esc_html__( 'Descending', 'ninezeroseven' ),
				    ],
			    ]
		    );

		    $this->add_control(
			    'portfolio_cats',
			    [
				    'label' => esc_html__( 'Portfolio Categories', 'wbc907-core' ),
				    'type' => Controls_Manager::SELECT2,
				    'label_block' => true,
				    'multiple' => true,
				    'options' => $this->get_portfolio_categories(),
			    ]
		    );


			$this->end_controls_section();


        
        // Start General Style        
			$this->start_controls_section( 
				'section_style_general',
	            [
	                'label' => esc_html__('Carousel Settings', 'wbc907-core'),
	                'tab' => Controls_Manager::TAB_STYLE,
	            ]
	        );

	        $this->add_control(
				'item_width',
				[
					'label' => __( 'Item Width', 'wbc907-core' ),
					'type' => Controls_Manager::NUMBER,
					'default' => '',
				]
			);
			$this->add_control(
				'item_scroll',
				[
					'label' => __( 'Scroll Amount', 'wbc907-core' ),
					'type' => Controls_Manager::NUMBER,
					'default' => '',
					'description' => 'How many items to scroll.',
				]
			);
			$this->add_control(
				'item_speed',
				[
					'label' => __( 'Scroll Speed', 'wbc907-core' ),
					'type' => Controls_Manager::NUMBER,
					'default' => '',
					'description' => 'Time till items scrolled. default 1000',
				]
			);

			$this->add_control(
				'item_min',
				[
					'label' => __( 'Min Amount shown', 'wbc907-core' ),
					'type' => Controls_Manager::NUMBER,
					'default' => '',
				]
			);

			$this->add_control(
				'item_max',
				[
					'label' => __( 'Max Amount shown', 'wbc907-core' ),
					'type' => Controls_Manager::NUMBER,
					'default' => '',
				]
			);
         

	        $this->end_controls_section();
        // /.End General Style

	    // Start Image Style
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
				'label' => __( 'Normal', 'wbc907-core' ),
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
				'label' => __( 'Hover', 'wbc907-core' ),
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

		if( isset( $atts['portfolio_cats'] ) && is_array( $atts['portfolio_cats'] ) ){
			if( count( $atts['portfolio_cats'] ) > 0 ){
				$atts['portfolio_cats'] = join(',',$atts['portfolio_cats']);
			}else{
				unset( $atts['portfolio_cats'] );
			}
		}


  		include WBC_INCLUDES_DIRECTORY.'elementor/includes/templates/portfolio-carousel/index.php';
			
	}

	protected function content_template() {}	

}
	\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \WBC_Portfolio_Carousel() );
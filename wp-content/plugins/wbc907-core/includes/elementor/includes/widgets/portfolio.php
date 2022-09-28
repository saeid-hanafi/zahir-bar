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

class WBC_Portfolio_Grid extends Widget_Base {

	public function get_name() {
        return 'wbc_portfolio';
    }
    
    public function get_title() {
        return esc_html__( 'Portfolio', 'wbc907-core' );
    }

    public function get_icon() {
        return 'eicon-gallery-justified wbc-icon-widget';
    }
    
    public function get_categories() {
        return [ 'wbc-ninezeroseven' ];
    }

    public function get_keywords() {
		return [ '907', 'ninezeroseven','post','portfolio','grid','gallery' ];
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
				'layout_type',
				[
					'label' => esc_html__( 'Gallery Type', 'wbc907-core' ),
					'type' => Controls_Manager::SELECT,
					'default' => '',
					'options' => [
						'' => esc_html__( 'Default', 'wbc907-core' ),
						'masonry' => esc_html__( 'Masonry', 'wbc907-core' ),
						'fitRows' => esc_html__( 'Fit Rows', 'wbc907-core' ),
						'brick' => esc_html__( 'Brick Wall', 'wbc907-core' ),
					],
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

			$this->add_control(
			'portfolio_display',
				[
					'label' => __( 'Show Excerpt?', 'wbc907-core' ),
					'type' => Controls_Manager::SWITCHER,
					'default' => '',
					'label_off' => __( 'No', 'wbc907-core' ),
					'label_on' => __( 'Yes', 'wbc907-core' ),
				]
			);

			$this->add_control(
				'excerpt_length',
				[
					'label' => __( 'Excerpt Length', 'wbc907-core' ),
					'type' => Controls_Manager::NUMBER,
					'default' => 40,
					'condition' => [
						'portfolio_display' => 'yes',
						]
				]
			);

			$this->add_control(
			'hide_dots',
				[
					'label' => __( 'Hide Dots (...)?', 'wbc907-core' ),
					'type' => Controls_Manager::SWITCHER,
					'default' => '',
					'label_off' => __( 'No', 'wbc907-core' ),
					'label_on' => __( 'Yes', 'wbc907-core' ),
					'condition' => [
						'portfolio_display' => 'yes',
						]
				]
			);
			

			
			$this->add_control(
				'column_settings',
				[
					'label' => esc_html__( 'Columns', 'wbc907-core' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

	        $this->add_control(
				'cols_xl',
				[
					'label' => __( 'Columns 1200', 'wbc907-core' ),
					'type' => Controls_Manager::NUMBER,
					'default' => 4,
					'max' => 10,
					'description' => 'Columns when container is over 1200',
				]
			);
			$this->add_control(
				'cols_l',
				[
					'label' => __( 'Columns 800', 'wbc907-core' ),
					'type' => Controls_Manager::NUMBER,
					'default' => 3,
					'max' => 10,
					'description' => 'Columns when container is over 800',
				]
			);

			$this->add_control(
				'cols_s',
				[
					'label' => __( 'Columns 600', 'wbc907-core' ),
					'type' => Controls_Manager::NUMBER,
					'default' => 2,
					'max' => 10,
					'description' => 'Columns when container is over 600',
				]
			);
			$this->add_control(
				'cols_xs',
				[
					'label' => __( 'Columns 400', 'wbc907-core' ),
					'type' => Controls_Manager::NUMBER,
					'default' => 1,
					'max' => 10,
					'description' => 'Columns when container is over 400',
					'separator' => 'after',
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
			'group_lightbox',
				[
				'label'            => __( 'Group Images Lightbox', 'wbc907-core' ),
				'type'             => Controls_Manager::SWITCHER,
				'default'          => '',
				'label_off'        => __( 'No', 'wbc907-core' ),
				'label_on'         => __( 'Yes', 'wbc907-core' ),
				'condition'        => [
									'hide_popup_link!' => 'yes',
									]
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
			'no_link_overlay',
				[
					'label' => __( 'Unlink Overlay?', 'wbc907-core' ),
					'type' => Controls_Manager::SWITCHER,
					'default' => '',
					'label_off' => __( 'No', 'wbc907-core' ),
					'label_on' => __( 'Yes', 'wbc907-core' ),
				]
			);

			$this->add_control(
			'mouse_over_play',
				[
					'label' => __( 'Mouse Over Videos?', 'wbc907-core' ),
					'type' => Controls_Manager::SWITCHER,
					'default' => '',
					'label_off' => __( 'No', 'wbc907-core' ),
					'label_on' => __( 'Yes', 'wbc907-core' ),
				]
			);

			$this->add_control(
			'mute_videos',
				[
					'label' => __( 'Mute Videos?', 'wbc907-core' ),
					'type' => Controls_Manager::SWITCHER,
					'default' => 'yes',
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

			//Filter Bar
			$this->start_controls_section( 
				'filter_bar',
	            [
	                'label' => esc_html__('Filter Bar', 'wbc907-core'),
	            ]
	        );

		    $this->add_control(
				'show_filter',
					[
						'label' => __( 'Show Filter?', 'wbc907-core' ),
						'type' => Controls_Manager::SWITCHER,
						'default' => '',
						'label_off' => __( 'No', 'wbc907-core' ),
						'label_on' => __( 'Yes', 'wbc907-core' ),
					]
				);
		    
		    $this->add_control(
			    'filter_align',
			    [
				    'label' => esc_html__( 'Align Filter', 'wbc907-core' ),
				    'type' => Controls_Manager::SELECT,
				    'default' => 'left',
				    'options' => [
							'left'   =>	esc_html__( 'Left', 'ninezeroseven' ),
							'right'  =>	esc_html__( 'Right', 'ninezeroseven' ),
							'center' =>	esc_html__( 'Center', 'ninezeroseven' ),

				    ],
				    'condition' => [
						'show_filter' => 'yes',
						]
			    ]
		    );

		    $this->add_control(
				'all_word',
				[
					'label' => __( 'All Word Text', 'elementor' ),
					'type' => Controls_Manager::TEXT,
					'default' => '',
					'placeholder' => 'All button text',
					'condition' => [
						'show_filter' => 'yes',
						]
				]
			);



			$this->end_controls_section();

			//END FILTERBAR

			$this->start_controls_section( 
				'pagination',
	            [
	                'label' => esc_html__('Pagination', 'wbc907-core'),
	            ]
	        );

		    $this->add_control(
				'paginate',
					[
						'label' => __( 'Paginate?', 'wbc907-core' ),
						'type' => Controls_Manager::SWITCHER,
						'default' => '',
						'label_off' => __( 'No', 'wbc907-core' ),
						'label_on' => __( 'Yes', 'wbc907-core' ),
					]
				);

		    $this->add_control(
				'ajaxed',
					[
						'label' => __( 'Ajax Pagination?', 'wbc907-core' ),
						'type' => Controls_Manager::SWITCHER,
						'default' => '',
						'label_off' => __( 'No', 'wbc907-core' ),
						'label_on' => __( 'Yes', 'wbc907-core' ),
						'condition' => [
							'paginate' => 'yes',
						]
					]
				);

		    $this->add_control(
				'paginate_by_filter',
					[
						'label' => __( 'Paginate By Filter Buttons?', 'wbc907-core' ),
						'type' => Controls_Manager::SWITCHER,
						'default' => '',
						'label_off' => __( 'No', 'wbc907-core' ),
						'label_on' => __( 'Yes', 'wbc907-core' ),
						'condition' => [
							'ajaxed' => 'yes',
						]
					]
				);

		    
		    
		    $this->add_control(
			    'pagination_align',
			    [
				    'label' => esc_html__( 'Align Pagination', 'wbc907-core' ),
				    'type' => Controls_Manager::SELECT,
				    'default' => 'right',
				    'options' => [
							'left'   =>	esc_html__( 'Left', 'ninezeroseven' ),
							'right'  =>	esc_html__( 'Right', 'ninezeroseven' ),
							'center' =>	esc_html__( 'Center', 'ninezeroseven' ),

				    ],
				    'condition' => [
						'paginate' => 'yes',
						]
			    ]
		    );


			$this->end_controls_section();

        
        // Start General Style        
			$this->start_controls_section( 
				'section_style_general',
	            [
	                'label' => esc_html__('General', 'wbc907-core'),
	                'tab' => Controls_Manager::TAB_STYLE,
	            ]
	        );

	        $this->add_control(
			    'gap',
			    [
				    'label' => esc_html__( 'Item Gap', 'wbc907-core' ),
				    'type' => Controls_Manager::SELECT,
				    'default' => '',
				    'options' => [
							''   =>	esc_html__( 'Default', 'ninezeroseven' ),
							'0'  =>	esc_html__( 'Gap 0', 'ninezeroseven' ),
							'1'  =>	esc_html__( 'Gap 1', 'ninezeroseven' ),
							'5'  =>	esc_html__( 'Gap 5', 'ninezeroseven' ),
							'10'  =>	esc_html__( 'Gap 10', 'ninezeroseven' ),
							'15'  =>	esc_html__( 'Gap 15', 'ninezeroseven' ),
							'20'  =>	esc_html__( 'Gap 20', 'ninezeroseven' ),
							'custom'  =>	esc_html__( 'Custom', 'ninezeroseven' ),

				    ],
			    ]
		    );

		     $this->add_control(
			    'padding',
			    [
				    'label' => esc_html__( 'Custom Gap', 'wbc907-core' ),
				    'type' => Controls_Manager::TEXT,
				    'default' => '',
				    'placeholder'=>'Enter number value i.e 30',
				    'condition' => [
						'gap' => 'custom',
						]
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
        // /.End Image Style

		// FILTER BAR
		$this->start_controls_section( 
				'filter_style',
	            [
	                'label' => esc_html__('Filter', 'wbc907-core'),
	                'tab' => Controls_Manager::TAB_STYLE,
	                'condition' => [
						'show_filter' => 'yes',
						]
	            ]
	        );

		$this->start_controls_tabs( 'button_tabs' );

			$this->start_controls_tab( 'button_normal',
				[
					'label' => __( 'Normal', 'wbc907-core' ),
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'button_typo',
					'label' => esc_html__( 'Typography', 'wbc907-core' ),
					'selector' => '{{WRAPPER}} .wbc-filter .btn-primary',
				]
			);
			$this->add_control(
				'button_text_color',
				[
					'label' => esc_html__( 'Text Color', 'wbc907-core' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .wbc-filter .btn-primary' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'button_text_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'wbc907-core' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .wbc-filter .btn-primary' => 'background-color: {{VALUE}};border-color: {{VALUE}};',
					],
				]
			);

			$this->end_controls_tab();
			$this->start_controls_tab( 'button_hover',
				[
					'label' => __( 'Hover', 'wbc907-core' ),
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'button_hover_typo',
					'label' => esc_html__( 'Typography', 'wbc907-core' ),
					'selector' => '{{WRAPPER}} .wbc-filter .btn-primary:hover',
				]
			);

			$this->add_control(
				'button_hover_color',
				[
					'label' => esc_html__( 'Text Color', 'wbc907-core' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .wbc-filter .btn-primary:hover' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'button_text_bg_hover_color',
				[
					'label' => esc_html__( 'Background Color', 'wbc907-core' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .wbc-filter .btn-primary:hover' => 'background-color: {{VALUE}};border-color: {{VALUE}};',
					],
				]
			);

			
			$this->end_controls_tab();
			$this->start_controls_tab( 'button_active',
				[
					'label' => __( 'Active', 'wbc907-core' ),
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'button_active_typo',
					'label' => esc_html__( 'Typography', 'wbc907-core' ),
					'selector' => '{{WRAPPER}} .wbc-filter .btn-primary.selected',
				]
			);

			$this->add_control(
				'button_active_color',
				[
					'label' => esc_html__( 'Text Color', 'wbc907-core' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .wbc-filter .btn-primary.selected' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'button_text_bg_active_color',
				[
					'label' => esc_html__( 'Background Color', 'wbc907-core' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .wbc-filter .btn-primary.selected' => 'background-color: {{VALUE}};border-color: {{VALUE}};',
					],
				]
			);

			
			$this->end_controls_tab();
			$this->end_controls_tabs();

			$this->add_responsive_control(
			'button_radius',
			[
				'label' => __( 'Border Radius', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [],
				'size_units' => [ 'px','%','em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .wbc-filter .btn-primary' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
			);

			$this->add_responsive_control(
			    'button_padding',
			    [
				    'label' => esc_html__( 'Padding', 'wbc907-core' ),
				    'type' => Controls_Manager::DIMENSIONS,
				    'size_units' => [ 'px', '%', 'em' ],
				    'selectors' => [
					    '{{WRAPPER}} .wbc-filter .btn-primary' => 'padding: {{top}}{{unit}} {{right}}{{unit}} {{bottom}}{{unit}} {{left}}{{unit}};',
				    ],
			    ]
		    );


		$this->end_controls_section();

	    // Start Title & Description Style
	        $this->start_controls_section( 
				'content_section',
	            [
	                'label' => esc_html__('Excerpt', 'wbc907-core'),
	                'tab' => Controls_Manager::TAB_STYLE,
	                'condition' => [
						'portfolio_display' => 'yes',
						]
	            ]
	        );

	        $this->add_control(
				'title_heading',
				[
					'label' => esc_html__( 'Title', 'wbc907-core' ),
					'type' => Controls_Manager::HEADING,
				]
			);


			$this->start_controls_tabs( 'blog_title_tabs' );

			$this->start_controls_tab( 'title_normal',
				[
					'label' => __( 'Normal', 'wbc907-core' ),
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'blog_title',
					'label' => esc_html__( 'Typography', 'wbc907-core' ),
					'selector' => '{{WRAPPER}} .portfolio-item .entry-title a',
				]
			);
			$this->add_control(
				'blog_title_color',
				[
					'label' => esc_html__( 'Color', 'wbc907-core' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .portfolio-item .entry-title a' => 'color: {{VALUE}}',
					],
				]
			);

			$this->end_controls_tab();
			$this->start_controls_tab( 'title_hover',
				[
					'label' => __( 'Hover', 'wbc907-core' ),
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'blog_title_hover',
					'label' => esc_html__( 'Typography', 'wbc907-core' ),
					'selector' => '{{WRAPPER}} .portfolio-item .entry-title:hover a',
				]
			);

			$this->add_control(
				'blog_title_hover_color',
				[
					'label' => esc_html__( 'Color', 'wbc907-core' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .portfolio-item .entry-title:hover a' => 'color: {{VALUE}}',
					],
				]
			);

			
			$this->end_controls_tab();
			$this->end_controls_tabs();


			$this->add_responsive_control(
			'blog_title_spacing',
			[
				'label' => __( 'Spacing', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [],
				'size_units' => [ 'px','em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'separator' => 'after',
				'selectors' => [
					'{{WRAPPER}} .portfolio-item .entry-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
			);



			$this->add_control(
				'meta_heading',
				[
					'label' => esc_html__( 'Text', 'wbc907-core' ),
					'type' => Controls_Manager::HEADING,
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'blog_meta_type',
					'label' => esc_html__( 'Typography', 'wbc907-core' ),
					'selector' => '{{WRAPPER}} .portfolio-item .portfolio-text-wrap p',
				]
			);

			$this->add_control(
				'blog_meta_color',
				[
					'label' => esc_html__( 'Color', 'wbc907-core' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .portfolio-item .portfolio-text-wrap' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_responsive_control(
			'blog_meta_spacing',
			[
				'label' => __( 'Spacing', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [],
				'size_units' => [ 'px','em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'separator' => 'after',
				'selectors' => [
					'{{WRAPPER}} .portfolio-item .portfolio-text-wrap' => 'padding-bottom: {{SIZE}}{{UNIT}};',
				],
			]
			);
			$this->end_controls_section();


			///PAGINATION
			$this->start_controls_section( 
				'pagination_style',
	            [
	                'label' => esc_html__('Pagination', 'wbc907-core'),
	                'tab' => Controls_Manager::TAB_STYLE,
	                'condition' => [
						'paginate' => 'yes',
						]
	            ]
	        );

			$this->start_controls_tabs( 'pagenav_tabs' );

			$this->start_controls_tab( 'pagenav_normal',
				[
					'label' => __( 'Normal', 'wbc907-core' ),
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'pagenav_typo',
					'label' => esc_html__( 'Typography', 'wbc907-core' ),
					'selector' => '{{WRAPPER}} .wbc-pagination a, {{WRAPPER}} .wbc-pagination span',
				]
			);
			$this->add_control(
				'pagenav_text_color',
				[
					'label' => esc_html__( 'Text Color', 'wbc907-core' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .wbc-pagination a, {{WRAPPER}} .wbc-pagination span' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'pagenav_text_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'wbc907-core' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .wbc-pagination a, {{WRAPPER}} .wbc-pagination span' => 'background-color: {{VALUE}};border-color: {{VALUE}};',
					],
				]
			);

			$this->end_controls_tab();
			$this->start_controls_tab( 'pagenav_hover',
				[
					'label' => __( 'Hover', 'wbc907-core' ),
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'pagenav_hover_typo',
					'label' => esc_html__( 'Typography', 'wbc907-core' ),
					'selector' => '{{WRAPPER}} .wbc-pagination a:hover',
				]
			);

			$this->add_control(
				'pagenav_hover_color',
				[
					'label' => esc_html__( 'Text Color', 'wbc907-core' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .wbc-pagination a:hover' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'pagenav_text_bg_hover_color',
				[
					'label' => esc_html__( 'Background Color', 'wbc907-core' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .wbc-pagination a:hover' => 'background-color: {{VALUE}};border-color: {{VALUE}};',
					],
				]
			);

			
			$this->end_controls_tab();
			$this->start_controls_tab( 'pagenav_active',
				[
					'label' => __( 'Active', 'wbc907-core' ),
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'pagenav_active_typo',
					'label' => esc_html__( 'Typography', 'wbc907-core' ),
					'selector' => '{{WRAPPER}} .wbc-pagination .current',
				]
			);

			$this->add_control(
				'pagenav_active_color',
				[
					'label' => esc_html__( 'Text Color', 'wbc907-core' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .wbc-pagination .current' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'pagenav_text_bg_active_color',
				[
					'label' => esc_html__( 'Background Color', 'wbc907-core' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .wbc-pagination .current' => 'background-color: {{VALUE}};border-color: {{VALUE}};',
					],
				]
			);

			
			$this->end_controls_tab();
			$this->end_controls_tabs();

			$this->add_responsive_control(
			'pagenav_radius',
			[
				'label' => __( 'Border Radius', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [],
				'size_units' => [ 'px','%','em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .wbc-pagination a, {{WRAPPER}} .wbc-pagination span' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
			);

			$this->add_responsive_control(
			    'pagenav_padding',
			    [
				    'label' => esc_html__( 'Padding', 'wbc907-core' ),
				    'type' => Controls_Manager::DIMENSIONS,
				    'size_units' => [ 'px', '%', 'em' ],
				    'selectors' => [
					    '{{WRAPPER}} .wbc-pagination a, {{WRAPPER}} .wbc-pagination span' => 'padding: {{top}}{{unit}} {{right}}{{unit}} {{bottom}}{{unit}} {{left}}{{unit}};',
				    ],
			    ]
		    );


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


  		include WBC_INCLUDES_DIRECTORY.'elementor/includes/templates/portfolio/index.php';
			
	}

	protected function content_template() {}	

}
	\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \WBC_Portfolio_Grid() );
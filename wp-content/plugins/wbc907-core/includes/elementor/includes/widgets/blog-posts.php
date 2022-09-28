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

class WBC_Blog_Posts extends Widget_Base {

	public function get_name() {
        return 'wbc_blog';
    }
    
    public function get_title() {
        return esc_html__( 'Blog Posts', 'wbc907-core' );
    }

    public function get_icon() {
        return 'eicon-post-list wbc-icon-widget';
    }
    
    public function get_categories() {
        return [ 'wbc-ninezeroseven' ];
    }

    public function get_keywords() {
		return [ '907', 'ninezeroseven','post','blog','grid' ];
	}



	public function get_blog_categories(){
		$results = array();
		$terms = get_terms( 'category' );

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
				'blog_layout',
				[
					'label' => esc_html__( 'Layout Type', 'wbc907-core' ),
					'type' => Controls_Manager::SELECT,
					'default' => '',
					'options' => [
						'' => esc_html__( 'Default', 'wbc907-core' ),
						'blog-style-1' => esc_html__( 'Big Image', 'wbc907-core' ),
						'blog-style-2' => esc_html__( 'Small Image', 'wbc907-core' ),
						'blog-style-3' => esc_html__( 'Masonry', 'wbc907-core' ),
					],
				]
			);

			$this->add_control(
				'show_post',
				[
					'label' => __( 'Posts Per Page', 'elementor-pro' ),
					'type' => Controls_Manager::NUMBER,
					'default' => 6,
				]
			);


			$this->add_control(
				'excerpt_length',
				[
					'label' => __( 'Excerpt Length', 'elementor-pro' ),
					'type' => Controls_Manager::NUMBER,
					'default' => 260,
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
				]
			);


			
			$this->add_control(
				'column_settings',
				[
					'label' => esc_html__( 'Masonry Columns', 'wbc907-core' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

	        $this->add_control(
				'cols_xl',
				[
					'label' => __( 'Columns 1200', 'elementor-pro' ),
					'type' => Controls_Manager::NUMBER,
					'default' => 4,
					'max' => 10,
					'description' => 'Columns when container is over 1200',
					'condition' => [
					'blog_layout' => 'blog-style-3',
						]
				]
			);
			$this->add_control(
				'cols_l',
				[
					'label' => __( 'Columns 800', 'elementor-pro' ),
					'type' => Controls_Manager::NUMBER,
					'default' => 3,
					'max' => 10,
					'description' => 'Columns when container is over 800',
					'condition' => [
					'blog_layout' => 'blog-style-3',
						]
				]
			);

			$this->add_control(
				'cols_s',
				[
					'label' => __( 'Columns 600', 'elementor-pro' ),
					'type' => Controls_Manager::NUMBER,
					'default' => 2,
					'max' => 10,
					'description' => 'Columns when container is over 600',
					'condition' => [
						'blog_layout' => 'blog-style-3',
						]
				]
			);
			$this->add_control(
				'cols_xs',
				[
					'label' => __( 'Columns 400', 'elementor-pro' ),
					'type' => Controls_Manager::NUMBER,
					'default' => 1,
					'max' => 10,
					'description' => 'Columns when container is over 400',
					'separator' => 'after',
					'condition' => [
					'blog_layout' => 'blog-style-3',
						]
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
			'hide_popup_link',
				[
					'label' => __( 'Hide Popup Link?', 'elementor-pro' ),
					'type' => Controls_Manager::SWITCHER,
					'default' => '',
					'label_off' => __( 'No', 'elementor-pro' ),
					'label_on' => __( 'Yes', 'elementor-pro' ),
				]
			);

			$this->add_control(
			'hide_page_link',
				[
					'label' => __( 'Hide Page Link?', 'elementor-pro' ),
					'type' => Controls_Manager::SWITCHER,
					'default' => '',
					'label_off' => __( 'No', 'elementor-pro' ),
					'label_on' => __( 'Yes', 'elementor-pro' ),
				]
			);

			$this->add_control(
			'no_link_overlay',
				[
					'label' => __( 'Unlink Overlay?', 'elementor-pro' ),
					'type' => Controls_Manager::SWITCHER,
					'default' => '',
					'label_off' => __( 'No', 'elementor-pro' ),
					'label_on' => __( 'Yes', 'elementor-pro' ),
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
			    'blog_cats',
			    [
				    'label' => esc_html__( 'Blog Categories', 'wbc907-core' ),
				    'type' => Controls_Manager::SELECT2,
				    'label_block' => true,
				    'multiple' => true,
				    'options' => $this->get_blog_categories(),
			    ]
		    );


			$this->end_controls_section();


			$this->start_controls_section( 
				'pagination',
	            [
	                'label' => esc_html__('Pagination', 'wbc907-core'),
	            ]
	        );

		    $this->add_control(
				'paginate',
					[
						'label' => __( 'Paginate?', 'elementor-pro' ),
						'type' => Controls_Manager::SWITCHER,
						'default' => '',
						'label_off' => __( 'No', 'elementor-pro' ),
						'label_on' => __( 'Yes', 'elementor-pro' ),
					]
				);

		    $this->add_control(
				'ajaxed',
					[
						'label' => __( 'Ajax Pagination?', 'elementor-pro' ),
						'type' => Controls_Manager::SWITCHER,
						'default' => '',
						'label_off' => __( 'No', 'elementor-pro' ),
						'label_on' => __( 'Yes', 'elementor-pro' ),
						'condition' => [
							'paginate' => 'yes',
						]
					]
				);
		    
		    $this->add_control(
			    'page_nav_align',
			    [
				    'label' => esc_html__( 'Align Pagination', 'wbc907-core' ),
				    'type' => Controls_Manager::SELECT,
				    'default' => [],
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

		    $this->add_control(
			    'page_nav_type',
			    [
				    'label' => esc_html__( 'Paging Type', 'wbc907-core' ),
				    'type' => Controls_Manager::SELECT,
				    'default' => [],
				    'options' => [
							'load-more'    =>	esc_html__( 'Load More Button', 'ninezeroseven' ),
							'numbers' =>	esc_html__( 'Numbers', 'ninezeroseven' ),
				    ],
				    'condition' => [
						'ajaxed' => 'yes',
						]
			    ]
		    );


		    $this->add_control(
				'load_more_text',
				[
					'label' => __( 'Load More Button Text', 'elementor' ),
					'type' => Controls_Manager::TEXT,
					'default' => '',
					'placeholder' => 'Your Button Text',
					'condition' => [
						'page_nav_type' => 'load-more',
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

	        $this->add_responsive_control(
				'align',
				[
					'label' => esc_html__( 'Alignment', 'wbc907-core' ),
					'type' => Controls_Manager::CHOOSE,
					'options' => [
						'left' => [
							'title' => esc_html__( 'Left', 'wbc907-core' ),
							'icon' => 'eicon-text-align-left',
						],
						'center' => [
							'title' => esc_html__( 'Center', 'wbc907-core' ),
							'icon' => 'eicon-text-align-center',
						],
						'right' => [
							'title' => esc_html__( 'Right', 'wbc907-core' ),
							'icon' => 'eicon-text-align-right',
						],
					],
					'default' => [],
					'selectors' => [
						'{{WRAPPER}} .post-contents' => 'text-align: {{VALUE}};',
						'{{WRAPPER}} .post-contents .more-link' => 'text-align: {{VALUE}};',
					],
				]
			);

	        $this->add_responsive_control(
			    'box_padding',
			    [
				    'label' => esc_html__( 'Padding', 'wbc907-core' ),
				    'type' => Controls_Manager::DIMENSIONS,
				    'size_units' => [ 'px', '%', 'em' ],
				    'selectors' => [
					    '{{WRAPPER}} .wbc-blog-post-wrapper article:not(.format-link):not(.format-quote) .post-contents,{{WRAPPER}} .wbc-blog-post-wrapper article.format-link a.link-format ,{{WRAPPER}} .wbc-blog-post-wrapper article.format-quote a.quote-format' => 'padding: {{top}}{{unit}} {{right}}{{unit}} {{bottom}}{{unit}} {{left}}{{unit}};',
				    ],
			    ]
		    );

		    $this->add_control(
				'background_color',
				[
					'label' => esc_html__( 'Background Color', 'wbc907-core' ),
					'type' => Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .wbc-blog-post-wrapper article:not(.format-link):not(.format-quote) .post-contents' => 'background-color: {{VALUE}}',
					],
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
				'label' => __( 'Normal', 'elementor-pro' ),
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'thumbnail_filters',
				'selector' => '{{WRAPPER}} .wbc-blog-post-wrapper article .wbc-image-wrap img',
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
				'selector' => '{{WRAPPER}} .wbc-blog-post-wrapper article:hover .wbc-image-wrap img',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'image_spacing',
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
				'selectors' => [
					'{{WRAPPER}} .wbc-blog-post-wrapper .post-featured + .post-contents .post-header' => 'padding-top: {{SIZE}}{{UNIT}};',
				],
			]
			);

		$this->end_controls_section();
        // /.End Image Style

	    // Start Title & Description Style
	        $this->start_controls_section( 
				'content_section',
	            [
	                'label' => esc_html__('Content', 'wbc907-core'),
	                'tab' => Controls_Manager::TAB_STYLE,
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
					'label' => __( 'Normal', 'elementor-pro' ),
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'blog_title',
					'label' => esc_html__( 'Typography', 'wbc907-core' ),
					'selector' => '{{WRAPPER}} .post-header .entry-title,{{WRAPPER}} a.link-format .entry-title',
				]
			);
			$this->add_control(
				'blog_title_color',
				[
					'label' => esc_html__( 'Color', 'wbc907-core' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .post-header .entry-title a' => 'color: {{VALUE}}',
					],
				]
			);

			$this->end_controls_tab();
			$this->start_controls_tab( 'title_hover',
				[
					'label' => __( 'Hover', 'elementor-pro' ),
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'blog_title_hover',
					'label' => esc_html__( 'Typography', 'wbc907-core' ),
					'selector' => '{{WRAPPER}} .entry-title a:hover,{{WRAPPER}} a.link-format:hover .entry-title',
				]
			);

			$this->add_control(
				'blog_title_hover_color',
				[
					'label' => esc_html__( 'Color', 'wbc907-core' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .post-header .entry-title a:hover' => 'color: {{VALUE}}',
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
					'{{WRAPPER}} .post-header .entry-title, {{WRAPPER}} .link-format .entry-title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
			);



			$this->add_control(
				'meta_heading',
				[
					'label' => esc_html__( 'Meta', 'wbc907-core' ),
					'type' => Controls_Manager::HEADING,
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'blog_meta_type',
					'label' => esc_html__( 'Typography', 'wbc907-core' ),
					'selector' => '{{WRAPPER}} .post-header .entry-meta',
				]
			);

			$this->add_control(
				'blog_meta_color',
				[
					'label' => esc_html__( 'Color', 'wbc907-core' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .post-header .entry-meta' => 'color: {{VALUE}}',
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
					'{{WRAPPER}} .post-header .entry-meta' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
			);


			$this->add_control(
				'blog_contents',
				[
					'label' => esc_html__( 'Entry Text', 'wbc907-core' ),
					'type' => Controls_Manager::HEADING,
				]
			);
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'blog_contents_type',
					'label' => esc_html__( 'Typography', 'wbc907-core' ),
					'selector' => '{{WRAPPER}} .post-contents .entry-content',
				]
			);

			$this->add_control(
				'blog_contents_color',
				[
					'label' => esc_html__( 'Color', 'wbc907-core' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .post-contents .entry-content' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_responsive_control(
			'blog_contents_spacing',
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
					'{{WRAPPER}} .post-contents .entry-content .more-link' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
			);

			//Button
			$this->add_control(
				'button_heading',
				[
					'label' => esc_html__( 'Button', 'wbc907-core' ),
					'type' => Controls_Manager::HEADING,
				]
			);


			$this->start_controls_tabs( 'button_tabs' );

			$this->start_controls_tab( 'button_normal',
				[
					'label' => __( 'Normal', 'elementor-pro' ),
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'button_typo',
					'label' => esc_html__( 'Typography', 'wbc907-core' ),
					'selector' => '{{WRAPPER}} a.btn-primary',
				]
			);
			$this->add_control(
				'button_text_color',
				[
					'label' => esc_html__( 'Text Color', 'wbc907-core' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .btn-primary' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'button_text_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'wbc907-core' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .btn-primary' => 'background-color: {{VALUE}};border-color: {{VALUE}};',
					],
				]
			);

			$this->end_controls_tab();
			$this->start_controls_tab( 'button_hover',
				[
					'label' => __( 'Hover', 'elementor-pro' ),
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'button_hover_typo',
					'label' => esc_html__( 'Typography', 'wbc907-core' ),
					'selector' => '{{WRAPPER}} .btn-primary:hover',
				]
			);

			$this->add_control(
				'button_hover_color',
				[
					'label' => esc_html__( 'Text Color', 'wbc907-core' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .btn-primary:hover' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'button_text_bg_hover_color',
				[
					'label' => esc_html__( 'Background Color', 'wbc907-core' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .btn-primary:hover' => 'background-color: {{VALUE}};border-color: {{VALUE}};',
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
					'{{WRAPPER}} .btn-primary' => 'border-radius: {{SIZE}}{{UNIT}};',
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
					    '{{WRAPPER}} .btn-primary' => 'padding: {{top}}{{unit}} {{right}}{{unit}} {{bottom}}{{unit}} {{left}}{{unit}};',
				    ],
			    ]
		    );

			//END BUTTON

	        $this->end_controls_section();

	}

	protected function render($instance = []) {
		$atts = $this->get_settings_for_display();

		if( isset( $atts['blog_cats'] ) && is_array( $atts['blog_cats'] ) ){
			if( count( $atts['blog_cats'] ) > 0 ){
				$atts['blog_cats'] = join(',',$atts['blog_cats']);
			}else{
				unset( $atts['blog_cats'] );
			}
		}


  		include WBC_INCLUDES_DIRECTORY.'elementor/includes/templates/blog-posts/index.php';
			
	}

	protected function content_template() {}	

}
	\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \WBC_Blog_Posts() );
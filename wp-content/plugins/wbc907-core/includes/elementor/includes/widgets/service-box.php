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

class WBC_Service_Box extends Widget_Base {

	public function get_name() {
        return 'wbc_service_box';
    }
    
    public function get_title() {
        return esc_html__( 'Service Box', 'wbc907-core' );
    }

    public function get_icon() {
        return 'eicon-icon-box wbc-icon-widget';
    }
    
    public function get_categories() {
        return [ 'wbc-ninezeroseven' ];
    }

    public function get_keywords() {
		return [ '907', 'image','icon box','services'];
	}


	protected function _register_controls() {
        // Start Image        
			$this->start_controls_section(
				'section_icon',
				[
					'label' => esc_html__( 'Icon', 'elementor' ),
				]
			);

		$this->add_control(
			'icon_type',
			[
				'label' => esc_html__( 'Icon Type', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'icon' => esc_html__( 'Icon', 'elementor' ),
					'image' => esc_html__( 'Image', 'elementor' ),
				],
				'default' => 'icon',
			]
		);

		$this->add_control(
			'selected_icon',
			[
				'label' => esc_html__( 'Icon', 'elementor' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'default' => [
					'value' => 'fas fa-star',
					'library' => 'fa-solid',
				],
				'condition' => [
					// 'view!' => 'default',
					'icon_type[value]!' => 'image',
				],
			]
		);


		$this->add_control(
			'image',
			[
				'label' => esc_html__( 'Choose Image', 'elementor' ),
				'type' => Controls_Manager::MEDIA,
				'dynamic' => [
					'active' => true,
				],
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'condition' => [
					// 'view!' => 'default',
					'icon_type[value]' => 'image',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
				'default' => 'large',
				'separator' => 'none',
				'condition' => [
					// 'view!' => 'default',
					'icon_type[value]' => 'image',
				],
				'separator' => 'after',
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => __( 'Icon Size', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [],
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 400,
					],
				],
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}} .wbc-icon-box .wbc-icon-wrapper .wbc-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
			);

		$this->add_control(
			'icon_align',
			[
				'label' => esc_html__( 'Icon Align', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'default'     => esc_html__( 'Default', 'elementor' ),
					'icon-center' => esc_html__( 'Icon Center', 'elementor' ),
					'icon-left'   => esc_html__( 'Icon Left', 'elementor' ),
					'icon-right'  => esc_html__( 'Icon Right', 'elementor' ),
					'icon-left-wrap'  => esc_html__( 'Icon Wrap Left', 'elementor' ),
					'icon-right-wrap'  => esc_html__( 'Icon Wrap Right', 'elementor' ),
				],
				'default' => 'icon-center',
			]
		);

		$this->add_control(
			'icon_shape',
			[
				'label' => esc_html__( 'Icon Shape', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'default'     => esc_html__( 'Default', 'elementor' ),
					'icon-circle' => esc_html__( 'Circle', 'elementor' ),
				],
				'default' => 'default',
			]
		);

		$this->add_control(
			'icon_style',
			[
				'label' => esc_html__( 'Icon Style', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'default' => esc_html__( 'Default', 'elementor' ),
					'style-1' => esc_html__( 'Style One', 'elementor' ),
					'style-2' => esc_html__( 'Style Two', 'elementor' ),
					'style-3' => esc_html__( 'Style Three', 'elementor' ),
					'style-4' => esc_html__( 'Style Four', 'elementor' ),
				],
				'default' => 'default',
			]
		);

		$this->add_control(
			'title_text',
			[
				'label' => esc_html__( 'Title & Description', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'This is the heading', 'elementor' ),
				'placeholder' => esc_html__( 'Enter your title', 'elementor' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'sub_text',
			[
				'label' => esc_html__( 'Sub Heading', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => '',
				'placeholder' => esc_html__( 'Subheading', 'elementor' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'description_text',
			[
				'label' => '',
				'type' => Controls_Manager::WYSIWYG,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'elementor' ),
				'placeholder' => esc_html__( 'Enter your description', 'elementor' ),
				'separator' => 'none',
				'show_label' => false,
			]
		);

		$this->add_control(
			'link',
			[
				'label' => esc_html__( 'Link', 'elementor' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://your-link.com', 'elementor' ),
				'separator' => 'before',
			]
		);

			$this->end_controls_section();


			$this->start_controls_section(
				'section_style_icon',
				[
					'label' => esc_html__( 'Icon', 'elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);

			$this->start_controls_tabs( 'thumbnail_effects_tabs' );

			$this->start_controls_tab( 'normal',
				[
					'label' => __( 'Normal', 'elementor-pro' ),
				]
			);

			$this->add_control(
				'icon_color',
				[
					'label' => esc_html__( 'Icon Color', 'wbc907-core' ),
					'type' => Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .wbc-icon-wrapper .wbc-icon' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'icon_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'wbc907-core' ),
					'type' => Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .wbc-icon-wrapper .wbc-icon' => 'background-color: {{VALUE}}',
						'{{WRAPPER}} .wbc-icon-wrapper.wbc-icon-style-2,{{WRAPPER}} .wbc-icon-wrapper.wbc-icon-style-3,{{WRAPPER}} .wbc-icon-wrapper.wbc-icon-style-4' => 'border-color: {{VALUE}}',
						
					],
				]
			);

			$this->end_controls_tab();

			$this->start_controls_tab( 'hover',
				[
					'label' => __( 'Hover', 'elementor-pro' ),
				]
			);

			$this->add_control(
				'icon_hover_color',
				[
					'label' => esc_html__( 'Icon Color', 'wbc907-core' ),
					'type' => Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .wbc-icon-box:hover .wbc-icon-wrapper .wbc-icon' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_control(
				'icon_bg_hover_color',
				[
					'label' => esc_html__( 'Background Color', 'wbc907-core' ),
					'type' => Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .wbc-icon-box:hover .wbc-icon-wrapper .wbc-icon' => 'background-color: {{VALUE}}',
						'{{WRAPPER}} .wbc-icon-box:hover .wbc-icon-wrapper.wbc-icon-style-2,{{WRAPPER}} .wbc-icon-box:hover .wbc-icon-wrapper.wbc-icon-style-3,{{WRAPPER}} .wbc-icon-box:hover .wbc-icon-wrapper.wbc-icon-style-4' => 'border-color: {{VALUE}}',
					],
				]
			);

			$this->end_controls_tab();

			$this->end_controls_tabs();

			$this->add_responsive_control(
			'icon_margin_bottom',
			[
				'label' => __( 'Margin Bottom', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [],
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 400,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wbc-icon-box .wbc-icon-wrapper' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
			);

			$this->add_responsive_control(
			'icon_border_radius',
			[
				'label' => __( 'Border Radius', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [],
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 400,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wbc-icon-box .wbc-icon-wrapper' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
			]
			);

			$this->add_responsive_control(
			'icon_border_width',
			[
				'label' => __( 'Border Width', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [],
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 400,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wbc-icon-box .wbc-icon-wrapper' => 'border-width: {{SIZE}}{{UNIT}};',
				],
			]
			);

			$this->add_responsive_control(
			'icon_border_spacing',
			[
				'label' => __( 'Border Padding', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [],
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 400,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wbc-icon-box .wbc-icon-wrapper' => 'padding: {{SIZE}}{{UNIT}};',
				],
			]
			);

			$this->end_controls_section();

			
			$this->start_controls_section(
				'section_style_content',
				[
					'label' => esc_html__( 'Content', 'elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);


			$this->add_control(
			'heading_title',
				[
					'label' => esc_html__( 'Title', 'elementor' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_responsive_control(
			'title_spacing',
			[
				'label' => __( 'Spacing', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [],
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 400,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wbc-icon-box h4' => 'padding-bottom: {{SIZE}}{{UNIT}};',
				],
			]
			);

			$this->add_control(
				'title_color',
				[
					'label' => esc_html__( 'Color', 'wbc907-core' ),
					'type' => Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .wbc-icon-box h4' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'heading_typography',
					'label' => esc_html__( 'Typography', 'wbc907-core' ),
					'selector' => '{{WRAPPER}} .wbc-icon-box h4',
				]
			);

			$this->add_control(
				'pipe_title_color',
				[
					'label' => esc_html__( 'WBC Color', 'wbc907-core' ),
					'type' => Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .wbc-icon-box .wbc-color' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_control(
			'content_title',
				[
					'label' => esc_html__( 'Content', 'elementor' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_control(
				'content_color',
				[
					'label' => esc_html__( 'Color', 'wbc907-core' ),
					'type' => Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .wbc-icon-box .wbc-box-content' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'content_typography',
					'label' => esc_html__( 'Typography', 'wbc907-core' ),
					'selector' => '{{WRAPPER}} .wbc-icon-box .wbc-box-content',
				]
			);



			$this->end_controls_section();
	}

	protected function render($instance = []) {
		$settings = $this->get_settings_for_display();
		$this->add_render_attribute( 'wbc-icon-box', 'class', 'wbc-icon-box' );
		$this->add_render_attribute( 'wbc-icon-box', 'class', $settings['icon_align'] );
		?>

		<div <?php $this->print_render_attribute_string( 'wbc-icon-box' ); ?>>
			<?php 
			if ( ! empty( $settings['link']['url'] ) ) {
				$title = __("Click Here",'ninezeroseven');
				$this->add_link_attributes( 'url', $settings['link'] );
				$this->add_render_attribute( 'url', 'class', 'wbc-link-box' );

				$title = sprintf( '<a %1$s>%2$s</a>', $this->get_render_attribute_string( 'url' ), $title );
				echo $title;
			}
			$heading = '';
			if( !empty( $settings['title_text'] ) ){
			 $heading = $this->pipe_to_color( $this->get_settings_for_display( 'title_text' ) );
			}
			if( !empty( $settings['sub_text'] ) ){
				$heading = $heading.'<span class="box-sub-heading">'.$this->get_settings_for_display( 'sub_text' ).'</span>';
			}

			$html = '';
			$icon_markup = $this->get_icon_markup( $settings );
			if($settings['icon_align'] === 'icon-left-wrap'){
				$html .= '<div class="wbc-icon-header-wrap clearfix">';
				$html .= $icon_markup;
				if(isset($heading) && !empty($heading) ) $html .= '<h4>'.do_shortcode( trim( $heading ) ).'</h4>';
				$html .= '</div>';
			}elseif( $settings['icon_align'] === 'icon-right-wrap' ){
				$html .= '<div class="wbc-icon-header-wrap clearfix">';
				if(isset($heading) && !empty($heading) ) $html .= '<h4>'.do_shortcode( trim( $heading ) ).'</h4>';
				$html .= $icon_markup;
				$html .= '</div>';
			}else{
				$html .= $icon_markup;
			}

			echo $html;
			?>
			<div class="wbc-box-content">
				<?php
					if( $settings['icon_align'] !== 'icon-right-wrap' && $settings['icon_align'] !== 'icon-left-wrap'  ){
						echo '<h4>'.$heading.'</h4>';
					}
					
					$editor_content = $this->get_settings_for_display( 'description_text' );
					$editor_content = $this->parse_text_editor( $editor_content );
					echo $editor_content;
				?>
				
			</div>
		</div> <!-- END ICONBOX -->
<?php
		// echo $html;
	}

	public function pipe_to_color( $content ){
		if( empty( $content ) ){
			return $content;
		}

		$wbc_color ='';
		$wbc_color_before = ( !empty( $wbc_color ) ) ? '[wbc_color color="'.$wbc_color.'"]' : '[wbc_color]';
		$wbc_color_after = '[/wbc_color]';

		if ( preg_match_all( "/\|([^\|]+)\|/", $content, $matches, PREG_SET_ORDER ) !== false ) {

			foreach ( $matches as $key => $value ) {
				if ( !empty( $matches[$key][0] ) && !empty( $matches[$key][1] ) ) {
					$content = str_replace( $matches[$key][0], $wbc_color_before.$matches[$key][1].$wbc_color_after, $content );
				}
			}
		}
	
		return do_shortcode( $content );

	}

	public function get_icon_markup( $settings ){
		$this->add_render_attribute( 'wbc-icon-wrapper', 'class', 'wbc-icon-wrapper' );
		$this->add_render_attribute( 'wbc-icon-wrapper', 'class', 'wbc-icon-'.$settings['icon_style'] );
		$this->add_render_attribute( 'wbc-icon-wrapper', 'class', $settings['icon_shape'] );

		if( $settings['icon_type'] == 'image' ){
			$this->add_render_attribute( 'wbc-icon-wrapper', 'class', 'icon-img' );
		}
		// print_r($settings['image'])
		$markup = '';
		$markup .='<div '.$this->get_render_attribute_string( 'wbc-icon-wrapper' ).'>';
		$markup .='<span class="wbc-icon">';
		if( $settings['icon_type'] == 'image' &&  !empty( $settings['image']['url'] ) ){
			$markup .= $image_html = wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail', 'image' ) );
		}else{
			$this->add_render_attribute( 'i', 'class', $settings['selected_icon'] );
			$this->add_render_attribute( 'i', 'class', 'wbc-font-icon');
			$this->add_render_attribute( 'i', 'aria-hidden', 'true' );
			$markup .='<i '.$this->get_render_attribute_string( 'i' ).'></i>';
		}
		
		
		$markup .='</span></div>';
		
		return $markup;
	}

	protected function content_template() {}	

}
	\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \WBC_Service_Box() );
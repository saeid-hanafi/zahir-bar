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

class WBC_Team_Member extends Widget_Base {

	public function get_name() {
        return 'wbc_team_member';
    }
    
    public function get_title() {
        return esc_html__( 'Team Member', 'wbc907-core' );
    }

    public function get_icon() {
        return 'eicon-person wbc-icon-widget';
    }
    
    public function get_categories() {
        return [ 'wbc-ninezeroseven' ];
    }

    public function get_keywords() {
		return [ '907', 'image','team','member','about','user'];
	}


	protected function _register_controls() {
        // Start Image        
			$this->start_controls_section(
				'team_image',
				[
					'label' => esc_html__( 'Image + Info', 'elementor' ),
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
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
				'default' => 'large',
				'separator' => 'none',
				'separator' => 'after',
			]
		);

		$this->add_control(
			'name',
			[
				'label' => esc_html__( 'Name', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'James |Wilson|', 'elementor' ),
				'placeholder' => esc_html__( 'Enter your name', 'elementor' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'job',
			[
				'label' => esc_html__( 'Job Title', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => 'CEO',
				'placeholder' => esc_html__( 'CEO', 'elementor' ),
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

			$this->end_controls_section();
			

			//SOCIAL
			$this->start_controls_section( 
				'social_section',
	            [
	                'label' => esc_html__('Social Links', 'wbc907-core'),
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
					'{{WRAPPER}} .wbc-team-box .wbc-icon-wrapper .wbc-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
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
			$repeater = new Repeater();
			
			$repeater->add_control(
				'selected_icon',
				[
					'label' => esc_html__( 'Icon', 'elementor' ),
					'type' => Controls_Manager::ICONS,
					'fa4compatibility' => 'icon',
					'default' => [
						'value' => 'fas fa-star',
						'library' => 'fa-solid',
					],
				]
			);
			$repeater->start_controls_tabs( 'icon_color_selections' );
			$repeater->start_controls_tab( 'normal',
				[
					'label' => __( 'Normal', 'elementor-pro' ),
				]
			);
			$repeater->add_control(
				'icon_color',
				[
					'label' => esc_html__( 'Icon Color', 'wbc907-core' ),
					'type' => Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .wbc-icon-wrapper{{CURRENT_ITEM}} .wbc-icon' => 'color: {{VALUE}}',
					],
				]
			);
			$repeater->add_control(
				'icon_bg_color',
				[
					'label' => esc_html__( 'Background Color', 'wbc907-core' ),
					'type' => Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .wbc-icon-wrapper{{CURRENT_ITEM}} .wbc-icon' => 'background-color: {{VALUE}}',
						'{{WRAPPER}} .wbc-icon-wrapper{{CURRENT_ITEM}}.wbc-icon-style-2,{{WRAPPER}} .wbc-icon-wrapper{{CURRENT_ITEM}}.wbc-icon-style-3,{{WRAPPER}} .wbc-icon-wrapper{{CURRENT_ITEM}}.wbc-icon-style-4' => 'border-color: {{VALUE}}',
						
					],
				]
			);
			$repeater->end_controls_tab();

			$repeater->start_controls_tab( 'hover',
				[
					'label' => __( 'Hover', 'elementor-pro' ),
				]
			);

			$repeater->add_control(
				'icon_hover_color',
				[
					'label' => esc_html__( 'Icon Color', 'wbc907-core' ),
					'type' => Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .wbc-icon-wrapper{{CURRENT_ITEM}}:hover .wbc-icon' => 'color: {{VALUE}}',
					],
				]
			);

			$repeater->add_control(
				'icon_bg_hover_color',
				[
					'label' => esc_html__( 'Background Color', 'wbc907-core' ),
					'type' => Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .wbc-icon-wrapper{{CURRENT_ITEM}}:hover .wbc-icon' => 'background-color: {{VALUE}}',
						'{{WRAPPER}} .wbc-icon-wrapper{{CURRENT_ITEM}}:hover.wbc-icon-style-2,{{WRAPPER}} .wbc-icon-wrapper{{CURRENT_ITEM}}:hover.wbc-icon-style-3,{{WRAPPER}} .wbc-icon-wrapper{{CURRENT_ITEM}}:hover.wbc-icon-style-4' => 'border-color: {{VALUE}}',
					],
				]
			);

			$repeater->end_controls_tab();

			$repeater->end_controls_tabs();

			$repeater->add_control(
				'link',
				[
					'label' => esc_html__( 'Link', 'elementor' ),
					'type' => Controls_Manager::URL,
					'dynamic' => [
						'active' => true,
					],
					'default' => [
						'url' => '#',
					],
					'placeholder' => esc_html__( 'https://your-link.com', 'elementor' ),
					'separator' => 'before',
				]
			);
			
			$this->add_control(
				'network_icons',
				[
					'label' => __( 'Icons', 'elementor-pro' ),
					'type' => Controls_Manager::REPEATER,
					'fields' => $repeater->get_controls(),
					'title_field' => '{{{ elementor.helpers.renderIcon( this, selected_icon, {}, "i", "panel" )}}}',
					// 'default' => $this->get_repeater_defaults(),
					'separator' => 'after',
				]
			);

			
			$this->end_controls_section();

			//

			$this->start_controls_section(
				'section_style_icon',
				[
					'label' => esc_html__( 'Icon', 'elementor' ),
					'tab' => Controls_Manager::TAB_STYLE,
				]
			);

			$this->add_responsive_control(
			    'icon_spacing',
			    [
				    'label' => esc_html__( 'Spacing', 'wbc907-core' ),
				    'type' => Controls_Manager::DIMENSIONS,
				    'size_units' => [ 'px', '%', 'em' ],
				    'selectors' => [
					    '{{WRAPPER}} .wbc-team-box .wbc-icon-wrapper' => 'margin: {{top}}{{unit}} {{right}}{{unit}} {{bottom}}{{unit}} {{left}}{{unit}};',
						'{{WRAPPER}} .wbc-team-box .team-icons' => 'margin-left: -{{left}}{{unit}}; margin-right:-{{right}}{{unit}};',
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
					'{{WRAPPER}} .wbc-team-box .wbc-icon-wrapper' => 'border-radius: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .wbc-team-box .wbc-icon-wrapper' => 'border-width: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .wbc-team-box .wbc-icon-wrapper' => 'padding: {{SIZE}}{{UNIT}};',
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
			'name_title',
				[
					'label' => esc_html__( 'Name', 'elementor' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_responsive_control(
			'name_spacing',
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
					'{{WRAPPER}} .wbc-team-box .team-name h5' => 'padding-bottom: {{SIZE}}{{UNIT}};',
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
						'{{WRAPPER}} .wbc-team-box h5' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'heading_typography',
					'label' => esc_html__( 'Typography', 'wbc907-core' ),
					'selector' => '{{WRAPPER}} .wbc-team-box h5',
				]
			);

			$this->add_control(
				'pipe_title_color',
				[
					'label' => esc_html__( 'WBC Color', 'wbc907-core' ),
					'type' => Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .wbc-team-box .wbc-color' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_control(
			'job_setting',
				[
					'label' => esc_html__( 'Job Position', 'elementor' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);
			$this->add_responsive_control(
			'job_spacing',
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
					'{{WRAPPER}} .wbc-team-box .team-name' => 'padding-bottom: {{SIZE}}{{UNIT}};',
				],
			]
			);

			$this->add_control(
				'job_color',
				[
					'label' => esc_html__( 'Color', 'wbc907-core' ),
					'type' => Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .wbc-team-box .team-name > span' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'job_typography',
					'label' => esc_html__( 'Typography', 'wbc907-core' ),
					'selector' => '{{WRAPPER}} .wbc-team-box .team-name > span',
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

			$this->add_responsive_control(
			'content_spacing',
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
					'{{WRAPPER}} .wbc-team-box .member-details' => 'padding-bottom: {{SIZE}}{{UNIT}};',
				],
			]
			);

			$this->add_control(
				'content_color',
				[
					'label' => esc_html__( 'Color', 'wbc907-core' ),
					'type' => Controls_Manager::COLOR,
					'default' => '',
					'selectors' => [
						'{{WRAPPER}} .wbc-team-box .member-details' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' => 'content_typography',
					'label' => esc_html__( 'Typography', 'wbc907-core' ),
					'selector' => '{{WRAPPER}} .wbc-team-box .member-details',
				]
			);



			$this->end_controls_section();
	}

	protected function render($instance = []) {
		$settings = $this->get_settings_for_display();
		$this->add_render_attribute( 'wbc-team-member', 'class', 'wbc-team-box' );
		?>

		<div class="wbc-team-box clearfix">
		<?php
			echo wp_kses_post( Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail', 'image' ) );
		?>
		<div class="wbc-team-content">
			<div class="team-name">
				<?php
					if( !empty( $settings['name'] ) ){
						echo '<h5>'.$this->pipe_to_color( $this->get_settings_for_display( 'name' ) ).'</h5>';
					}

					if( !empty( $settings['job'] ) ){
						echo '<span>'.$this->pipe_to_color( $this->get_settings_for_display( 'job' ) ).'</span>';
					}
				?>
			</div>
			<?php
			if( !empty( $settings['description_text'] ) ){
			?>
				<div class="member-details">
					<?php echo $this->get_settings_for_display( 'description_text' );?>
				</div>
			<?php
			}
			if(isset($settings['network_icons']) && is_array( $settings['network_icons'] ) && count($settings['network_icons']) > 0)
				{
					$icons = '';
					foreach($settings['network_icons'] as $index => $icon ){
						$icons.= $this->get_icon_markup($index, $icon, $settings);
					}

					echo '<div class="team-icons">';
					echo $icons;
					echo '</div>';
				}
			?>
		</div>
	</div>
<?php
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

	public function get_icon_markup( $index, $icons, $settings ){
		$this->add_render_attribute( 'wbc-icon-wrapper_'.$index, 'class', 'wbc-icon-wrapper' );
		$this->add_render_attribute( 'wbc-icon-wrapper_'.$index, 'class', 'wbc-icon-'.$settings['icon_style'] );
		$this->add_render_attribute( 'wbc-icon-wrapper_'.$index, 'class', 'elementor-repeater-item-'.$icons['_id'] );
		$this->add_render_attribute( 'wbc-icon-wrapper_'.$index, 'class', $settings['icon_shape'] );

		$this->add_link_attributes( 'wbc-icon-link_'.$index, $icons['link'] );
		$markup = '';
		$markup .='<a  '.$this->get_render_attribute_string( 'wbc-icon-link_'.$index ).'>';
		$markup .='<div '.$this->get_render_attribute_string( 'wbc-icon-wrapper_'.$index ).'>';
		
		$markup .='<span class="wbc-icon">';
		$this->add_render_attribute( 'i_'.$index, 'class', $icons['selected_icon'] );
		$this->add_render_attribute( 'i_'.$index, 'class', 'wbc-font-icon');
		$this->add_render_attribute( 'i_'.$index, 'aria-hidden', 'true' );
		$markup .='<i '.$this->get_render_attribute_string( 'i_'.$index ).'></i>';
		$markup .='</span></div></a>';
		
		return $markup;
	}

	protected function content_template() {}	

}
	\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \WBC_Team_Member() );
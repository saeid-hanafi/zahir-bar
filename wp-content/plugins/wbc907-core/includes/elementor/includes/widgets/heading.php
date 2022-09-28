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

class WBC_Heading extends Widget_Base {

	public function get_name() {
        return 'wbc_heading';
    }
    
    public function get_title() {
        return esc_html__( 'WBC Heading', 'wbc907-core' );
    }

    public function get_icon() {
        return 'eicon-t-letter wbc-icon-widget';
    }
    
    public function get_categories() {
        return [ 'wbc-ninezeroseven' ];
    }

    public function get_keywords() {
		return [ '907', 'text','title','heading'];
	}

	protected function _register_controls() {
        $this->start_controls_section(
			'section_title',
			[
				'label' => esc_html__( 'Title', 'elementor' ),
			]
		);

		$this->add_control(
			'title',
			[
				'label' => esc_html__( 'Title', 'elementor' ),
				'type' => Controls_Manager::TEXTAREA,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Enter your title', 'elementor' ),
				'default' => esc_html__( 'Add Your Heading Text Here', 'elementor' ),
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
				'default' => [
					'url' => '',
				],
				'separator' => 'before',
			]
		);

		$this->add_control(
			'style',
			[
				'label' => esc_html__( 'Style', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default' => esc_html__( 'Default', 'elementor' ),
					'heading-1' => esc_html__( 'Style 1', 'elementor' ),
					'heading-2' => esc_html__( 'Style 2', 'elementor' ),
					'heading-3' => esc_html__( 'Style 3', 'elementor' ),
					'heading-4' => esc_html__( 'Style 4', 'elementor' )
				],
			]
		);

		$this->add_control(
			'header_size',
			[
				'label' => esc_html__( 'HTML Tag', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'p' => 'p',
				],
				'default' => 'h2',
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => esc_html__( 'Alignment', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'elementor' ),
						'icon' => 'eicon-text-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Justified', 'elementor' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
		$this->start_controls_section(
			'section_title_style',
			[
				'label' => esc_html__( 'Title', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Text Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wbc-heading .wbc-heading-widget' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'typography',
				'selector' => '{{WRAPPER}} .wbc-heading .wbc-heading-widget',
			]
		);

		$this->add_control(
			'pipe_color',
			[
				'label' => esc_html__( 'WBC Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wbc-heading .wbc-heading-widget .wbc-color' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'pipe_typography',
				'label' => esc_html__( 'WBC Color Typography', 'wbc907-core' ),
				'selector' => '{{WRAPPER}} .wbc-heading .wbc-heading-widget .wbc-color',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
			[
				'name' => 'text_shadow',
				'selector' => '{{WRAPPER}} .wbc-heading .wbc-heading-widget',
			]
		);

		$this->add_control(
			'blend_mode',
			[
				'label' => esc_html__( 'Blend Mode', 'elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'Normal', 'elementor' ),
					'multiply' => 'Multiply',
					'screen' => 'Screen',
					'overlay' => 'Overlay',
					'darken' => 'Darken',
					'lighten' => 'Lighten',
					'color-dodge' => 'Color Dodge',
					'saturation' => 'Saturation',
					'color' => 'Color',
					'difference' => 'Difference',
					'exclusion' => 'Exclusion',
					'hue' => 'Hue',
					'luminosity' => 'Luminosity',
				],
				'selectors' => [
					'{{WRAPPER}} .wbc-heading .wbc-heading-widget' => 'mix-blend-mode: {{VALUE}}',
				],
				'separator' => 'none',
			]
		);

		$this->end_controls_section();
	}

	protected function render($instance = []) {
		$settings = $this->get_settings_for_display();

		if( empty($settings['title']) ) return;

		$title = str_replace('{{PAGE_TITLE}}', get_the_title( get_the_id() ), $settings['title']);

		$title = $this->pipe_to_color($title);

		$tag = ( !empty( $settings['header_size'] ) ) ? $settings['header_size'] : 'h3';
		
		$heading_class = ( !empty( $settings['style'] )  && $settings['style'] !== 'default') ? 'special-'.$settings['style'] : 'default-heading';

		$this->add_render_attribute( 'wbc-heading', 'class', $heading_class );
		$this->add_render_attribute( 'wbc-heading', 'class', 'wbc-heading-widget' );

		$link = $this->get_link_url( $settings );
		if ( $link ) {
			$this->add_link_attributes( 'link', $link );
		}
		?>
			<div class="wbc-heading clearfix">
				<?php if ( $link ) : ?>
				<a <?php $this->print_render_attribute_string( 'link' ); ?>>
				<?php endif;?>
				<?php echo '<'.$tag.' '.$this->get_render_attribute_string( 'wbc-heading').'>'.$title.'</'.$tag.'>';?>
				<?php if ( $link ) : ?>
				</a>
				<?php endif;?>
			</div>
		<?php
		
	}
	
	private function get_link_url( $settings ) {
		if ( empty( $settings['link']['url'] ) ) {
				return false;
			}

			return $settings['link'];
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

	protected function content_template() {}	

}
	\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \WBC_Heading() );
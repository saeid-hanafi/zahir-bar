<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 *  WBC907_Elementor_Extend - WBC907 Theme
 *  @author  Webcreations907
 *
 */
if ( !class_exists( 'WBC907_Elementor_Extend' ) ) {
	class WBC907_Elementor_Extend {

		/**
		 * Instance of this class.
		 *
		 * @since    1.0.0
		 *
		 * @var      object
		 */
		protected static $instance = null;


		/**
		 * Fire it up
		 *
		 * @since  1.0.0
		 */
		public function __construct() {

		}


		public function init() {
			add_action( 'wp_enqueue_scripts', [ $this , 'enqueue_scripts'],22);
			add_action( 'elementor/element/common/_section_position/before_section_end', [ $this , 'common_controls'] );
			add_action( 'elementor/element/section/section_background/before_section_end', [ $this , 'section_controls'] );
			add_action( 'elementor/frontend/section/before_render', [ $this , 'before_render'] );
			add_action( 'elementor/section/print_template', [ $this , 'print_template'],10, 2);
			
			//Button
			add_action( 'elementor/element/button/section_style/before_section_end', [ $this , 'button_controls'] );
		}

		public function enqueue_scripts(){
			if(\Elementor\Plugin::$instance->preview->is_preview_mode()){
				wp_enqueue_script( 'wbc-froogaloop' );
			}
			
		}


		public function button_controls( $element ){
			$element->start_injection( [
				'at' => 'after',
				'of' => 'text_padding',
			] );

			$element->add_responsive_control(
				'_button_custom_width',
				[
					'label' => __( 'Custom Width', 'elementor' ),
					'type' => \Elementor\Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'max' => 1000,
							'step' => 1,
						],
						'%' => [
							'max' => 100,
							'step' => 1,
						],
					],
					'size_units' => [ 'px', '%', 'vw' ],
					'selectors' => [
						'{{WRAPPER}} .elementor-button' => 'width: 100%; max-width: {{SIZE}}{{UNIT}}',
					],
				]
			);

			$element->end_injection();
		}


		public function print_template( $template, $widget ){

			if ( 'section' === $widget->get_name() ) {
				$template_preceding = "
		            <# if( settings.wbc_parallax ){
		            	let parallaxSpeed = settings.wbc_parallax_speed.size !== '' ? settings.wbc_parallax_speed.size : '0.3'; #>
				        <span class='editor-wbc-parallax-speed' data-speed='{{settings.wbc_parallax_speed.size}}'></span>
				    <# } #>";
		            $template = $template_preceding . " " . $template;
			}
			return $template;
		}



		public function before_render( $element ){
			if( 'section' !== $element->get_name() ) {
		            return;
		        }
	        $settings = $element->get_settings_for_display();

	        if( $settings['wbc_parallax'] == 'parallax-section' ){
	       		$element->add_render_attribute( '_wrapper', 'class','parallax-section');
	       		$speed = ! empty ( $settings['wbc_parallax_speed']['size'] ) ? $settings['wbc_parallax_speed']['size'] : 0.4;
	       		$element->add_render_attribute( '_wrapper', 'data-parallax-speed', $speed );
	        }	
		}


		public function common_controls( $element ){
			$element->start_injection( [
				'at' => 'before',
				'of' => '_element_vertical_align',
			] );
			$element->add_responsive_control(
					'wbc-align-box',
					[
						'label' => __( 'Align Box', 'elementor' ),
						'type' => \Elementor\Controls_Manager::CHOOSE,
						'condition' => [
							'_element_width' => 'initial',
						]
						// ,
						// 'device_args' => [
						// 	\Elementor\Controls_Stack::RESPONSIVE_TABLET => [
						// 		'condition' => [
						// 			'_element_width_tablet' => [ 'initial' ],
						// 		],
						// 	],
						// 	\Elementor\Controls_Stack::RESPONSIVE_MOBILE => [
						// 		'condition' => [
						// 			'_element_width_mobile' => [ 'initial' ],
						// 		],
						// 	],
						// ]
						,
						'options' => [
							'left' => [
								'title' => __( 'Left', 'elementor' ),
								'icon' => 'eicon-h-align-left',
							],
							'center' => [
								'title' => __( 'Center', 'elementor' ),
								'icon' => 'eicon-h-align-center',
							],
							'right' => [
								'title' => __( 'Right', 'elementor' ),
								'icon' => 'eicon-h-align-right',
							],
						],
						'selectors' => [
							'{{WRAPPER}}' => '{{VALUE}}',
						],
						'selectors_dictionary' => [
							'center' => 'margin-left:auto;margin-right:auto;',
							'left'   => 'margin-right:auto;margin-left:0;',
							'right'  => 'margin-left:auto;margin-right:0;',
						],
					]
				);

			$element->end_injection();
		}


		public function section_controls( $element ){
			$element->start_injection( [
				'at' => 'after',
				'of' => 'background_bg_width',
			] );
			$element->add_control(
					'wbc_parallax',
					[
						'label' => __( 'Enable 907 Parallax', 'elementor' ),
						'type' => \Elementor\Controls_Manager::SWITCHER,
						'default' => '',
						'prefix_class' => '',
						'separator' => 'before',
						'return_value' => 'parallax-section',
						'label_off' => __( 'No', 'elementor-pro' ),
						'label_on' => __( 'Yes', 'elementor-pro' ),
						'frontend_available' => true,
						'render_type' =>'ui',
						'conditions' => [
							'relation' => 'and',
							'terms' => [
								[
									'name' => 'background_background',
									'value' => 'classic',
								],
								[
									'name' => 'background_image[url]',
									'operator' => '!=',
									'value' => '',
								],
							],
						],
					]
				);

			$element->add_control(
					'wbc_parallax_speed',
					[
						'label' => __( 'Speed', 'elementor-pro' ),
						'type' => \Elementor\Controls_Manager::SLIDER,
						'default' => [
							'size' => 0.3,
						],
						'range' => [
							'px' => [
								'max' => 1.5,
								'step' => 0.1,
							],
						],
						'condition' => [
							'wbc_parallax' => 'parallax-section',
							],
						'separator' => 'after',
		 				'frontend_available' => true,
					]
				);

			$element->end_injection();
		}

		/**
		 * Return an instance of this class.
		 *
		 * @since     1.0.0
		 *
		 * @return    object    A single instance of this class.
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;
		}

	}

	WBC907_Elementor_Extend::get_instance()->init();
}
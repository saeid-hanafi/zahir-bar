<?php
/************************************************************************
* WooCommerce Shortcode Extend
*************************************************************************/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( !class_exists( 'WBC907_WooCommerce_Shortcodes' ) ) {
	class WBC907_WooCommerce_Shortcodes {

		/**
		 * 
		 * Start it up!
		 * 
		 */
		public static function init() {
			add_action( 'pre_get_posts', array( __CLASS__, 'add_woocommerce_query_args' ) );
			add_action( 'vc_after_init', array( __CLASS__, 'update_vc_woo_shortcodes' ) );
			add_filter( 'woocommerce_shortcode_products_query', array( __CLASS__, 'shortcode_paged_arg' ), 10, 3 );
			add_filter( 'woocommerce_composite_component_options_query_args', array( __CLASS__, 'composite_component_options_query_args' ), 10, 3 );

			if ( !is_admin() ) {
				add_filter( 'woocommerce_shortcode_products_query', array( __CLASS__, 'get_shortcode_transient' ), 100, 3 );
				self::add_action_filter_shortcodes();
			}


		}

		/**
		 * Frontend: Add the paged param to the shortcode product query.
		 *
		 * @param WP_Query $query
		 */
		public static function add_woocommerce_query_args( $query ) {
			
			global $woocommerce;
			
			if(isset($woocommerce->version) && !empty($woocommerce->version)){
				if (version_compare($woocommerce->version, '3.3.0', '>=')) {
				    return $query;
				}
			}

			$is_product_query = self::is_product_query( $query );

			if ( is_archive() || is_post_type_archive() || ! $is_product_query ) {
				return;
			}

			$paged = self::get_paged_var();

			if ( $query->is_main_query() && $is_product_query ) {
				$GLOBALS['woocommerce_loop']['paged'] = $paged;
			}

			$query->is_paged                    = true;
			$query->query['paged']              = $paged;
			$query->query_vars['paged']         = $paged;
			$query->query['no_found_rows']      = false;
			$query->query_vars['no_found_rows'] = false;
		}

		/**
		 * Adds params to Visual Composer's Woocommerce elements, not since in
		 * add different ones.
		 * added by action vc_after_init
		 */
		public static function update_vc_woo_shortcodes() {


			if (!function_exists('vc_add_params')) {
				return;
			}

			vc_add_param("product",array(
				'type' => 'textfield',
				'heading' => __( 'Columns', 'ninezeroseven' ),
				'value' => 1,
				'param_name' => 'columns',
				'weight' => 1
				)
			);

			$add_params = array(
				array(
					'type' => 'textfield',
					'heading' => __( 'Per page', 'ninezeroseven' ),
					'value' => 12,
					'save_always' => true,
					'param_name' => 'per_page',
					'description' => __( 'The "per_page" shortcode determines how many products to show on the page', 'ninezeroseven' ),
					'weight' => 1
				),
				array(
					'type' => 'checkbox',
					'heading' => esc_html__( 'Paginate?', 'ninezeroseven' ),
					'param_name' => 'paginate',
					'admin_label' => true,
					// 'description' => esc_html__( 'If selected, this will set min-height to browser height.', 'ninezeroseven' ),
					'dependency' => array( 'element' => 'per_page', 'not_empty' => true),
					'value' => array( esc_html__( 'Yes, please', 'ninezeroseven' ) => 'yes' ),
				),
				array(
					'type' => 'checkbox',
					'heading' => esc_html__( 'Ajax Pagination?', 'ninezeroseven' ),
					'param_name' => 'ajaxed',
					'description' => esc_html__( 'This option will load next post without page reload.', 'ninezeroseven' ),
					'value' => array( esc_html__( 'Yes, please', 'ninezeroseven' ) => 'yes' ),
					"dependency" => array('element' => "paginate", 'value' => array('yes')),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Pagination Align', 'ninezeroseven' ),
					'param_name' => 'pagination_align',
					'value' => array(
						esc_html__( 'Default', 'ninezeroseven' ) => '',
						esc_html__( 'Center', 'ninezeroseven' )  =>  'center',
						esc_html__( 'Right', 'ninezeroseven' )   =>  'right',
						esc_html__( 'Left', 'ninezeroseven' )    =>  'left',
					),
					"dependency" => array('element' => "paginate", 'value' => array('yes')),
					'description' => esc_html__( 'Align pagination buttons.', 'ninezeroseven' ),
				),

			);

			$shortcodes = self::get_shortcodes();

			foreach ($shortcodes as $shortcode) {
				vc_add_params($shortcode, $add_params);
			}

		}

		/**
		 * Shortcode products query args.
		 *
		 * @param array   $query_args
		 * @param array   $atts
		 * @param string  $loop_type
		 *
		 * @return array
		 */
		public static function shortcode_paged_arg( $query_args, $atts, $loop_type ) {
			$query_args['paged'] = self::get_paged_var();

			return $query_args;
		}

		/**
		 * Add arg to composite product component.
		 *
		 * @param array   $args
		 * @param array   $query_args
		 * @param array   $component_data
		 *
		 * @return array
		 */
		public static function composite_component_options_query_args( $args, $query_args, $component_data ) {
			$args['composite_component'] = true;

			return $args;
		}

		/**
		 * Get paged var
		 */
		public static function get_paged_var() {
			$query_var = is_front_page() ? 'page' : 'paged';

			return get_query_var( $query_var ) ? get_query_var( $query_var ) : 1;
		}

		/**
		 * Only apply to products/woocommerce
		 * 
		 * @param WP_Query $query
		 *
		 * @return bool
		 */
		public static function is_product_query( $query ) {
			if ( ! isset( $query->query['post_type'] ) || ! empty( $query->query['p'] ) || isset( $query->query['composite_component'] ) ) {
				return false;
			}

			$post_type = $query->query['post_type'];

			if ( is_array( $post_type ) && in_array( 'product', $post_type ) ) {
				return true;
			}

			if ( $post_type == "product" ) {
				return true;
			}

			return false;
		}

		/**
		 * Generate and return the transient name for this shortcode based on the query args.
		 *
		 * @param array   $atts
		 * @param string  $loop_type
		 *
		 * @return string
		 */
		protected static function get_transient_name( $atts, $loop_type ) {
			$transient_name = 'wbc907_woocommerce_query_' . substr( md5( wp_json_encode( $atts ) . $loop_type ), 28 );
			return $transient_name;
		}

		/**
		 * Use the shortcode query args to fetch our product
		 * collection data and cache the results.
		 *
		 * @param array   $query_args
		 * @param array   $atts
		 * @param string  $loop_type
		 *
		 * @return array
		 */
		public static function get_shortcode_transient( $query_args, $atts, $loop_type ) {
			$transient_name = self::get_transient_name( $atts, $loop_type );
			$query_data     = get_transient( $transient_name );



			if ( $query_data === false ) {
				$products   = new WP_Query( $query_args );
				$query_data = array(
					'found_posts'   => $products->found_posts,
					'max_num_pages' => $products->max_num_pages,
					'post_count'    => $products->post_count,
					'current_post'  => $products->current_post,
				);
				set_transient( $transient_name, $query_data, DAY_IN_SECONDS * 30 );
			}

			return $query_args;
		}


		/**
		 * Action and Filters
		 */
		public static function add_action_filter_shortcodes() {
			$shortcodes = self::get_shortcodes();

			foreach ( $shortcodes as $shortcode ) {
				add_filter( 'shortcode_atts_' . $shortcode, array( __CLASS__, 'add_pagination_att' ), 10, 4 );
				add_action( 'woocommerce_shortcode_before_' . $shortcode . '_loop', array( __CLASS__, 'wbc_wrap_woocommerce' ), 10, 4 );
				add_action( 'woocommerce_shortcode_after_' . $shortcode . '_loop', array( __CLASS__, 'add_pagination' ), 10, 4 );
			}
		}

		/**
		 * Wrap WooCommerce Shortcodes
		 * @param  array $atts shortcodes array
		 * @return nothing
		 */
		public static function wbc_wrap_woocommerce( $atts ) {

			global $wbc_woo_sc_count;

			if ( empty( $wbc_woo_sc_count) ) $wbc_woo_sc_count = 1;

			$ex_class = '';
			if (isset($atts['ajaxed']) && !empty($atts['ajaxed']) && $atts['ajaxed']) {
				// remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
				$ex_class = ' ajaxed';
			}

			remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

			echo '<div class="wbc-woocommerce-wrapper'. esc_html( $ex_class ) .'" id="wbc-woo-'.intval($wbc_woo_sc_count).'">';
			echo '<span class="wbc-content-loader"><i class="fa fa-spinner fa-spin"></i></span>';

		}

		/**
		 * Add pagination.
		 *
		 * @param unknown $atts
		 */
		public static function add_pagination( $atts ) {

			if ( empty( $atts['paginate'] ) ) {
				echo '</div>'; //Ends WBC woo wrap
				return;
			}

			global $wbc_woo_sc_count;
			$wbc_woo_sc_count++;

			$loop_type      = str_replace( array( 'woocommerce_shortcode_after_', '_loop' ), '', current_action() );
			$transient_name = self::get_transient_name( $atts, $loop_type );
			$query_data     = get_transient( $transient_name );

			if ( $query_data['max_num_pages'] <= 1 ) {
				return;
			}



			$page_nav_align = (isset($atts['pagination_align']) && !empty($atts['pagination_align'])) ? $atts['pagination_align'] : 'right';
			if ( $page_nav_align == 'left' || $page_nav_align == 'right' || $page_nav_align == 'center' ) {
				$page_nav_align = 'text-'.$page_nav_align;
			}else {
				$page_nav_align = 'text-right';
			}

			$page_links = paginate_links( array(
					'base' => esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) ),
					'format' => '?paged=%#%',
					'current' => max( 1, self::get_paged_var() ),
					'total' => $query_data['max_num_pages'],
					'type' => 'array'
				) );

			if ( is_array( $page_links ) ) {

				echo '<div class="wbc-pagination-woocommerce '.esc_attr( $page_nav_align ).'">';
				echo '<ul data-gallery-id="test" class="wbc-pagination">';

				foreach ( $page_links as $link ) {
					echo '<li>'.$link.'</li>';
				}

				echo '</ul></div>';
			}


			echo '</div>'; //Ends WBC woo wrap
		}

		/**
		 * Add shortcode 'pagination' parameter.
		 *
		 * @param array   $out
		 * @param array   $pairs
		 * @param array   $atts
		 * @param string  $shortcode
		 *
		 * @return mixed
		 */
		public static function add_pagination_att( $out, $pairs, $atts, $shortcode ) {
			$out['paginate']       = empty( $atts['paginate'] ) ? false : wc_string_to_bool( $atts['paginate'] );
			$out['ajaxed']           = empty( $atts['ajaxed'] ) ? false : wc_string_to_bool( $atts['ajaxed'] );
			$out['pagination_align'] = empty( $atts['pagination_align'] ) ? false : $atts['pagination_align'];
			return $out;
		}

		/**
		 * Get shortcodes.
		 *
		 * @return array
		 */
		public static function get_shortcodes() {
			return apply_filters( 'wbc-woocommerce-shortcodes', array(
					'products',
					'product_category',
					'product_categories',
					'recent_products',
					'featured_products',
					'sale_products',
					'best_selling_products',
					'top_rated_products',
					'product_attribute'
				) );
		}

	}

	WBC907_WooCommerce_Shortcodes::init();
}

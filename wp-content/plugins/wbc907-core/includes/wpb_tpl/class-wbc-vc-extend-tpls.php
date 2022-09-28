<?php 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Class WBC907_Vc_Templates_Panel_Editor
 * @since 1.0
 */
class WBC907_Vc_Templates_Panel_Editor {
	/**
	 * @since 4.4
	 * @var bool
	 */
	protected $wbc907_templates = false;
	/**
	 * @since 4.4
	 * Add ajax hooks, filters.
	 */
	protected $initialized = false;

	/**
	 * @since 4.4
	 * Add ajax hooks, filters.
	 */
	public function init() {
		if ( $this->initialized ) {
			return;
		}
		$this->initialized = true;

		add_filter( 'admin_notices', array( $this, 'check_image_urls'), 100);

		add_filter( 'vc_load_default_templates', array( $this, 'load_theme_defaults' ), 200 );	

		add_filter( 'vc_templates_render_category', array(
			$this,
			'renderTemplateBlock',
		), 10 );
		add_filter( 'vc_templates_render_template', array(
			$this,
			'renderTemplateWindow',
		), 10, 2 );

		add_filter( 'vc_get_all_templates', array(
			$this,
			'addTemplatesTab',
		) );
		
		add_action( 'vc_backend_editor_enqueue_js_css', array( $this, 'load_styles_js' ));
		add_action( 'vc_frontend_editor_enqueue_js_css', array( $this, 'load_styles_js' ));

	}

	public function check_image_urls(){
		global $post;
		$current_page = get_current_screen();
		if( is_admin() && isset($current_page->parent_base) && $current_page->parent_base == 'edit' && isset($post) ){

			if(!empty($post->post_content) && preg_match('/webcreations907.com\/ninezeroseven\/([^\/]+)\/wp-content\//', $post->post_content)){
				//webcreations907.com\/ninezeroseven\/([^\/]+)\/wp-content\/.*?(.jpg|.png|.jpeg)
				$class = 'notice notice-error';
				$message = esc_html__( 'Replace "hotlinked" images to your image paths in the editor below, sections are highlighted in red when using WPBakery Page Builder.', 'ninezeroseven' );
			
				printf( '<div class="%1$s" style="margin-top:10px;padding:5px;"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) ); 
			}
		}
	}

	public function load_styles_js(  ){
		$script_vc = 'backend';
		if(isset($_GET['vc_action']) && $_GET['vc_action'] == 'vc_inline'){
			$script_vc = 'frontend';
		}
		wp_enqueue_script( 'wbc907-WPB-tpls-build-js', WBC_INCLUDES_DIRECTORY_URI . 'wpb_tpl/js/wpb-'.$script_vc.'-editor-tpl.js', array( 'jquery' ), WBC907_CORE_PLUGIN_VERSION, true );
		wp_enqueue_style( 'wbc907-WPB-tpls-build-css', WBC_INCLUDES_DIRECTORY_URI . 'wpb_tpl/css/wpb-tpl.css',false, WBC907THEME_VERSION );
	}

	public function load_theme_defaults( $data ){
		return $this->wbc907_vc_templates();
	}


	public function wbc907_vc_templates(){
		if ( ! $this->initialized ) {
			$this->init(); // add hooks if not added already (fix for in frontend)
		}

		if ( ! is_array( $this->wbc907_templates ) ) {
			require_once dirname( __FILE__ ) . '/vc-templates.php';
			$this->wbc907_templates = $templates;
			do_action( 'vc_load_wbc907_templates_action' );
		}

		return apply_filters('wbc907_theme_default_templates', $this->wbc907_templates );
	}

	/**
	 * @param $data
	 *
	 * @return array
	 */
	public function addTemplatesTab( $data ) {
		$newCategory = array(
			'category'        => 'wbc907_templates',
			'category_name'   => esc_html__( '907 Theme Blocks', 'ninezeroseven' ),
			'category_weight' => 1,
			'templates'       => $this->getAllTemplates(),
		);
		$data[] = $newCategory;

		return $data;
	}

	public function getTemplates() {
		
		return $this->wbc907_vc_templates();
	}

	protected function get_template_filter_nav() {

		$output = '';

		$template_filters = array(
			'about'       => esc_html__( 'About', 'ninezeroseven' ),
			'banner'      => esc_html__( 'Banners', 'ninezeroseven' ),
			'blog'        => esc_html__( 'Blogs', 'ninezeroseven'),
			'chart'       => esc_html__( 'Charts', 'ninezeroseven' ),
			'client'      => esc_html__( 'Clients', 'ninezeroseven' ),
			'counter'     => esc_html__( 'Counters', 'ninezeroseven' ),
			'contact'     => esc_html__( 'Contact', 'ninezeroseven' ),
			'faq'         => esc_html__( 'FAQ', 'ninezeroseven' ),
			'footer'      => esc_html__( 'Footers', 'ninezeroseven' ),
			'header'      => esc_html__( 'Headers', 'ninezeroseven' ),
			'icon-box'    => esc_html__( 'Icon Boxes', 'ninezeroseven' ),
			'map'         => esc_html__( 'Maps', 'ninezeroseven' ),
			'misc'        => esc_html__( 'Misc', 'ninezeroseven' ),
			'gallery'     => esc_html__( 'Portfolio', 'ninezeroseven' ),
			'pricing'     => esc_html__( 'Pricing', 'ninezeroseven' ),
			'progress'    => esc_html__( 'Progress', 'ninezeroseven' ),
			'parallax'    => esc_html__( 'Parallax', 'ninezeroseven' ),
			'service'     => esc_html__( 'Services', 'ninezeroseven' ),
			'shop'        => esc_html__( 'Shop', 'ninezeroseven' ),
			'tabs'        => esc_html__( 'Tabs', 'ninezeroseven' ),
			'team'        => esc_html__( 'Team', 'ninezeroseven' ),
			'testimonial' => esc_html__( 'Testimonials', 'ninezeroseven' ),
			'text'        => esc_html__( 'Text', 'ninezeroseven' ),
			'video'       => esc_html__( 'Video', 'ninezeroseven' )

		);

		$output .= '<div class="wbc-category-template-list">';
		$output .= '<ul>';
		$output .= '<li data-filter="all" class="wbc-active-template-tab">'.esc_html__( 'All', 'ninezeroseven' ).'<span class="wbc-template-count">0</span></li>';
		
		foreach( $template_filters as $filter => $name ) {
			$output .= '<li data-filter="' . $filter . '">' . $name . ' <span class="wbc-template-count">0</span></li>';
		}
		
		$output .= '</ul>';
		$output .= '</div>';

		return $output;

	}

	public function renderTemplateBlock( $category ) {

		if ( 'wbc907_templates' === $category['category'] ) {

			$category['output'] = '<div class="vc_col-md-2 wbc907-sorting-container">';
			$category['output'] .= $this->get_template_filter_nav();
			$category['output'] .= '</div>';


			$category['output'] .= '<div class="vc_column vc_col-md-10 wbc907-templates-container">';
			$category['output'] .= '<div class="vc_ui-template-list vc_templates-list-default_templates vc_ui-list-bar" data-vc-action="collapseAll">';
			if ( ! empty( $category['templates'] ) ) {
				foreach ( $category['templates'] as $template ) {
					$category['output'] .= $this->renderTemplateListItem( $template );
				}
			}
			$category['output'] .= '
			</div>
		</div>';


		$category['output'] = apply_filters( 'wbc907_render_template_block', $category['output'] );

		}

		return $category;
	}

	/** Output rendered template in new panel dialog
	 * @since 4.4
	 *
	 * @param $template_name
	 * @param $template_data
	 *
	 * @return string
	 */
	function renderTemplateWindow( $template_name, $template_data ) {

		if ( 'wbc907_templates' === $template_data['type'] ) {
			return $this->renderTemplateWindowWBC907Templates( $template_name, $template_data );
		}

		return $template_name;
	}

	/**
	 * @since 4.4
	 *
	 * @param $template_name
	 * @param $template_data
	 *
	 * @return string
	 */
	public function renderTemplateWindowWBC907Templates( $template_name, $template_data ) {
		ob_start();
		$template_id = esc_attr( $template_data['unique_id'] );
		$template_id_hash = md5( $template_id ); // needed for jquery target for TTA
		$template_name = esc_html( $template_name );
		$preview_template_title = esc_attr( 'Preview template', 'ninezeroseven' );
		$add_template_title = esc_attr( 'Add template', 'ninezeroseven' );

		echo <<<HTML
		<button type="button" class="vc_ui-list-bar-item-trigger" title="$add_template_title"
			data-template-handler=""
			data-vc-ui-element="template-title">$template_name</button>
		<div class="vc_ui-list-bar-item-actions">
			<button type="button" class="vc_general vc_ui-control-button" title="$add_template_title"
					data-template-handler="">
				<i class="vc-composer-icon vc-c-icon-add"></i>
			</button>
		</div>
HTML;

		return ob_get_clean();
	}

	/**
	 * @since 4.7
	 */
	public function renderUITemplate() {
		vc_include_template( 'editors/popups/vc_ui-panel-templates.tpl.php', array(
			'box' => $this,
		) );

		return '';
	}

	/**
	 * Loading Any templates Shortcodes for backend by string $template_id from AJAX
	 * @since 4.4
	 * vc_filter: vc_templates_render_backend_template - called when unknown template requested to render in backend
	 */
	public function renderBackendTemplate() {

		$template_id = vc_post_param( 'template_unique_id' );
		$template_type = vc_post_param( 'template_type' );

		if ( ! isset( $template_id, $template_type ) || '' === $template_id || '' === $template_type ) {
			die( 'Error: Vc_WBC907_Templates::renderBackendTemplate:1' );
		}
		WPBMap::addAllMappedShortcodes();
		$this->getBackendDefaultTemplate();
		die();
	}

	/**
	 * @since 4.4
	 *
	 * @param $templates
	 *
	 * vc_filter: vc_load_wbc907_templates_limit_total - total items to show
	 *
	 * @return array
	 */
	public function loadDefaultTemplatesLimit( $templates ) {
		$start_index = 0;
		$total_templates_to_show = apply_filters( 'vc_load_default_templates_limit_total', 6 );

		return array_slice( $templates, $start_index, $total_templates_to_show );
	}

	/**
	 * Get user templates
	 *
	 * @since 4.12
	 * @return mixed
	 */
	public function getUserTemplates() {
		return apply_filters( 'vc_get_user_templates', get_option( $this->option_name ) );
	}

	/**
	 * Function to get all templates for display
	 *  - with image (optional preview image)
	 *  - with unique_id (required for do something for rendering.. )
	 *  - with name (required for display? )
	 *  - with type (required for requesting data in server)
	 *  - with category key (optional/required for filtering), if no category provided it will be displayed only in
	 * "All" category type vc_filter: vc_get_user_templates - hook to override "user My Templates" vc_filter:
	 * vc_get_all_templates - hook for override return array(all templates), hook to add/modify/remove more templates,
	 *  - this depends only to displaying in panel window (more layouts)
	 * @since 4.4
	 * @return array - all templates with name/unique_id/category_key(optional)/image
	 */
	public function getAllTemplates() {

		$data = array();
		$wbc907_templates = $this->getTemplates();
		$category_templates = array();

		if(is_array($wbc907_templates)){
			foreach ( $wbc907_templates as $template_id => $template_data ) {
				$category_templates[] = array(
					'unique_id' => $template_id,
					'name' => $template_data['name'],
					'type' => 'wbc907_templates',
					'image' => isset( $template_data['image_path'] ) ? $template_data['image_path'] : false,
					'custom_class' => isset( $template_data['custom_class'] ) ? $template_data['custom_class'] : false,
					'filter_label' => isset( $template_data['filter_label'] ) ? $template_data['filter_label'] : false,
				);
				if ( ! empty( $category_templates ) ) {
					$data = $category_templates;
				}
			}
		}

		return $data;
	}

	/**
	 * Load default templates list and initialize variable
	 * @since 4.4
	 * @return array
	 */
	public function loadDefaultTemplates() {

		if ( ! is_array( $this->wbc907_templates ) ) {
			$this->wbc907_templates = $this->allTemplates();
		}

		return $this->wbc907_templates;
	}

	/**
	 * Alias for loadDefaultTemplates
	 * @since 4.4
	 * @return array - list of default templates
	 */
	public function getDefaultTemplates() {
		return $this->loadDefaultTemplates();
	}

	/**
	 * Get default template data by template index in array.
	 * @since 4.4
	 *
	 * @param number $template_index
	 *
	 * @return array|bool
	 */
	public function getDefaultTemplate( $template_index ) {

		$this->loadDefaultTemplates();
		if ( ! is_numeric( $template_index ) || ! is_array( $this->wbc907_templates ) || ! isset( $this->wbc907_templates[ $template_index ] ) ) {
			return false;
		}

		return $this->wbc907_templates[ $template_index ];
	}

	/**
	 * Add custom template to default templates list ( at end of list )
	 * $data = array( 'name'=>'', 'image'=>'', 'content'=>'' )
	 * @since 4.4
	 *
	 * @param $data
	 *
	 * @return bool true if added, false if failed
	 */
	public function addDefaultTemplates( $data ) {
		if ( is_array( $data ) && ! empty( $data ) && isset( $data['name'], $data['content'] ) ) {
			if ( ! is_array( $this->wbc907_templates ) ) {
				$this->wbc907_templates = array();
			}
			$this->wbc907_templates[] = $data;

			return true;
		}

		return false;
	}

	/**
	 * Load default template content by index from ajax
	 * @since 4.4
	 *
	 * @param bool $return | should function return data or not
	 *
	 * @return string
	 */
	public function getBackendDefaultTemplate( $return = false ) {

		$template_index = (int) vc_request_param( 'template_unique_id' );
		$data = $this->getDefaultTemplate( $template_index );

		if ( ! $data ) {
			die( 'Error: Vc_Templates_Panel_Editor::getBackendDefaultTemplate:1' );
		}
		if ( $return ) {
			return trim( $data['content'] );
		} else {
			echo trim( $data['content'] );
			die();
		}
	}

	/**
	 * @since 4.4
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	public function sortTemplatesByCategories( array $data ) {
		$buffer = $data;
		uasort( $buffer, array(
			&$this,
			'cmpCategory',
		) );

		return $buffer;
	}

	/**
	 * @since 4.4
	 *
	 * @param array $data
	 *
	 * @return array
	 */
	public function sortTemplatesByNameWeight( array $data ) {
		$buffer = $data;
		uasort( $buffer, array(
			&$this,
			'cmpNameWeight',
		) );

		return $buffer;
	}

	/**
	 * Function should return array of templates categories
	 * @since 4.4
	 *
	 * @param array $categories
	 *
	 * @return array - associative array of category key => and visible Name
	 */
	public function getAllCategoriesNames( array $categories ) {
		$categories_names = array();

		foreach ( $categories as $category ) {
			if ( isset( $category['category'] ) ) {
				$categories_names[ $category['category'] ] = isset( $category['category_name'] ) ? $category['category_name'] : $category['category'];
			}
		}

		return $categories_names;
	}

	/**
	 * @since 4.4
	 * @return array
	 */
	public function getAllTemplatesSorted() {
		$data = $this->getAllTemplates();
		// firstly we need to sort by categories
		$data = $this->sortTemplatesByCategories( $data );
		// secondly we need to sort templates by their weight or name
		foreach ( $data as $key => $category ) {
			$data[ $key ]['templates'] = $this->sortTemplatesByNameWeight( $category['templates'] );
		}

		return $data;
	}

	/**
	 * Used to compare two templates by category, category_weight
	 * If category weight is less template will appear in first positions
	 * @since 4.4
	 *
	 * @param array $a - template one
	 * @param array $b - second template to compare
	 *
	 * @return int
	 */
	protected function cmpCategory( $a, $b ) {
		$a_k = isset( $a['category'] ) ? $a['category'] : '*';
		$b_k = isset( $b['category'] ) ? $b['category'] : '*';
		$a_category_weight = isset( $a['category_weight'] ) ? $a['category_weight'] : 0;
		$b_category_weight = isset( $b['category_weight'] ) ? $b['category_weight'] : 0;

		return $a_category_weight == $b_category_weight ? strcmp( $a_k, $b_k ) : $a_category_weight - $b_category_weight;
	}

	/**
	 * @since 4.4
	 *
	 * @param $a
	 * @param $b
	 *
	 * @return int
	 */
	protected function cmpNameWeight( $a, $b ) {
		$a_k = isset( $a['name'] ) ? $a['name'] : '*';
		$b_k = isset( $b['name'] ) ? $b['name'] : '*';
		$a_weight = isset( $a['weight'] ) ? $a['weight'] : 0;
		$b_weight = isset( $b['weight'] ) ? $b['weight'] : 0;

		return $a_weight == $b_weight ? strcmp( $a_k, $b_k ) : $a_weight - $b_weight;
	}

	/**
	 * Calls do_shortcode for templates.
	 *
	 * @param $content
	 *
	 * @return string
	 */
	public function frontendDoTemplatesShortcodes( $content ) {
		return do_shortcode( $content );
	}

	/**
	 * Add custom css from shortcodes from template for template editor.
	 *
	 * Used by action 'wp_print_scripts'.
	 *
	 * @todo move to autoload or else some where.
	 * @since 4.4.3
	 *
	 */
	public function addFrontendTemplatesShortcodesCustomCss() {
		$output = $shortcodes_custom_css = '';
		$shortcodes_custom_css = visual_composer()->parseShortcodesCustomCss( vc_frontend_editor()->getTemplateContent() );
		if ( ! empty( $shortcodes_custom_css ) ) {
			$shortcodes_custom_css = strip_tags( $shortcodes_custom_css );
			$output .= '<style type="text/css" data-type="vc_shortcodes-custom-css">';
			$output .= $shortcodes_custom_css;
			$output .= '</style>';
		}
		echo !empty( $output ) ? $output : '';
	}

	public function addScriptsToTemplatePreview() {
	}

	public function renderTemplateListItem( $template ) {
		$name                = isset( $template['name'] ) ? esc_html( $template['name'] ) : esc_html( __( 'No title', 'ninezeroseven' ) );
		$template_id         = esc_attr( $template['unique_id'] );
		$template_id_hash    = md5( $template_id ); // needed for jquery target for TTA
		$template_name       = esc_html( $name );
		$template_name_lower = esc_attr( vc_slugify( $template_name ) );
		$template_type       = esc_attr( isset( $template['type'] ) ? $template['type'] : 'custom' );
		$custom_class        = esc_attr( isset( $template['custom_class'] ) ? $template['custom_class'] : '' );
		$template_image      = esc_attr( isset( $template['image'] ) ? $template['image'] : '' );
		$template_menu_item  = esc_attr( isset( $template['filter_label'] ) ? $template['filter_label'] : '' );

		$output = <<<HTML
					<div class="vc_ui-template vc_templates-template-type-default_templates $custom_class"
						data-template_id="$template_id"
						data-template_id_hash="$template_id_hash"
						data-category="$template_type"
						data-template_unique_id="$template_id"
						data-template_name="$template_name_lower"
						data-template_type="default_templates"
						data-vc-content=".vc_ui-template-content">
						<div class="vc_ui-list-bar-item">
HTML;
		$output .= '<div class="wbc907-template-preview">';
		$output .= '<span class="spinner"></span>';
		$output .= '<img class="lazy-img" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAZAAAADfCAQAAAByz01KAAABxUlEQVR42u3TMQ0AAAzDsJU/6SHo1deGECk5oIoEYBAwCBgEDAIGAYOAQcAgYBDAIGAQMAgYBAwCBgGDgEHAIGAQwCBgEDAIGAQMAgYBg4BBwCCAQcAgYBAwCBgEDAIGAYOAQcAggEHAIGAQMAgYBAwCBgGDgEEAg4BBwCBgEDAIGAQMAgYBg4BBAIOAQcAgYBAwCBgEDAIGAYMABgGDgEHAIGAQMAgYBAwCBgGDAAYBg4BBwCBgEDAIGAQMAgYBg0gABgGDgEHAIGAQMAgYBAwCBgEMAgYBg4BBwCBgEDAIGAQMAgYBDAIGAYOAQcAgYBAwCBgEDAIYBAwCBgGDgEHAIGAQMAgYBAwCGAQMAgYBg4BBwCBgEDAIGAQwCBgEDAIGAYOAQcAgYBAwCBgEMAgYBAwCBgGDgEHAIGAQMAhgEDAIGAQMAgYBg4BBwCBgEDAIYBAwCBgEDAIGAYOAQcAgYBAwiARgEDAIGAQMAgYBg4BBwCBgEMAgYBAwCBgEDAIGAYOAQcAgYBDAIGAQMAgYBAwCBgGDgEHAIIBBwCBgEDAIGAQMAgYBg4BBwCCAQcAgYBAwCBgEDAIGAYOAQQCDgEFg8A9bAOAdKQ3JAAAAAElFTkSuQmCC" data-lazy-src="' . esc_url( $template_image ) . '" alt="' . esc_attr( $name ) . '" width="300" height="200" /></div>';
		$output .= apply_filters( 'vc_templates_render_template', $name, $template );
		$output .= <<<HTML
						</div>
						<div class="vc_ui-template-content" data-js-content>
						</div>
					</div>
HTML;

		return $output;
	}

	public function getOptionName() {
		return $this->option_name;
	}
}


add_action( 'vc_after_init', 'wbc907_vc_template_panel' , 25 );
function wbc907_vc_template_panel(){
	$wbc907_templates = new WBC907_Vc_Templates_Panel_Editor();
	$wbc907_templates->init();
}
?>
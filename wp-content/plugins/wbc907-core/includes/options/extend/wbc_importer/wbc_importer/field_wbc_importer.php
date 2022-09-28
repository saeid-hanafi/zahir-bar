<?php
/**
 * Extension-Boilerplate
 * @link https://github.com/ReduxFramework/extension-boilerplate
 *
 * Radium Importer - Modified For ReduxFramework
 * @link https://github.com/FrankM1/radium-one-click-demo-install
 *
 * @package     WBC_Importer - Extension for Importing demo content
 * @author      Webcreations907
 * @version     1.0.1
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// Don't duplicate me!
if ( !class_exists( 'ReduxFramework_wbc_importer' ) ) {

    /**
     * Main ReduxFramework_wbc_importer class
     *
     * @since       1.0.0
     */
    class ReduxFramework_wbc_importer {

        /**
         * Field Constructor.
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        function __construct( $field = array(), $value ='', $parent ) {
            $this->parent = $parent;
            $this->field = $field;
            $this->value = $value;

            $class = ReduxFramework_extension_wbc_importer::get_instance();

            if ( !empty( $class->demo_data_dir ) ) {
                $this->demo_data_dir = $class->demo_data_dir;
                $this->demo_data_url = plugin_dir_url( dirname( __FILE__ ) ).'demo-data/';
            }

            if ( empty( $this->extension_dir ) ) {
                $this->extension_dir = trailingslashit( str_replace( '\\', '/', dirname( __FILE__ ) ) );
                $this->extension_url = trailingslashit(plugin_dir_url( __FILE__ ));
            }
        }

        /**
         * Field Render Function.
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function render() {

            echo '</fieldset></td></tr><tr><td colspan="2"><fieldset class="redux-field wbc_importer">';

            $nonce = wp_create_nonce( "wbc_demo_importer" );

            // No errors please
            $defaults = array(
                'id'        => '',
                'url'       => '',
                'width'     => '',
                'height'    => '',
                'thumbnail' => '',
            );

            $this->value = wp_parse_args( $this->value, $defaults );

            $imported = false;

            $this->field['wbc_demo_imports'] = apply_filters( "redux/{$this->parent->args['opt_name']}/field/wbc_importer_files", array() );

            echo '<div class="wbc-builder-buttons">Filter By: <span class="wbc-builder-button">WPBakery</span><span class="wbc-builder-button elementor-builder">Elementor</span></div><div class="theme-browser"><div class="themes">';

            if ( !empty( $this->field['wbc_demo_imports'] ) && false !== apply_filters('wbc_importer_demo_process', false ) ) {

                foreach ( $this->field['wbc_demo_imports'] as $section => $imports ) {

                    if ( empty( $imports ) ) {
                        continue;
                    }

                    if( isset( $imports['version'] ) && !defined( 'WBC907THEME_VERSION' ) || !version_compare( WBC907THEME_VERSION , $imports['version'] , '>=' ) ){
                        continue;
                    }

                    if ( !array_key_exists( 'imported', $imports ) ) {
                        $extra_class = 'not-imported';
                        $imported = false;
                        $import_message = esc_html__( 'Import Demo', 'wbc907-core' );
                    }else {
                        $imported = true;
                        $extra_class = 'active imported';
                        $import_message = esc_html__( 'Demo Imported', 'wbc907-core' );
                    }
                    echo '<div class="wrap-importer theme '.$extra_class.'" data-demo-id="'.esc_attr( $section ).'"  data-nonce="' . $nonce . '" id="' . $this->field['id'] . '-custom_imports"><div class="wbc-demo-padding-wrap">';

                    echo '<div class="theme-screenshot">';

                    if ( isset( $imports['image'] ) ) {
                        echo '<img class="wbc_image" src="'.esc_attr( esc_url( $imports['image'] ) ).'"/>';

                    }
                    echo '</div>';

                    if( $imports['builder'] && !empty( $imports['builder'] )){
                        $img_file = wp_normalize_path( plugin_dir_path( dirname( __FILE__ ) ) ) .'demo-data/images/builder-icon-'.esc_attr( sanitize_title( strtolower( $imports['builder'] ) ) ).'.png';

                        if( file_exists( $img_file ) ){
                            echo '<span class="wbc-builder-icon wbc-builder-'.esc_attr( sanitize_title( strtolower( $imports['builder'] ) ) ).'">';
                            echo '<img src="'. wp_normalize_path( trailingslashit( plugin_dir_url( dirname( __FILE__ ) ) ) ) .'demo-data/images/builder-icon-'.esc_attr( sanitize_title( strtolower( $imports['builder'] ) ) ).'.png">';
                            echo "</span>";
                        }
                       
                    }

                    echo '<span class="more-details">'.$import_message.'</span>';
                    echo '<h3 class="theme-name">'. esc_html( apply_filters( 'wbc_importer_directory_title', $imports['name'] ) ) .'</h3>';

                    echo '<div class="theme-actions">';
                    if ( false == $imported ) {
                        echo '<div class="wbc-importer-buttons"><span class="spinner">'.esc_html__( 'Please Wait...', 'wbc907-core' ).'</span><span class="button-primary importer-button import-demo-data">' . __( 'Import Demo', 'wbc907-core' ) . '</span></div>';
                    }else {
                        echo '<div class="wbc-importer-buttons button-secondary importer-button">'.esc_html__( 'Imported', 'wbc907-core' ).'</div>';
                        echo '<span class="spinner">'.esc_html__( 'Please Wait...', 'wbc907-core' ).'</span>';
                        echo '<div id="wbc-importer-reimport" class="wbc-importer-buttons button-primary import-demo-data importer-button">'.esc_html__( 'Re-Import', 'wbc907-core' ).'</div>';
                    }
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';

                }

            } else {
                echo apply_filters('wbc_nodemos_provided_message', '<h2>'.esc_html__( 'Must activate your purchase license to import demos.', 'wbc907-core' ).'</h2>');
                do_action('wbc_importer_after_message');
            }

            echo '</div></div>';
            echo '</fieldset></td></tr>';

        }

        /**
         * Enqueue Function.
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function enqueue() {

            $min = Redux_Functions::isMin();

            wp_enqueue_script(
                'redux-field-wbc-importer-js',
                $this->extension_url . 'field_wbc_importer.js',
                array( 'jquery' ),
                WBC907_CORE_PLUGIN_VERSION,
                true
            );

            wp_enqueue_style(
                'redux-field-wbc-importer-css',
                $this->extension_url . 'field_wbc_importer.css',
                WBC907_CORE_PLUGIN_VERSION,
                true
            );

        }
    }
}

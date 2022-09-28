<?php
if (!class_exists('WBC907_Options_Framework')) {

    class WBC907_Options_Framework {

        public $args        = array();
        public $sections    = array();
        public $theme;
        public $ReduxFramework;

        public function __construct() {

            if (!class_exists('ReduxFramework')) {
                return;
            }
            // This is needed. Bah WordPress bugs.  ;)
            if (  true == Redux_Helpers::isTheme(__FILE__) ) {
                $this->initSettings();
            } else {
                $this->initSettings();
            }
        }

        public function initSettings() {

            // Set the default arguments
            $this->setArguments();

            // Set a few help tabs so you can see how it's done
            // $this->setHelpTabs();

            // Create the sections and fields
            $this->setSections();

            //add_filter('redux/options/'.$this->args['opt_name'].'/compiler', array( $this, 'compiler_action' ), 10, 3);

            if (!isset($this->args['opt_name'])) { // No errors please
                return;
            }


            add_action('admin_enqueue_scripts', array($this ,'wbc907_admin_scripts'));


            $this->ReduxFramework = new ReduxFramework($this->sections, $this->args);

        }

        public function wbc907_admin_scripts(){
            wp_register_style( 'font-awesome-shim', get_template_directory_uri().'/assets/css/font-icons/font-awesome/css/v4-shims.min.css',false, WBC907THEME_VERSION );
            wp_register_style( 'font-awesome', get_template_directory_uri().'/assets/css/font-icons/font-awesome/css/all.min.css',array( 'font-awesome-shim' ), WBC907THEME_VERSION );
            wp_enqueue_style( 'font-awesome' );
        }



        public function setSections() {

            /************************************************************************
            * Home Section
            *************************************************************************/

            /**
             * Gets FontAwesome Array
             * $sort   = true   // Sorts the Icons
             * $w_name = true   // Adds named array like array(fa-cogs => Cogs)
             * $no_fa  = true   // Removes 'fa' from 'fa fa-cogs'
             */
            $iconArray = wbc_fontawesome_array( true, true, true );
            $options_values = get_option('wbc907_data');

            if(isset($options_values) && is_array($options_values)){
                if(isset($options_values['opts-topbar-right']) && isset($options_values['opts-topbar-right']['field-icon']) && count($options_values['opts-topbar-right']['field-icon'])>0){

                    foreach ($options_values['opts-topbar-right']['field-icon'] as $icon) {
                        if( !empty($icon) &&  preg_match( '/fa fa-/', $icon ) ){
                            $iconArray[$icon] = ucwords(str_replace('fa fa-', '', $icon));
                        }
                    }
                }

            }

            if(isset($options_values) && is_array($options_values)){
                if(isset($options_values['opts-topbar-left']) && isset($options_values['opts-topbar-left']['field-icon']) && count($options_values['opts-topbar-left']['field-icon'])>0){

                    foreach ($options_values['opts-topbar-left']['field-icon'] as $icon) {
                        if( !empty($icon) &&  preg_match( '/fa fa-/', $icon ) ){
                            $iconArray[$icon] = ucwords(str_replace('fa fa-', '', $icon));
                        }
                    }
                }

            }
            /**
             * Backwards compatible color field
             * new one supports alpha
             */
            if(defined('WBC907_CORE_PLUGIN_VERSION')){
                if( version_compare( WBC907_CORE_PLUGIN_VERSION , '1.8','>=')){
                    $options_color_field = 'color_alpha';
                }else{
                    $options_color_field = 'color';    
                }
            }else{
                $options_color_field = 'color';
            }


            /************************************************************************
            * General Settings
            *************************************************************************/
            $this->sections[] = array(
                'title'     => esc_html__('General Settings', 'ninezeroseven'),
                'icon'      => 'el-icon-cogs',
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(
                    ///BOXED
                    array(
                        'id'        => 'opts-boxed-layout',
                        'type'      => 'switch',
                        'title'     => esc_html__('Boxed Layout', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Enables boxed layout site wide', 'ninezeroseven'),
                        'default' => 0,
                        'on'        => esc_html__('Enable', 'ninezeroseven'),
                        'off'       => esc_html__('Disable', 'ninezeroseven'),
                    ),
                    array(
                        'id'       => 'opts-boxed-bg',
                        'type'     => 'background',
                        'title'    => esc_html__('Body Background', 'ninezeroseven'),
                        'output'    => array('body'),
                        'required'  => array('opts-boxed-layout', "=", 1),
                        'subtitle' => esc_html__('Body background with image, color, etc.', 'ninezeroseven'),
                    ),
                    array(
                        'id'        => 'opts-boxed-width',
                        'type'      => 'slider',
                        'required'  => array('opts-boxed-layout', "=", 1),
                        'title'     => esc_html__('Boxed Width', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Changes width of boxed area, default is 1240px', 'ninezeroseven'),
                        "default"   => 1240,
                        "min"       => 500,
                        "step"      => 1,
                        "max"       => 2000,
                        'display_value' => 'text'
                    ),
                    /// END BOXED
                    /// Retina
                    array(
                        'id'        => 'opts-retina-enable',
                        'type'      => 'switch',
                        'title'     => esc_html__('Retina Images', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Enables Retina Images', 'ninezeroseven'),
                        'desc'      => esc_html__('You must upload images you want retina with @2x before the file extension i.e image@2x.jpg. Or use WP Retina 2x Plugin which will generate retina images for you from your uploaded images. If you use WP Retina 2x Plugin, leave this option disabled.', 'ninezeroseven'),
                        'default' => 0,
                        'on'        => esc_html__('Enable', 'ninezeroseven'),
                        'off'       => esc_html__('Disable', 'ninezeroseven'),

                    ),
                    array(
                        'id'          => 'opts-primary-color',
                        'type'        => $options_color_field,
                        'title'       => esc_html__('Primary Color', 'ninezeroseven'),
                        'subtitle'    => esc_html__('Change the main colors(links,buttons,etc)', 'ninezeroseven'),
                        'transparent' => false,
                        'default'     => '',
                        'output'      => apply_filters( 'opts-primary-color', array() )
                    ),
                    array(
                        'id'        => 'opts-page-bg-color',
                        'type'      => $options_color_field,
                        'title'     => esc_html__('Page BG Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Change the page background color', 'ninezeroseven'),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'background-color' => '.page-wrapper'
                            )
                    ),
                    array(
                        'id'        => 'opts-page-content-color',
                        'type'      => $options_color_field,
                        'title'     => esc_html__('Page Content Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Change the primary color.(boxes,borders,etc)', 'ninezeroseven'),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => apply_filters('opts-page-content-color', array())
                    ),
                    array(
                        'id'          => 'opts-default-overlay-color',
                        'type'        => $options_color_field,
                        'title'       => esc_html__('Image Overlay Color', 'ninezeroseven'),
                        'subtitle'    => esc_html__('Changes overlay on images when hovered over, when left blank color will be used from "Primary Color" field above. ', 'ninezeroseven'),
                        'transparent' => false,
                        'default'     => '',
                        'output'      => array(
                                'background-color' => '.wbc-image-wrap .item-link-overlay'
                            )
                    ),
                    array(
                        'id'             => 'opts-maincontent-spacing',
                        'type'           => 'spacing',
                        'output'         => array('.main-content-area'),
                        'units'          => 'px',
                        'left'           => false,
                        'right'          => false,
                        'units_extended' => false,
                        'title'          => esc_html__('Main Content Spacing', 'ninezeroseven'),
                        'subtitle'       => esc_html__('Set spacing for top and bottom of main content', 'ninezeroseven'),
                        'desc'           => esc_html__('Enter width value + unit. IE 20px, 1em, etc.', 'ninezeroseven'),
                        'default'        => array(
                            'padding-top'    => '',
                            'padding-bottom' => '',
                        )
                    ),
                    array(
                        'id'   => 'opts-page-buttons-style-info',
                        'type' => 'info',
                        'style'=> 'normal',
                        'desc' => __('<b>Button Styling Options</b>', 'ninezeroseven')
                    ),
                    array(
                        'id'             => 'opts-default-button-text',
                        'type'           => 'typography',
                        'title'          => esc_html__('Typography', 'ninezeroseven'),
                        'subtitle'       => esc_html__('Change button font', 'ninezeroseven'),
                        'google'         => true,
                        'text-align'     => false,
                        'letter-spacing' => true,
                        'font-backup'    => true,
                        'default'        => array(
                            'font-size'     => '',
                            'font-family'   => '',
                            'font-weight'   => ''
                        ),
                        'output'    => array('.button:not(.wbc-arrow-buttons), input[type="submit"]')
                    ),//ADD FILTERS
                    array(
                        'id'             => 'opts-default-button-spacing',
                        'type'           => 'spacing',
                        'output'         => array('.button:not(.wbc-arrow-buttons), input[type="submit"]'),
                        'units'          => 'px',
                        'units_extended' => false,
                        'title'          => esc_html__('Button Padding', 'ninezeroseven'),
                        'desc'           => esc_html__('Enter padding value + unit. IE 20px, 1em, etc.', 'ninezeroseven'),
                        'default'        => array(
                            'padding-top'    => '',
                            'padding-bottom' => '',
                            'padding-right'  => '',
                            'padding-left'   => '',
                        )
                    ),
                    array(
                        'id'          => 'opts-default-button-bg-color',
                        'type'        => $options_color_field,
                        'title'       => esc_html__('Button Color', 'ninezeroseven'),
                        'subtitle'    => esc_html__('Change button color, if left empty, color is used from "Primary" color above.', 'ninezeroseven'),
                        'transparent' => false,
                        'default'     => '',
                        'output'      => apply_filters( 'opts_buttons_bg_color', array() )
                    ),
                    array(
                        'id'          => 'opts-default-button-color',
                        'type'        => $options_color_field,
                        'title'       => esc_html__('Button Text Color', 'ninezeroseven'),
                        'subtitle'    => esc_html__('Change button text color.', 'ninezeroseven'),
                        'transparent' => false,
                        'default'     => '',
                        'output'      => apply_filters( 'opts_buttons_text_color', array() )
                    ),
                    array(
                        'id'          => 'opts-default-button-bg-hover-color',
                        'type'        => $options_color_field,
                        'title'       => esc_html__('Button Hover Color', 'ninezeroseven'),
                        'subtitle'    => esc_html__('Change button hover color.', 'ninezeroseven'),
                        'transparent' => false,
                        'default'     => '',
                        'output'      => apply_filters( 'opts_buttons_bg_hover_color', array() )
                    ),
                    array(
                        'id'          => 'opts-default-button-hover-color',
                        'type'        => $options_color_field,
                        'title'       => esc_html__('Button Hover Text Color', 'ninezeroseven'),
                        'subtitle'    => esc_html__('Change button hover text color.', 'ninezeroseven'),
                        'transparent' => false,
                        'default'     => '',
                        'output'      => apply_filters( 'opts_buttons_text_hover_color', array() )
                    ),
                    array(
                        'id'   => 'opts-page-custom-extra-info',
                        'type' => 'info',
                        'style'=> 'normal',
                        'desc' => __('<b>MISC Options</b>', 'ninezeroseven')
                    ),
                    array(
                        'id'        => 'opts-custom-css',
                        'type'      => 'ace_editor',
                        'title'     => esc_html__('CSS Code', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Add your CSS code here.', 'ninezeroseven'),
                        'mode'      => 'css',
                        'theme'     => 'monokai',
                        'validate' => 'css',
                        'default'   => ""
                    ),
                    array(
                        'id'        => 'opts-custom-js',
                        'type'      => 'textarea',
                        'title'     => esc_html__('Custom JS Code', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Add custom JS code here, which will get added into the footer.', 'ninezeroseven'),
                        'desc'      => '',
                    ),

                    ),
                );

            /************************************************************************
            * Nav Bar Settings
            *************************************************************************/
            
             $this->sections[] = array(
                'title'     => esc_html__('Header', 'ninezeroseven'),
                //'desc'      => esc_html__('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'ninezeroseven'),
                'icon'      => 'fa fa-sliders',
                //'subsection' => true,
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array());

             $this->sections[] = array(
                'title'     => esc_html__('Logos', 'ninezeroseven'),
                'subsection' => true,
                'fields'    => array(
                        array(
                        'id'        => 'logo-enabled',
                        'type'      => 'switch',
                        'title'     => esc_html__('Logo Type', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Select logo type you\'d like in nav bar', 'ninezeroseven'),
                        'default' => 0,
                        'on'        => 'Image',
                        'off'       => 'Text',

                    ),
                    array(
                        'id'        => 'opts-nav-text',
                        'type'      => 'text',
                        'title'     => esc_html__('Site Name', 'ninezeroseven'),
                        'subtitle'  => esc_html__('If you\'d like your site name different in nav bar then what you\'ve set on settings page.', 'ninezeroseven'),
                        'validate'  => 'no_html',
                        'required'  => array('logo-enabled', "=", 0),
                        'default'   => get_bloginfo( 'name' )
                    ),
                    array(
                        'id'        => 'opts-nav-logo',
                        'type'      => 'media',
                        'title'     => esc_html__('Main Logo', 'ninezeroseven'),
                        'mode'      => 'image', // Can be set to false to allow any media type, or can also be set to any mime type.
                        //'desc'      => esc_html__('Basic media uploader with disabled URL input field.', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Main default menu bar logo.', 'ninezeroseven'),
                        'required'  => array('logo-enabled', "=", 1),
                        'default' => '',
                    ),
                    array(
                        'id'        => 'opts-nav-transparent-logo',
                        'type'      => 'media',
                        'title'     => esc_html__('Transparent Logo', 'ninezeroseven'),
                        'mode'      => 'image', // Can be set to false to allow any media type, or can also be set to any mime type.
                        //'desc'      => esc_html__('Basic media uploader with disabled URL input field.', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Used on page(s) that have transparent menu option selected.', 'ninezeroseven'),
                        'required'  => array('logo-enabled', "=", 1),
                        'default' => '',
                    ),
                    array(
                        'id'        => 'opts-nav-sticky-logo',
                        'type'      => 'media',
                        'title'     => esc_html__('Sticky Logo', 'ninezeroseven'),
                        'mode'      => 'image', // Can be set to false to allow any media type, or can also be set to any mime type.
                        //'desc'      => esc_html__('Basic media uploader with disabled URL input field.', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Used if sticky menu is enabled, leave blank to use default logo.', 'ninezeroseven'),
                        'required'  => array('logo-enabled', "=", 1),
                        'default' => '',
                    ),

                    array(
                        'id'                => 'opts-logo-width',
                        'type'              => 'dimensions',
                        
                        // 'units'             => 'px',    // You can specify a unit value. Possible: px, em, %
                        'height' => false,
                        'title'             => esc_html__('Logo Max Width', 'ninezeroseven'),

                    ),

                    ));

                $this->sections[] = array(
                'title'     => esc_html__('Main Header', 'ninezeroseven'),
                //'desc'      => esc_html__('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'ninezeroseven'),
                'subsection' => true,
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(
                    array(
                        'id'       => 'opts-menubar-fullwidth',
                        'type'     => 'switch',
                        'title'    => esc_html__('100% Menu Bar Width', 'ninezeroseven'),
                        'subtitle' => esc_html__('Makes the menu bar/logo area full width.', 'ninezeroseven'),
                        'default'  => 0,
                        'on'       => 'Enabled',
                        'off'      => 'Disabled',
                    ),
                    array(
                        'id'             => 'opts-menubar-spacing',
                        'type'           => 'spacing',
                        'output'         => array('.wbc-fullwidth-container.header-bar .container'),
                        'required'       => array('opts-menubar-fullwidth', "=", 1),
                        'units'          => 'px',
                        'top'           => false,
                        'bottom'          => false,
                        'units_extended' => false,
                        'title'          => esc_html__('Menu Bar Padding', 'ninezeroseven'),
                        'subtitle'       => esc_html__('Set spacing for left and right of menubar', 'ninezeroseven'),
                        'desc'           => esc_html__('Enter width value + unit. IE 20px, 1em, etc.', 'ninezeroseven'),
                        'default'        => array(
                            'padding-left'    => '',
                            'padding-right' => '',
                        )
                    ),
                    array(
                        'id'                => 'opts-menu-height',
                        'type'              => 'dimensions',
                        // 'units'             => 'px',    // You can specify a unit value. Possible: px, em, %
                        'width' => false,
                        'title'             => esc_html__('Menu Bar Height', 'ninezeroseven'),
                        'subtitle'          => esc_html__('If you\'d like to change the height of the menu bar, enter value here', 'ninezeroseven'),
                        // 'desc'              => esc_html__('You can enable or disable any piece of this field. Width, Height, or Units.', 'ninezeroseven'),

                    ),

                    /************************************************************************
                    * Top Bar Options
                    *************************************************************************/
                    array(
                        'id'        => 'opts-topbar',
                        'type'      => 'switch',
                        'title'     => esc_html__('Show/Hide Topbar', 'ninezeroseven'),
                        'subtitle'  => esc_html__('You can choose to show/hide the top bar here.', 'ninezeroseven'),
                        'default' => 0,
                        'on'        => 'Enabled',
                        'off'       => 'Disabled',

                    ),array(
                        'id'           => 'opts-topbar-left',
                        'type'         => 'repeater',
                        'title'        => 'Left Topbar Items',
                        'subtitle'     => 'Select a social icon to append a link to.',
                        'group_values' => true,
                        'item_name' => 'Item',
                        'required'  => array('opts-topbar', "=", 1),
                        'fields'    => array(
                            array(
                                'id'       => 'field-icon',
                                'type'     => 'select',
                                'select2'  => array( 'containerCssClass' => ' el' ),
                                'title'    => esc_html__('Icon', 'ninezeroseven'),
                                'subtitle' => esc_html__('Select a icon to appear before.', 'ninezeroseven'),
                                'class'    => ' font-icons ef',
                                'options'  => $iconArray
                            ),
                            array(
                                'id'          => 'field-info',
                                'type'        => 'text',
                                'class'       => ' large-text',
                                'title'       => esc_html__('Text','ninezeroseven'),
                                'subtitle'    => esc_html__('Text you would like displayed.','ninezeroseven'),
                                'default'     => '',
                            )
                        )
                    ),array(
                        'id'           => 'opts-topbar-right',
                        'type'         => 'repeater',
                        'title'        => esc_html__('Right Topbar Social', 'ninezeroseven'),
                        'subtitle'     => esc_html__('Select a social icon to append a link to.', 'ninezeroseven'),
                        'group_values' => true,
                        'item_name' => 'Social Icons',
                        'required'  => array('opts-topbar', "=", 1),
                        'fields'    => array(
                            array(
                                'id'       => 'field-icon',
                                'type'     => 'select',
                                'select2'  => array( 'containerCssClass' => ' el' ),
                                'title'    => esc_html__('Social Icon', 'ninezeroseven'),
                                'subtitle' => esc_html__('Select a social icon to append a link to.', 'ninezeroseven'),
                                'class'    => ' font-icons ef',
                                'options'  => $iconArray
                            ),
                            array(
                                'id'        => 'field-info',
                                'type'      => 'text',
                                'title'     => esc_html__('Link URL', 'ninezeroseven'),
                                'subtitle'  => esc_html__('This must be a valid URL i.e http://www.twitter.com/username', 'ninezeroseven'),
                                'desc'      => esc_html__('This will make the icon linked.', 'ninezeroseven'),
                                'validate'  => 'url',
                                'default'   => '',
                            ),
                            array(
                                'id'        => 'field-target',
                                'type'      => 'checkbox',
                                'title'     => esc_html__('Open In New Window?', 'ninezeroseven'),
                                'subtitle'  => esc_html__('Check box to open link in a new window/browser', 'ninezeroseven'),
                                'default'   => '0'// 1 = on | 0 = off
                            ),
                        )
                    ),
                    array(
                        'id'        => 'opts-enable-topmenu-color',
                        'type'      => 'checkbox',
                        'title'     => esc_html__('Top Bar Coloring', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Check to enable color fields for top bar.', 'ninezeroseven'),
                        'required'  => array('opts-topbar', "=", 1),
                        'default'   => '0'// 1 = on | 0 = off
                    ),
                    array(
                        'id'        => 'opts-topmenu-link-color-border',
                        'type'      => 'color',
                        'title'     => esc_html__('Top Bar Background Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Change the background color.', 'ninezeroseven'),
                        'required'  => array('opts-enable-topmenu-color', "=", 1),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'background-color' => '.top-extra-bar',
                                'border-color' => '.top-extra-bar'
                            )
                    ),
                    array(
                        'id'          => 'opts-topmenu-color',
                        'type'        => 'color',
                        'title'       => esc_html__('Top Bar Text Color', 'ninezeroseven'),
                        'subtitle'    => esc_html__('Change the text color.', 'ninezeroseven'),
                        'required'    => array('opts-enable-topmenu-color', "=", 1),
                        'transparent' => false,
                        'default'     => '',
                        'output'      => array(
                                'color' => '.top-extra-bar'
                            )
                    ),
                    array(
                        'id'        => 'opts-topmenu-link-color',
                        'type'      => 'color',
                        'title'     => esc_html__('Top Bar Link Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Change the link color.', 'ninezeroseven'),
                        'required'  => array('opts-enable-topmenu-color', "=", 1),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'color' => '.top-extra-bar a,.header-bar .social-links a'
                            )
                    ),
                    array(
                        'id'        => 'opts-topmenu-link-color-hover',
                        'type'      => 'color',
                        'title'     => esc_html__('Top Bar Link Hover Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Change the link hover color.', 'ninezeroseven'),
                        'required'  => array('opts-enable-topmenu-color', "=", 1),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'color' => '.top-extra-bar a:hover,.header-bar .social-links a:hover'
                            )
                    ),

                    /************************************************************************
                    * Main Nav Colors
                    *************************************************************************/
                    array(
                        'id'       => 'opts-menu-shadow',
                        'type'     => 'switch',
                        'title'    => esc_html__('Menu Shadow', 'ninezeroseven'),
                        'subtitle' => esc_html__('Enable/disable menu bar shadow', 'ninezeroseven'),
                        'default'  => 1,
                        'on'       => 'Enabled',
                        'off'      => 'Disabled',

                    ),
                    array(
                        'id'        => 'opts-enable-menu-color',
                        'type'      => 'checkbox',
                        'title'     => esc_html__('Nav Bar Coloring', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Check to enable color fields for menu', 'ninezeroseven'),
                        'default'   => '0'// 1 = on | 0 = off
                    ),
                    array(
                        'id'        => 'opts-nav-background',
                        'type'      => $options_color_field,
                        // 'output'    => array('.header-bar'),
                        'title'     => esc_html__('Main Nav Background Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Change the main navigation background color. If you set opactity below 1 then the menu overlays the content below, if you have page title/breadcrumbs shown make sure to add padding to it since the menu will be over it.', 'ninezeroseven'),
                        'required'  => array('opts-enable-menu-color', "=", 1),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'background-color' => '.menu-bar-wrapper,.menu-bar-wrapper.is-sticky'
                            )
                    ),
                    array(
                        'id'        => 'opts-nav-link-color',
                        'type'      => $options_color_field,
                        'output'    => array('.header-inner'),
                        'title'     => esc_html__('Main Nav Link Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Change the main navigation link color.', 'ninezeroseven'),
                        'required'  => array('opts-enable-menu-color', "=", 1),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'color' => '.header-inner a','.wbc_menu > li > a,.primary-menu .wbc_menu a,.mobile-nav-menu .wbc_menu a'
                            )
                    ),
                    array(
                        'id'        => 'opts-nav-link-color-hover',
                        'type'      => $options_color_field,
                        'output'    => array('.header-inner'),
                        'title'     => esc_html__('Main Nav Hover Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Change the main navigation hover color.', 'ninezeroseven'),
                        'required'  => array('opts-enable-menu-color', "=", 1),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'color' => '.header-inner a:hover','.wbc_menu > li > a:hover,.header-inner .primary-menu .wbc_menu a:hover,.mobile-nav-menu .wbc_menu a:hover'
                            )
                    ),
                    array(
                        'id'        => 'opts-nav-link-color-active',
                        'type'      => $options_color_field,
                        'output'    => array('.header-bar'),
                        'title'     => esc_html__('Main Nav Active Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Change the main navigation active color.', 'ninezeroseven'),
                        'required'  => array('opts-enable-menu-color', "=", 1),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'color' => '.wbc_menu li.active > a,.mobile-menu .primary-menu .wbc_menu li.active a'
                            )
                    ),

                    /************************************************************************
                    * Sub Nav Colors
                    *************************************************************************/
                    array(
                        'id'        => 'opts-subnav-background',
                        'type'      => $options_color_field,
                        'title'     => esc_html__('Sub Nav Background Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Change the sub navigation background color.', 'ninezeroseven'),
                        'required'  => array('opts-enable-menu-color', "=", 1),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'background-color' => '.primary-menu .wbc_menu li > ul, .mobile-nav-menu'
                            )
                    ),
                    array(
                        'id'        => 'opts-subnav-link-color-border',
                        'type'      => $options_color_field,
                        'title'     => esc_html__('Sub Nav border Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Change the sub navigation border color.', 'ninezeroseven'),
                        'required'  => array('opts-enable-menu-color', "=", 1),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'border-color' => '.primary-menu .wbc_menu ul li a, .mobile-nav-menu .wbc_menu a'
                            )
                    ),
                    array(
                        'id'        => 'opts-subnav-link-color',
                        'type'      => $options_color_field,
                        'title'     => esc_html__('Sub Nav Link Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Change the sub navigation link color.', 'ninezeroseven'),
                        'required'  => array('opts-enable-menu-color', "=", 1),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'color' => '.primary-menu .wbc_menu ul.sub-menu li a,.mobile-nav-menu .wbc_menu a'
                            )
                    ),
                    array(
                        'id'        => 'opts-subnav-link-color-hover',
                        'type'      => $options_color_field,
                        'title'     => esc_html__('Sub Nav Hover Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Change the sub navigation hover color.', 'ninezeroseven'),
                        'required'  => array('opts-enable-menu-color', "=", 1),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'color' => '.primary-menu .wbc_menu ul.sub-menu li a:hover,.mobile-nav-menu .wbc_menu a:hover'
                            )
                    ),




                    ),
                );
            /************************************************************************
            * Sticky Header
            *************************************************************************/
            $this->sections[] = array(
                'title'     => esc_html__('Sticky Header', 'ninezeroseven'),
                //'desc'      => esc_html__('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'ninezeroseven'),
                'subsection' => true,
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(
                    array(
                        'id'        => 'opts-sticky-menu',
                        'type'      => 'switch',
                        'title'     => esc_html__('Sticky Menu', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Here you can choose to enable/disable the sticky menu(menu follows on scroll)', 'ninezeroseven'),
                        'default' => 0,
                        'on'        => 'Enabled',
                        'off'       => 'Disabled',

                    ),array(
                        'id'       => 'opts-elastic-menu',
                        'type'     => 'switch',
                        'title'    => esc_html__('Elastic Menu', 'ninezeroseven'),
                        'subtitle' => esc_html__('Here you can choose to enable/disable the shrinking menu feature.', 'ninezeroseven'),
                        'default'  => 0,
                        'required' => array('opts-sticky-menu', "=", 1),
                        'on'       => 'Enabled',
                        'off'      => 'Disabled',

                    ),
                    array(
                        'id'                => 'opts-elastic-height',
                        'type'              => 'dimensions',
                        // 'units'             => 'px',    // You can specify a unit value. Possible: px, em, %
                        'width' => false,
                        'title'             => esc_html__('Menu Bar Shrink To', 'ninezeroseven'),
                        'subtitle'          => esc_html__('If you\'d like to change the small menu height, do so here', 'ninezeroseven'),
                        // 'desc'              => esc_html__('You can enable or disable any piece of this field. Width, Height, or Units.', 'ninezeroseven'),
                        'required' => array('opts-elastic-menu', "=", 1),

                    ),

                    /************************************************************************
                    * Main Nav Colors
                    *************************************************************************/
                    array(
                        'id'       => 'opts-menu-sticky-shadow',
                        'type'     => 'switch',
                        'required' => array('opts-sticky-menu', "=", 1),
                        'title'    => esc_html__('Menu Sticky Shadow', 'ninezeroseven'),
                        'subtitle' => esc_html__('Enable/disable menu bar shadow when sticky enabled', 'ninezeroseven'),
                        'default'  => 1,
                        'on'       => 'Enabled',
                        'off'      => 'Disabled',

                    ),
                    array(
                        'id'        => 'opts-enable-menu-sticky-color',
                        'type'      => 'checkbox',
                        'title'     => esc_html__('Sticky Nav Bar Coloring', 'ninezeroseven'),
                        'required' => array('opts-sticky-menu', "=", 1),
                        'subtitle'  => esc_html__('Check to enable color fields for Sticky menu', 'ninezeroseven'),
                        'default'   => '0'// 1 = on | 0 = off
                    ),
                    array(
                        'id'        => 'opts-nav-sticky-background',
                        'type'      => $options_color_field,
                        'title'     => esc_html__('Sticky Nav Background Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Change the background color for the menu bar when scrolled.', 'ninezeroseven'),
                        'required'  => array('opts-enable-menu-sticky-color', "=", 1),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'background-color' => '.menu-bar-wrapper.is-sticky'
                            )
                    ),
                    array(
                        'id'        => 'opts-nav-sticky-link-color',
                        'type'      => $options_color_field,
                        'output'    => array('.header-inner'),
                        'title'     => esc_html__('Main Nav Link Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Change the main navigation link color.', 'ninezeroseven'),
                        'required'  => array('opts-enable-menu-sticky-color', "=", 1),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'color' => '.menu-bar-wrapper.is-sticky .header-inner a','.menu-bar-wrapper.is-sticky .primary-menu .wbc_menu > li > a,.mobile-menu .menu-bar-wrapper.is-sticky .primary-menu .wbc_menu a'
                            )
                    ),
                    array(
                        'id'        => 'opts-nav-sticky-link-color-hover',
                        'type'      => $options_color_field,
                        'output'    => array('.header-inner'),
                        'title'     => esc_html__('Main Nav Hover Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Change the main navigation hover color.', 'ninezeroseven'),
                        'required'  => array('opts-enable-menu-sticky-color', "=", 1),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'color' => '.menu-bar-wrapper.is-sticky .header-inner a:hover','.menu-bar-wrapper.is-sticky .primary-menu .wbc_menu > li > a:hover,.mobile-menu .menu-bar-wrapper.is-sticky .primary-menu .wbc_menu a:hover'
                            )
                    ),
                    array(
                        'id'        => 'opts-nav-sticky-link-color-active',
                        'type'      => $options_color_field,
                        'output'    => array('.header-bar'),
                        'title'     => esc_html__('Main Nav Active Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Change the main navigation active color.', 'ninezeroseven'),
                        'required'  => array('opts-enable-menu-sticky-color', "=", 1),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'color' => '.menu-bar-wrapper.is-sticky .primary-menu .wbc_menu li.active > a,.mobile-menu .menu-bar-wrapper.is-sticky .primary-menu .wbc_menu li.active a'
                            )
                    ),

                    /************************************************************************
                    * Sub Nav Colors
                    *************************************************************************/
                    array(
                        'id'        => 'opts-subnav-sticky-background',
                        'type'      => $options_color_field,
                        'title'     => esc_html__('Sub Nav Background Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Change the sub navigation background color.', 'ninezeroseven'),
                        'required'  => array('opts-enable-menu-sticky-color', "=", 1),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'background-color' => '.menu-bar-wrapper.is-sticky .primary-menu .wbc_menu li > ul,.menu-bar-wrapper.is-sticky .primary-menu.mobile-show, .menu-bar-wrapper.is-sticky .primary-menu.mobile-show a'
                            )
                    ),
                    array(
                        'id'        => 'opts-subnav-sticky-link-color-border',
                        'type'      => $options_color_field,
                        'title'     => esc_html__('Sub Nav border Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Change the sub navigation border color.', 'ninezeroseven'),
                        'required'  => array('opts-enable-menu-sticky-color', "=", 1),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'border-color' => '.menu-bar-wrapper.is-sticky .primary-menu  .wbc_menu ul li a, .menu-bar-wrapper.is-sticky .mobile-show .wbc_menu li a,.menu-bar-wrapper.is-sticky .mobile-show ul li:last-child > a'
                            )
                    ),
                    array(
                        'id'        => 'opts-subnav-sticky-link-color',
                        'type'      => $options_color_field,
                        'title'     => esc_html__('Sub Nav Link Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Change the sub navigation link color.', 'ninezeroseven'),
                        'required'  => array('opts-enable-menu-sticky-color', "=", 1),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'color' => '.menu-bar-wrapper.is-sticky .primary-menu .wbc_menu ul.sub-menu li a'
                            )
                    ),
                    array(
                        'id'        => 'opts-subnav-sticky-link-color-hover',
                        'type'      => $options_color_field,
                        'title'     => esc_html__('Sub Nav Hover Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Change the sub navigation hover color.', 'ninezeroseven'),
                        'required'  => array('opts-enable-menu-sticky-color', "=", 1),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'color' => '.menu-bar-wrapper.is-sticky .primary-menu .wbc_menu ul.sub-menu li a:hover'
                            )
                    ),




                    ),
                );
            //END STICKY
            /************************************************************************
            * Mobile Menu
            *************************************************************************/
            $this->sections[] = array(
                'title'     => esc_html__('Mobile Menu', 'ninezeroseven'),
                //'desc'      => esc_html__('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'ninezeroseven'),
                'subsection' => true,
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(
                    array(
                        'id'        => 'opts-mobile-menu-base',
                        'type'      => 'switch',
                        'title'     => esc_html__('Always Active', 'ninezeroseven'),
                        'subtitle'  => esc_html__('If enabled main nav will be hidden and mobile nav will always be active regardless of screen width. Leave disabled to have menu active at a certain screen width.', 'ninezeroseven'),
                        'default'   => 0,
                        'on'        => 'Enabled',
                        'off'       => 'Disabled',

                    ),
                    array(
                        'id'        => 'opts-mobile-menu-width',
                        'type'      => 'slider',
                        'required'  => array('opts-mobile-menu-base', "!=", 1),
                        'title'     => esc_html__('Menu Visible At', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Changes when the mobile menu becomes active/visible based on screen width. Default is 767px', 'ninezeroseven'),
                        "default"   => 767,
                        "min"       => 0,
                        "step"      => 1,
                        "max"       => 2000,
                        'display_value' => 'text'
                    ),
                    array(
                    'id'        => 'opts-mobile-menu-background',
                    'type'      => $options_color_field,
                    // 'output'    => array('.header-bar'),
                    'title'     => esc_html__('Background Color', 'ninezeroseven'),
                    'subtitle'  => esc_html__('Change the mobile menu background color.', 'ninezeroseven'),
                    'transparent' => false,
                    'default'   => '',
                    'output'    => array(
                            'background-color' => '.mobile-nav-menu,.menu-bar-wrapper.is-sticky .mobile-nav-menu .wbc_menu li > ul'
                        )
                    ),
                    array(
                        'id'        => 'opts-mobile-menu-link-background',
                        'type'      => $options_color_field,
                        // 'output'    => array('.header-bar'),
                        'title'     => esc_html__('Hover Background Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Sets a background color when hover over links within the mobile menu', 'ninezeroseven'),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'background-color' => '.mobile-nav-menu .wbc_menu a:hover'
                            )
                    ),
                    array(
                        'id'        => 'opts-mobile-menu-toggle',
                        'type'      => $options_color_field,
                        // 'output'    => array('.header-bar'),
                        'title'     => esc_html__('Toggle Icon Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Changes color of mobile menu icon.', 'ninezeroseven'),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'color' => '.has-transparent-menu .mobile-menu .menu-bar-wrapper a.menu-icon, .header-bar .menu-icon'
                            )
                    ),
                    array(
                        'id'        => 'opts-mobile-menu-hover-toggle',
                        'type'      => $options_color_field,
                        // 'output'    => array('.header-bar'),
                        'title'     => esc_html__('Toggle Hover Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Changes color of mobile menu icon when hovered over.', 'ninezeroseven'),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'color' => '.has-transparent-menu .mobile-menu .menu-bar-wrapper a.menu-icon:hover,.header-bar .menu-icon:hover'
                            )
                    ),
                    array(
                        'id'        => 'opts-mobile-menu-link-color',
                        'type'      => $options_color_field,
                        'output'    => array('.header-inner'),
                        'title'     => esc_html__('Link Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Change the mobile menu link colors.', 'ninezeroseven'),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'color' => '.has-transparent-menu .menu-bar-wrapper:not(.is-sticky) .mobile-nav-menu .wbc_menu a,.mobile-nav-menu .wbc_menu a,.menu-bar-wrapper.is-sticky .mobile-nav-menu .wbc_menu > li > a,.mobile-nav-menu li.menu-item-has-children i'
                            )
                    ),
                    array(
                        'id'        => 'opts-mobile-menu-link-color-hover',
                        'type'      => $options_color_field,
                        'output'    => array('.header-inner'),
                        'title'     => esc_html__('Link Hover Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Change the mobile menu link hover colors.', 'ninezeroseven'),
                        // 'required'  => array('opts-enable-menu-color', "=", 1),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'color' => '.has-transparent-menu .menu-bar-wrapper:not(.is-sticky) .wbc_menu > li > a:hover,.has-transparent-menu .menu-bar-wrapper:not(.is-sticky) .mobile-nav-menu .wbc_menu li a:hover,.mobile-nav-menu .wbc_menu a:hover,.menu-bar-wrapper.is-sticky .mobile-nav-menu .wbc_menu > li > a:hover'
                            )
                    ),
                    array(
                        'id'        => 'opts-mobile-menu-link-color-border',
                        'type'      => $options_color_field,
                        'title'     => esc_html__('Border Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Change border color below mobile menu items', 'ninezeroseven'),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'border-color' => '.mobile-nav-menu .wbc_menu a,.menu-bar-wrapper.is-sticky .mobile-nav-menu .wbc_menu ul li a'
                            )
                    ),
                    // array(
                    //     'id'        => 'opts-mobile-menu-link-color-active',
                    //     'type'      => $options_color_field,
                    //     'output'    => array('.header-bar'),
                    //     'title'     => esc_html__('Main Nav Active Color', 'ninezeroseven'),
                    //     'subtitle'  => esc_html__('Change the main navigation active color.', 'ninezeroseven'),
                    //     'required'  => array('opts-enable-menu-color', "=", 1),
                    //     'transparent' => false,
                    //     'default'   => '',
                    //     'output'    => array(
                    //             'color' => '.mobile-nav-menu .wbc_menu li.active a'
                    //         )
                    // ),
                    )
                );
            /************************************************************************
            * Page Title Bar
            *************************************************************************/
            $this->sections[] = array(
                'title'     => esc_html__('Page Title Bar', 'ninezeroseven'),
                //'desc'      => esc_html__('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'ninezeroseven'),
                'icon'      => 'fa fa-th-list',
                //'subsection' => true,
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(
                    array(
                        'id'        => 'opts-bread-crumb',
                        'type'      => 'switch',
                        'title'     => esc_html__('Show/Hide Page Title Bar', 'ninezeroseven'),
                        'subtitle'  => esc_html__('You can choose to show/hide the page title bar here.', 'ninezeroseven'),
                        'default'   => 1,
                        'on'        => 'Enabled',
                        'off'       => 'Disabled',

                    ),
                    array(
                        'id'        => 'opts-breadcrumb-align',
                        'type'      => 'button_set',
                        'required'  => array('opts-bread-crumb', "=", 1),
                        'title'     => esc_html__('Page Title Alignment', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Aligns the text within the page title area based on selected.', 'ninezeroseven'),                        
                        //Must provide key => value pairs for radio options
                        'options'   => array(
                            'left'   => 'Left', 
                            'center' => 'Center', 
                            'right'  => 'Right'
                        ), 
                        'default'   => 'left'
                    ),

                    array(
                        'id'        => 'opts-blog-posts-title-bar',
                        'type'      => 'button_set',
                        'required'  => array('opts-bread-crumb', "=", 1),
                        'title'     => esc_html__('Posts Title Option', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Allows to have "Blog"(generic) title shown in title bar, or post title. Only applies to post single page view. ', 'ninezeroseven'),                        
                        //Must provide key => value pairs for radio options
                        'options'   => array(
                            'generic' => 'Generic', 
                            'title'  => 'Post Title'
                        ), 
                        'default'   => 'generic'
                    ),
                    array(
                        'id'   => 'opts-page-title-style-info',
                        'type' => 'info',
                        'required'  => array('opts-bread-crumb', "=", 1),
                        'style'=> 'normal',
                        'desc' => __('<b>Page Title Styling</b>', 'ninezeroseven')
                    ),
                    array(
                        'id'       => 'opts-breadcrumb-fullwidth',
                        'type'     => 'switch',
                        'title'    => esc_html__('100% Page Title Width', 'ninezeroseven'),
                        'subtitle' => esc_html__('Makes the page title area full width.', 'ninezeroseven'),
                        'required' => array('opts-bread-crumb', "=", 1),
                        'default'  => 0,
                        'on'       => 'Enabled',
                        'off'      => 'Disabled',

                    ),
                    array(
                        'id'             => 'opts-breadcrumb-spacing',
                        'type'           => 'spacing',
                        'output'         => array('.page-title-wrap'),
                        'required'       => array('opts-bread-crumb', "=", 1),
                        'units'          => 'px',
                        'left'           => false,
                        'right'          => false,
                        'units_extended' => false,
                        'title'          => esc_html__('Page Title Padding', 'ninezeroseven'),
                        'subtitle'       => esc_html__('Set page title padding top/bottom :)', 'ninezeroseven'),
                        'desc'           => esc_html__('Enter width value + unit. IE 20px, 1em, etc.', 'ninezeroseven'),
                        'default'        => array(
                            'padding-top'     => '',
                            'padding-bottom'  => '',
                        )
                    ),
                    array(
                        'id'        => 'opts-breadcrumb-background',
                        'type'      => 'background',
                        'required'  => array('opts-bread-crumb', "=", 1),
                        'output'    => array('.page-title-wrap'),
                        'transparent' => false,
                        'title'     => esc_html__('Page Title Bar Background', 'ninezeroseven'),
                        'subtitle'  => esc_html__('You can set background here for page title bar area.', 'ninezeroseven'),
                        'default'   => array(
                            'background-color'      => '',
                            'background-repeat'     => '',
                            'background-attachment' => '',
                            'background-position'   => '',
                            'background-image'      => '',
                            'background-clip'       => '',
                            'background-origin'     => '',
                            'background-size'       => '',
                            )
                        ,
                    ),
                    array(
                        'id'             => 'opts-breadcrumb-title-font',
                        'type'           => 'typography',
                        'title'          => esc_html__('Page Title Font', 'ninezeroseven'),
                        'subtitle'       => esc_html__('Changes the font for the page title within the page title bar', 'ninezeroseven'),
                        'google'         => true,
                        'required'  => array('opts-bread-crumb', "=", 1),
                        'text-align'     => false,
                        'letter-spacing' => true,
                        'font-backup'    => true,
                        'margin-bottom' => true,
                        'default'        => array(
                            'font-size'     => '',
                            'font-family'   => '',
                            'font-weight'   => ''
                        ),
                        'output'         => array('.page-title-wrap .entry-title')
                
                    ),
                    array(
                        'id'   => 'opts-page-title-breadcrumb-info',
                        'type' => 'info',
                        'required'  => array('opts-bread-crumb', "=", 1),
                        'style'=> 'normal',
                        'desc' => __('<b>Breadcrumb Links</b>', 'ninezeroseven')
                    ),
                    array(
                        'id'       => 'opts-breadcrumb-links-visible',
                        'type'     => 'switch',
                        'title'    => esc_html__('Breadcrumb Links', 'ninezeroseven'),
                        'subtitle' => esc_html__('Hide the breadcrumb links', 'ninezeroseven'),
                        'required' => array('opts-bread-crumb', "=", 1),
                        'default'  => 1,
                        'on'       => 'Show',
                        'off'      => 'Hide',

                    ),
                    array(
                        'id'             => 'opts-breadcrumb-links-spacing',
                        'type'           => 'spacing',
                        'output'         => array('.page-title-wrap .breadcrumb'),
                        'required'  => array('opts-breadcrumb-links-visible', "=", 1),
                        'units'          => 'px',
                        'left'           => false,
                        'right'          => false,
                        'units_extended' => false,
                        'title'          => esc_html__('Breadcrumb Padding', 'ninezeroseven'),
                        'subtitle'       => esc_html__('Set breadcrumb padding top/bottom :)', 'ninezeroseven'),
                        'desc'           => esc_html__('Enter width value + unit. IE 20px, 1em, etc.', 'ninezeroseven'),
                        'default'        => array(
                            'padding-top'     => '',
                            'padding-bottom'  => '',
                        )
                    ),
                    array(
                        'id'             => 'opts-breadcrumb-font',
                        'type'           => 'typography',
                        'title'          => esc_html__('Breadcrumb Typography', 'ninezeroseven'),
                        'subtitle'       => esc_html__('Font options for breadcrumb links', 'ninezeroseven'),
                        'required'       => array('opts-breadcrumb-links-visible', "=", 1),
                        'google'         => true,
                        'text-align'     => false,
                        'letter-spacing' => true,
                        'font-backup'    => true,
                        // 'margin-top'     => true,
                        // 'margin-bottom'  => true,
                        'default'        => array(
                            'font-size'     => '',
                            'font-family'   => '',
                            'font-weight'   => ''
                        ),
                        'output'    => array('.page-title-wrap .breadcrumb')
                
                    ),
                    array(
                        'id'        => 'opts-breadcrumb-color',
                        'type'      => $options_color_field,
                        'title'     => esc_html__('Breadcrumb Font Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Change the breadcrumb color.', 'ninezeroseven'),
                        'required'  => array('opts-breadcrumb-links-visible', "=", 1),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'color' => '.page-title-wrap'
                            )
                    )
                    ,
                    array(
                        'id'        => 'opts-breadcrumb-link-color',
                        'type'      => $options_color_field,
                        'title'     => esc_html__('Breadcrumb Link Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Change the breadcrumb link color.', 'ninezeroseven'),
                        'required'  => array('opts-breadcrumb-links-visible', "=", 1),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'color' => '.page-title-wrap a'
                            )
                    ),
                    array(
                        'id'        => 'opts-breadcrumb-hover-color',
                        'type'      => $options_color_field,
                        'title'     => esc_html__('Breadcrumb Hover Link Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Change the breadcrumb hover color.', 'ninezeroseven'),
                        'required'  => array('opts-breadcrumb-links-visible', "=", 1),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'color' => '.page-title-wrap a:hover'
                            )
                    ),
                    ),
                );//END PAGE TITLE BAR
            /************************************************************************
            * Page Title Bar
            *************************************************************************/
            $this->sections[] = array(
                'title'     => esc_html__('Sidebars', 'ninezeroseven'),
                //'desc'      => esc_html__('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'ninezeroseven'),
                'icon'      => 'fa fa-columns',
                // 'subsection' => true,
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(),
                );
            $this->sections[] = array(
                'title'     => esc_html__('Widget Styling', 'ninezeroseven'),
                //'desc'      => esc_html__('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'ninezeroseven'),
                'subsection' => true,
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(
                        array(
                        'id'             => 'opts-sidebar-widget-font',
                        'type'           => 'typography',
                        'title'          => esc_html__('Sidebar Widgets Heading', 'ninezeroseven'),
                        'subtitle'       => esc_html__('Font options for sidebar widgets', 'ninezeroseven'),
                        'google'         => true,
                        'text-align'     => false,
                        'letter-spacing' => true,
                        'font-backup'    => true,
                        'margin-top'     => true,
                        'margin-bottom'  => true,
                        'default'        => array(
                            'font-size'     => '',
                            'font-family'   => '',
                            'font-weight'   => ''
                        ),
                        'output'    => array('.side-bar .widget .widget-title, .side-bar .widget .widgettitle')
                
                    ),
                    array(
                        'id'             => 'opts-sidebar-widget-text',
                        'type'           => 'typography',
                        'title'          => esc_html__('Sidebar Widget Text Typography', 'ninezeroseven'),
                        'subtitle'       => esc_html__('Font options for text in sidebar widgets', 'ninezeroseven'),
                        'google'         => true,
                        'text-align'     => false,
                        'letter-spacing' => true,
                        'font-backup'    => true,
                        'margin-top'     => true,
                        'margin-bottom'  => true,
                        'default'        => array(
                            'font-size'     => '',
                            'font-family'   => '',
                            'font-weight'   => ''
                        ),
                        'output'    => array('.side-bar .widget, .side-bar .widget')
                
                    ),
                    array(
                        'id'        => 'opts-sidebar-link-color',
                        'type'      => $options_color_field,
                        'title'     => esc_html__('Sidebar Widget Link Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Changes link color in sidebar.', 'ninezeroseven'),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'color' => '.side-bar .widget a, .side-bar .widget a'
                            )
                    ),
                    array(
                        'id'        => 'opts-sidebar-link-color-hover',
                        'type'      => $options_color_field,
                        'title'     => esc_html__('Sidebar Widget Hover Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Changes link hover color in sidebar.', 'ninezeroseven'),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'color' => '.side-bar .widget a:hover, .side-bar .widget a:hover'
                            )
                    ),
                    array(
                        'id'        => 'opts-sidebar-widget-ul-border',
                        'type'      => $options_color_field,
                        'title'     => esc_html__('Sidebar Widget LI\'s Border Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Changes color of border in ul widgets.', 'ninezeroseven'),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'border-bottom-color' => '.side-bar .widget ul li'
                            )
                    ),
                    array(
                        'id'             => 'opts-sidebar-widget-spacing',
                        'type'           => 'spacing',
                        'output'         => array('.side-bar .widget, .side-bar .widget'),
                        'units'          => 'px',
                        'mode'           => 'margin',
                        'left'           => false,
                        'right'          => false,
                        'units_extended' => false,
                        'title'          => esc_html__('Sidebar Widget Spacing', 'ninezeroseven'),
                        'subtitle'       => esc_html__('Sets spacing between above/below sidebar widgets.', 'ninezeroseven'),
                        'desc'           => esc_html__('Enter width value + unit. IE 20px, 1em, etc.', 'ninezeroseven'),
                        'default'        => array(
                            'margin-bottom' => '',
                        )
                    ),
                    array(
                        'id'             => 'opts-sidebar-widget-li-spacing',
                        'type'           => 'spacing',
                        'output'         => array('.side-bar .widget li, .side-bar .widget li'),
                        'units'          => 'px',
                        'left'           => false,
                        'right'          => false,
                        'units_extended' => false,
                        'title'          => esc_html__('Sidebar Widget LI Spacing', 'ninezeroseven'),
                        'subtitle'       => esc_html__('Sets spacing between li elements in sidebar widgets.', 'ninezeroseven'),
                        'desc'           => esc_html__('Enter width value + unit. IE 20px, 1em, etc.', 'ninezeroseven'),
                        'default'        => array(
                            'padding-top'    => '',
                            'padding-bottom' => '',
                        )
                    ),
                    ),
                );//END Widget Styling
            
            /************************************************************************
            * Footer Settings
            *************************************************************************/
            $this->sections[] = array(
                'title'     => esc_html__('Footer', 'ninezeroseven'),
                //'desc'      => esc_html__('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'ninezeroseven'),
                'icon'      => 'fa fa-th-large',
                //'subsection' => true, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(
                    array(
                        'id'       => 'opts-footer-disable',
                        'type'     => 'switch',
                        'title'    => esc_html__('Show/Hide Footer', 'ninezeroseven'),
                        'on'       => 'Enabled',
                        'off'      => 'Disabled',
                        'default'  => 1,

                    ),
                    array(
                        'id'       => 'opts-footer-fullwidth',
                        'type'     => 'switch',
                        'title'    => esc_html__('100% Footer Width', 'ninezeroseven'),
                        'subtitle' => esc_html__('Makes the footer area full width.', 'ninezeroseven'),
                        'required' => array('opts-footer-disable', "=", 1),
                        'default'  => 0,
                        'on'       => 'Enabled',
                        'off'      => 'Disabled',

                    ),
                    array(
                        'id'       => 'opts-footer-widget-area-disable',
                        'type'     => 'switch',
                        'required'  => array('opts-footer-disable', "=", 1),
                        'title'    => esc_html__('Show/Hide Footer Widget Area', 'ninezeroseven'),
                        'on'       => 'Enabled',
                        'off'      => 'Disabled',
                        'default'  => 1,

                    ),
                    array(
                        'id'             => 'opts-footer-spacing',
                        'type'           => 'spacing',
                        'output'         => array('.main-footer .widgets-area'),
                        'required'       => array('opts-footer-widget-area-disable', "=", 1),
                        'units'          => 'px',
                        // 'left'           => false,
                        // 'right'          => false,
                        'units_extended' => false,
                        'title'          => esc_html__('Footer Spacing', 'ninezeroseven'),
                        'subtitle'       => esc_html__('Set footer padding, using left and right is best with 100% full width footer enabled.', 'ninezeroseven'),
                        'desc'           => esc_html__('Enter width value + unit. IE 20px, 1em, etc.', 'ninezeroseven'),
                        'default'        => array(
                            'padding-top'    => '',
                            'padding-bottom' => '',
                            'padding-left'   => '',
                            'padding-top'    => '',
                        )
                    ),
                    array(
                        'id'        => 'opts-footer',
                        'type'      => 'select',
                        'required'  => array('opts-footer-widget-area-disable', "=", 1),
                        'title'     => esc_html__('Footer Columns', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Select how many columns you\'d like in the footer', 'ninezeroseven'),
                        
                        //Must provide key => value pairs for select options
                        'options'   => array(
                            '3' => '3 Columns', 
                            '4' => '4 Columns'
                        ),
                        'default'   => '4'
                    ),
                    array(
                        'id'        => 'opts-enable-footer-color',
                        'required'  => array('opts-footer-widget-area-disable', "=", 1),
                        'type'      => 'checkbox',
                        'title'     => esc_html__('Footer Coloring', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Check to enable color fields for footer.', 'ninezeroseven'),
                        'default'   => '0'// 1 = on | 0 = off
                    ),
                    array(
                        'id'   => 'opts-footer-widget-style-info',
                        'type' => 'info',
                        'required'  => array('opts-enable-footer-color', "=", 1),
                        'style'=> 'normal',
                        'desc' => __('<b>Footer Widget Area Styling</b>', 'ninezeroseven')
                    ),//GO HERE
                    array(
                        'id'        => 'opts-footer-background-image',
                        'type'      => 'background',
                        'required'  => array('opts-enable-footer-color', "=", 1),
                        'output'    => array('.main-footer'),
                        'transparent' => false,
                        'background-color' => false,
                        'title'     => esc_html__('Footer Background Image', 'ninezeroseven'),
                        'subtitle'  => esc_html__('You can set background here for footer widget area.', 'ninezeroseven'),
                        'default'   => array(
                            'background-repeat'     => '',
                            'background-attachment' => '',
                            'background-position'   => '',
                            'background-image'      => '',
                            'background-clip'       => '',
                            'background-origin'     => '',
                            'background-size'       => '',
                            )
                        ,
                    ),
                    array(
                        'id'        => 'opts-footer-background-color',
                        'type'      => $options_color_field,
                        'title'     => esc_html__('Footer Background Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Change the background color.', 'ninezeroseven'),
                        'required'  => array('opts-enable-footer-color', "=", 1),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'background-color' => '.main-footer'
                            )
                    ),
                    array(
                        'id'             => 'opts-footer-widget-font',
                        'type'           => 'typography',
                        'title'          => esc_html__('Footer Widget Heading Typography', 'ninezeroseven'),
                        'subtitle'       => esc_html__('Font options for footer widgets', 'ninezeroseven'),
                        'required'  => array('opts-enable-footer-color', "=", 1),
                        'google'         => true,
                        'text-align'     => false,
                        'letter-spacing' => true,
                        'font-backup'    => true,
                        'margin-top'     => true,
                        'margin-bottom'  => true,
                        'default'        => array(
                            'font-size'     => '',
                            'font-family'   => '',
                            'font-weight'   => ''
                        ),
                        'output'    => array('.main-footer .widgets-area h4')
                
                    ),
                    array(
                        'id'             => 'opts-footer-widget-text',
                        'type'           => 'typography',
                        'title'          => esc_html__('Footer Widget Text Typography', 'ninezeroseven'),
                        'subtitle'       => esc_html__('Font options for text in footer widgets', 'ninezeroseven'),
                        'required'  => array('opts-enable-footer-color', "=", 1),
                        'google'         => true,
                        'text-align'     => false,
                        'letter-spacing' => true,
                        'font-backup'    => true,
                        'margin-top'     => true,
                        'margin-bottom'  => true,
                        'default'        => array(
                            'font-size'     => '',
                            'font-family'   => '',
                            'font-weight'   => ''
                        ),
                        'output'    => array('.main-footer .widgets-area .widget')
                
                    ),
                    array(
                        'id'        => 'opts-footer-color',
                        'type'      => $options_color_field,
                        'title'     => esc_html__('Footer Text Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Changes footer text color', 'ninezeroseven'),
                        'required'  => array('opts-enable-footer-color', "=", 1),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'color' => '.main-footer'
                            )
                    ),
                    array(
                            'id'        => 'opts-footer-heading-widget-link-color',
                            'type'      => $options_color_field,
                            'title'     => esc_html__('Footer Heading Link Color', 'ninezeroseven'),
                            'subtitle'  => esc_html__('Changes the heading link colors in the recent post/portfolio widgets.', 'ninezeroseven'),
                            'required'  => array('opts-enable-footer-color', "=", 1),
                            'transparent' => false,
                            'default'   => '',
                            'output'    => array(
                                    'color' => '.main-footer .widgets-area .wbc-recent-post-widget h6 a'
                                )
                        ),
                    array(
                        'id'        => 'opts-footer-heading-color',
                        'type'      => $options_color_field,
                        'title'     => esc_html__('Footer Heading Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Changes heading color for the widgets', 'ninezeroseven'),
                        'required'  => array('opts-enable-footer-color', "=", 1),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'color' => '.main-footer .widgets-area h4'
                            )
                    ),
                    array(
                        'id'        => 'opts-footer-link-color',
                        'type'      => $options_color_field,
                        'title'     => esc_html__('Footer Link Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Changes link colors', 'ninezeroseven'),
                        'required'  => array('opts-enable-footer-color', "=", 1),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'color' => '.main-footer a'
                            )
                    ),
                    array(
                        'id'        => 'opts-footer-link-color-hover',
                        'type'      => $options_color_field,
                        'title'     => esc_html__('Footer Hover Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Changes link hover colors', 'ninezeroseven'),
                        'required'  => array('opts-enable-footer-color', "=", 1),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'color' => '.main-footer a:hover'
                            )
                    ),
                    array(
                        'id'        => 'opts-footer-widget-ul-border',
                        'type'      => $options_color_field,
                        'title'     => esc_html__('Footer Widget UL Border Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Changes color of border in ul widgets.', 'ninezeroseven'),
                        'required'  => array('opts-enable-footer-color', "=", 1),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'border-bottom-color' => '.main-footer .widgets-area .widget li'
                            )
                    ),
                    array(
                        'id'             => 'opts-footer-widget-spacing',
                        'type'           => 'spacing',
                        'output'         => array('.main-footer .widgets-area .widget'),
                        'units'          => 'px',
                        'mode'           => 'margin',
                        'left'           => false,
                        'right'          => false,
                        'units_extended' => false,
                        'title'          => esc_html__('Footer Widget Spacing', 'ninezeroseven'),
                        'subtitle'       => esc_html__('Sets spacing above/below widgets.', 'ninezeroseven'),
                        'desc'           => esc_html__('Enter width value + unit. IE 20px, 1em, etc.', 'ninezeroseven'),
                        'default'        => array(
                            'margin-bottom' => '',
                        )
                    ),
                    array(
                        'id'             => 'opts-footer-widget-li-spacing',
                        'type'           => 'spacing',
                        'output'         => array('.main-footer .widgets-area .widget li'),
                        'required'       => array('opts-enable-footer-color', "=", 1),
                        'units'          => 'px',
                        'left'           => false,
                        'right'          => false,
                        'units_extended' => false,
                        'title'          => esc_html__('Footer LI Spacing', 'ninezeroseven'),
                        'subtitle'       => esc_html__('Sets spacing between li elements in footer widgets.', 'ninezeroseven'),
                        'desc'           => esc_html__('Enter width value + unit. IE 20px, 1em, etc.', 'ninezeroseven'),
                        'default'        => array(
                            'padding-top'    => '',
                            'padding-bottom' => '',
                        )
                    ),

                    /************************************************************************
                    * Second Footer
                    *************************************************************************/
                    array(
                        'id'       => 'opts-footer-copyright-disable',
                        'type'     => 'switch',
                        'required'  => array('opts-footer-disable', "=", 1),
                        'title'    => esc_html__('Show/Hide Footer Copyright area.', 'ninezeroseven'),
                        'on'       => 'Enabled',
                        'off'      => 'Disabled',
                        'default'  => 1,

                    ),
                    array(
                        'id'   => 'opts-footer-copyright-info',
                        'type' => 'info',
                        'required'  => array('opts-footer-copyright-disable', "=", 1),
                        'style'=> 'normal',
                        'desc' => __('<b>Copyright Bar</b>', 'ninezeroseven')
                    ),
                    array(
                        'id'        => 'opts-footer-credit',
                        'type'      => 'textarea',
                        'required'  => array('opts-footer-copyright-disable', "=", 1),
                        'title'     => esc_html__('Footer Credit Area', 'ninezeroseven'),
                        'subtitle'  => esc_html__('This is the credit area in the footer area', 'ninezeroseven'),
                        'validate'  => 'html',
                        'desc'      => '',
                    ),
                    array(
                        'id'             => 'opts-footer-credit-spacing',
                        'type'           => 'spacing',
                        'output'         => array('.main-footer .bottom-band'),
                        'required'       => array('opts-footer-copyright-disable', "=", 1),
                        'units'          => 'px',
                        // 'left'           => false,
                        // 'right'          => false,
                        'units_extended' => false,
                        'title'          => esc_html__('Copyright bar spacing', 'ninezeroseven'),
                        'subtitle'       => esc_html__('Set copyright padding, using left and right is best with 100% full width footer enabled.', 'ninezeroseven'),
                        'desc'           => esc_html__('Enter width value + unit. IE 20px, 1em, etc.', 'ninezeroseven'),
                        'default'        => array(
                            'padding-top'    => '',
                            'padding-bottom' => '',
                            'padding-left'   => '',
                            'padding-top'    => '',
                        )
                    ),
                    array(
                        'id'        => 'opts-enable-footer2-color',
                        'required'  => array('opts-footer-copyright-disable', "=", 1),
                        'type'      => 'checkbox',
                        'title'     => esc_html__('Copyright Bar Stying Options', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Check to enable color fields for bottom footer, this is the band at the bottom of the page.', 'ninezeroseven'),
                        'default'   => '0'// 1 = on | 0 = off
                    ),
                    array(
                        'id'   => 'opts-footer-copyright-info-style',
                        'type' => 'info',
                        'required'  => array('opts-enable-footer2-color', "=", 1),
                        'style'=> 'normal',
                        'desc' => __('<b>Copyright Bar Styling</b>', 'ninezeroseven')
                    ),
                    array(
                        'id'        => 'opts-footer2-background-color',
                        'type'      => $options_color_field,
                        'title'     => esc_html__('Background Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Change the background color.', 'ninezeroseven'),
                        'required'  => array('opts-enable-footer2-color', "=", 1),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'background-color' => '.bottom-band,body'
                            )
                    ),
                    array(
                        'id'        => 'opts-footer2-border-color',
                        'type'      => $options_color_field,
                        'title'     => esc_html__('Top Border Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Change the top border color.', 'ninezeroseven'),
                        'required'  => array('opts-enable-footer2-color', "=", 1),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'border-top-color' => '.bottom-band'
                            )
                    ),
                    array(
                        'id'        => 'opts-footer2-color',
                        'type'      => $options_color_field,
                        'title'     => esc_html__('Text Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Changes the text color in the bottom footer.', 'ninezeroseven'),
                        'required'  => array('opts-enable-footer2-color', "=", 1),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'color' => '.bottom-band'
                            )
                    ),
                    array(
                        'id'        => 'opts-footer2-link-color',
                        'type'      => $options_color_field,
                        'title'     => esc_html__('Link Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Changes the text link color in the bottom footer', 'ninezeroseven'),
                        'required'  => array('opts-enable-footer2-color', "=", 1),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'color' => '.bottom-band a'
                            )
                    ),
                    array(
                        'id'        => 'opts-footer2-link-color-hover',
                        'type'      => $options_color_field,
                        'title'     => esc_html__('Link Hover Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Changes the text link hover color in the bottom footer', 'ninezeroseven'),
                        'required'  => array('opts-enable-footer2-color', "=", 1),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'color' => '.bottom-band a:hover'
                            )
                    ),////

                    ),
                );


            /************************************************************************
            * Blog/Page Layout Options
            *************************************************************************/

            $this->sections[] = array(
                'title'     => esc_html__('Blog', 'ninezeroseven'),
                //'desc'      => esc_html__('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'ninezeroseven'),
                'icon'      => 'fa fa-pencil',
                //'subsection' => true, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(
                    array(
                        'id'        => 'opts-author-box',
                        'type'      => 'checkbox',
                        'title'     => esc_html__('Author Box', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Uncheck to hide author box on single post pages', 'ninezeroseven'),
                        'default'   => '1'// 1 = on | 0 = off
                    ),

                    array(
                        'id'        => 'opts-blog-style',
                        'type'      => 'select',
                        'title'     => esc_html__('Blog Post Style', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Can select your default blog style here', 'ninezeroseven'),
                        
                        //Must provide key => value pairs for select options
                        'options'   => array(
                            'blog-style-1' => 'Big Image', 
                            'blog-style-2' => 'Small Image', 
                            'blog-style-3' => 'Masonry'
                        ),
                        'default'   => 'blog-style-1'
                    ),

                    array(
                        'id'        => 'opts-default-layout',
                        'type'      => 'image_select',
                        'title'     => esc_html__('Main Blog/Index Layout', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Select layout for blog/index page', 'ninezeroseven'),
                        
                        //Must provide key => value(array:title|img) pairs for radio options
                        'options'   => array(
                            'no-sidebar' => array('alt' => 'Full Width',        'img' => get_template_directory_uri() . '/includes/admin/configs/img/1col.png' ),
                            'sidebar-left' => array('alt' => 'Left Sidebar',   'img' => get_template_directory_uri() . '/includes/admin/configs/img/2cl.png' ),
                            'default' => array('alt' => 'Right Sidebar',  'img' => get_template_directory_uri() . '/includes/admin/configs/img/2cr.png' ),
                        ), 
                        'default' => 'default'
                    ),
                    array(
                        'id'        => 'opts-main-sidebar-global',
                        'type'      => 'select',
                        'required'  => array('opts-default-layout', "!=", 'no-sidebar'),
                        'title'     => esc_html__('Main Pages Sidebar', 'ninezeroseven'),
                        'subtitle'  => esc_html__('This option is for sidebar on main pages.', 'ninezeroseven'),
                        'desc'      => esc_html__('You can create additional sidebars on Appearance > Widgets', 'ninezeroseven'),
                        'data'      => 'sidebars'
                    ),
                    array(
                        'id'        => 'opts-blog-layout',
                        'type'      => 'image_select',
                        'title'     => esc_html__('Single Page Blog Layout', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Select layout for single blog page', 'ninezeroseven'),
                        
                        //Must provide key => value(array:title|img) pairs for radio options
                        'options'   => array(
                            'no-sidebar' => array('alt' => 'Full Width',        'img' => get_template_directory_uri() . '/includes/admin/configs/img/1col.png' ),
                            'sidebar-left' => array('alt' => 'Left Sidebar',   'img' => get_template_directory_uri() . '/includes/admin/configs/img/2cl.png' ),
                            'default' => array('alt' => 'Right Sidebar',  'img' => get_template_directory_uri() . '/includes/admin/configs/img/2cr.png' ),
                        ), 
                        'default' => 'default'
                    ),
                    array(
                        'id'        => 'opts-single-page-sidebar-global',
                        'type'      => 'select',
                        'required'  => array('opts-blog-layout', "!=", 'no-sidebar'),
                        'title'     => esc_html__('Single Pages Sidebar', 'ninezeroseven'),
                        'subtitle'  => esc_html__('This option is for sidebar on single pages/posts.', 'ninezeroseven'),
                        'desc'      => esc_html__('You can create additional sidebars on Appearance > Widgets', 'ninezeroseven'),
                        'data'      => 'sidebars'
                    ),
                    array(
                        'id'       => 'opts-post-single-page-images',
                        'type'     => 'switch',
                        'title'    => esc_html__('Link Images to Light box?', 'ninezeroseven'),
                        'subtitle' => esc_html__('Disable to unlink images/lightbox on single page view.', 'ninezeroseven'),
                        'default'  => 1,
                        'on'       => 'Enabled',
                        'off'      => 'Disabled',
                    ),



                ));

            $this->sections[] = array(
                'title'     => esc_html__('Portfolio', 'ninezeroseven'),
                //'desc'      => esc_html__('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'ninezeroseven'),
                'icon'      => 'fa fa-picture-o',
                //'subsection' => true, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(
                    array(
                        'id'                => 'opts-portfolio-slug',
                        'type'              => 'text',
                        'title'             => esc_html__('Portfolio Slug', 'ninezeroseven'),
                        'subtitle'          => esc_html__('Change the /portfolio/ url slug.', 'ninezeroseven'),
                        'desc'              => esc_html__('Slug should be named lowercase with hypens inplace of spaces. ie \'your-slug\'', 'ninezeroseven'),
                        'validate_callback' => 'wbc907_sanitize_slug',
                        'default'           => 'portfolio'
                    ),
                    array(
                        'id'        => 'opts-portfolio-search',
                        'type'      => 'checkbox',
                        'title'     => esc_html__('Include Portfolio Search', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Checked will include portfolio in search results.', 'ninezeroseven'),
                        'default'   => '0'// 1 = on | 0 = off
                    ),
                    array(
                        'id'        => 'opts-portfolio-layout',
                        'type'      => 'image_select',
                        'title'     => esc_html__('Portfolio Layout', 'ninezeroseven'),
                        'subtitle'  => esc_html__('This is for Portfolio single page layout.', 'ninezeroseven'),
                        
                        //Must provide key => value(array:title|img) pairs for radio options
                        'options'   => array(
                            'full-width'   => array('alt' => 'Full Screen Width',     'img' => get_template_directory_uri() . '/includes/admin/configs/img/full-width.png' ),
                            'no-sidebar'   => array('alt' => 'Full Width',        'img' => get_template_directory_uri() . '/includes/admin/configs/img/1col.png' ),
                            'sidebar-left' => array('alt' => 'Left Sidebar',   'img' => get_template_directory_uri() . '/includes/admin/configs/img/2cl.png' ),
                            'default'      => array('alt' => 'Right Sidebar',  'img' => get_template_directory_uri() . '/includes/admin/configs/img/2cr.png' ),
                        ), 
                        'default' => 'default'
                    ),array(
                        'id'       => 'opts-single-portfolio-sidebar-global',
                        'subtitle'  => esc_html__('Set a default sidebar for portfolio pages', 'ninezeroseven'),
                        'title'    => esc_html__( 'Sidebar', 'ninezeroseven' ),
                        'desc'     => esc_html__('You can create additional sidebars under Appearance > Widgets.','ninezeroseven'),
                        'type'     => 'select',
                        'data'     => 'sidebars',
                        'required' => array('opts-portfolio-layout', '=', array('sidebar-left','default'))
                    ),
                    array(
                        'id'       => 'opts-portfolio-single-page-images',
                        'type'     => 'switch',
                        'title'    => esc_html__('Link Images to Light box?', 'ninezeroseven'),
                        'subtitle' => esc_html__('Disable to unlink images/lightbox on single page view.', 'ninezeroseven'),
                        'default'  => 1,
                        'on'       => 'Enabled',
                        'off'      => 'Disabled',
                    ),


                ));

            /************************************************************************
            * WBC Reusables
            *************************************************************************/
            $this->sections[] = array(
                'title'     => esc_html__('Reusables', 'ninezeroseven'),
                //'desc'      => esc_html__('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'ninezeroseven'),
                'icon'      => 'dashicons-before dashicons-chart-pie',
                // 'subsection' => true,
                // 'subsection' => true, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(
                    // array(
                    //     'id'                => 'opts-form-heitetsght',
                    //     'type'              => 'dimensions',
                    //     // 'units'             => 'px',    // You can specify a unit value. Possible: px, em, %
                    //     'width' => false,
                    //     'title'             => esc_html__('Form Fields Height', 'ninezeroseven'),
                    //     'subtitle'          => esc_html__('Height of inputs, selects, etc.', 'ninezeroseven'),
                    //     'output'    => array(
                    //             'line-height' => '.select2-container--default .select2-selection--single .select2-selection__rendered',
                    //             'height' => '.select2-container .select2-selection--single,input[type="text"],input[type="tel"], input[type="password"], input[type="email"], input[type="search"], select',
                    //         ),
                    //     'default'   => '',

                    // ),
                    )//END FIELDS
                );
            $this->sections[] = array(
                'title'     => esc_html__('Global', 'ninezeroseven'),
                //'desc'      => esc_html__('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'ninezeroseven'),
                // 'icon'      => 'dashicons-before dashicons-chart-pie',
                'subsection' => true, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(
                    //GLOBAL
                    array(
                        'id'       => 'opts-global-reuse-before',
                        'multi'    => true,
                        'title'    => esc_html__( 'Global Before', 'ninezeroseven' ),
                        'desc'     => esc_html__('Sets default reuseable section to all pages,posts, etc','ninezeroseven'),
                        'args'     => array('post_type' => array('wbc-reuseables'),'posts_per_page' => -1),
                        'type'     => 'select',
                        'sortable' => true,
                        'data'     => 'posts',
                    ),
                    array(
                        'id'       => 'opts-global-reuse-after',
                        'multi'    => true,
                        'title'    => esc_html__( 'Global After', 'ninezeroseven' ),
                        'desc'     => esc_html__('Sets default reuseable section to all pages,posts, etc','ninezeroseven'),
                        'args'     => array('post_type' => array('wbc-reuseables'),'posts_per_page' => -1),
                        'type'     => 'select',
                        'sortable' => true,
                        'data'     => 'posts',
                    ),
                ));

            //Portfolio
            $this->sections[] = array(
                'title'     => esc_html__('Portfolio', 'ninezeroseven'),
                //'desc'      => esc_html__('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'ninezeroseven'),
                'subsection' => true,
                // 'subsection' => true, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(
                    array(
                        'id'       => 'opts-wbc-portfolio-reuse-before',
                        'multi'    => true,
                        'title'    => esc_html__( 'Portfolio Before', 'ninezeroseven' ),
                        'desc'     => esc_html__('Sets default reuseable section to all portfolio pages','ninezeroseven'),
                        'args'     => array('post_type' => array('wbc-reuseables'),'posts_per_page' => -1),
                        'type'     => 'select',
                        'sortable' => true,
                        'data'     => 'posts',
                    ),
                    array(
                        'id'       => 'opts-wbc-portfolio-reuse-after',
                        'multi'    => true,
                        'title'    => esc_html__( 'Portfolio After', 'ninezeroseven' ),
                        'desc'     => esc_html__('Sets default reuseable section to all portfolio pages','ninezeroseven'),
                        'args'     => array('post_type' => array('wbc-reuseables'),'posts_per_page' => -1),
                        'type'     => 'select',
                        'sortable' => true,
                        'data'     => 'posts',
                    ),


                ));
            //posts
            $this->sections[] = array(
                'title'     => esc_html__('Posts', 'ninezeroseven'),
                //'desc'      => esc_html__('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'ninezeroseven'),
                'subsection' => true,
                // 'subsection' => true, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(
                    array(
                        'id'       => 'opts-post-reuse-before',
                        'multi'    => true,
                        'title'    => esc_html__( 'Posts Before', 'ninezeroseven' ),
                        'desc'     => esc_html__('Sets default reuseable section to all post pages','ninezeroseven'),
                        'args'     => array('post_type' => array('wbc-reuseables'),'posts_per_page' => -1),
                        'type'     => 'select',
                        'sortable' => true,
                        'data'     => 'posts',
                    ),
                    array(
                        'id'       => 'opts-post-reuse-after',
                        'multi'    => true,
                        'title'    => esc_html__( 'Posts After', 'ninezeroseven' ),
                        'desc'     => esc_html__('Sets default reuseable section to all post pages','ninezeroseven'),
                        'args'     => array('post_type' => array('wbc-reuseables'),'posts_per_page' => -1),
                        'type'     => 'select',
                        'sortable' => true,
                        'data'     => 'posts',
                    ),


                ));
            //page
            $this->sections[] = array(
                'title'     => esc_html__('Pages', 'ninezeroseven'),
                //'desc'      => esc_html__('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'ninezeroseven'),
                'subsection' => true,
                // 'subsection' => true, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(
                    array(
                        'id'       => 'opts-page-reuse-before',
                        'multi'    => true,
                        'title'    => esc_html__( 'Pages Before', 'ninezeroseven' ),
                        'desc'     => esc_html__('Sets default reuseable section to all page pages','ninezeroseven'),
                        'args'     => array('post_type' => array('wbc-reuseables'),'posts_per_page' => -1),
                        'type'     => 'select',
                        'sortable' => true,
                        'data'     => 'posts',
                    ),
                    array(
                        'id'       => 'opts-page-reuse-after',
                        'multi'    => true,
                        'title'    => esc_html__( 'Pages After', 'ninezeroseven' ),
                        'desc'     => esc_html__('Sets default reuseable section to all page pages','ninezeroseven'),
                        'args'     => array('post_type' => array('wbc-reuseables'),'posts_per_page' => -1),
                        'type'     => 'select',
                        'sortable' => true,
                        'data'     => 'posts',
                    ),

            ));
            
            //page
            $this->sections[] = array(
                'title'     => esc_html__('MISC', 'ninezeroseven'),
                //'desc'      => esc_html__('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'ninezeroseven'),
                'subsection' => true,
                // 'subsection' => true, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(
                    array(
                        'id'       => 'opts-category-reuse-before',
                        'multi'    => true,
                        'title'    => esc_html__( 'Category Before', 'ninezeroseven' ),
                        'desc'     => esc_html__('Sets default reuseable section to all category pages','ninezeroseven'),
                        'args'     => array('post_type' => array('wbc-reuseables'),'posts_per_page' => -1),
                        'type'     => 'select',
                        'sortable' => true,
                        'data'     => 'posts',
                    ),
                    array(
                        'id'       => 'opts-category-reuse-after',
                        'multi'    => true,
                        'title'    => esc_html__( 'Category After', 'ninezeroseven' ),
                        'desc'     => esc_html__('Sets default reuseable section to all category pages','ninezeroseven'),
                        'args'     => array('post_type' => array('wbc-reuseables'),'posts_per_page' => -1),
                        'type'     => 'select',
                        'sortable' => true,
                        'data'     => 'posts',
                    ),
                    array(
                        'id'       => 'opts-search-reuse-before',
                        'multi'    => true,
                        'title'    => esc_html__( 'Search Before', 'ninezeroseven' ),
                        'desc'     => esc_html__('Sets default reuseable section to all search pages','ninezeroseven'),
                        'args'     => array('post_type' => array('wbc-reuseables'),'posts_per_page' => -1),
                        'type'     => 'select',
                        'sortable' => true,
                        'data'     => 'posts',
                    ),
                    array(
                        'id'       => 'opts-search-reuse-after',
                        'multi'    => true,
                        'title'    => esc_html__( 'Search After', 'ninezeroseven' ),
                        'desc'     => esc_html__('Sets default reuseable section to all search pages','ninezeroseven'),
                        'args'     => array('post_type' => array('wbc-reuseables'),'posts_per_page' => -1),
                        'type'     => 'select',
                        'sortable' => true,
                        'data'     => 'posts',
                    ),

                    array(
                        'id'       => 'opts-404-reuse-before',
                        'multi'    => true,
                        'title'    => esc_html__( '404 Before', 'ninezeroseven' ),
                        'desc'     => esc_html__('Sets default reuseable section to all 404','ninezeroseven'),
                        'args'     => array('post_type' => array('wbc-reuseables'),'posts_per_page' => -1),
                        'type'     => 'select',
                        'sortable' => true,
                        'data'     => 'posts',
                    ),
                    array(
                        'id'       => 'opts-404-reuse-after',
                        'multi'    => true,
                        'title'    => esc_html__( '404 After', 'ninezeroseven' ),
                        'desc'     => esc_html__('Sets default reuseable section to all 404','ninezeroseven'),
                        'args'     => array('post_type' => array('wbc-reuseables'),'posts_per_page' => -1),
                        'type'     => 'select',
                        'sortable' => true,
                        'data'     => 'posts',
                    ),


                ));

            /************************************************************************
            * Typography Settings
            *************************************************************************/
            $this->sections[] = array(
                'title'     => esc_html__('Typography', 'ninezeroseven'),
                //'desc'      => esc_html__('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'ninezeroseven'),
                'icon'      => 'fa fa-font',
                // 'subsection' => true,
                // 'subsection' => true, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(
                    // array(
                    //     'id'                => 'opts-form-heitetsght',
                    //     'type'              => 'dimensions',
                    //     // 'units'             => 'px',    // You can specify a unit value. Possible: px, em, %
                    //     'width' => false,
                    //     'title'             => esc_html__('Form Fields Height', 'ninezeroseven'),
                    //     'subtitle'          => esc_html__('Height of inputs, selects, etc.', 'ninezeroseven'),
                    //     'output'    => array(
                    //             'line-height' => '.select2-container--default .select2-selection--single .select2-selection__rendered',
                    //             'height' => '.select2-container .select2-selection--single,input[type="text"],input[type="tel"], input[type="password"], input[type="email"], input[type="search"], select',
                    //         ),
                    //     'default'   => '',

                    // ),
                    )//END FIELDS
                );
            $this->sections[] = array(
                'title'     => esc_html__('Typography', 'ninezeroseven'),
                //'desc'      => esc_html__('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'ninezeroseven'),
                // 'icon'      => 'fa fa-font',
                'subsection' => true, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(
                    array(
                        'id'             => 'opts-typography-body',
                        'type'           => 'typography',
                        'title'          => esc_html__('Body Font', 'ninezeroseven'),
                        'subtitle'       => esc_html__('Specify the body font properties.', 'ninezeroseven'),
                        'google'         => true,
                        'text-align'     => false,
                        'letter-spacing' => true,
                        'font-backup'    => true,
                        'output'         => array('body')
                
                    ),
                    array(
                        'id'             => 'opts-typography-menu',
                        'type'           => 'typography',
                        'title'          => esc_html__('Main Menu Font', 'ninezeroseven'),
                        'subtitle'       => esc_html__('Specify the main menu font properties.', 'ninezeroseven'),
                        'google'         => true,
                        'text-align'     => false,
                        'letter-spacing' => true,
                        'color'          => false,
                        'line-height'    => false,
                        'font-backup'    => true,
                        'default'        => array(
                            'font-size'     => '',
                            'font-family'   => '',
                            'font-weight'   => ''
                        ),
                        'output'    => array('.wbc_menu > li > a,.mobile-nav-menu .wbc_menu a')
                
                    ),
                    array(
                        'id'             => 'opts-typography-submenu',
                        'type'           => 'typography',
                        'title'          => esc_html__('Sub Menu Font', 'ninezeroseven'),
                        'subtitle'       => esc_html__('Specify the Sub menu font properties.', 'ninezeroseven'),
                        'google'         => true,
                        'text-align'     => false,
                        'letter-spacing' => true,
                        'color'          => false,
                        'line-height'    => false,
                        'font-backup'    => true,
                        'default'        => array(
                            'font-size'     => '',
                            'font-family'   => '',
                            'font-weight'   => ''
                        ),
                        'output'    => array('.wbc_menu ul li a,.mobile-nav-menu .wbc_menu a')
                
                    ),
                    array(
                        'id'             => 'opts-typography-mobile-menu',
                        'type'           => 'typography',
                        'title'          => esc_html__('Mobile Menu Font', 'ninezeroseven'),
                        'subtitle'       => esc_html__('Specify the Sub menu font properties.', 'ninezeroseven'),
                        'google'         => true,
                        'text-align'     => false,
                        'letter-spacing' => true,
                        'color'          => false,
                        'line-height'    => false,
                        'font-backup'    => true,
                        'default'        => array(
                            'font-size'     => '',
                            'font-family'   => '',
                            'font-weight'   => ''
                        ),
                        'output'    => array('.mobile-nav-menu .wbc_menu a')
                
                    ),
                    

                    /************************************************************************
                    * HEADINGS
                    *************************************************************************/
                    array(
                        'id'        => 'opts-enable-heading-advance',
                        'type'      => 'checkbox',
                        'title'     => esc_html__('Advanced Headings (H tags)', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Check to enable advanced headings/control', 'ninezeroseven'),
                        'default'   => '0'// 1 = on | 0 = off
                    ),
                    array(
                        'id'             => 'opts-typography-heading',
                        'type'           => 'typography',
                        'required'       => array('opts-enable-heading-advance', "=", 0),
                        'title'          => esc_html__('Headings Font (H1-H6 tags)', 'ninezeroseven'),
                        'subtitle'       => esc_html__('Specify the heading tags font properties.', 'ninezeroseven'),
                        'google'         => true,
                        'text-align'     => false,
                        'letter-spacing' => false,
                        'font-size'      => false,
                        'line-height'    =>false,
                        'font-backup'    => true,
                        'default'        => array(
                            'font-size'     => '',
                            'font-family'   => '',
                            'font-weight'   => ''
                        ),
                        'output'    => array('h1,h2,h3,h4,h5,h6')
                
                    ),

                    array(
                        'id'             => 'opts-typography-h1',
                        'type'           => 'typography',
                        'required'       => array('opts-enable-heading-advance', "=", 1),
                        'title'          => esc_html__('H1 Font', 'ninezeroseven'),
                        'subtitle'       => esc_html__('Specify the H1 font properties.', 'ninezeroseven'),
                        'google'         => true,
                        'text-align'     => false,
                        'letter-spacing' => true,
                        'font-backup'    => true,
                        'margin-top'     => true,
                        'margin-bottom'  => true,
                        'default'        => array(
                            'font-size'     => '',
                            'font-family'   => '',
                            'font-weight'   => ''
                        ),
                        'output'    => array('h1')
                
                    ),
                    array(
                        'id'             => 'opts-typography-h2',
                        'type'           => 'typography',
                        'required'       => array('opts-enable-heading-advance', "=", 1),
                        'title'          => esc_html__('H2 Font', 'ninezeroseven'),
                        'subtitle'       => esc_html__('Specify the h2 font properties.', 'ninezeroseven'),
                        'google'         => true,
                        'text-align'     => false,
                        'letter-spacing' => true,
                        'font-backup'    => true,
                        'margin-top'     => true,
                        'margin-bottom'  => true,
                        'default'        => array(
                            'font-size'     => '',
                            'font-family'   => '',
                            'font-weight'   => ''
                        ),
                        'output'    => array('h2')
                
                    ),
                    array(
                        'id'             => 'opts-typography-h3',
                        'type'           => 'typography',
                        'required'       => array('opts-enable-heading-advance', "=", 1),
                        'title'          => esc_html__('H3 Font', 'ninezeroseven'),
                        'subtitle'       => esc_html__('Specify the h3 font properties.', 'ninezeroseven'),
                        'google'         => true,
                        'text-align'     => false,
                        'letter-spacing' => true,
                        'font-backup'    => true,
                        'margin-top'     => true,
                        'margin-bottom'  => true,
                        'default'        => array(
                            'font-size'     => '',
                            'font-family'   => '',
                            'font-weight'   => ''
                        ),
                        'output'    => array('h3')
                
                    ),
                    array(
                        'id'             => 'opts-typography-h4',
                        'type'           => 'typography',
                        'required'       => array('opts-enable-heading-advance', "=", 1),
                        'title'          => esc_html__('H4 Font', 'ninezeroseven'),
                        'subtitle'       => esc_html__('Specify the h4 font properties.', 'ninezeroseven'),
                        'google'         => true,
                        'text-align'     => false,
                        'letter-spacing' => true,
                        'font-backup'    => true,
                        'margin-top'     => true,
                        'margin-bottom'  => true,
                        'default'        => array(
                            'font-size'     => '',
                            'font-family'   => '',
                            'font-weight'   => ''
                        ),
                        'output'    => array('h4')
                
                    ),
                    array(
                        'id'             => 'opts-typography-h5',
                        'type'           => 'typography',
                        'required'       => array('opts-enable-heading-advance', "=", 1),
                        'title'          => esc_html__('H5 Font', 'ninezeroseven'),
                        'subtitle'       => esc_html__('Specify the H5 font properties.', 'ninezeroseven'),
                        'google'         => true,
                        'text-align'     => false,
                        'letter-spacing' => true,
                        'font-backup'    => true,
                        'margin-top'     => true,
                        'margin-bottom'  => true,
                        'default'        => array(
                            'font-size'     => '',
                            'font-family'   => '',
                            'font-weight'   => ''
                        ),
                        'output'    => array('h5')
                
                    ),
                    array(
                        'id'             => 'opts-typography-h6',
                        'type'           => 'typography',
                        'required'       => array('opts-enable-heading-advance', "=", 1),
                        'title'          => esc_html__('H6 Font', 'ninezeroseven'),
                        'subtitle'       => esc_html__('Specify the H6 font properties.', 'ninezeroseven'),
                        'google'         => true,
                        'text-align'     => false,
                        'letter-spacing' => true,
                        'font-backup'    => true,
                        'margin-top'     => true,
                        'margin-bottom'  => true,
                        'default'        => array(
                            'font-size'     => '',
                            'font-family'   => '',
                            'font-weight'   => ''
                        ),
                        'output'    => array('h6')
                
                    ),





                    )
                );

                /************************************************************************
                * Extra Heading Options
                *************************************************************************/
            $this->sections[] = array(
                'title'     => esc_html__('Extra Headings', 'ninezeroseven'),
                //'desc'      => esc_html__('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'ninezeroseven'),
                'subsection' => true,
                // 'subsection' => true, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(
                    array(
                        'id'             => 'opts-special-heading1',
                        'type'           => 'typography',
                        'title'          => esc_html__('Heading Styling 1, used in shortcodes.', 'ninezeroseven'),
                        // 'subtitle'       => esc_html__('Specify the Sub menu font properties.', 'ninezeroseven'),
                        'google'         => true,
                        'text-align'     => false,
                        'letter-spacing' => true,
                        'color'          => false,
                        'line-height'    => false,
                        'font-backup'    => true,
                        'default'        => array(
                            'font-size'     => '',
                            'font-family'   => '',
                            'font-weight'   => ''
                        ),
                        'output'    => array('.special-heading-1')
                
                    ),
                    array(
                        'id'             => 'opts-special-heading2',
                        'type'           => 'typography',
                        'title'          => esc_html__('Heading Styling 2, used in shortcodes.', 'ninezeroseven'),
                        // 'subtitle'       => esc_html__('Specify the Sub menu font properties.', 'ninezeroseven'),
                        'google'         => true,
                        'text-align'     => false,
                        'letter-spacing' => true,
                        'color'          => false,
                        'line-height'    => false,
                        'font-backup'    => true,
                        'default'        => array(
                            'font-size'     => '',
                            'font-family'   => '',
                            'font-weight'   => ''
                        ),
                        'output'    => array('.special-heading-2')
                
                    ),
                    array(
                        'id'             => 'opts-special-heading3',
                        'type'           => 'typography',
                        'title'          => esc_html__('Heading Styling 3, used in shortcodes.', 'ninezeroseven'),
                        // 'subtitle'       => esc_html__('Specify the Sub menu font properties.', 'ninezeroseven'),
                        'google'         => true,
                        'text-align'     => false,
                        'letter-spacing' => true,
                        'color'          => false,
                        'line-height'    => false,
                        'font-backup'    => true,
                        'default'        => array(
                            'font-size'     => '',
                            'font-family'   => '',
                            'font-weight'   => ''
                        ),
                        'output'    => array('.special-heading-3')
                
                    ),
                    array(
                        'id'             => 'opts-special-heading4',
                        'type'           => 'typography',
                        'title'          => esc_html__('Heading Styling 4, used in shortcodes.', 'ninezeroseven'),
                        // 'subtitle'       => esc_html__('Specify the Sub menu font properties.', 'ninezeroseven'),
                        'google'         => true,
                        'text-align'     => false,
                        'letter-spacing' => true,
                        'color'          => false,
                        'line-height'    => false,
                        'font-backup'    => true,
                        'default'        => array(
                            'font-size'     => '',
                            'font-family'   => '',
                            'font-weight'   => ''
                        ),
                        'output'    => array('.special-heading-4')
                
                    ),

                    





                    )
                );

            /************************************************************************
            * extra
            *************************************************************************/
            $this->sections[] = array(
                'title'     => esc_html__('Extras', 'ninezeroseven'),
                //'desc'      => esc_html__('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'ninezeroseven'),
                'icon'      => 'fa fa-cog',
                // 'subsection' => true,
                // 'subsection' => true, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(
                    // array(
                    //     'id'                => 'opts-form-heitetsght',
                    //     'type'              => 'dimensions',
                    //     // 'units'             => 'px',    // You can specify a unit value. Possible: px, em, %
                    //     'width' => false,
                    //     'title'             => esc_html__('Form Fields Height', 'ninezeroseven'),
                    //     'subtitle'          => esc_html__('Height of inputs, selects, etc.', 'ninezeroseven'),
                    //     'output'    => array(
                    //             'line-height' => '.select2-container--default .select2-selection--single .select2-selection__rendered',
                    //             'height' => '.select2-container .select2-selection--single,input[type="text"],input[type="tel"], input[type="password"], input[type="email"], input[type="search"], select',
                    //         ),
                    //     'default'   => '',

                    // ),
                    )//END FIELDS
                );
            /************************************************************************
            * Page Loader
            *************************************************************************/
            $this->sections[] = array(
                'title'     => esc_html__('Page Loader', 'ninezeroseven'),
                //'desc'      => esc_html__('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'ninezeroseven'),
                'subsection' => true,
                'fields'    => array(
                    array(
                        'id'        => 'opts-page-loader',
                        'type'      => 'switch',
                        'title'     => esc_html__('Page Loader', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Enable Page Loader?', 'ninezeroseven'),
                        // 'desc'      => esc_html__('This will allow you to update your theme from within the admin area like how default WordPress themes update.', 'ninezeroseven'),
                        'default' => 0,
                        'on'        => esc_html__('Enable', 'ninezeroseven'),
                        'off'       => esc_html__('Disable', 'ninezeroseven'),
                        // 'hint' => array(
                        //     'title' => 'Hint Title',
                        //     'contepant' => 'This is a <b>hint</b> for the media field with a Title.',
                        // )
                    ),
                    array(
                        'id'        => 'opts-page-loader-style',
                        'type'      => 'image_select',
                        'title'     => esc_html__('Loader Style', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Page loader show before page loads.', 'ninezeroseven'),
                        
                        //Must provide key => value(array:title|img) pairs for radio options
                        'options'   => array(
                            'wbc-loader-rotating-plane' => array('alt' => 'Rotating Plane',        'img' => get_template_directory_uri() . '/includes/admin/configs/img/spin-one.jpg' ),
                            'wbc-loader-double-bounce' => array('alt' => 'Double Bounce',   'img' => get_template_directory_uri() . '/includes/admin/configs/img/spin-two.jpg' ),
                            'wbc-loader-wave' => array('alt' => 'Wave',  'img' => get_template_directory_uri() . '/includes/admin/configs/img/spin-three.jpg' ),
                            'wbc-loader-wandering-cubes' => array('alt' => 'Wandering Cubes',        'img' => get_template_directory_uri() . '/includes/admin/configs/img/spin-four.jpg' ),
                            'wbc-loader-spinner-pulse' => array('alt' => 'Spinner Pulse',   'img' => get_template_directory_uri() . '/includes/admin/configs/img/spin-five.jpg' ),
                            'wbc-loader-chasing-dots' => array('alt' => 'Chasing Dots',  'img' => get_template_directory_uri() . '/includes/admin/configs/img/spin-six.jpg' ),
                            'wbc-loader-three-bounce' => array('alt' => 'Three Bounce',        'img' => get_template_directory_uri() . '/includes/admin/configs/img/spin-seven.jpg' ),
                            'wbc-loader-circle' => array('alt' => 'Circle',   'img' => get_template_directory_uri() . '/includes/admin/configs/img/spin-eight.jpg' ),
                            'wbc-loader-cube-grid' => array('alt' => 'Cube Grid',  'img' => get_template_directory_uri() . '/includes/admin/configs/img/spin-nine.jpg' ),
                            'wbc-loader-fading-circle' => array('alt' => 'Fading Circle',        'img' => get_template_directory_uri() . '/includes/admin/configs/img/spin-ten.jpg' ),
                            'wbc-loader-folding-cube' => array('alt' => 'Folding Cube',   'img' => get_template_directory_uri() . '/includes/admin/configs/img/spin-eleven.jpg' ),
                            
                        ), 
                        'default' => 'wbc-loader-circle',
                        'required'  => array('opts-page-loader', "=", 1),
                    ),
                    array(
                        'id'            => 'opts-page-loader-size',
                        'type'          => 'slider',
                        'required'      => array('opts-page-loader', "=", 1),
                        'title'         => esc_html__('Loader Size', 'ninezeroseven'),
                        'subtitle'      => esc_html__('Changes size of loader', 'ninezeroseven'),
                        "default"       => 60,
                        "min"           => 10,
                        "step"          => 1,
                        "max"           => 200,
                        'display_value' => 'text'
                    ),
        
                    array(
                        'id'        => 'opts-page-loader-bg-color',
                        'type'      => $options_color_field,
                        'title'     => esc_html__('Loader BG Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Change the background color.', 'ninezeroseven'),
                        'required'  => array('opts-page-loader', "=", 1),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'background-color' => '.wbc-loader-wrapper'
                            )
                    ),
                    array(
                        'id'        => 'opts-page-loader-color',
                        'type'      => $options_color_field,
                        'title'     => esc_html__('Loader Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Change the animated loader color.', 'ninezeroseven'),
                        'required'  => array('opts-page-loader', "=", 1),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'background-color' => '.wbc-loader-color,.wbc-loader div .wbc-loader-child-color,.wbc-loader div .wbc-loader-child-color-before:before'
                            )
                    ),

            ));//END PAGE LOADER
            
            /************************************************************************
            * Back To TOp
            *************************************************************************/
            $this->sections[] = array(
                'title'     => esc_html__('Back To Top', 'ninezeroseven'),
                //'desc'      => esc_html__('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'ninezeroseven'),
                'subsection' => true,
                'fields'    => array(
                    array(
                        'id'        => 'opts-page-btt',
                        'type'      => 'switch',
                        'title'     => esc_html__('Back To Top Button', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Enable Back To Top?', 'ninezeroseven'),
                        // 'desc'      => esc_html__('This will allow you to update your theme from within the admin area like how default WordPress themes update.', 'ninezeroseven'),
                        'default' => 0,
                        'on'        => esc_html__('Enable', 'ninezeroseven'),
                        'off'       => esc_html__('Disable', 'ninezeroseven'),
                        // 'hint' => array(
                        //     'title' => 'Hint Title',
                        //     'contepant' => 'This is a <b>hint</b> for the media field with a Title.',
                        // )
                    ),
                    array(
                        'id'       => 'opts-page-anchor',
                        'type'     => 'text',
                        'required' => array('opts-page-btt', "=", 1),
                        'title'    => esc_html__('Scroll To ID', 'ninezeroseven'),
                        'subtitle' => esc_html__('Custom section ID to scroll to', 'ninezeroseven'),
                        'description' => esc_html__('(Optional) Default scrolls to top of page, enter your ID ie about-me without hash tag', 'ninezeroseven'),
                        'default'  => '',
                    ),
                    array(
                        'id'       => 'opts-page-btt-icon',
                        'type'     => 'select',
                        'select2'  => array( 'containerCssClass' => ' el' ),
                        'required'      => array('opts-page-btt', "=", 1),
                        'title'    => esc_html__('Icon', 'ninezeroseven'),
                        'subtitle' => esc_html__('Displayed inside button.', 'ninezeroseven'),
                        'class'    => ' font-icons ef',
                        'default'  => 'fas fa-arrow-up',
                        'options'  => $iconArray
                    ),
                    array(
                        'id'            => 'opts-page-btt-icon-size',
                        'type'          => 'slider',
                        'required'      => array('opts-page-btt', "=", 1),
                        'title'         => esc_html__('Icon Size', 'ninezeroseven'),
                        'subtitle'      => esc_html__('Changes icon size. default 30', 'ninezeroseven'),
                        "default"       => 30,
                        "min"           => 10,
                        "step"          => 1,
                        "max"           => 100,
                        'display_value' => 'text'
                    ),
                    array(
                        'id'       => 'opts-page-btt-position',
                        'type'     => 'select',
                        'title'    => esc_html__('Button Position', 'ninezeroseven'),
                        'subtitle' => esc_html__('Where to button appears in browser window', 'ninezeroseven'),
                        'required' => array('opts-page-btt', "=", 1),
                        
                        //Must provide key => value pairs for select options
                        'options'   => array(
                                        'wbc-btt-bottom-right' => 'Bottom Right',
                                        'wbc-btt-bottom-left' => 'Bottom Left',
                                        ),
                    'default'   => 'wbc-btt-bottom-right'
                    ),
                    array(
                        'id'            => 'opts-page-btt-size',
                        'type'          => 'slider',
                        'required'      => array('opts-page-btt', "=", 1),
                        'title'         => esc_html__('Button Size', 'ninezeroseven'),
                        'subtitle'      => esc_html__('Changes button size. default 60', 'ninezeroseven'),
                        "default"       => 60,
                        "min"           => 10,
                        "step"          => 1,
                        "max"           => 200,
                        'display_value' => 'text'
                    ),
                    array(
                        'id'            => 'opts-page-btt-radius',
                        'type'          => 'slider',
                        'required'      => array('opts-page-btt', "=", 1),
                        'title'         => esc_html__('Border Radius.', 'ninezeroseven'),
                        'subtitle'      => esc_html__('Changes size border radius.  default 3', 'ninezeroseven'),
                        "default"       => 3,
                        "min"           => 0,
                        "step"          => 1,
                        "max"           => 200,
                        'display_value' => 'text'
                    ),
                    array(
                        'id'       => 'opts-page-btt-animation',
                        'type'     => 'select',
                        'title'    => esc_html__('Animation Effect', 'ninezeroseven'),
                        'subtitle' => esc_html__('Animation while scrolling back to top.', 'ninezeroseven'),
                        'required' => array('opts-page-btt', "=", 1),
                        
                        //Must provide key => value pairs for select options
                        'options'   => array('linear' => 'linear',
                                        'swing' => 'swing',
                                        'easeInQuad' => 'easeInQuad',
                                        'easeOutQuad' => 'easeOutQuad',
                                        'easeInOutQuad' => 'easeInOutQuad',
                                        'easeInCubic' => 'easeInCubic',
                                        'easeOutCubic' => 'easeOutCubic',
                                        'easeInOutCubic' => 'easeInOutCubic',
                                        'easeInQuart' => 'easeInQuart',
                                        'easeOutQuart' => 'easeOutQuart',
                                        'easeInOutQuart' => 'easeInOutQuart',
                                        'easeInQuint' => 'easeInQuint',
                                        'easeOutQuint' => 'easeOutQuint',
                                        'easeInOutQuint' => 'easeInOutQuint',
                                        'easeInExpo' => 'easeInExpo',
                                        'easeOutExpo' => 'easeOutExpo',
                                        'easeInOutExpo' => 'easeInOutExpo',
                                        'easeInSine' => 'easeInSine',
                                        'easeOutSine' => 'easeOutSine',
                                        'easeInOutSine' => 'easeInOutSine',
                                        'easeInCirc' => 'easeInCirc',
                                        'easeOutCirc' => 'easeOutCirc',
                                        'easeInOutCirc' => 'easeInOutCirc',
                                        'easeInElastic' => 'easeInElastic',
                                        'easeOutElastic' => 'easeOutElastic',
                                        'easeInOutElastic' => 'easeInOutElastic',
                                        'easeInBack' => 'easeInBack',
                                        'easeOutBack' => 'easeOutBack',
                                        'easeInOutBack' => 'easeInOutBack',
                                        'easeInBounce' => 'easeInBounce',
                                        'easeOutBounce' => 'easeOutBounce',
                                        'easeInOutBounce' => 'easeInOutBounce'
                                        ),
                    'default'   => 'swing'
                    ),
                    array(
                        'id'            => 'opts-page-btt-offset',
                        'type'          => 'slider',
                        'required'      => array('opts-page-btt', "=", 1),
                        'title'         => esc_html__('Visible Offset', 'ninezeroseven'),
                        'subtitle'      => esc_html__('How many pixels scrolled down before button becomes visible. default 300', 'ninezeroseven'),
                        "default"       => 300,
                        "min"           => 0,
                        "step"          => 10,
                        "max"           => 2000,
                        'display_value' => 'text'
                    ),
                    array(
                        'id'            => 'opts-page-btt-duration',
                        'type'          => 'slider',
                        'required'      => array('opts-page-btt', "=", 1),
                        'title'         => esc_html__('Scroll Duration', 'ninezeroseven'),
                        'subtitle'      => esc_html__('How long the animation will run, lower number scrolls faster to top when clicked. default 1500', 'ninezeroseven'),
                        "default"       => 1500,
                        "min"           => 0,
                        "step"          => 10,
                        "max"           => 5000,
                        'display_value' => 'text'
                    ),
                    array(
                        'id'        => 'opts-page-bbt-bg-color',
                        'type'      => $options_color_field,
                        'title'     => esc_html__('BG Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Changes button background color.', 'ninezeroseven'),
                        'required'  => array('opts-page-btt', "=", 1),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'background-color' => 'div.wbc-backtotop-button'
                            )
                    ),
                    array(
                        'id'        => 'opts-page-bbt-bg-hover-color',
                        'type'      => $options_color_field,
                        'title'     => esc_html__('Hover BG Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Changes button background color when hovered.', 'ninezeroseven'),
                        'required'  => array('opts-page-btt', "=", 1),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'background-color' => 'div.wbc-backtotop-button:hover'
                            )
                    ),
                    array(
                        'id'        => 'opts-page-bbt-icon-color',
                        'type'      => $options_color_field,
                        'title'     => esc_html__('Icon Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Changes icon color.', 'ninezeroseven'),
                        'required'  => array('opts-page-btt', "=", 1),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'color' => 'div.wbc-backtotop-button'
                            )
                    ),
                    array(
                        'id'        => 'opts-page-bbt-hover-icon-color',
                        'type'      => $options_color_field,
                        'title'     => esc_html__('Icon Hover Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Changes icon color when hovered.', 'ninezeroseven'),
                        'required'  => array('opts-page-btt', "=", 1),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'color' => 'div.wbc-backtotop-button:hover'
                            )
                    ),

            ));//END Back To Top
            /************************************************************************
            * Form Styling
            *************************************************************************/
            
            $this->sections[] = array(
                'title'     => esc_html__('Form Styling', 'ninezeroseven'),
                //'desc'      => esc_html__('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'ninezeroseven'),
                // 'icon'      => '',
                'subsection' => true,
                // 'subsection' => true, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(
                    array(
                        'id'                => 'opts-form-height',
                        'type'              => 'dimensions',
                        // 'units'             => 'px',    // You can specify a unit value. Possible: px, em, %
                        'width' => false,
                        'title'             => esc_html__('Form Fields Height', 'ninezeroseven'),
                        'subtitle'          => esc_html__('Height of inputs, selects, etc.', 'ninezeroseven'),
                        'output'    => array(
                                'line-height' => '.select2-container--default .select2-selection--single .select2-selection__rendered',
                                'height' => '.select2-container .select2-selection--single,input[type="text"],input[type="tel"], input[type="password"], input[type="email"], input[type="search"], select',
                            ),
                        'default'   => '',

                    ),
                    array(
                        'id'        => 'opts-page-forms-color',
                        'type'      => $options_color_field,
                        'title'     => esc_html__('Form Field BG color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Change Form Field background color.(textarea,inputs,etc)', 'ninezeroseven'),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'background-color' => '.select2-container--default .select2-selection--single,input[type="tel"],input[type="text"], input[type="password"], input[type="email"], input[type="search"], select,textarea',
                            )
                    ),
                    array(
                        'id'        => 'opts-page-forms-border-color',
                        'type'      => $options_color_field,
                        'title'     => esc_html__('Form Border Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Border around form fields', 'ninezeroseven'),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'border-color' => '.select2-container--default .select2-selection--single,input[type="text"], input[type="password"],input[type="tel"], input[type="email"], input[type="search"], select,textarea',
                                'border-left-color' => '.wbc-select-wrap .wbc-select-arrow',
                            )
                    ),
                    array(
                        'id'        => 'opts-page-forms-font-color',
                        'type'      => $options_color_field,
                        'title'     => esc_html__('Form Field Font Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Font color within form fields', 'ninezeroseven'),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'color' => '.select2-container--default .select2-selection--single .select2-selection__rendered,.wpcf7-checkbox,input[type="tel"], input[type="text"], input[type="password"], input[type="email"], input[type="search"], select,textarea',
                            )
                    ),
                    array(
                        'id'        => 'opts-page-forms-label-color',
                        'type'      => $options_color_field,
                        'title'     => esc_html__('Form Field Label Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Font color for labels within form fields', 'ninezeroseven'),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'color' => 'form label,.wpcf7-checkbox',
                            )
                    ),
                    array(
                        'id'        => 'opts-select-wrap-option',
                        'type'      => 'switch',
                        'title'     => esc_html__('Select Wrap', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Can turn off/on the select wrap, which is the down arrow form selects. Disable this if you\'re having issues with other plugins', 'ninezeroseven'),
                        'default'   => 1,
                        'on'        => esc_html__('Enable', 'ninezeroseven'),
                        'off'       => esc_html__('Disable', 'ninezeroseven'),
                    ),
                    array(
                        'id'        => 'opts-page-select-arrow-bg-color',
                        'type'      => $options_color_field,
                        'title'     => esc_html__('Form Field Select BG Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Selects BG color behide arrow toggle.', 'ninezeroseven'),
                        'transparent' => false,
                        'required'  => array('opts-select-wrap-option', "=", 1),
                        'default'   => '',
                        'output'    => array(
                                'background-color' => '.wbc-select-wrap .wbc-select-arrow',
                            )
                    ),
                    array(
                        'id'          => 'opts-page-select-arrow-color',
                        'type'        => $options_color_field,
                        'title'       => esc_html__('Form Field Select Arrow Color', 'ninezeroseven'),
                        'subtitle'    => esc_html__('Selects arrow color toggle.', 'ninezeroseven'),
                        'transparent' => false,
                        'required'    => array('opts-select-wrap-option', "=", 1),
                        'default'     => '',
                        'output'      => array(
                                'color' => '.wbc-select-wrap .wbc-select-arrow',
                            )
                    ),
                    array(
                        'id'        => 'opts-page-select-arrow-border-color',
                        'type'      => $options_color_field,
                        'title'     => esc_html__('Form Field Select Border Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Changes left border color of select arrow', 'ninezeroseven'),
                        'transparent' => false,
                        'required'  => array('opts-select-wrap-option', "=", 1),
                        'default'   => '',
                        'output'    => array(
                                'border-left-color' => '.wbc-select-wrap .wbc-select-arrow',
                            )
                    ), 
                    /************************************************************************
                    * Pre/Next Navigation
                    *************************************************************************/
                    
                    )//END FIELDS
                ); //END FORM STYLING
            /************************************************************************
            * Prev/Next Links
            *************************************************************************/
            $this->sections[] = array(
                'title'     => esc_html__('Prev/Next Navigation', 'ninezeroseven'),
                //'desc'      => esc_html__('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'ninezeroseven'),
                // 'icon'      => '',
                'subsection' => true,
                // 'subsection' => true, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(
                    ///PREV/NEXT OPTIONS
                    array(
                        'id'        => 'opts-page-navigation-enabled',
                        'type'      => 'switch',
                        'title'     => esc_html__('Next/Prev Post Navigation', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Displays links to next/prev post when viewing single post pages.', 'ninezeroseven'),
                        'default' => 0,
                        'on'        => esc_html__('Enable', 'ninezeroseven'),
                        'off'       => esc_html__('Disable', 'ninezeroseven'),
                    ),
                    array(
                        'id'        => 'opts-page-floating-nav',
                        'type'      => 'switch',
                        'title'     => esc_html__('Floating Side Navigation', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Next/Prev links are floated on the sides and expand when hovered.', 'ninezeroseven'),
                        'required'  => array('opts-page-navigation-enabled', "=", 1),
                        'default'   => 1,
                        'on'        => esc_html__('Enable', 'ninezeroseven'),
                        'off'       => esc_html__('Disable', 'ninezeroseven'),
                    ),
                    array(
                        'id'          => 'opts-page-floating-nav-bg',
                        'type'        => $options_color_field,
                        'required'  => array('opts-page-floating-nav', "=", 1),
                        'title'       => esc_html__('Floating Nav Color', 'ninezeroseven'),
                        'subtitle'    => esc_html__('Background color floating nav', 'ninezeroseven'),
                        'transparent' => false,
                        'default'     => '',
                        'output'      => array(
                                'background-color' => '.wbc-page-nav-floating'
                            ),
                    ),
                    array(
                        'id'          => 'opts-page-floating-nav-bg-hover',
                        'type'        => $options_color_field,
                        'required'  => array('opts-page-floating-nav', "=", 1),
                        'title'       => esc_html__('Floating Nav Hover Color', 'ninezeroseven'),
                        'subtitle'    => esc_html__('Background color when hovered over floating nav', 'ninezeroseven'),
                        'transparent' => false,
                        'default'     => '',
                        'output'      => array(
                                'background-color' => '.wbc-page-nav-floating:hover, .wbc-page-nav-floating:active, .wbc-page-nav-floating:focus'
                            ),
                    ),
                    array(
                        'id'          => 'opts-page-floating-nav-color',
                        'type'        => $options_color_field,
                        'required'  => array('opts-page-floating-nav', "=", 1),
                        'title'       => esc_html__('Floating Nav Text Color', 'ninezeroseven'),
                        'subtitle'    => esc_html__('Changes text color within floating nav', 'ninezeroseven'),
                        'transparent' => false,
                        'default'     => '',
                        'output'      => array(
                                'color' => '.wbc-page-nav-floating, .wbc-page-nav-floating:hover, .wbc-page-nav-floating:visited, .wbc-page-nav-floating:active, .wbc-page-nav-floating:focus'
                            ),
                    ),
                    array(
                        'id'        => 'opts-page-after-post-nav',
                        'type'      => 'switch',
                        'title'     => esc_html__('Navigation Below Posts', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Next/Prev links are shown at bottom of post content.', 'ninezeroseven'),
                        'required'  => array('opts-page-navigation-enabled', "=", 1),
                        'default'   => 0,
                        'on'        => esc_html__('Enable', 'ninezeroseven'),
                        'off'       => esc_html__('Disable', 'ninezeroseven'),
                    ),
                    array(
                        'id'        => 'opts-page-after-main-content-nav',
                        'type'      => 'switch',
                        'title'     => esc_html__('Navigation Below Main Content', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Next/Prev links are shown at bottom of main content, which makes it go full width and has more styling options.', 'ninezeroseven'),
                        'required'  => array('opts-page-after-post-nav', "=", 1),
                        'default'   => 0,
                        'on'        => esc_html__('Enable', 'ninezeroseven'),
                        'off'       => esc_html__('Disable', 'ninezeroseven'),
                    ),
                    array(
                        'id'        => 'opts-page-after-post-nav-style',
                        'type'      => 'select',
                        'title'     => esc_html__('Navigation Style', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Nav style options.', 'ninezeroseven'),
                        'required'  => array(array('opts-page-navigation-enabled', "=", 1),
                                        array('opts-page-after-post-nav', "=", 1)
                                        ),
                        
                        //Must provide key => value pairs for select options
                        'options'   => array(
                            'wbc-nav-style-2' => esc_html('Only Next/Prev', 'ninezeroseven'), 
                            'wbc-nav-style-1' => esc_html('Next/Prev With Post Title', 'ninezeroseven')
                        ),
                        'default'   => 'wbc-nav-style-1'
                    ),
                    array(
                        'id'             => 'opts-page-after-main-content-space',
                        'type'           => 'spacing',
                        'output'         => array('.page-wrapper > .wbc-nav-row-1,.page-wrapper > .wbc-nav-row-2,.container .wbc-nav-row-1,.container .wbc-nav-row-2'),
                        'required'       => array('opts-page-after-post-nav', "=", 1),
                        'units'          => 'px',
                        'left'           => false,
                        'right'          => false,
                        'units_extended' => false,
                        'title'          => esc_html__('Navigation Padding', 'ninezeroseven'),
                        'subtitle'       => esc_html__('Sets padding top/bottom of navigation container.', 'ninezeroseven'),
                        'desc'           => esc_html__('Can use px, em, %, etc.', 'ninezeroseven'),
                        'default'        => array(
                            'padding-top'     => '',
                            'padding-bottom'  => '',
                        )
                    ),
                    array(
                        'id'       => 'opts-page-after-main-content-bg',
                        'type'     => 'background',
                        'title'    => esc_html__('Navigation Background', 'ninezeroseven'),
                        'output'    => array(
                                'background-color' => '.page-wrapper > .wbc-nav-row-1,.page-wrapper > .wbc-nav-row-2'
                            ),
                        'required'  => array('opts-page-after-main-content-nav', "=", 1),
                        'subtitle' => esc_html__('Add background to the navigation container.', 'ninezeroseven'),
                    ),
                    array(
                        'id'          => 'opts-page-after-main-content-overlay',
                        'type'        => $options_color_field,
                        'required'  => array('opts-page-after-main-content-nav', "=", 1),
                        'title'       => esc_html__('Navigation Background Overlay', 'ninezeroseven'),
                        'subtitle'    => esc_html__('If you used a image background above, this will overlay it with a color.', 'ninezeroseven'),
                        'transparent' => false,
                        'default'     => '',
                        'output'      => array(
                                'background-color' => '.page-wrapper > .wbc-nav-row-1:before,.page-wrapper > .wbc-nav-row-2:before'
                            ),
                    ),array(
                        'id'             => 'opts-page-after-main-content-title-font',
                        'type'           => 'typography',
                        'title'          => esc_html__('Main Title Font', 'ninezeroseven'),
                        'subtitle'       => esc_html__('Changes the font for links in the Prev/Next navigation area.', 'ninezeroseven'),
                        'google'         => true,
                        'required'  => array(array('opts-page-after-post-nav', "=", 1)),
                        'text-align'     => false,
                        'letter-spacing' => true,
                        'font-backup'    => true,
                        'default'        => array(
                            'font-size'     => '',
                            'font-family'   => '',
                            'font-weight'   => ''
                        ),
                        'output'         => array('.wbc-nav-row-1 .wbc-nav-title,.wbc-nav-row-2 a')
                
                    ),
                    array(
                        'id'          => 'opts-page-after-main-content-title-hover',
                        'type'        => $options_color_field,
                        'required'  => array('opts-page-after-post-nav', "=", 1),
                        'title'       => esc_html__('Main Title Font Hover', 'ninezeroseven'),
                        'subtitle'    => esc_html__('Change the font\'s hovered color', 'ninezeroseven'),
                        'transparent' => false,
                        'default'     => '',
                        'output'      => array(
                                'color' => '.wbc-nav-row-1 .wbc-nav-title a:hover,.wbc-nav-row-1 .wbc-nav-title a:focus,.wbc-nav-row-1 .wbc-nav-title a:active,.wbc-nav-row-2 a:hover,.wbc-nav-row-2 a:active'
                            ),
                    ),
                    array(
                        'id'             => 'opts-page-after-main-content-small-font',
                        'type'           => 'typography',
                        'title'          => esc_html__('Small Title Font', 'ninezeroseven'),
                        'subtitle'       => esc_html__('Changes small font used for some of the options for "Navigation Style" above', 'ninezeroseven'),
                        'google'         => true,
                        'required'       => array(
                                                array('opts-page-after-post-nav', "=", 1),
                                                array('opts-page-after-post-nav-style', "!=", 'wbc-nav-style-2')
                                            ),
                        'text-align'     => false,
                        'letter-spacing' => true,
                        'font-backup'    => true,
                        'default'        => array(
                            'font-size'     => '',
                            'font-family'   => '',
                            'font-weight'   => ''
                        ),
                        'output'         => array('.wbc-nav-row-1 .wbc-page-nav span')
                
                    ),
                    ///END PREV/NEXT OPTIONS
                    ///

                )
            ); //END PREV/NEXT Navigation
            
            $this->sections[] = array(
                'title'     => esc_html__('Light Box Options', 'ninezeroseven'),
                //'desc'      => esc_html__('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'ninezeroseven'),
                // 'icon'      => '',
                'subsection' => true,
                // 'subsection' => true, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(
                    ///PREV/NEXT OPTIONS
                    array(
                        'id'          => 'opts-lightbox-bg',
                        'type'        => $options_color_field,
                        'title'       => esc_html__('Site Overlay Color', 'ninezeroseven'),
                        'subtitle'    => esc_html__('Color the site is overlayed when lightbox is open', 'ninezeroseven'),
                        'transparent' => false,
                        'default'     => '',
                        'output'      => array(
                                'background-color' => '.fancybox-bg'
                            ),
                    ),
                    array(
                        'id'          => 'opts-lightbox-toolbar',
                        'type'        => $options_color_field,
                        'title'       => esc_html__('Toolbar Background Color', 'ninezeroseven'),
                        'subtitle'    => esc_html__('Color of the background behind the icons in the top corner', 'ninezeroseven'),
                        'transparent' => false,
                        'default'     => '',
                        'output'      => array(
                                'background-color' => '.fancybox-toolbar .fancybox-button'
                            ),
                    ),
                    array(
                        'id'          => 'opts-lightbox-button',
                        'type'        => $options_color_field,
                        'title'       => esc_html__('Toolbar Icon Color', 'ninezeroseven'),
                        'subtitle'    => esc_html__('Icon color in the toolbar', 'ninezeroseven'),
                        'transparent' => false,
                        'default'     => '',
                        'output'      => array(
                                'color' => '.fancybox-toolbar .fancybox-button'
                            ),
                    ),
                    array(
                        'id'          => 'opts-lightbox-button-hover',
                        'type'        => $options_color_field,
                        'title'       => esc_html__('Toolbar Icon Hover Color', 'ninezeroseven'),
                        'subtitle'    => esc_html__('Icon over color in the toolbar', 'ninezeroseven'),
                        'transparent' => false,
                        'default'     => '',
                        'output'      => array(
                                'color' => '.fancybox-toolbar .fancybox-button:hover'
                            ),
                    ),
                    array(
                        'id'          => 'opts-lightbox-thumbs-bg',
                        'type'        => $options_color_field,
                        'title'       => esc_html__('Background Thumbs Color', 'ninezeroseven'),
                        'subtitle'    => esc_html__('Color of the thumbnail list when open.', 'ninezeroseven'),
                        'transparent' => false,
                        'default'     => '',
                        'output'      => array(
                                'background-color' => '.fancybox-thumbs'
                            ),
                    ),
                    array(
                        'id'          => 'opts-lightbox-thumbs-highlight',
                        'type'        => $options_color_field,
                        'title'       => esc_html__('Thumbnail Highlight Color', 'ninezeroseven'),
                        'subtitle'    => esc_html__('Color of active selectd thumnbail border', 'ninezeroseven'),
                        'transparent' => false,
                        'default'     => '',
                        'output'      => array(
                                'border-color' => '.fancybox-thumbs__list a:before'
                            ),
                    ),
                    array(
                        'id'          => 'opts-lightbox-nav-buttons',
                        'type'        => $options_color_field,
                        'title'       => esc_html__('Navigation Next/Prev BG Color', 'ninezeroseven'),
                        'subtitle'    => esc_html__('Background color of next/prev buttons', 'ninezeroseven'),
                        'transparent' => false,
                        'default'     => '',
                        'output'      => array(
                                'background-color' => '.fancybox-navigation .fancybox-button'
                            ),
                    ),
                    array(
                        'id'          => 'opts-lightbox-nav-button',
                        'type'        => $options_color_field,
                        'title'       => esc_html__('Next/Prev Icon Color', 'ninezeroseven'),
                        'subtitle'    => esc_html__('Icon color in the Next/Prev', 'ninezeroseven'),
                        'transparent' => false,
                        'default'     => '',
                        'output'      => array(
                                'color' => '.fancybox-navigation .fancybox-button'
                            ),
                    ),
                    array(
                        'id'          => 'opts-lightbox-nav-button-hover',
                        'type'        => $options_color_field,
                        'title'       => esc_html__('Next/Prev Icon Hover Color', 'ninezeroseven'),
                        'subtitle'    => esc_html__('Icon over color in the Next/Prev', 'ninezeroseven'),
                        'transparent' => false,
                        'default'     => '',
                        'output'      => array(
                                'color' => '.fancybox-navigation .fancybox-button:hover'
                            ),
                    ),
                    array(
                        'id'          => 'opts-lightbox-progressbar',
                        'type'        => $options_color_field,
                        'title'       => esc_html__('Progress Bar Color', 'ninezeroseven'),
                        'subtitle'    => esc_html__('Color of progress bar when playing through thumbnail list.', 'ninezeroseven'),
                        'transparent' => false,
                        'default'     => '',
                        'output'      => array(
                                'background-color' => '.fancybox-progress'
                            ),
                    ),
                    ///END PREV/NEXT OPTIONS
                    ///

                )
            );
        }

        /**

          All the possible arguments for Redux.
          For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments

         * */
        public function setArguments() {

            $theme = wp_get_theme(); // For use with some settings. Not necessary.

            $this->args = array(
                // TYPICAL -> Change these values as you need/desire
                'opt_name'          => 'wbc907_data',            // This is where your data is stored in the database and also becomes your global variable name.
                'display_name'      => $theme->get('Name'),     // Name that appears at the top of your panel
                'display_version'   => $theme->get('Version'),  // Version that appears at the top of your panel
                'menu_type'         => 'submenu',                  //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
                'allow_sub_menu'    => true,                    // Show the sections below the admin menu item or not
                'menu_title'        => esc_html__('Theme Options', 'ninezeroseven'),
                'page_title'        => esc_html__('Theme Options', 'ninezeroseven'),
                'disable_tracking'  => 'true',
                
                // You will need to generate a Google API key to use this feature.
                // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
                'google_api_key' => 'xxxxxx', // Must be defined to add google fonts to the typography module
                
                'async_typography'  => false,                    // Use a asynchronous font on the front end or font string
                'admin_bar'         => true,                    // Show the panel pages on the admin bar
                'global_variable'   => '',                      // Set a different name for your global variable other than the opt_name
                'dev_mode'          => false,                    // Show the time the page took to load, etc
                'customizer'        => true,
                'ajax_save'         => false,                   // Enable basic customizer support
                
                // OPTIONAL -> Give you extra features
                'page_priority'     => 43,                    // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
                'page_parent'       => 'ninezeroseven',            // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
                'page_permissions'  => 'manage_options',        // Permissions needed to access the options panel.
                'menu_icon'         => '',                      // Specify a custom URL to an icon
                'last_tab'          => '',                      // Force your panel to always open to a specific tab (by id)
                'page_icon'         => '',           // Icon displayed in the admin panel next to your menu_title
                'page_slug'         => '_options',              // Page slug used to denote the panel
                'save_defaults'     => true,                    // On load save the defaults to DB before user clicks save or not
                'default_show'      => false,                   // If true, shows the default value next to each field that is not the default value.
                'default_mark'      => '',                      // What to print by the field's title if the value shown is default. Suggested: *
                'show_import_export' => true,                   // Shows the Import/Export panel when not used as a field.
                
                // CAREFUL -> These options are for advanced use only
                'transient_time'    => 60 * MINUTE_IN_SECONDS,
                'output'            => true,                    // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
                'output_tag'        => true,                    // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
                // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.
                
                // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
                'database'              => '', // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
                'system_info'           => false, // REMOVE

                // HINTS
                'hints' => array(
                    'icon' => 'el el-question-sign',
                    'icon_position' => 'right',
                    'icon_color' => 'lightgray',
                    'icon_size' => 'small',
                    'tip_style' => array(
                        'color' => 'dark',
                        'shadow' => true,
                        'rounded' => false,
                        'style' => 'bootstrap',
                    ),
                    'tip_position' => array(
                        'my' => 'top right',
                        'at' => 'bottom left',
                    ),
                    'tip_effect' => array(
                        'show' => array(
                        'effect' => 'slide',
                        'duration' => '500',
                        'event' => 'mouseover',
                    ),
                    'hide' => array(
                        'effect' => 'slide',
                        'duration' => '500',
                        'event' => 'click mouseleave',
                        ),
                    ),
                    )
            );
            
            $wbc907_support_url = join('',array(
                    'http',
                    '://',
                    'support',
                    '.webcreations907',
                    '.com/'
                ));
            
            //messages
            $wbc907_config_message = '<p>Need Support? Check out the <a href="'.esc_attr( $wbc907_support_url ).'">Theme\'s Support Form</a></p>';
            
            $this->args['intro_text'] = $wbc907_config_message;
            // Add content after the form.
            $this->args['footer_text'] = $wbc907_config_message;
        }

    }
    
    global $reduxConfig;
    $reduxConfig = new WBC907_Options_Framework();
}


if (!function_exists('wbc907_sanitize_slug')){
    function wbc907_sanitize_slug($field, $value, $existing_value) {
        $error = false;
        $value = $value;

        if(empty($value)){

            $return['value'] = 'portfolio';

        }elseif($value != $existing_value){

            $return['value'] = trim( sanitize_title( $value ) );
        }else{
            $return['value'] = $value;
        }

        return $return;
    }
}
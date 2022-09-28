<?php

/************************************************************************
* Meta Box Options/fields
*
* 1- Post Formats
*         1a Link Format
*         1b Quote Format
*         1c Video Format
*         1d Audio Format
*         1e Gallery Format
*     1.1 Post SideBar
*
* 2- Page Options
*
* 3- Portfolio Options
*     3.1 Portfolio Sidebar
*
*
* 4- Page, Post, wbc_portfolio Options
*     4.1 General
*     4.2 Header Options
*     4.3 Footer Options
*     
*************************************************************************/

$redux_opt_name = "wbc907_data";

$post_type = (isset($_GET['post_type'])) ? $_GET['post_type'] : 'post';


if ( !function_exists( "wbc907_add_metaboxes" ) ):
    function wbc907_add_metaboxes($metaboxes) {

    global $post_type;

    /**
     * Gets FontAwesome Array
     * $sort = true // Sorts the Icons
     * $w_name = true // Adds named array like array(fa-cogs => Cogs)
     * $no_fa = true // Removes 'fa' from 'fa fa-cogs'
     */
    $iconArray = wbc_fontawesome_array( true, true, true );

    $options_values = get_option('wbc907_data');

    if(isset($options_values) && is_array($options_values)){
        if(isset($options_values['opts-topbar-right-override']) && isset($options_values['opts-topbar-right-override']['field-icon']) && count($options_values['opts-topbar-right-override']['field-icon'])>0){

            foreach ($options_values['opts-topbar-right-override']['field-icon'] as $icon) {
                if( !empty($icon) &&  preg_match( '/fa fa-/', $icon ) ){
                    $iconArray[$icon] = ucwords(str_replace('fa fa-', '', $icon));
                }
            }
        }

    }

    if(isset($options_values) && is_array($options_values)){
        if(isset($options_values['opts-topbar-left-override']) && isset($options_values['opts-topbar-left-override']['field-icon']) && count($options_values['opts-topbar-left-override']['field-icon'])>0){

            foreach ($options_values['opts-topbar-left-override']['field-icon'] as $icon) {
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


    $metaboxes = array();

    // // Grab options panel values.
    // $options_values = get_option('wbc907_data');


    /************************************************************************
    * 1- Begin Post Formats
    *************************************************************************/

    /* 1a Link Format*/
    $link_options = array();
    $link_options[] = array(
        'icon_class'    => 'icon-large',
        'icon'          => 'el-icon-home',
        'fields'        => array(
            array(
                'id'       => 'wbc-link-format-text',
                'type'     => 'text',
                'title'    => esc_html__('Link Title', 'ninezeroseven'),
                'validate' => 'no_html',
            ),
            array(
                'id'        => 'wbc-link-format-link',
                'type'      => 'text',
                'title'     => esc_html__('Enter Link Here', 'ninezeroseven'),
                'subtitle'  => esc_html__('This must be a URL starting with http://', 'ninezeroseven'),
                'validate'  => 'url',
                'default'   => '',
            )
            
        ),
    );

    $metaboxes[] = array(
        'id'          => 'link-format',
        'title'       => esc_html__( 'Link Format', 'ninezeroseven' ),
        'post_types'  => array('post'),
        'post_format' => array('link'),
        'position'    => 'normal', // normal, advanced, side
        'priority'    => 'high', // high, core, default, low
        'sidebar'     => false, // enable/disable the sidebar in the normal/advanced positions
        'sections'    => $link_options,
    );
    /* 1a End Link Format*/



    /* 1b QUOTE FORMAT*/
    $quote_options = array();
    $quote_options[] = array(
        'icon_class'    => 'icon-large',
        'icon'          => 'el-icon-home',
        'fields'        => array(
            array(
                'id'       => 'wbc-quote-who',
                'type'     => 'text',
                'title'    => esc_html__('Quote\'s Credit/Name', 'ninezeroseven'),
                'validate' => 'no_html',
                'default'  => ''
            ),
            array(
                'id'        => 'wbc-quote-message',
                'type'      => 'textarea',
                'title'     => esc_html__('Quote Message', 'ninezeroseven'),
                'subtitle'  => esc_html__('Enter quote below', 'ninezeroseven'),
                'validate'  => 'no_html',
                'default'   => ''
            ),
            
        ),
    );

    $metaboxes[] = array(
        'id'          => 'quote-format',
        'title'       => esc_html__( 'Quote Format', 'ninezeroseven' ),
        'post_types'  => array('post'),
        'post_format' => array('quote'),
        'position'    => 'normal', // normal, advanced, side
        'priority'    => 'high', // high, core, default, low
        'sidebar'     => false, // enable/disable the sidebar in the normal/advanced positions
        'sections'    => $quote_options,
    );
    /* 1b End QUOTE FORMAT*/

  
    /* 1c Video Format*/

    $video_options = array();
    $video_options[] = array(
        'icon_class'    => 'icon-large',
        'icon'          => 'el-icon-home',
        'fields'        => array(
            array(
                'id'       => 'wbc-video-embed',
                'type'     => 'textarea',
                'title'    => esc_html__('Video Embed Code', 'ninezeroseven'),
                'subtitle' => esc_html__('Enter embed code below', 'ninezeroseven'),
                'validate' => 'html_custom',
                'default'  => ''
            ),
            
        ),
    );

    $metaboxes[] = array(
        'id'          => 'video-format',
        'title'       => esc_html__( 'Video Format', 'ninezeroseven' ),
        'post_types'  => array('post'),
        'post_format' => array('video'),
        'position'    => 'normal', // normal, advanced, side
        'priority'    => 'high', // high, core, default, low
        'sidebar'     => false, // enable/disable the sidebar in the normal/advanced positions
        'sections'    => $video_options,
    );
    /* 1c End Video Format*/

    /* 1d Audio Format*/

    $audio_options = array();
    $audio_options[] = array(
        // 'title'         => esc_html__(' ', 'ninezeroseven'),
        'icon_class'    => 'icon-large',
        'icon'          => 'el-icon-home',
        'fields'        => array(

            array(
                'id'      => 'wbc-audio-mp3',
                'type'    => 'text',
                'title'   => esc_html__('Enter MP3 URL Here', 'ninezeroseven'),
                'default' => ''
            ),

            array(
                'id'      => 'wbc-audio-ogg',
                'type'    => 'text',
                'title'   => esc_html__('Enter OGG URL Here', 'ninezeroseven'),
                'default' => ''
            ),
            array(
                'id'       => 'wbc-audio-embed',
                'type'     => 'textarea',
                'title'    => esc_html__('Audio Embed Code', 'ninezeroseven'),
                'subtitle' => esc_html__('Optional: Enter Embed/Shortcode code Here', 'ninezeroseven'),
                'validate' => 'html_custom',
                'default'  => ''
            ),
            
        ),
    );

    $metaboxes[] = array(
        'id'          => 'audio-format',
        'title'       => esc_html__( 'Audio Format', 'ninezeroseven' ),
        'post_types'  => array('post'),
        'post_format' => array('audio'),
        'position'    => 'normal', // normal, advanced, side
        'priority'    => 'high', // high, core, default, low
        'sidebar'     => false, // enable/disable the sidebar in the normal/advanced positions
        'sections'    => $audio_options,
    );
    /* 1d End Audio Format*/

    /* 1e Gallery Format*/
    $gallery_options = array();
    $gallery_options[] = array(
        // 'title'         => esc_html__(' ', 'ninezeroseven'),
        'icon_class'    => 'icon-large',
        'icon'          => 'el-icon-home',
        'fields'        => array(
            array(
                'id'        => 'wbc-gallery-format',
                'type'      => 'gallery',
                'title'     => esc_html__('Gallery Images', 'ninezeroseven'),
                'subtitle'  => esc_html__('You can add images here for slider/gallery.', 'ninezeroseven'),
            ),
            
        ),
    );

    $metaboxes[] = array(
        'id'          => 'gallery-format',
        'title'       => esc_html__( 'Gallery Format', 'ninezeroseven' ),
        'post_types'  => array('post'),
        'post_format' => array('gallery'),
        'position'    => 'normal', // normal, advanced, side
        'priority'    => 'high', // high, core, default, low
        'sidebar'     => false, // enable/disable the sidebar in the normal/advanced positions
        'sections'    => $gallery_options,
    );
    /* 1e END Gallery Format*/

    /************************************************************************
    * 1- END POST FORMATS
    *************************************************************************/
     
    
    /************************************************************************
    * 1.1 Post Sidebar
    *************************************************************************/
    
    $boxSections = array();
    $boxSections[] = array(
        'icon_class' => 'icon-large',
        'icon' => 'el-icon-home',
        'fields' => array(
            array(

                'id'        => 'opts-blog-layout',
                'type'      => 'image_select',
                'title'     => esc_html__('Page Layout', 'ninezeroseven'),
                'options'   => array(
                        'no-sidebar'   => array('alt' => 'Full Width',     'img' => get_template_directory_uri() . '/includes/admin/configs/img/1col.png' ),
                        'sidebar-left' => array('alt' => 'Left Sidebar',   'img' => get_template_directory_uri() . '/includes/admin/configs/img/2cl.png' ),
                        'default'      => array('alt' => 'Right Sidebar',  'img' => get_template_directory_uri() . '/includes/admin/configs/img/2cr.png' ),
                        )
            ),
            array(
                'id'       => 'opts-single-page-sidebar',
                'title'    => esc_html__( 'Sidebar', 'ninezeroseven' ),
                'desc'     => esc_html__('You can create additional sidebars under Appearance > Widgets.','ninezeroseven'),
                'type'     => 'select',
                'data'     => 'sidebars',
                'required' => array('opts-blog-layout', '!=', 'no-sidebar')
            ),
            array(
                'id'       => 'opts-page-menu-override',
                'title'    => esc_html__( 'Main Menu', 'ninezeroseven' ),
                'desc'     => esc_html__('You can create additional menus under Appearance > Menus.','ninezeroseven'),
                'type'     => 'select',
                'data'     => 'menus',
            ),
            array(
                'id'       => 'opts-page-menu-footer-override',
                'title'    => esc_html__( 'Footer Menu', 'ninezeroseven' ),
                'desc'     => esc_html__('You can create additional menus under Appearance > Menus.','ninezeroseven'),
                'required'  => array(
                            array('opts-footer-disable', "=", 1),
                            array('opts-footer-copyright-disable', "=", 1),
                            ),
                'type'     => 'select',
                'data'     => 'menus',
            ),
            array(
                'id'       => 'opts-bread-crumb',
                'type'     => 'switch',
                'title'    => esc_html__('Show/Hide Breadcrumb', 'ninezeroseven'),
                'on'       => 'Enabled',
                'off'      => 'Disabled',

            ),array(
                'id'       => 'opts-parent-options',
                'type'     => 'select',
                'title'    => esc_html__('Inherit Options', 'ninezeroseven'),
                'desc'     => esc_html__('This option will inherit some options from the selected page(ie menus, logo/title)', 'ninezeroseven'),
                'data'     => 'pages',
                'args'     => array('meta_key' => 'opts-is-parent','meta_value' =>'1','posts_per_page' => -1), 
                'default'  => '',
            ),

        )
    );
  
    $metaboxes[] = array(
        'id'            => 'wbc-page-layout',
        'title'         => esc_html__('Misc Settings', 'ninezeroseven'),
        'post_types'    => array('post'),
        //'page_template' => array('page-test.php'),
        //'post_format'   => array('image'),
        'position'      => 'side', // normal, advanced, side
        'priority'      => 'core', // high, core, default, low
        'sections'      => $boxSections
    );

    /************************************************************************
    * 1.1 End Post Sidebar
    *************************************************************************/


    /************************************************************************
    * 2- Page Options
    *************************************************************************/
    
    $pageSections = array();
    $pageSections[] = array(
        'icon_class' => 'icon-large',
        'icon' => 'el-icon-home',
        'fields' => array(
            array(
                'id'       => 'opts-single-page-sidebar',
                'title'    => esc_html__( 'Sidebar', 'ninezeroseven' ),
                'desc'     => esc_html__('You can create additional sidebars under Appearance > Widgets.','ninezeroseven'),
                'type'     => 'select',
                'data'     => 'sidebars'
            ),

        )
    );

    $metaboxes[] = array(
        'id'            => 'wbc-page-sidebar-option',
        'title'         => esc_html__('Sidebar', 'ninezeroseven'),
        'post_types'    => array('page'),
        'page_template' => array('template-page-right.php','template-page-left.php'),
        //'post_format'   => array('image'),
        'position'      => 'side', // normal, advanced, side
        'priority'      => 'core', // high, core, default, low
        'sections'      => $pageSections,
    );


    $pageSections = array();
    $pageSections[] = array(
        'icon_class' => 'icon-large',
        'icon' => 'el-icon-home',
        'fields' => array(
            array(
                'id'        => 'opts-page-menu-position',
                'type'      => 'select',
                'title'     => esc_html__('Menu Position', 'ninezeroseven'),
                'desc'     => esc_html__('Select where you\'d like the menu bar','ninezeroseven'),
                
                //Must provide key => value pairs for select options
                'options'   => array(
                    'top' => 'Top of Browser',
                    'bottom' => 'Bottom of Browser',
                    'after_num' => 'After X amount rows',
                    
                ),
                'default'   => ''
            ),
            array(
                'id'        => 'opts-page-menu-after',
                'type'      => 'select',
                'title'     => esc_html__('Show Menu After', 'ninezeroseven'),
                'desc'     => esc_html__('Select what row you want the menu displayed below.','ninezeroseven'),
                
                //Must provide key => value pairs for select options
                'options'   => array(
                    '1'  => 'First Row',
                    '2'  => 'Second Row',
                    '3'  => 'Third Row',
                    '4'  => 'Fourth Row',
                    '5'  => 'Fifth Row',
                    '6'  => 'Sixth Row',
                    '7'  => 'Seventh Row',
                    '8'  => 'Eighth Row',
                    '9'  => 'Nineth Row',
                    '10' => 'Tenth Row',
                    
                ),
                'default'   => '1',
                'required'  => array(
                            array('opts-page-menu-position', "=", 'after_num'),
                            ),
            ),

        )//HERE
    );

    $metaboxes[] = array(
        'id'            => 'wbc-page-menu-option',
        'title'         => esc_html__('Menu Position', 'ninezeroseven'),
        'post_types'    => array('page'),
        'page_template' => array('template-page-full.php'),
        //'post_format'   => array('image'),
        'position'      => 'side', // normal, advanced, side
        'priority'      => 'core', // high, core, default, low
        'sections'      => $pageSections,
    );

    $boxSections = array();
    $boxSections[] = array(
        'icon_class' => 'icon-large',
        'icon' => 'el-icon-home',
        'fields' => array(
            array(
                'id'       => 'opts-page-menu-override',
                'title'    => esc_html__( 'Main Menu', 'ninezeroseven' ),
                'desc'     => esc_html__('You can create additional menus under Appearance > Menus.','ninezeroseven'),
                'type'     => 'select',
                'data'     => 'menus',
            ),
            array(
                'id'       => 'opts-page-menu-footer-override',
                'title'    => esc_html__( 'Footer Menu', 'ninezeroseven' ),
                'desc'     => esc_html__('You can create additional menus under Appearance > Menus.','ninezeroseven'),
                'required'  => array(
                            array('opts-footer-disable', "=", 1),
                            array('opts-footer-copyright-disable', "=", 1),
                            ),
                'type'     => 'select',
                'data'     => 'menus',
            ),
            array(
                'id'       => 'opts-bread-crumb',
                'type'     => 'switch',
                'title'    => esc_html__('Show/Hide Breadcrumb', 'ninezeroseven'),
                'on'       => 'Enabled',
                'off'      => 'Disabled',

            ),
            array(
                'id'       => 'opts-is-parent',
                'type'     => 'switch',
                'title'    => esc_html__('Is Parent', 'ninezeroseven'),
                'desc'     => esc_html__('Will set it as a parent/main page for other posts to inherit from.','ninezeroseven'),
                'on'       => 'Enabled',
                'off'      => 'Disabled',
                'default'  => ''

            ),

        )
    );
  
    $metaboxes[] = array(
        'id'            => 'wbc-page-options',
        'title'         => esc_html__('Misc Settings', 'ninezeroseven'),
        'post_types'    => array('page'),
        //'page_template' => array('page-test.php'),
        //'post_format'   => array('image'),
        'position'      => 'side', // normal, advanced, side
        'priority'      => 'core', // high, core, default, low
        'sections'      => $boxSections
    );


    /************************************************************************
    * 2- END Page Options
    *************************************************************************/
    

    /************************************************************************
    * 3- Portfolio Options
    *************************************************************************/

    $portfolioBoxes = array();
    $portfolioBoxes[] = array(
        'icon_class'    => 'icon-large',
        'icon'          => 'el-icon-home',
        'fields'        => array(
            array(
                'id'        => 'opts-portfolio-type',
                'type'      => 'button_set',
                'title'     => esc_html__('Portfolio Type', 'ninezeroseven'),
                'subtitle'  => esc_html__('You can chose the content type here', 'ninezeroseven'),
                'desc'      => esc_html__('If left on "Image" it will use the "Featured Image".', 'ninezeroseven'),
                
                //Must provide key => value pairs for radio options
                'options'   => array(
                    'image' => 'Image', 
                    'video'   => 'Video', 
                    'gallery' => 'Gallery'
                ), 
                'default'   => 'image'
            ),
            array(
                'id'       => 'wbc-portfolio-video-embed',
                'type'     => 'textarea',
                'title'    => esc_html__('Video Embed Code', 'ninezeroseven'),
                'subtitle' => esc_html__('Enter embed code below', 'ninezeroseven'),
                'validate' => 'html_custom',
                'required' => array('opts-portfolio-type', '=', 'video'),
                'default'  => ''
            ),
            array(
                'id'        => 'wbc-portfolio-gallery-format',
                'type'      => 'gallery',
                'title'     => esc_html__('Gallery Images', 'ninezeroseven'),
                'subtitle'  => esc_html__('You can add images here for slider/gallery.', 'ninezeroseven'),
                'required' => array('opts-portfolio-type', '=', 'gallery')
            ),
            // array(
            //     'id'        => 'wbc-image-format',
            //     'type'      => 'media',
            //     'title'     => esc_html__('Image (Optional)', 'ninezeroseven'),
            //     'desc'      => esc_html__('This is optional, you can just use the "Featured Image".', 'ninezeroseven'),
            //     'subtitle'  => esc_html__('If you upload image to here it will be used instead of featured image.', 'ninezeroseven'),
            //     'required' => array('opts-portfolio-type', '=', 'image'),
            // ),
            array(
                'id'        => 'opts-portfolio-image-size',
                'type'      => 'button_set',
                'title'     => esc_html__('Image Size', 'ninezeroseven'),
                'subtitle'  => esc_html__('This will be used for masonry galleries.', 'ninezeroseven'),
                'desc'      => esc_html__('Make sure to set the "Featured Image"', 'ninezeroseven'),
                
                //Must provide key => value pairs for radio options
                'options'   => array(
                    'square'     => esc_html__( 'Square', 'ninezeroseven'),
                    'landscape'  => esc_html__( '2x Width', 'ninezeroseven'),
                    'portrait'   => esc_html__( '2x Height', 'ninezeroseven'), 
                    'dbl-square' => esc_html__( '2x Width & Height' , 'ninezeroseven'),
                ), 
                'default'   => 'square'
            ),

            //Portfolio Link
            array(
                'id'        => 'opts-portfolio-link',
                'type'      => 'text',
                'title'     => esc_html__('External Link', 'ninezeroseven'),
                'subtitle'  => esc_html__('Link to a external link, can also be linked to internal pages. ', 'ninezeroseven'),
                'validate'  => 'url',
            ),
            array(
                'id'       => 'opts-portfolio-link-target',
                'type'     => 'switch',
                'title'    => esc_html__('Link target', 'ninezeroseven'),
                'subtitle'  => esc_html__('Open link in a new window or the same window when clicked.', 'ninezeroseven'),
                'on'       => 'Same Window',
                'off'      => 'Blank Window',
                'default'  => 0,
            ),
            array(
                'id'       => 'opts-portfolio-link-icon',
                'type'     => 'select',
                'select2'  => array( 'containerCssClass' => ' el' ),
                'title'    => esc_html__('Link Icon', 'ninezeroseven'),
                'default'  => 'fa fa-external-link',
                'class'    => ' font-icons ef',
                'options'  => array(
                        'fa fa-globe'                => 'Globe',
                        'fa fa-link'                 => 'Link',
                        'fa fa-external-link'        => 'External Link',
                        'fa fa-external-link-square' => 'External Link',
                    )
            ),
    
        ),
    );

    $metaboxes[] = array(
        'id'          => 'portfolio-options',
        'title'       => esc_html__( 'Portfolio Options', 'ninezeroseven' ),
        'post_types'  => array('wbc-portfolio'),
        'position'    => 'normal', // normal, advanced, side
        'priority'    => 'high', // high, core, default, low
        'sidebar'     => false, // enable/disable the sidebar in the normal/advanced positions
        'sections'    => $portfolioBoxes,
    );

    // 3.1 Portfolio Sidebar
    $boxSections = array();
    $boxSections[] = array(
        'icon_class' => 'icon-large',
        'icon' => 'el-icon-home',
        'fields' => array(
            array(

                'id'        => 'opts-portfolio-layout',
                'type'      => 'image_select',
                'title'     => esc_html__('Page Layout', 'ninezeroseven'),
                'options'   => array(
                        'full-width'   => array('alt' => 'Full Screen Width',     'img' => get_template_directory_uri() . '/includes/admin/configs/img/full-width.png'),
                        'no-sidebar'   => array('alt' => 'Full Width',     'img' => get_template_directory_uri() . '/includes/admin/configs/img/1col.png' ),
                        'sidebar-left' => array('alt' => 'Left Sidebar',   'img' => get_template_directory_uri() . '/includes/admin/configs/img/2cl.png' ),
                        'default'      => array('alt' => 'Right Sidebar',  'img' => get_template_directory_uri() . '/includes/admin/configs/img/2cr.png' ),
                        )
            ),
            array(
                'id'       => 'opts-single-portfolio-sidebar',
                'title'    => esc_html__( 'Sidebar', 'ninezeroseven' ),
                'desc'     => esc_html__('You can create additional sidebars under Appearance > Widgets.','ninezeroseven'),
                'type'     => 'select',
                'data'     => 'sidebars',
                'required' => array('opts-portfolio-layout', '=', array('sidebar-left','default'))
            ),
            array(
                'id'        => 'opts-portfolio-menu-position',
                'type'      => 'select',
                'title'     => esc_html__('Menu Position', 'ninezeroseven'),
                'desc'     => esc_html__('Select where you\'d like the menu bar','ninezeroseven'),
                'required' => array('opts-portfolio-layout', '=', array('full-width')),
                
                //Must provide key => value pairs for select options
                'options'   => array(
                    'top'       => 'Top of Browser',
                    'bottom'    => 'Bottom of Browser',
                    'after_num' => 'After X amount rows',
                    
                ),
                'default'   => ''
            ),
            array(
                'id'        => 'opts-portfolio-menu-after',
                'type'      => 'select',
                'title'     => esc_html__('Show Menu After', 'ninezeroseven'),
                'desc'     => esc_html__('Select what row you want the menu displayed below.','ninezeroseven'),
                
                //Must provide key => value pairs for select options
                'options'   => array(
                    '1'  => 'First Row',
                    '2'  => 'Second Row',
                    '3'  => 'Third Row',
                    '4'  => 'Fourth Row',
                    '5'  => 'Fifth Row',
                    '6'  => 'Sixth Row',
                    '7'  => 'Seventh Row',
                    '8'  => 'Eighth Row',
                    '9'  => 'Nineth Row',
                    '10' => 'Tenth Row',
                    
                ),
                'default'   => '1',
                'required'  => array(
                            array('opts-portfolio-menu-position', "=", 'after_num'),
                            ),
            ),
            array(
                'id'       => 'opts-page-menu-override',
                'title'    => esc_html__( 'Main Menu', 'ninezeroseven' ),
                'desc'     => esc_html__('You can create additional menus under Appearance > Menus.','ninezeroseven'),
                'type'     => 'select',
                'data'     => 'menus',
            ),
            array(
                'id'       => 'opts-page-menu-footer-override',
                'title'    => esc_html__( 'Footer Menu', 'ninezeroseven' ),
                'desc'     => esc_html__('You can create additional menus under Appearance > Menus.','ninezeroseven'),
                'required'  => array(
                            array('opts-footer-disable', "=", 1),
                            array('opts-footer-copyright-disable', "=", 1),
                            ),
                'type'     => 'select',
                'data'     => 'menus',
            ),
             array(
                'id'       => 'opts-parent-options',
                'type'     => 'select',
                'title'    => esc_html__('Inherit Options', 'ninezeroseven'),
                'desc'     => esc_html__('This option will inherit some options from the selected page(ie menus, logo/title)', 'ninezeroseven'),
                'data'     => 'pages',
                'args'     => array('meta_key' => 'opts-is-parent','meta_value' =>'1','posts_per_page' => -1), 
                'default'  => '',
            ),
            array(
                'id'       => 'opts-bread-crumb',
                'type'     => 'switch',
                'title'    => esc_html__('Show/Hide Breadcrumb', 'ninezeroseven'),
                'on'       => 'Enabled',
                'off'      => 'Disabled',

            ),

        )
    );
  
    $metaboxes[] = array(
        'id'            => 'wbc-portfolio-options',
        'title'         => esc_html__('Page Options', 'ninezeroseven'),
        'post_types'    => array('wbc-portfolio'),
        //'page_template' => array('page-test.php'),
        //'post_format'   => array('image'),
        'position'      => 'side', // normal, advanced, side
        'priority'      => 'core', // high, core, default, low
        'sections'      => $boxSections
    );
    // 3.1 END Portfolio Sidebar

    /************************************************************************
    * 3- Reuseables
    *************************************************************************/
    // 3.1 Portfolio Sidebar
    $boxSections = array();
    $boxSections[] = array(
        'icon_class' => 'icon-large',
        'icon' => 'el-icon-home',
        'fields' => array(
            array(
                'id'       => 'opts-reuseable-switch',
                'type'     => 'switch',
                'title'    => esc_html__('Reuseables', 'ninezeroseven'),
                'on'       => 'Enabled',
                'off'      => 'Disabled',
                'default'   => 1,

            ),
            array(
                'id'       => 'opts-reuseable-before-switch',
                'type'     => 'switch',
                'title'    => esc_html__('Reuseable Befores', 'ninezeroseven'),
                'required'  => array(
                            array('opts-reuseable-switch', "=", 1)
                            ),
                'on'       => 'Enabled',
                'off'      => 'Disabled',
                'default'   => 1,

            ),
            array(
                'id'       => 'opts-single-reuse-before',
                'multi'    => true,
                'title'    => esc_html__( 'Before', 'ninezeroseven' ),
                'required'  => array(
                            array('opts-reuseable-switch', "=", 1),
                            array('opts-reuseable-before-switch', "=", 1),
                            ),
                'desc'     => esc_html__('Sets sections to displayed at top of page','ninezeroseven'),
                'args'     => array('post_type' => array('wbc-reuseables'),'posts_per_page' => -1),
                'type'     => 'select',
                'sortable' => true,
                'data'     => 'posts',
            ),
            array(
                'id'       => 'opts-reuseable-after-switch',
                'type'     => 'switch',
                'title'    => esc_html__('Reuseables Afters', 'ninezeroseven'),
                'required'  => array(
                            array('opts-reuseable-switch', "=", 1)
                            ),
                'on'       => 'Enabled',
                'off'      => 'Disabled',
                'default'   => 1,

            ),
            array(
                'id'       => 'opts-single-reuse-after',
                'multi'    => true,
                'title'    => esc_html__( 'After', 'ninezeroseven' ),
                'desc'     => esc_html__('Sets sections to displayed at bottom of page','ninezeroseven'),
                'required'  => array(
                            array('opts-reuseable-switch', "=", 1),
                            array('opts-reuseable-after-switch', "=", 1),
                            ),
                'args'     => array('post_type' => array('wbc-reuseables'),'posts_per_page' => -1),
                'type'     => 'select',
                'sortable' => true,
                'data'     => 'posts',
            ),

        )
    );
  
    $metaboxes[] = array(
        'id'            => 'wbc-reuseable-options',
        'title'         => esc_html__('Reuseables', 'ninezeroseven'),
        'post_types'    => apply_filters('wbc_reuseable_support', array('wbc-portfolio','page','post')),
        //'page_template' => array('page-test.php'),
        //'post_format'   => array('image'),
        'position'      => 'side', // normal, advanced, side
        'priority'      => 'low', // high, core, default, low
        'sections'      => $boxSections
    );





    /************************************************************************
    * 4- Page, Post, wbc_portfolio Options
    *************************************************************************/


    // Extended Options
    $boxSections = array();


    // 4.1 Header Options
    $boxSections[] = array(
        'title' => esc_html__('General', 'ninezeroseven'),
        'icon_class' => 'icon-large',
        'icon' => 'fa fa-cog',
        'fields' => array(
            ///BOXED
                    array(
                        'id'        => 'opts-boxed-layout',
                        'type'      => 'switch',
                        'title'     => esc_html__('Boxed Layout', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Enables boxed layout', 'ninezeroseven'),
                        'on'        => esc_html__('Enable', 'ninezeroseven'),
                        'off'       => esc_html__('Disable', 'ninezeroseven'),
                        // 'hint' => array(
                        //     'title' => 'Hint Title',
                        //     'content' => 'This is a <b>hint</b> for the media field with a Title.',
                        // )
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
                        "min"       => 500,
                        "step"      => 1,
                        "max"       => 2000,
                        'display_value' => 'text'
                    ),
                    array(
                        'id'        => 'opts-page-font-color-override',
                        'type'      => $options_color_field,
                        'title'     => esc_html__('Font Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Change the body font color', 'ninezeroseven'),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'color' => 'body'
                            )
                    ),
                  array(
                        'id'        => 'opts-primary-color-override',
                        'type'      => $options_color_field,
                        'title'     => esc_html__('Primary Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Change the main colors(links,buttons,etc)', 'ninezeroseven'),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => apply_filters( 'opts-primary-color', array() )
                    ),
                    array(
                        'id'        => 'opts-page-bg-color-override',
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
                        'id'        => 'opts-page-content-color-override',
                        'type'      => $options_color_field,
                        'title'     => esc_html__('Page Content Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Change the primary color.(boxes,borders,etc)', 'ninezeroseven'),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => apply_filters('opts-page-content-color', array())
                    ),
                    array(
                        'id'          => 'opts-default-overlay-color-override',
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
                        'id'             => 'opts-maincontent-spacing-override',
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
                        'id'             => 'opts-default-button-text-override',
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
                    ),
                    array(
                        'id'             => 'opts-default-button-spacing-override',
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
                        'id'          => 'opts-default-button-bg-color-override',
                        'type'        => $options_color_field,
                        'title'       => esc_html__('Button Color', 'ninezeroseven'),
                        'subtitle'    => esc_html__('Change button color, if left empty, color is used from "Primary" color above.', 'ninezeroseven'),
                        'transparent' => false,
                        'default'     => '',
                        'output'      => apply_filters( 'opts_buttons_bg_color', array() )
                    ),
                    array(
                        'id'          => 'opts-default-button-color-override',
                        'type'        => $options_color_field,
                        'title'       => esc_html__('Button Text Color', 'ninezeroseven'),
                        'subtitle'    => esc_html__('Change button text color.', 'ninezeroseven'),
                        'transparent' => false,
                        'default'     => '',
                        'output'      => apply_filters( 'opts_buttons_text_color', array() )
                    ),
                    array(
                        'id'          => 'opts-default-button-bg-hover-color-override',
                        'type'        => $options_color_field,
                        'title'       => esc_html__('Button Hover Color', 'ninezeroseven'),
                        'subtitle'    => esc_html__('Change button hover color.', 'ninezeroseven'),
                        'transparent' => false,
                        'default'     => '',
                        'output'      => apply_filters( 'opts_buttons_bg_hover_color', array() )
                    ),
                    array(
                        'id'          => 'opts-default-button-hover-color-override',
                        'type'        => $options_color_field,
                        'title'       => esc_html__('Button Hover Text Color', 'ninezeroseven'),
                        'subtitle'    => esc_html__('Change button hover text color.', 'ninezeroseven'),
                        'transparent' => false,
                        'default'     => '',
                        'output'      => apply_filters( 'opts_buttons_text_hover_color', array() )
                    ),


            ));

            


    // 4.2 Header Options
    $boxSections[] = array(
        'title' => esc_html__('Header', 'ninezeroseven'),
        'icon_class' => 'icon-large',
        'icon' => 'fa fa-sliders',
        'fields' => array());

    $boxSections[] = array(
            'title'     => esc_html__('Logos', 'ninezeroseven'),
            'subsection' => true,
            'fields'    => array(
                array(
                'id'        => 'opts-main-logo-override',
                'type'      => 'checkbox',
                'title'     => esc_html__('Override Nav logo/text', 'ninezeroseven'),
                'subtitle'  => esc_html__('If you\'d like to override default logo/text', 'ninezeroseven'),
                'default'   => 0 // 1 = on | 0 = off
            ),
            array(
                'id'        => 'logo-enabled-override',
                'type'      => 'switch',
                'title'     => esc_html__('Logo Type', 'ninezeroseven'),
                'required'  => array('opts-main-logo-override', "=", 1),
                'subtitle'  => esc_html__('Select logo type you\'d like in nav bar', 'ninezeroseven'),
                'default'   => 1,
                'on'        => 'Image',
                'off'       => 'Text',

            ),
            array(
                'id'        => 'opts-nav-text-override',
                'type'      => 'text',
                'title'     => esc_html__('Site Name', 'ninezeroseven'),
                'subtitle'  => esc_html__('If you\'d like your site name different in nav bar then what you\'ve set on settings page.', 'ninezeroseven'),
                'validate'  => 'no_html',
                'required'  => array(
                                array('opts-main-logo-override', "=", 1),
                                array('logo-enabled-override', "=", 0),
                            ),
                'default'   => get_bloginfo( 'name' )
            ),
            array(
                'id'        => 'opts-nav-logo-override',
                'type'      => 'media',
                'title'     => esc_html__('Site Navbar Logo', 'ninezeroseven'),
                'mode'      => 'image', // Can be set to false to allow any media type, or can also be set to any mime type.
                'subtitle'  => esc_html__('Upload logo', 'ninezeroseven'),
                'required'  => array(
                                array('opts-main-logo-override', "=", 1),
                                array('logo-enabled-override', "=", 1),
                            ),
                'default' => '',
            ),
            array(
                'id'        => 'opts-nav-transparent-logo-override',
                'type'      => 'media',
                'title'     => esc_html__('Transparent Logo', 'ninezeroseven'),
                'mode'      => 'image', // Can be set to false to allow any media type, or can also be set to any mime type.
                'subtitle'  => esc_html__('Upload logo', 'ninezeroseven'),
                'required'  => array(
                                array('opts-main-logo-override', "=", 1),
                                array('logo-enabled-override', "=", 1),
                            ),
                'default' => '',
            ),
            array(
                'id'        => 'opts-nav-sticky-logo-override',
                'type'      => 'media',
                'title'     => esc_html__('Sticky Logo', 'ninezeroseven'),
                'mode'      => 'image', // Can be set to false to allow any media type, or can also be set to any mime type.
                //'desc'      => esc_html__('Basic media uploader with disabled URL input field.', 'ninezeroseven'),
                'subtitle'  => esc_html__('Used if sticky menu is enabled, leave blank to use default logo.', 'ninezeroseven'),
                'required'  => array(
                                array('opts-main-logo-override', "=", 1),
                                array('logo-enabled-override', "=", 1),
                            ),
                'default' => '',
            ),

        ));
        
        $boxSections[] = array(
        'title' => esc_html__('Main Header', 'ninezeroseven'),
        'subsection' => true,
        'fields' => array(
            array(
                'id'       => 'opts-menubar-fullwidth',
                'type'     => 'switch',
                'required'  => array('opts-main-logo-override', "=", 1),
                'title'    => esc_html__('100% Menu Bar Width', 'ninezeroseven'),
                'subtitle' => esc_html__('Makes the menu bar/logo area full width.', 'ninezeroseven'),
                'on'       => 'Enabled',
                'off'      => 'Disabled',
            ),
            array(
                'id'                => 'opts-menu-height-override',
                'type'              => 'dimensions',
                'width' => false,
                'title'             => esc_html__('Menu Bar Height', 'ninezeroseven'),
                'subtitle'          => esc_html__('If you\'d like to change the height of the menu bar, enter value here', 'ninezeroseven'),
                'required'  => array('opts-main-logo-override', "=", 1),

            ),

            /************************************************************************
            * TOP Bar
            *************************************************************************/
            array(
                'id'        => 'opts-topbar',
                'type'      => 'switch',
                'title'     => esc_html__('Show/Hide Topbar', 'ninezeroseven'),
                'subtitle'  => esc_html__('You can choose to show/hide the top bar here.', 'ninezeroseven'),
                'on'        => 'Enabled',
                'off'       => 'Disabled',

            ),

            //group
            array(
                'id'        => 'opts-content-topbar-override',
                'type'      => 'checkbox',
                'title'     => esc_html__('Override Topbar Content', 'ninezeroseven'),
                'subtitle'  => esc_html__('Check this if you\'d like to override topbar content', 'ninezeroseven'),
                'required'  => array('opts-topbar', "=", 1),
                'default'   => '0'// 1 = on | 0 = off
            ),
            //Left Group
            array(
                'id'           => 'opts-topbar-left-override',
                'type'         => 'repeater',
                'title'        => 'Left Topbar Items',
                'subtitle'     => 'Select a social icon to append a link to.',
                'group_values' => true,
                'item_name' => 'Item',
                'required'  => array('opts-content-topbar-override', "=", 1),
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
            ),
            //Right Group
            array(
                'id'           => 'opts-topbar-right-override',
                'type'         => 'repeater',
                'title'        => esc_html__('Right Topbar Social', 'ninezeroseven'),
                'subtitle'     => esc_html__('Select a social icon to append a link to.', 'ninezeroseven'),
                'group_values' => true,
                'item_name' => 'Social Icons',
                'required'  => array('opts-content-topbar-override', "=", 1),
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
            //Coloring
            array(
                'id'        => 'opts-enable-topmenu-color-override',
                'type'      => 'checkbox',
                'title'     => esc_html__('Top Bar Coloring', 'ninezeroseven'),
                'subtitle'  => esc_html__('Check to enable color fields for top bar.', 'ninezeroseven'),
                'required'  => array('opts-topbar', "=", 1),
                'default'   => '0'// 1 = on | 0 = off
            ),
            array(
                'id'        => 'opts-topmenu-link-color-border-override',
                'type'      => 'color',
                'title'     => esc_html__('Top Bar Background Color', 'ninezeroseven'),
                'subtitle'  => esc_html__('Change the background color.', 'ninezeroseven'),
                'required'  => array('opts-enable-topmenu-color-override', "=", 1),
                'transparent' => false,
                'default'   => '',
                'output'    => array(
                        'background-color' => '.top-extra-bar',
                        'border-color' => '.top-extra-bar'
                    )
            ),
            array(
                'id'          => 'opts-topmenu-color-override',
                'type'        => 'color',
                'title'       => esc_html__('Top Bar Text Color', 'ninezeroseven'),
                'subtitle'    => esc_html__('Change the text color.', 'ninezeroseven'),
                'required'    => array('opts-enable-topmenu-color-override', "=", 1),
                'transparent' => false,
                'default'     => '',
                'output'      => array(
                        'color' => '.top-extra-bar'
                    )
            ),
            array(
                'id'        => 'opts-topmenu-link-color-override',
                'type'      => 'color',
                'title'     => esc_html__('Top Bar Link Color', 'ninezeroseven'),
                'subtitle'  => esc_html__('Change the link color.', 'ninezeroseven'),
                'required'  => array('opts-enable-topmenu-color-override', "=", 1),
                'transparent' => false,
                'default'   => '',
                'output'    => array(
                        'color' => '.top-extra-bar a,.header-bar .social-links a'
                    )
            ),
            array(
                'id'        => 'opts-topmenu-link-color-hover-override',
                'type'      => 'color',
                'title'     => esc_html__('Top Bar Link Hover Color', 'ninezeroseven'),
                'subtitle'  => esc_html__('Change the link hover color.', 'ninezeroseven'),
                'required'  => array('opts-enable-topmenu-color-override', "=", 1),
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
                'on'       => 'Enabled',
                'off'      => 'Disabled',

            ),
            array(
                'id'        => 'opts-enable-menu-color-override',
                'type'      => 'checkbox',
                'title'     => esc_html__('Nav Bar Coloring', 'ninezeroseven'),
                'subtitle'  => esc_html__('Check to hide/show options for menu bar', 'ninezeroseven'),
                'default'   => '0'// 1 = on | 0 = off
            ),
            array(
                'id'        => 'opts-nav-background-override',
                'type'      => $options_color_field,
                 // 'output'    => array('.header-bar'),
                'title'     => esc_html__('Main Nav Background Color', 'ninezeroseven'),
                'subtitle'  => esc_html__('Change the main navigation background color.', 'ninezeroseven'),
                'required'  => array('opts-enable-menu-color-override', "=", 1),
                'transparent' => false,
                'default'   => '',
                'output'    => array(
                        'background-color' => '.menu-bar-wrapper,.menu-bar-wrapper.is-sticky'
                    )
            ),
            array(
                'id'        => 'opts-nav-link-color-override',
                'type'      => $options_color_field,
                'output'    => array('.header-inner'),
                'title'     => esc_html__('Main Nav Link Color', 'ninezeroseven'),
                'subtitle'  => esc_html__('Change the main navigation link color.', 'ninezeroseven'),
                'required'  => array('opts-enable-menu-color-override', "=", 1),
                'transparent' => false,
                'default'   => '',
                'output'    => array(
                        'color' => '.header-inner a','.wbc_menu > li > a,.primary-menu .wbc_menu a,.mobile-nav-menu .wbc_menu a'
                    )
            ),
            array(
                'id'        => 'opts-nav-link-color-hover-override',
                'type'      => $options_color_field,
                'output'    => array('.header-inner'),
                'title'     => esc_html__('Main Nav Hover Color', 'ninezeroseven'),
                'subtitle'  => esc_html__('Change the main navigation hover color.', 'ninezeroseven'),
                'required'  => array('opts-enable-menu-color-override', "=", 1),
                'transparent' => false,
                'default'   => '',
                'output'    => array(
                        'color' => '.header-inner a:hover','.wbc_menu > li > a:hover,.primary-menu .wbc_menu a:hover,.mobile-nav-menu .wbc_menu a:hover'
                    )
            ),
            array(
                'id'        => 'opts-nav-link-color-active-override',
                'type'      => $options_color_field,
                'output'    => array('.header-bar'),
                'title'     => esc_html__('Main Nav Active Color', 'ninezeroseven'),
                'subtitle'  => esc_html__('Change the main navigation active color.', 'ninezeroseven'),
                'required'  => array('opts-enable-menu-color-override', "=", 1),
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
                'id'        => 'opts-subnav-background-override',
                'type'      => $options_color_field,
                'title'     => esc_html__('Sub Nav Background Color', 'ninezeroseven'),
                'subtitle'  => esc_html__('Change the sub navigation background color.', 'ninezeroseven'),
                'required'  => array('opts-enable-menu-color-override', "=", 1),
                'transparent' => false,
                'default'   => '',
                'output'    => array(
                        'background-color' => '.primary-menu .wbc_menu li > ul, .mobile-nav-menu'
                    )
            ),
            array(
                'id'        => 'opts-subnav-link-color-border-override',
                'type'      => $options_color_field,
                'title'     => esc_html__('Sub Nav border Color', 'ninezeroseven'),
                'subtitle'  => esc_html__('Change the sub navigation border color.', 'ninezeroseven'),
                'required'  => array('opts-enable-menu-color-override', "=", 1),
                'transparent' => false,
                'default'   => '',
                'output'    => array(
                        'border-color' => '.primary-menu .wbc_menu ul li a, .mobile-nav-menu .wbc_menu a'
                    )
            ),
            array(
                'id'        => 'opts-subnav-link-color-override',
                'type'      => $options_color_field,
                'title'     => esc_html__('Sub Nav Link Color', 'ninezeroseven'),
                'subtitle'  => esc_html__('Change the sub navigation link color.', 'ninezeroseven'),
                'required'  => array('opts-enable-menu-color-override', "=", 1),
                'transparent' => false,
                'default'   => '',
                'output'    => array(
                        'color' => '.primary-menu .wbc_menu ul.sub-menu li a,.mobile-nav-menu .wbc_menu a'
                    )
            ),
            array(
                'id'        => 'opts-subnav-link-color-hover-override',
                'type'      => $options_color_field,
                'title'     => esc_html__('Sub Nav Hover Color', 'ninezeroseven'),
                'subtitle'  => esc_html__('Change the sub navigation hover color.', 'ninezeroseven'),
                'required'  => array('opts-enable-menu-color-override', "=", 1),
                'transparent' => false,
                'default'   => '',
                'output'    => array(
                        'color' => '.primary-menu .wbc_menu ul.sub-menu li a:hover,.mobile-nav-menu .wbc_menu a:hover'
                    )
            ),
            /************************************************************************
            * transpartent Nav Colors
            *************************************************************************/
            array(
                'id'        => 'opts-enable-transparent',
                'type'      => 'checkbox',
                'title'     => esc_html__('Enable Transparent Menu', 'ninezeroseven'),
                'subtitle'  => esc_html__('Only works when using full page/width template with menu set to top or bottom of browser', 'ninezeroseven'),
                'default'   => '0'// 1 = on | 0 = off
            ),
            array(
                'id'        => 'opts-transparent-links',
                'type'      => $options_color_field,
                 // 'output'    => array('.header-bar'),
                'title'     => esc_html__('Link Color', 'ninezeroseven'),
                'subtitle'  => esc_html__('Changes the link color', 'ninezeroseven'),
                'required'  => array('opts-enable-transparent', "=", 1),
                'transparent' => false,
                'default'   => '',
                'output'    => array(
                        'color' => '.has-transparent-menu .menu-bar-wrapper:not(.is-sticky),.has-transparent-menu .menu-bar-wrapper:not(.is-sticky) .menu-icon,.has-transparent-menu .menu-bar-wrapper:not(.is-sticky) .wbc_menu > li > a'
                    )
            ),
            array(
                'id'        => 'opts-transparent-link-hovers',
                'type'      => $options_color_field,
                'title'     => esc_html__('Hover Link Color', 'ninezeroseven'),
                'subtitle'  => esc_html__('Changes the link hover color', 'ninezeroseven'),
                'required'  => array('opts-enable-transparent', "=", 1),
                'transparent' => false,
                'default'   => '',
                'output'    => array(
                        'color' => '.has-transparent-menu .menu-bar-wrapper:not(.is-sticky) .mobile-nav-menu .wbc_menu a:hover,.has-transparent-menu .menu-bar-wrapper:not(.is-sticky) .menu-icon:hover,.has-transparent-menu .menu-bar-wrapper:not(.is-sticky) .menu-icon.menu-open,.has-transparent-menu .menu-bar-wrapper:not(.is-sticky) .wbc_menu > li > a:hover,.has-transparent-menu .menu-bar-wrapper:not(.is-sticky) .wbc_menu > li.active > a'
                    )
            ),
            array(
                'id'        => 'opts-transparent-heading-text',
                'type'      => $options_color_field,
                'title'     => esc_html__('Logo/Title Color', 'ninezeroseven'),
                'subtitle'  => esc_html__('Changes color of site title.', 'ninezeroseven'),
                'required'  => array('opts-enable-transparent', "=", 1),
                'transparent' => false,
                'default'   => '',
                'output'    => array(
                        'color' => '.has-transparent-menu .menu-bar-wrapper:not(.is-sticky) .site-logo-title a'
                    )
            ),
            //END MAIN NAV COLOR
            


        )
    );
    /************************************************************************
    * Sticky Header
    *************************************************************************/
    $boxSections[] = array(
        'title'     => esc_html__('Sticky Header', 'ninezeroseven'),
        'subsection' => true,
        'fields'    => array(
            //menu
            array(
                'id'        => 'opts-sticky-menu',
                'type'      => 'switch',
                'title'     => esc_html__('Sticky Menu', 'ninezeroseven'),
                'subtitle'  => esc_html__('Here you can choose to enable/disable the sticky menu(menu follows on scroll)', 'ninezeroseven'),
                'on'        => 'Enabled',
                'off'       => 'Disabled',

            ),array(
                'id'       => 'opts-elastic-menu',
                'type'     => 'switch',
                'title'    => esc_html__('Elastic Menu', 'ninezeroseven'),
                'subtitle' => esc_html__('Here you can choose to enable/disable the shrinking menu feature.', 'ninezeroseven'),
                'required' => array('opts-sticky-menu', "=", 1),
                'on'       => 'Enabled',
                'off'      => 'Disabled',

            ),
            array(
                'id'                => 'opts-elastic-height-override',
                'type'              => 'dimensions',
                // 'units'             => 'px',    // You can specify a unit value. Possible: px, em, %
                'width' => false,
                'title'             => esc_html__('Menu Bar Shrink To', 'ninezeroseven'),
                'subtitle'          => esc_html__('If you\'d like to change the small menu height, do so here', 'ninezeroseven'),
                'required' => array(
                                array('opts-main-logo-override', "=", 1),
                                array('opts-elastic-menu', "=", 1),
                            ),

            ),

            /************************************************************************
            * Main Nav Colors
            *************************************************************************/
            array(
                'id'       => 'opts-menu-sticky-shadow',
                'required' => array('opts-sticky-menu', "=", 1),
                'type'     => 'switch',
                'title'    => esc_html__('Menu Sticky Shadow', 'ninezeroseven'),
                'subtitle' => esc_html__('Enable/disable menu bar shadow when sticky enabled', 'ninezeroseven'),
                'on'       => 'Enabled',
                'off'      => 'Disabled',

            ),
            array(
                'id'        => 'opts-enable-menu-sticky-color-override',
                'type'      => 'checkbox',
                'title'     => esc_html__('Sticky Nav Bar Coloring', 'ninezeroseven'),
                'required' => array('opts-sticky-menu', "=", 1),
                'subtitle'  => esc_html__('Check to enable color fields for Sticky menu', 'ninezeroseven'),
                'default'   => '0'// 1 = on | 0 = off
            ),
            array(
                'id'        => 'opts-nav-sticky-background-override',
                'type'      => $options_color_field,
                'title'     => esc_html__('Sticky Nav Background Color', 'ninezeroseven'),
                'subtitle'  => esc_html__('Change the background color for the menu bar when scrolled.', 'ninezeroseven'),
                'required'  => array('opts-enable-menu-sticky-color-override', "=", 1),
                'transparent' => false,
                'default'   => '',
                'output'    => array(
                        'background-color' => '.menu-bar-wrapper.is-sticky'
                    )
            ),
            array(
                'id'        => 'opts-nav-sticky-link-color-override',
                'type'      => $options_color_field,
                'output'    => array('.header-inner'),
                'title'     => esc_html__('Main Nav Link Color', 'ninezeroseven'),
                'subtitle'  => esc_html__('Change the main navigation link color.', 'ninezeroseven'),
                'required'  => array('opts-enable-menu-sticky-color-override', "=", 1),
                'transparent' => false,
                'default'   => '',
                'output'    => array(
                        'color' => '.menu-bar-wrapper.is-sticky .header-inner a','.menu-bar-wrapper.is-sticky .primary-menu .wbc_menu > li > a,.mobile-menu .menu-bar-wrapper.is-sticky .primary-menu .wbc_menu a'
                    )
            ),
            array(
                'id'        => 'opts-nav-sticky-link-color-hover-override',
                'type'      => $options_color_field,
                'output'    => array('.header-inner'),
                'title'     => esc_html__('Main Nav Hover Color', 'ninezeroseven'),
                'subtitle'  => esc_html__('Change the main navigation hover color.', 'ninezeroseven'),
                'required'  => array('opts-enable-menu-sticky-color-override', "=", 1),
                'transparent' => false,
                'default'   => '',
                'output'    => array(
                        'color' => '.menu-bar-wrapper.is-sticky .header-inner a:hover','.menu-bar-wrapper.is-sticky .primary-menu .wbc_menu > li > a:hover,.mobile-menu .menu-bar-wrapper.is-sticky .primary-menu .wbc_menu a:hover'
                    )
            ),
            array(
                'id'        => 'opts-nav-sticky-link-color-active-override',
                'type'      => $options_color_field,
                'output'    => array('.header-bar'),
                'title'     => esc_html__('Main Nav Active Color', 'ninezeroseven'),
                'subtitle'  => esc_html__('Change the main navigation active color.', 'ninezeroseven'),
                'required'  => array('opts-enable-menu-sticky-color-override', "=", 1),
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
                'id'        => 'opts-subnav-sticky-background-override',
                'type'      => $options_color_field,
                'title'     => esc_html__('Sub Nav Background Color', 'ninezeroseven'),
                'subtitle'  => esc_html__('Change the sub navigation background color.', 'ninezeroseven'),
                'required'  => array('opts-enable-menu-sticky-color-override', "=", 1),
                'transparent' => false,
                'default'   => '',
                'output'    => array(
                        'background-color' => '.menu-bar-wrapper.is-sticky .primary-menu .wbc_menu li > ul,.menu-bar-wrapper.is-sticky .primary-menu.mobile-show, .menu-bar-wrapper.is-sticky .primary-menu.mobile-show a'
                    )
            ),
            array(
                'id'        => 'opts-subnav-sticky-link-color-border-override',
                'type'      => $options_color_field,
                'title'     => esc_html__('Sub Nav border Color', 'ninezeroseven'),
                'subtitle'  => esc_html__('Change the sub navigation border color.', 'ninezeroseven'),
                'required'  => array('opts-enable-menu-sticky-color-override', "=", 1),
                'transparent' => false,
                'default'   => '',
                'output'    => array(
                        'border-color' => '.menu-bar-wrapper.is-sticky .primary-menu  .wbc_menu ul li a, .menu-bar-wrapper.is-sticky .mobile-show .wbc_menu li a,.menu-bar-wrapper.is-sticky .mobile-show ul li:last-child > a'
                    )
            ),
            array(
                'id'        => 'opts-subnav-sticky-link-color-override',
                'type'      => $options_color_field,
                'title'     => esc_html__('Sub Nav Link Color', 'ninezeroseven'),
                'subtitle'  => esc_html__('Change the sub navigation link color.', 'ninezeroseven'),
                'required'  => array('opts-enable-menu-sticky-color-override', "=", 1),
                'transparent' => false,
                'default'   => '',
                'output'    => array(
                        'color' => '.menu-bar-wrapper.is-sticky .primary-menu .wbc_menu ul.sub-menu li a'
                    )
            ),
            array(
                'id'        => 'opts-subnav-sticky-link-color-hover-override',
                'type'      => $options_color_field,
                'title'     => esc_html__('Sub Nav Hover Color', 'ninezeroseven'),
                'subtitle'  => esc_html__('Change the sub navigation hover color.', 'ninezeroseven'),
                'required'  => array('opts-enable-menu-sticky-color-override', "=", 1),
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
    $boxSections[] = array(
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
                'on'        => 'Enabled',
                'off'       => 'Disabled',

            ),
            array(
                'id'        => 'opts-mobile-menu-width',
                'type'      => 'slider',
                'required'  => array('opts-mobile-menu-base', "!=", 1),
                'title'     => esc_html__('Menu Visible At', 'ninezeroseven'),
                'subtitle'  => esc_html__('Changes when the mobile menu becomes active/visible based on screen width. Default is 767px', 'ninezeroseven'),
                "min"       => 0,
                "step"      => 1,
                "max"       => 2000,
                'display_value' => 'text'
            ),
            array(
            'id'        => 'opts-mobile-menu-background-override',
            'type'      => $options_color_field,
            // 'output'    => array('.header-bar'),
            'title'     => esc_html__('Background Color', 'ninezeroseven'),
            'subtitle'  => esc_html__('Change the mobile menu background color.', 'ninezeroseven'),
            'transparent' => false,
            // 'default'   => '',
            'output'    => array(
                    'background-color' => '.mobile-nav-menu,.menu-bar-wrapper.is-sticky .mobile-nav-menu .wbc_menu li > ul'
                )
            ),
            array(
                'id'        => 'opts-mobile-menu-link-background-override',
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
                'id'        => 'opts-mobile-menu-toggle-override',
                'type'      => $options_color_field,
                // 'output'    => array('.header-bar'),
                'title'     => esc_html__('Toggle Icon Color', 'ninezeroseven'),
                'subtitle'  => esc_html__('Changes color of mobile menu icon.', 'ninezeroseven'),
                'transparent' => false,
                // 'default'   => '',
                'output'    => array(
                        'color' => '.has-transparent-menu .mobile-menu .menu-bar-wrapper a.menu-icon, .header-bar .menu-icon'
                    )
            ),
            array(
                'id'        => 'opts-mobile-menu-hover-toggle-override',
                'type'      => $options_color_field,
                // 'output'    => array('.header-bar'),
                'title'     => esc_html__('Toggle Hover Color', 'ninezeroseven'),
                'subtitle'  => esc_html__('Changes color of mobile menu icon when hovered over.', 'ninezeroseven'),
                'transparent' => false,
                // 'default'   => '',
                'output'    => array(
                        'color' => '.has-transparent-menu .mobile-menu .menu-bar-wrapper a.menu-icon:hover,.header-bar .menu-icon:hover'
                    )
            ),
            array(
                'id'        => 'opts-mobile-menu-link-color-override',
                'type'      => $options_color_field,
                'output'    => array('.header-inner'),
                'title'     => esc_html__('Link Color', 'ninezeroseven'),
                'subtitle'  => esc_html__('Change the mobile menu link colors.', 'ninezeroseven'),
                'transparent' => false,
                // 'default'   => '',
                'output'    => array(
                        'color' => '.has-transparent-menu .menu-bar-wrapper:not(.is-sticky) .mobile-nav-menu .wbc_menu a,.mobile-nav-menu .wbc_menu a,.menu-bar-wrapper.is-sticky .mobile-nav-menu .wbc_menu > li > a,.mobile-nav-menu li.menu-item-has-children i'
                    )
            ),
            array(
                'id'        => 'opts-mobile-menu-link-color-hover-override',
                'type'      => $options_color_field,
                'output'    => array('.header-inner'),
                'title'     => esc_html__('Link Hover Color', 'ninezeroseven'),
                'subtitle'  => esc_html__('Change the mobile menu link hover colors.', 'ninezeroseven'),
                // 'required'  => array('opts-enable-menu-color', "=", 1),
                'transparent' => false,
                // 'default'   => '',
                'output'    => array(
                        'color' => '.has-transparent-menu .menu-bar-wrapper:not(.is-sticky) .wbc_menu > li > a:hover,.has-transparent-menu .menu-bar-wrapper:not(.is-sticky) .mobile-nav-menu .wbc_menu li a:hover,.mobile-nav-menu .wbc_menu a:hover,.menu-bar-wrapper.is-sticky .mobile-nav-menu .wbc_menu > li > a:hover'
                    )
            ),
            array(
                'id'        => 'opts-mobile-menu-link-color-border-override',
                'type'      => $options_color_field,
                'title'     => esc_html__('Border Color', 'ninezeroseven'),
                'subtitle'  => esc_html__('Change border color below mobile menu items', 'ninezeroseven'),
                'transparent' => false,
                // 'default'   => '',
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
    // 4.2 END Header Options
    //PAGE TITLE OPTIONS
    $boxSections[] = array(
                'title'     => esc_html__('Page Title Bar', 'ninezeroseven'),
                //'desc'      => esc_html__('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'ninezeroseven'),
                'icon'      => 'fa fa-th-list',
                //'subsection' => true,
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(
                    array(
                        'id'        => 'opts-show-bread-options',
                        'type'      => 'switch',
                        'title'     => esc_html__('Page Title Options', 'ninezeroseven'),
                        'required'  => array('opts-bread-crumb', "=", 1),
                        'subtitle'  => esc_html__('You can choose to show/hide the options for this.', 'ninezeroseven'),
                        'default'   => 0,
                        'on'        => 'Show',
                        'off'       => 'Hide',
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
                        // 'default'   => 'left'
                    ),
                    array(
                        'id'   => 'opts-page-title-style-info-options',
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
                        'on'       => 'Enabled',
                        'off'      => 'Disabled',

                    ),
                    array(
                        'id'             => 'opts-override-spacing',
                        'type'           => 'spacing',
                        'output'         => array('.page-title-wrap'),
                        'required'       => array(
                                            array('opts-bread-crumb', "=", 1),
                                            array('opts-show-bread-options', "=", 1)
                                        ),
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
                        'id'        => 'opts-breadcrumb-override-background',
                        'type'      => 'background',
                        'required'  => array(
                                            array('opts-bread-crumb', "=", 1),
                                            array('opts-show-bread-options', "=", 1)
                                        ),
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
                        'id'             => 'opts-breadcrumb-title-font-override',
                        'type'           => 'typography',
                        'title'          => esc_html__('Page Title Font', 'ninezeroseven'),
                        'subtitle'       => esc_html__('Changes the font for the page title within the page title bar', 'ninezeroseven'),
                        'google'         => true,
                        'required'  => array(
                                array('opts-bread-crumb', "=", 1),
                                array('opts-show-bread-options', "=", 1)
                            ),
                        'text-align'     => false,
                        'letter-spacing' => true,
                        'font-backup'    => true,
                        'default'        => array(
                            'font-size'     => '',
                            'font-family'   => '',
                            'font-weight'   => ''
                        ),
                        'output'         => array('.page-title-wrap .entry-title')
                
                    ),
                    array(
                        'id'   => 'opts-page-title-breadcrumb-info-options',
                        'type' => 'info',
                        'required'  => array(
                                array('opts-bread-crumb', "=", 1),
                                array('opts-show-bread-options', "=", 1)
                            ),
                        'style'=> 'normal',
                        'desc' => __('<b>Breadcrumb Links</b>', 'ninezeroseven')
                    ),
                    array(
                        'id'       => 'opts-breadcrumb-links-visible',
                        'type'     => 'switch',
                        'title'    => esc_html__('Breadcrumb Links', 'ninezeroseven'),
                        'subtitle' => esc_html__('Hide the breadcrumb links', 'ninezeroseven'),
                        'required' => array(
                                            array('opts-bread-crumb', "=", 1),
                                            array('opts-show-bread-options', "=", 1)
                                        ),
                        'on'       => 'Show',
                        'off'      => 'Hide',

                    ),
                    array(
                        'id'             => 'opts-breadcrumb-links-spacing-override',
                        'type'           => 'spacing',
                        'output'         => array('.page-title-wrap .breadcrumb'),
                        'required'       => array(
                                             array('opts-bread-crumb', "=", 1),
                                             array('opts-breadcrumb-links-visible', "=", 1)
                                           ),
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
                        'id'             => 'opts-breadcrumb-font-override',
                        'type'           => 'typography',
                        'title'          => esc_html__('Breadcrumb Typography', 'ninezeroseven'),
                        'subtitle'       => esc_html__('Font options for breadcrumb links', 'ninezeroseven'),
                        'required'       => array(
                                            array('opts-bread-crumb', "=", 1),
                                            array('opts-breadcrumb-links-visible', "=", 1)
                                        ),
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
                        'id'        => 'opts-breadcrumb-override-color',
                        'type'      => $options_color_field,
                        'title'     => esc_html__('Breadcrumb Font Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Change the breadcrumb color.', 'ninezeroseven'),
                        'required'  => array(
                                            array('opts-bread-crumb', "=", 1),
                                            array('opts-breadcrumb-links-visible', "=", 1)
                                        ),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'color' => '.page-title-wrap'
                            )
                    )
                    ,
                    array(
                        'id'          => 'opts-breadcrumb-override-link-color',
                        'type'        => $options_color_field,
                        'title'     => esc_html__('Breadcrumb Link Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Change the breadcrumb link color.', 'ninezeroseven'),
                        'required'    => array(
                                            array('opts-bread-crumb', "=", 1),
                                            array('opts-breadcrumb-links-visible', "=", 1)
                                        ),
                        'transparent' => false,
                        'default'     => '',
                        'output'      => array(
                                        'color' => '.page-title-wrap a'
                                        )
                    ),
                    array(
                        'id'          => 'opts-breadcrumb-override-hover-color',
                        'type'        => $options_color_field,
                        'title'     => esc_html__('Breadcrumb Hover Link Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Change the breadcrumb hover color.', 'ninezeroseven'),
                        'required'    => array(
                                            array('opts-bread-crumb', "=", 1),
                                            array('opts-breadcrumb-links-visible', "=", 1)
                                        ),
                        'transparent' => false,
                        'default'     => '',
                        'output'      => array(
                                'color' => '.page-title-wrap a:hover'
                            )
                    ),
                    // array(
                    //     'id'             => 'opts-override-spacing',
                    //     'type'           => 'spacing',
                    //     'output'         => array('.page-title-wrap'),
                    //     'required'       => array(
                    //                             array('opts-bread-crumb', "=", 1),
                    //                             array('opts-show-bread-options', "=", 1)
                    //                         ),
                    //     'units'          => 'px',
                    //     'left'           => false,
                    //     'right'          => false,
                    //     'units_extended' => false,
                    //     'title'          => esc_html__('Bread Crumb Padding', 'ninezeroseven'),
                    //     'subtitle'       => esc_html__('Set breadcrumb padding top/bottom :)', 'ninezeroseven'),
                    //     'desc'           => esc_html__('Please enter a pixel value without the \'px\'', 'ninezeroseven'),
                    // ),
                    )//END FIELDS
                );


    //END PAGE TITLE OPTIONS
    /************************************************************************
    * SIDEBARS
    *************************************************************************/
        $boxSections[] = array(
            'title'     => esc_html__('Sidebars', 'ninezeroseven'),
            //'desc'      => esc_html__('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'ninezeroseven'),
            'icon'      => 'fa fa-columns',
            // 'subsection' => true,
            // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
            'fields'    => array(),
            );
            $boxSections[] = array(
                'title'     => esc_html__('Widget Styling', 'ninezeroseven'),
                //'desc'      => esc_html__('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'ninezeroseven'),
                'subsection' => true,
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(
                        array(
                        'id'             => 'opts-sidebar-widget-font-override',
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
                        'id'             => 'opts-sidebar-widget-text-override',
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
                        'id'        => 'opts-sidebar-link-color-override',
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
                        'id'        => 'opts-sidebar-link-color-hover-override',
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
                        'id'        => 'opts-sidebar-widget-ul-border-override',
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
                        'id'             => 'opts-sidebar-widget-spacing-override',
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
                        'id'             => 'opts-sidebar-widget-li-spacing-override',
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
    //END SIDEBARS
    // 4.3 Footer Options
    $boxSections[] = array(
        'title' => esc_html__('Footer', 'ninezeroseven'),
        //'desc' => esc_html__('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'ninezeroseven'),
        'icon_class' => 'icon-large',
        'icon' => 'fa fa-th-large',
        // 'subsection' => true,
        'fields' => array(
            
            /************************************************************************
            * Footer Options
            *************************************************************************/
            array(
                'id'       => 'opts-footer-disable',
                'type'     => 'switch',
                'title'    => esc_html__('Show/Hide Footer', 'ninezeroseven'),
                'on'       => 'Enabled',
                'off'      => 'Disabled',
                // 'default'  => 1,

            ),
            array(
                'id'       => 'opts-footer-fullwidth',
                'type'     => 'switch',
                'title'    => esc_html__('100% Footer Width', 'ninezeroseven'),
                'subtitle' => esc_html__('Makes the footer area full width.', 'ninezeroseven'),
                'required' => array('opts-footer-disable', "=", 1),
                // 'default'  => 0,
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
                // 'default'  => 1,

            ),
            array(
                'id'             => 'opts-footer-spacing-override',
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
                'id'   => 'opts-footer-widget-style-info-option',
                'type'    => 'info',
                // 'required'  => array('opts-enable-footer-color-override', "=", 1),
                
                'style'=> 'normal',
                'desc' => __('<b>Footer Widget Area Styling </b>', 'ninezeroseven')
            ),
            array(
                'id'        => 'opts-enable-footer-color-override',
                'type'      => 'checkbox',
                'required'  => array(
                                array('opts-footer-disable', "=", 1),
                                array('opts-footer-widget-area-disable', "=", 1),
                                ),

                'title'     => esc_html__('Footer Coloring', 'ninezeroseven'),
                'subtitle'  => esc_html__('Check to enable color fields for footer.', 'ninezeroseven'),
                'default'   => '0' // 1 = on | 0 = off
            ),
            //GO HERE
            array(
                'id'        => 'opts-footer-background-image-override',
                'type'      => 'background',
                'required'  => array(
                                array('opts-footer-disable', "=", 1),
                                array('opts-footer-widget-area-disable', "=", 1),
                                array('opts-enable-footer-color-override', "=", 1),
                                ),
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
                'id'        => 'opts-footer-background-color-override',
                'type'      => $options_color_field,
                'title'     => esc_html__('Footer Background Color', 'ninezeroseven'),
                'subtitle'  => esc_html__('Change the background color.', 'ninezeroseven'),
                'required'  => array(
                                array('opts-footer-disable', "=", 1),
                                array('opts-footer-widget-area-disable', "=", 1),
                                array('opts-enable-footer-color-override', "=", 1),
                                ),
                'transparent' => false,
                'default'   => '',
                'output'    => array(
                        'background-color' => '.main-footer'
                    )
            ),
            array(
                'id'             => 'opts-footer-widget-font-override',
                'type'           => 'typography',
                'title'          => esc_html__('Footer Widget Heading Typography', 'ninezeroseven'),
                'subtitle'       => esc_html__('Font options for footer widgets', 'ninezeroseven'),
                'required'  => array(
                                array('opts-footer-disable', "=", 1),
                                array('opts-footer-widget-area-disable', "=", 1),
                                array('opts-enable-footer-color-override', "=", 1),
                                ),
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
                'id'             => 'opts-footer-widget-text-override',
                'type'           => 'typography',
                'title'          => esc_html__('Footer Widget Text Typography', 'ninezeroseven'),
                'subtitle'       => esc_html__('Font options for text in footer widgets', 'ninezeroseven'),
                'required'  => array(
                                array('opts-footer-disable', "=", 1),
                                array('opts-footer-widget-area-disable', "=", 1),
                                array('opts-enable-footer-color-override', "=", 1),
                                ),
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
                'id'        => 'opts-footer-color-override',
                'type'      => $options_color_field,
                'title'     => esc_html__('Footer Text Color', 'ninezeroseven'),
                'subtitle'  => esc_html__('Changes footer text color', 'ninezeroseven'),
                'required'  => array(
                                array('opts-footer-disable', "=", 1),
                                array('opts-footer-widget-area-disable', "=", 1),
                                array('opts-enable-footer-color-override', "=", 1),
                                ),
                'transparent' => false,
                'default'   => '',
                'output'    => array(
                        'color' => '.main-footer'
                    )
            ),
            array(
                'id'        => 'opts-footer-heading-widget-link-color-override',
                'type'      => $options_color_field,
                'title'     => esc_html__('Footer Heading Link Color', 'ninezeroseven'),
                'subtitle'  => esc_html__('Changes the heading link colors in the recent post/portfolio widgets.', 'ninezeroseven'),
                'required'  => array(
                                array('opts-footer-disable', "=", 1),
                                array('opts-footer-widget-area-disable', "=", 1),
                                array('opts-enable-footer-color-override', "=", 1),
                                ),
                'transparent' => false,
                'default'   => '',
                'output'    => array(
                        'color' => '.main-footer .widgets-area .wbc-recent-post-widget h6 a'
                    )
            ),
            array(
                'id'        => 'opts-footer-heading-color-override',
                'type'      => $options_color_field,
                'title'     => esc_html__('Footer Heading Color', 'ninezeroseven'),
                'subtitle'  => esc_html__('Changes heading color for the widgets', 'ninezeroseven'),
                'required'  => array(
                                array('opts-footer-disable', "=", 1),
                                array('opts-footer-widget-area-disable', "=", 1),
                                array('opts-enable-footer-color-override', "=", 1),
                                ),
                'transparent' => false,
                'default'   => '',
                'output'    => array(
                        'color' => '.main-footer .widgets-area h4'
                    )
            ),
            array(
                'id'        => 'opts-footer-link-color-override',
                'type'      => $options_color_field,
                'title'     => esc_html__('Footer Link Color', 'ninezeroseven'),
                'subtitle'  => esc_html__('Changes link colors', 'ninezeroseven'),
                'required'  => array(
                                array('opts-footer-disable', "=", 1),
                                array('opts-footer-widget-area-disable', "=", 1),
                                array('opts-enable-footer-color-override', "=", 1),
                                ),
                'transparent' => false,
                'default'   => '',
                'output'    => array(
                        'color' => '.main-footer a'
                    )
            ),
            array(
                'id'        => 'opts-footer-link-color-hover-override',
                'type'      => $options_color_field,
                'title'     => esc_html__('Footer Hover Color', 'ninezeroseven'),
                'subtitle'  => esc_html__('Changes link hover colors', 'ninezeroseven'),
                'required'  => array(
                                array('opts-footer-disable', "=", 1),
                                array('opts-footer-widget-area-disable', "=", 1),
                                array('opts-enable-footer-color-override', "=", 1),
                                ),
                'transparent' => false,
                'default'   => '',
                'output'    => array(
                        'color' => '.main-footer a:hover'
                    )
            ),
            array(
                        'id'        => 'opts-footer-widget-ul-border-override',
                        'type'      => $options_color_field,
                        'title'     => esc_html__('Footer Widget UL Border Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Changes color of border in ul widgets.', 'ninezeroseven'),
                        'required'  => array(
                                array('opts-footer-disable', "=", 1),
                                array('opts-footer-widget-area-disable', "=", 1),
                                array('opts-enable-footer-color-override', "=", 1),
                                ),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'border-bottom-color' => '.main-footer .widgets-area .widget li'
                            )
                    ),
                    array(
                        'id'             => 'opts-footer-widget-spacing-override',
                        'type'           => 'spacing',
                        'output'         => array('.main-footer .widgets-area .widget'),
                        'units'          => 'px',
                        'mode'           => 'margin',
                        'left'           => false,
                        'right'          => false,
                        'units_extended' => false,
                        'required'       => array(
                                array('opts-footer-disable', "=", 1),
                                array('opts-footer-widget-area-disable', "=", 1),
                                array('opts-enable-footer-color-override', "=", 1),
                                ),
                        'title'          => esc_html__('Footer Widget Spacing', 'ninezeroseven'),
                        'subtitle'       => esc_html__('Sets spacing above/below widgets.', 'ninezeroseven'),
                        'desc'           => esc_html__('Enter width value + unit. IE 20px, 1em, etc.', 'ninezeroseven'),
                        'default'        => array(
                            'margin-bottom' => '',
                        )
                    ),
                    array(
                        'id'             => 'opts-footer-widget-li-spacing-override',
                        'type'           => 'spacing',
                        'output'         => array('.main-footer .widgets-area .widget li'),
                        'required'       => array(
                                array('opts-footer-disable', "=", 1),
                                array('opts-footer-widget-area-disable', "=", 1),
                                array('opts-enable-footer-color-override', "=", 1),
                                ),
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
                // 'default'  => 1,

            ),
            array(
                'id'   => 'opts-footer-copyright-info-options',
                'type' => 'info',
                'required'  => array(
                            array('opts-footer-disable', "=", 1),
                            array('opts-footer-copyright-disable', "=", 1),
                            ),
                'style'=> 'normal',
                'desc' => __('<b>Copyright Bar</b>', 'ninezeroseven')
            ),
            array(
                'id'             => 'opts-footer-credit-spacing-override',
                'type'           => 'spacing',
                'output'         => array('.main-footer .bottom-band'),
                'required'       => array(
                                    array('opts-footer-disable', "=", 1),
                                    array('opts-footer-copyright-disable', "=", 1),
                                    ),
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
                'id'        => 'opts-enable-footer2-color-override',
                'type'      => 'checkbox',
                'required'  => array(
                            array('opts-footer-disable', "=", 1),
                            array('opts-footer-copyright-disable', "=", 1),
                            ),
                'title'     => esc_html__('Copyright Bar Stying Options', 'ninezeroseven'),
                'subtitle'  => esc_html__('Check to enable style options, this is the band at the bottom of the page.', 'ninezeroseven'),
                'default'   => '0'// 1 = on | 0 = off
            ),
            array(
                'id'   => 'opts-footer-copyright-info-style-options',
                'type' => 'info',
                'required'  => array(
                                array('opts-footer-disable', "=", 1),
                                array('opts-footer-copyright-disable', "=", 1),
                                array('opts-enable-footer2-color-override', "=", 1),
                                ),
                'style'=> 'normal',
                'desc' => __('<b>Copyright Bar Styling</b>', 'ninezeroseven')
            ),
            array(
                'id'        => 'opts-footer2-background-color-override',
                'type'      => $options_color_field,
                'title'     => esc_html__('Bottom Footer Background Color', 'ninezeroseven'),
                'subtitle'  => esc_html__('Change the background color.', 'ninezeroseven'),
                'required'  => array(
                                array('opts-footer-disable', "=", 1),
                                array('opts-footer-copyright-disable', "=", 1),
                                array('opts-enable-footer2-color-override', "=", 1),
                                ),
                'transparent' => false,
                'default'   => '',
                'output'    => array(
                        'background-color' => '.bottom-band,body'
                    )
            ),
            array(
                'id'        => 'opts-footer2-border-color-override',
                'type'      => $options_color_field,
                'title'     => esc_html__('Top Border Color', 'ninezeroseven'),
                'subtitle'  => esc_html__('Change the top border color.', 'ninezeroseven'),
                'required'  => array(
                                array('opts-footer-disable', "=", 1),
                                array('opts-footer-copyright-disable', "=", 1),
                                array('opts-enable-footer2-color-override', "=", 1),
                                ),
                'transparent' => false,
                'default'   => '',
                'output'    => array(
                        'border-top-color' => '.bottom-band'
                    )
            ),
            array(
                'id'        => 'opts-footer2-color-override',
                'type'      => $options_color_field,
                'title'     => esc_html__('Text Color', 'ninezeroseven'),
                'subtitle'  => esc_html__('Changes the text color in the bottom footer.', 'ninezeroseven'),
                'required'  => array(
                                array('opts-footer-disable', "=", 1),
                                array('opts-footer-copyright-disable', "=", 1),
                                array('opts-enable-footer2-color-override', "=", 1),
                                ),
                'transparent' => false,
                'default'   => '',
                'output'    => array(
                        'color' => '.bottom-band'
                    )
            ),
            array(
                'id'        => 'opts-footer2-link-color-override',
                'type'      => $options_color_field,
                'title'     => esc_html__('Link Color', 'ninezeroseven'),
                'subtitle'  => esc_html__('Changes the text link color in the bottom footer.', 'ninezeroseven'),
                'required'  => array(
                                array('opts-footer-disable', "=", 1),
                                array('opts-footer-copyright-disable', "=", 1),
                                array('opts-enable-footer2-color-override', "=", 1),
                                ),
                'transparent' => false,
                'default'   => '',
                'output'    => array(
                        'color' => '.bottom-band a'
                    )
            ),
            array(
                'id'        => 'opts-footer2-link-color-hover-override',
                'type'      => $options_color_field,
                'title'     => esc_html__('Link Hover Color', 'ninezeroseven'),
                'subtitle'  => esc_html__('Changes the text link hover color in the bottom footer.', 'ninezeroseven'),
                'required'  => array(
                                array('opts-footer-disable', "=", 1),
                                array('opts-footer-copyright-disable', "=", 1),
                                array('opts-enable-footer2-color-override', "=", 1),
                                ),
                'transparent' => false,
                'default'   => '',
                'output'    => array(
                        'color' => '.bottom-band a:hover'
                    )
            ),

        )
    );
    // 4.3 END Footer Options
    // 4.3 Footer Options
    $boxSections[] = array(
                'title' => esc_html__('Typography', 'ninezeroseven'),
                //'desc' => esc_html__('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'ninezeroseven'),
                'icon_class' => 'icon-large',
                'icon' => 'fa fa-font',
                // 'subsection' => true,
                // 'subsection' => true, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array()//END FIELDS
                );

    $boxSections[] = array(
        'title' => esc_html__('Typography', 'ninezeroseven'),
        //'desc' => esc_html__('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'ninezeroseven'),
        // 'icon_class' => 'icon-large',
        // 'icon' => 'fa fa-font',
        'subsection' => true,
        'fields' => array(
            array(
                'id'             => 'opts-typography-body-override',
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
                'id'             => 'opts-typography-menu-override',
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
                'id'             => 'opts-typography-submenu-override',
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
                'id'             => 'opts-typography-mobile-menu-override',
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
                'id'        => 'opts-enable-heading-advance-override',
                'type'      => 'checkbox',
                'title'     => esc_html__('Advanced Headings (H tags)', 'ninezeroseven'),
                'subtitle'  => esc_html__('Check to enable advanced headings/control', 'ninezeroseven'),
                'default'   => '0'// 1 = on | 0 = off
            ),
            array(
                'id'             => 'opts-typography-heading-override',
                'type'           => 'typography',
                'required'       => array('opts-enable-heading-advance-override', "=", 0),
                'title'          => esc_html__('Headings Font (H1-H6 tags)', 'ninezeroseven'),
                'subtitle'       => esc_html__('Specify the heading tags font properties.', 'ninezeroseven'),
                'google'         => true,
                'text-align'     => false,
                'letter-spacing' => false,
                'font-size'      => false,
                'line-height'    => false,
                'font-backup'    => true,
                'default'        => array(
                    'font-size'     => '',
                    'font-family'   => '',
                    'font-weight'   => ''
                ),
                'output'    => array('h1,h2,h3,h4,h5,h6')
        
            ),

            array(
                'id'             => 'opts-typography-h1-override',
                'type'           => 'typography',
                'required'       => array('opts-enable-heading-advance-override', "=", 1),
                'title'          => esc_html__('H1 Font', 'ninezeroseven'),
                'subtitle'       => esc_html__('Specify the H1 font properties.', 'ninezeroseven'),
                'google'         => true,
                'text-align'     => false,
                'letter-spacing' => true,
                'font-backup'    => true,
                'default'        => array(
                    'font-size'     => '',
                    'font-family'   => '',
                    'font-weight'   => ''
                ),
                'output'    => array('h1')
        
            ),
            array(
                'id'             => 'opts-typography-h2-override',
                'type'           => 'typography',
                'required'       => array('opts-enable-heading-advance-override', "=", 1),
                'title'          => esc_html__('H2 Font', 'ninezeroseven'),
                'subtitle'       => esc_html__('Specify the h2 font properties.', 'ninezeroseven'),
                'google'         => true,
                'text-align'     => false,
                'letter-spacing' => true,
                'font-backup'    => true,
                'default'        => array(
                    'font-size'     => '',
                    'font-family'   => '',
                    'font-weight'   => ''
                ),
                'output'    => array('h2')
        
            ),
            array(
                'id'             => 'opts-typography-h3-override',
                'type'           => 'typography',
                'required'       => array('opts-enable-heading-advance-override', "=", 1),
                'title'          => esc_html__('H3 Font', 'ninezeroseven'),
                'subtitle'       => esc_html__('Specify the h3 font properties.', 'ninezeroseven'),
                'google'         => true,
                'text-align'     => false,
                'letter-spacing' => true,
                'font-backup'    => true,
                'default'        => array(
                    'font-size'     => '',
                    'font-family'   => '',
                    'font-weight'   => ''
                ),
                'output'    => array('h3')
        
            ),
            array(
                'id'             => 'opts-typography-h4-override',
                'type'           => 'typography',
                'required'       => array('opts-enable-heading-advance-override', "=", 1),
                'title'          => esc_html__('H4 Font', 'ninezeroseven'),
                'subtitle'       => esc_html__('Specify the h4 font properties.', 'ninezeroseven'),
                'google'         => true,
                'text-align'     => false,
                'letter-spacing' => true,
                'font-backup'    => true,
                'default'        => array(
                    'font-size'     => '',
                    'font-family'   => '',
                    'font-weight'   => ''
                ),
                'output'    => array('h4')
        
            ),
            array(
                'id'             => 'opts-typography-h5-override',
                'type'           => 'typography',
                'required'       => array('opts-enable-heading-advance-override', "=", 1),
                'title'          => esc_html__('H5 Font', 'ninezeroseven'),
                'subtitle'       => esc_html__('Specify the H5 font properties.', 'ninezeroseven'),
                'google'         => true,
                'customCSS'      => '.chicken',
                'text-align'     => false,
                'letter-spacing' => true,
                'font-backup'    => true,
                'default'        => array(
                    'font-size'     => '',
                    'font-family'   => '',
                    'font-weight'   => ''
                ),
                'output'    => array('h5')
        
            ),
            array(
                'id'             => 'opts-typography-h6-override',
                'type'           => 'typography',
                'required'       => array('opts-enable-heading-advance-override', "=", 1),
                'title'          => esc_html__('H6 Font', 'ninezeroseven'),
                'subtitle'       => esc_html__('Specify the H6 font properties.', 'ninezeroseven'),
                'google'         => true,
                'text-align'     => false,
                'letter-spacing' => true,
                'font-backup'    => true,
                'default'        => array(
                    'font-size'     => '',
                    'font-family'   => '',
                    'font-weight'   => ''
                ),
                'output'    => array('h6')
        
            ),




            ));

        /************************************************************************
                * Extra Heading Options
                *************************************************************************/
            $boxSections[] = array(
                'title'     => esc_html__('Extra Headings', 'ninezeroseven'),
                //'desc'      => esc_html__('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'ninezeroseven'),
                // 'icon'      => 'fa fa-caret-right',
                'subsection' => true,
                // 'subsection' => true, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(
                    array(
                        'id'             => 'opts-special-heading1-override',
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
                        'id'             => 'opts-special-heading2-override',
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
                        'id'             => 'opts-special-heading3-override',
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
                        'id'             => 'opts-special-heading4-override',
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
    * EXTRAS
    *************************************************************************/
    /************************************************************************
            * extra
            *************************************************************************/
            $boxSections[] = array(
                'title'     => esc_html__('Extras', 'ninezeroseven'),
                //'desc'      => esc_html__('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'ninezeroseven'),
                'icon'      => 'fa fa-cog',
                // 'subsection' => true,
                // 'subsection' => true, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array()//END FIELDS
                );
            /************************************************************************
            * Page Loader
            *************************************************************************/
            $boxSections[] = array(
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
                        // 'default' => 0,
                        'on'        => esc_html__('Enable', 'ninezeroseven'),
                        'off'       => esc_html__('Disable', 'ninezeroseven'),
                        // 'hint' => array(
                        //     'title' => 'Hint Title',
                        //     'contepant' => 'This is a <b>hint</b> for the media field with a Title.',
                        // )
                    ),
                    array(
                        'id'        => 'opts-page-loader-override',
                        'type'      => 'checkbox',
                        'title'     => esc_html__('Override Page Loader', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Override theme option values', 'ninezeroseven'),
                        'required'  => array(
                                array('opts-page-loader', "=", 1)),
                        'default'   => '0'// 1 = on | 0 = off
                    ),
                    array(
                        'id'        => 'opts-page-loader-style-override',
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
                        'required'  => array(
                                        array('opts-page-loader', "=", 1),
                                        array('opts-page-loader-override', "=", 1),
                                        ),
                    ),
                    array(
                        'id'            => 'opts-page-loader-size-override',
                        'type'          => 'slider',
                        'required'      => array(
                                        array('opts-page-loader', "=", 1),
                                        array('opts-page-loader-override', "=", 1),
                                        ),
                        'title'         => esc_html__('Loader Size', 'ninezeroseven'),
                        'subtitle'      => esc_html__('Changes size of loader', 'ninezeroseven'),
                        "default"       => 60,
                        "min"           => 10,
                        "step"          => 1,
                        "max"           => 200,
                        'display_value' => 'text'
                    ),
        
                    array(
                        'id'        => 'opts-page-loader-bg-color-override',
                        'type'      => $options_color_field,
                        'title'     => esc_html__('Loader BG Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Change the background color.', 'ninezeroseven'),
                        'required'  => array(
                                        array('opts-page-loader', "=", 1),
                                        array('opts-page-loader-override', "=", 1),
                                        ),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'background-color' => '.wbc-loader-wrapper'
                            )
                    ),
                    array(
                        'id'        => 'opts-page-loader-color-override',
                        'type'      => $options_color_field,
                        'title'     => esc_html__('Loader Color', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Change the animated loader color.', 'ninezeroseven'),
                        'required'  => array(
                                        array('opts-page-loader', "=", 1),
                                        array('opts-page-loader-override', "=", 1),
                                        ),
                        'transparent' => false,
                        'default'   => '',
                        'output'    => array(
                                'background-color' => '.wbc-loader-color,.wbc-loader div .wbc-loader-child-color,.wbc-loader div .wbc-loader-child-color-before:before'
                            )
                    ),

            ));//END PAGE LOADER
            /************************************************************************
            * Form Styling
            *************************************************************************/
            
            $boxSections[] = array(
                'title'     => esc_html__('Form Styling', 'ninezeroseven'),
                //'desc'      => esc_html__('Redux Framework was created with the developer in mind. It allows for any theme developer to have an advanced theme panel with most of the features a developer would need. For more information check out the Github repo at: <a href="https://github.com/ReduxFramework/Redux-Framework">https://github.com/ReduxFramework/Redux-Framework</a>', 'ninezeroseven'),
                // 'icon'      => '',
                'subsection' => true,
                // 'subsection' => true, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(
                    array(
                        'id'                => 'opts-form-height-override',
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
                        'id'        => 'opts-page-forms-color-override',
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
                        'id'        => 'opts-page-forms-border-color-override',
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
                        'id'        => 'opts-page-forms-font-color-override',
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
                        'id'        => 'opts-page-forms-label-color-override',
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
                        // 'default'   => 1,
                        'on'        => esc_html__('Enable', 'ninezeroseven'),
                        'off'       => esc_html__('Disable', 'ninezeroseven'),
                    ),
                    array(
                        'id'        => 'opts-page-select-arrow-bg-color-override',
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
                        'id'          => 'opts-page-select-arrow-color-override',
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
                        'id'        => 'opts-page-select-arrow-border-color-override',
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
            $boxSections[] = array(
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
                        // 'default' => 0,
                        'on'        => esc_html__('Enable', 'ninezeroseven'),
                        'off'       => esc_html__('Disable', 'ninezeroseven'),
                    ),
                    array(
                        'id'        => 'opts-page-floating-nav',
                        'type'      => 'switch',
                        'title'     => esc_html__('Floating Side Navigation', 'ninezeroseven'),
                        'subtitle'  => esc_html__('Next/Prev links are floated on the sides and expand when hovered.', 'ninezeroseven'),
                        'required'  => array('opts-page-navigation-enabled', "=", 1),
                        // 'default'   => 1,
                        'on'        => esc_html__('Enable', 'ninezeroseven'),
                        'off'       => esc_html__('Disable', 'ninezeroseven'),
                    ),
                    array(
                        'id'          => 'opts-page-floating-nav-bg-override',
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
                        'id'          => 'opts-page-floating-nav-bg-hover-override',
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
                        'id'          => 'opts-page-floating-nav-color-override',
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
                        'id'        => 'opts-page-after-post-nav-override',
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
                        // 'default'   => 0,
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
                        // 'default'   => 'wbc-nav-style-1'
                    ),
                    array(
                        'id'             => 'opts-page-after-main-content-space-override',
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
                        'id'       => 'opts-page-after-main-content-bg-override',
                        'type'     => 'background',
                        'title'    => esc_html__('Navigation Background', 'ninezeroseven'),
                        'output'    => array(
                                'background-color' => '.page-wrapper > .wbc-nav-row-1,.page-wrapper > .wbc-nav-row-2'
                            ),
                        'required'  => array('opts-page-after-main-content-nav', "=", 1),
                        'subtitle' => esc_html__('Add background to the navigation container.', 'ninezeroseven'),
                    ),
                    array(
                        'id'          => 'opts-page-after-main-content-overlay-override',
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
                        'id'             => 'opts-page-after-main-content-title-font-override',
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
                        'id'          => 'opts-page-after-main-content-title-hover-override',
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
                        'id'             => 'opts-page-after-main-content-small-font-override',
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

    //END EXTRAS

    $metaboxes[] = array(
        'id'            => 'wbc-page-layout-extended',
        'title'         => esc_html__('Page Options', 'ninezeroseven'),
        'post_types'    => array('post','page','wbc-portfolio'),
        //'page_template' => array('page-test.php'),
        //'post_format'   => array('image'),
        'position'      => 'normal', // normal, advanced, side
        'priority'      => 'default', // high, core, default, low
        'sections'      => $boxSections
    );

    /************************************************************************
    * 4- END Page, Post, wbc_portfolio Options
    *************************************************************************/



    // Kind of overkill, but ahh well.  ;)
    $metaboxes = apply_filters( 'wbc_theme_meta_boxes', $metaboxes );

    return $metaboxes;
  }
  add_action('redux/metaboxes/'.$redux_opt_name.'/boxes', 'wbc907_add_metaboxes');
endif;
?>
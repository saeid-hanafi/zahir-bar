<?php

/**
 * The Smart User Slug Hider admin plugin class
 *
 * @since 4.0.0
 */
 
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The admin plugin class
 */
if ( !class_exists( 'PP_Smart_User_Slug_Hider_Admin' ) ) {
  
  class PP_Smart_User_Slug_Hider_Admin extends PPF08_Admin {

    
    /**
	   * Do Init
     *
     * @since 4.0.0
     * @access public
     */
    public function init() {
      
      $this->init_rating_notice( array( 
        'title'          => esc_html__( 'Are you happy with the Smart User Slug Hider plugin?', 'smart-user-slug-hider' ),
        'subtitle'       => esc_html__( 'You\'ve  been using this plugin for a while now. Would be great to get some feedback!', 'smart-user-slug-hider' ),
        'button_yes'     => esc_html__( 'Yes, I\'m happy with it', 'smart-user-slug-hider' ),
        'button_no'      => esc_html__( 'Not really', 'smart-user-slug-hider' ),
        'button_later'   => esc_html__( 'Ask me later', 'smart-user-slug-hider' ),
        'button_close'   => esc_html__( 'Never show again', 'smart-user-slug-hider' ),
        'like'           => esc_html__( 'I\'m really glad you like it.  I do not ask for a donation. All I\'m asking you for is to give it a good rating. Thank you very much.', 'smart-user-slug-hider' ),
        'button_rate'    => esc_html__( 'Yes, I\'d like to rate it', 'smart-user-slug-hider' ),
        'dislike'        => esc_html__( 'I\'m really sorry you don\'t like it. Would you please do me a favor and drop me line, why you are not happy with it? Maybe I can do better...', 'smart-user-slug-hider' ),
        'button_contact' => esc_html__( 'Yes sure', 'smart-user-slug-hider' )
      ),
      array(
        'rate'           => 'https://wordpress.org/support/plugin/' . $this->core()->get_plugin_slug() . '/reviews/',
        'contact'        => 'https://petersplugins.com/contact/'
      ));

      $this->add_actions( array( 
        'admin_init',
        'admin_menu'
      ) );
      
      add_filter( 'plugin_action_links_' . plugin_basename( $this->core()->get_plugin_file() ), array( $this, 'add_settings_links' ) ); 
    
    }
    
    
    /**
     * init admin 
     * moved to PP_Smart_User_Slug_Hider_Admin in v 4.0.0
     */
    function action_admin_init() {
      
      $this->add_setting_sections(
      
        array(
          
          array(
        
            'section' => 'info',
            'order'   => 10,
            'title'   => esc_html__( 'Info', 'smart-user-slug-hider' ),
             'icon'    => 'info',
            'html' => '<p><strong>' . esc_html__( 'This Plugin replaces user names with 16 digits coded strings.', 'smart-user-slug-hider' ) . '</strong></p><p>' . esc_html__('There are no settings.', 'smart-user-slug-hider' ) . '</p><hr /><p>' . esc_html__('Your coded user slug: ', 'smart-user-slug-hider' ) . $this->core()->get_smart_user_slug() . '</p>',
            'nosubmit' => true
        
          )
          
        )
        
      );
      
    }
    
    
    /**
     * create the menu entry
     * moved to PP_Smart_User_Slug_Hider_Admin in v 4.0.0
     */
    function action_admin_menu() {
      
      $screen_id = add_options_page( $this->core()->get_plugin_shortname(), $this->core()->get_plugin_shortname(), 'manage_options', 'smartuserslughidersettings', array( $this, 'show_admin' ) );
      $this->set_screen_id( $screen_id );
      
    }
    
   
    /**
     * show admin page
     * moved to PP_Smart_User_Slug_Hider_Admin in v 4.0.0
     */
    function show_admin() {
      
      $this->add_toolbar_icons( array(
        array( 
          'link'  => 'https://wordpress.org/support/plugin/' . $this->core()->get_plugin_slug() . '/reviews/',
          'title' => __( 'Please rate Plugin', 'smart-user-slug-hider' ),
          'icon'  => 'dashicons-star-filled',
          'highlight' => true
        ),
        array( 
          'link'  => 'https://wordpress.org/plugins/' . $this->core()->get_plugin_slug(),
          'title' => __( 'WordPress.org Plugin Page', 'smart-user-slug-hider' ),
          'icon'  => 'dashicons-wordpress'
        ),
        array( 
          'link'  => 'https://wordpress.org/support/plugin/' . $this->core()->get_plugin_slug(),
          'title' => __( 'Support', 'smart-user-slug-hider' ),
          'icon'  => 'dashicons-editor-help'
        )
      ) );
      
      $this->show( 'manage_options' );
      
    }
    
    
    /**
     * add links to plugins table
     * moved to   PP_Smart_User_Slug_Hider_Admin in v 4.0.0
     */
    function add_settings_links( $links ) {
      
      return array_merge( $links, array( '<a href="' . admin_url( 'options-general.php?page=smartuserslughidersettings' ) . '" title="' . esc_html__( 'Information', 'smart-user-slug-hider' ) . '">' . esc_html__( 'Information', 'smart-user-slug-hider' ) . '</a>', '<a href="https://wordpress.org/support/plugin/' . $this->core()->get_plugin_slug() . '/reviews/" title="' . esc_html__( 'Please rate plugin', 'smart-user-slug-hider' ) . '">' . esc_html__( 'Please rate plugin', 'smart-user-slug-hider' ) . '</a>' ) );
      
    }
    
    /**
     * create nonce
     *
     * @since  4.0.0
     * @access private
     * @return string Nonce
     */
    private function get_nonce() {
      
      return wp_create_nonce( 'pp_smart_user_slug_hider_dismiss_admin_notice' );
      
    }
    
    
    /**
     * check nonce
     *
     * @since  4.0.0
     * @access private
     * @return boolean
     */
    private function check_nonce() {
      
      return check_ajax_referer( 'pp_smart_user_slug_hider_dismiss_admin_notice', 'securekey', false );
      
    }

  }
  
}

?>
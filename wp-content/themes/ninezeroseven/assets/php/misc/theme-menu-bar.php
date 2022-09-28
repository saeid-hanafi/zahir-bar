<!-- Begin Header -->

    <header <?php wbc907_menu_class( 'header-bar mobile-menu' ); ?><?php echo wbc907_height_data(); ?>>


      <?php
      if ( !function_exists('wbc907_do_template_location') ||  !wbc907_do_template_location( 'header' ) ) {
      /* Currently topbar area hooked */
      do_action( 'wbc907_before_nav_bar' );

      ?>

      <div class="menu-bar-wrapper">
        <div class="container">
          <div class="header-inner">

            <?php

            /* Currently logo/title area hooked */
            do_action( 'wbc907_logo_title' );


            /* Displays top main menu */
            wp_nav_menu( array(
                'container'       => 'nav',
                'container_class' => 'primary-menu',
                'container_id'    => 'wbc9-main',
                'menu'            => apply_filters( 'wbc907_page_menu', '' ),
                'menu_id'         => 'main-menu',
                'menu_class'      => 'wbc_menu',
                'theme_location'  => 'wbc907-primary',
                'fallback_cb'     => ''
              ) );

            ?>
            <div class="clearfix"></div>
          </div><!-- ./header-inner -->


          <a href="#" class="menu-icon" aria-label="Toggle Menu"><i class="fa fa-bars"></i></a>
          <nav id="mobile-nav-menu" class="mobile-nav-menu" style="display:none;">
            <?php 
              wp_nav_menu( array(
                  'container'       => false,
                  'menu'            => apply_filters( 'wbc907_page_menu', '' ),
                  'menu_id'         => 'mobile-nav-menu-ul',
                  'menu_class'      => 'wbc_menu',
                  'theme_location'  => 'wbc907-primary',
                  'fallback_cb'     => ''
                ) );

            ?>
          </nav>
         <div class="clearfix"></div>
        </div><!-- ./container -->
      </div> <!-- ./menu-bar-wrapper -->
      <?php } ?>
    </header>
<!-- End Header -->

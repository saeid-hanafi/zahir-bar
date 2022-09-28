<?php
/************************************************************************
* Sidebar File
*************************************************************************/
?>

<div class="side-bar">
  <?php 
    if( is_active_sidebar( apply_filters( 'wbc907_custom_sidebars' , 'sidebar-1' ) ) ){
        dynamic_sidebar( apply_filters( 'wbc907_custom_sidebars' , 'sidebar-1' ) );
    }
  ?>
</div>

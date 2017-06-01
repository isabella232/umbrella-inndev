<?php

// RESTRICT ACCESS TO THE ADMIN AREA TO ONLY ADMINS
function pauopps_restrict_redirect(){
    
    // FIND OUT WHAT PAGE THEY'RE REQUESTING
    global $pagenow;
    
    // IF IT'S AN ADMIN REQUEST
    if ( is_admin() ) {
    
        // IF THEY'RE TRYING TO ACCESS THE ORIGINAL NEW OPPORTUNITIES PAGE, REDIRECT THEM
        if ( isset( $_GET['post_type'] ) ) {
	        if ( $pagenow == 'post-new.php' && 'pp_opportunity' == $_GET['post_type'] ) {
		        wp_redirect( admin_url( 'admin.php?page=pauopps_list' ) );
		        die();
	        }
		}
    }
}
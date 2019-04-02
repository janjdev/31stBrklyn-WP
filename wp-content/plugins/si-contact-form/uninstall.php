<?php

if ( defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	// settings get deleted when plugin is deleted from admin plugins page
	delete_option( 'fs_contact_global' );

	// delete up to 100 forms (a unique configuration for each contact form)
	for ( $i = 1; $i <= 100; $i++ ) {
	   delete_option( "fs_contact_form$i" );
	}
}

// end of file  
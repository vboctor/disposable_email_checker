<?php
require_once( dirname( dirname( __FILE__ ) ) . '/disposable.php' );

function echo_domain( $p_domain ) {
	DisposableEmailChecker::echo_results( $p_domain );
	echo '<br />';
}

DisposableEmailChecker::echo_stats();
echo '<br />';

echo_domain( 'someone@outlook.com' );
echo_domain( 'someone@gmail.com' );
echo_domain( 'someone@mantishub.com' );

<?php
# Disposable Email Checker - a static php based check for spam emails
# Copyright (C) 2007-2015 Victor Boctor

# This program is distributed under the terms and conditions of the MIT
# See the README and LICENSE files for details

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

<?php
	# Disposable Email Checker - a static php based check for spam emails
	# Copyright (C) 2007-2008 Victor Boctor
	
	# This program is distributed under the terms and conditions of the LGPL
	# See the README and LICENSE files for details

/**
 * A class that checks an email address and provides some facts about whether
 * it is a disposable, free web mail, etc.  The data that is used to make
 * such decision is static as part of the class implementation, hence
 * avoiding a round trip to a remote service.  This makes the class much
 * more efficient in scenarios where performance is an issue.
 */
class DisposableEmailChecker
{
	private static $forwarding_domains_array = null;
	private static $trash_domains_array = null;
	private static $shredder_domains_array = null;
	private static $time_bound_domains_array = null;
	private static $open_domains_array = null;

	/**
	 * Determines if the email address is disposable.
	 *
	 * @param $p_email  The email address to validate.
	 * @returns true: disposable, false: non-disposable.
	 */
	public static function is_disposable_email( $p_email ) {
		return (
			DisposableEmailChecker::is_forwarding_email( $p_email ) ||
			DisposableEmailChecker::is_trash_email( $p_email ) ||
			DisposableEmailChecker::is_time_bound_email( $p_email ) ||
			DisposableEmailChecker::is_shredder_email( $p_email ) );
	}

	/**
	 * Determines if the email address is disposable email that forwards to
	 * users' email address.  This is one of the best kind of disposable 
	 * addresses since emails end up in the user's inbox unless the user
	 * cancel the address.
	 *
	 * @param $p_email  The email address to check.
	 * @returns true: disposable forwarding, false: otherwise.
	 */
	public static function is_forwarding_email( $p_email ) {
		$t_domain = DisposableEmailChecker::_get_domain_from_address( $p_email );

		if ( DisposableEmailChecker::$forwarding_domains_array === null ) {
			DisposableEmailChecker::$forwarding_domains_array = DisposableEmailChecker::_load_file( 'forwarding_domains' );
		}

		return in_array( $t_domain, DisposableEmailChecker::$forwarding_domains_array );
	}

	/**
	 * Determines if the email address is trash email that doesn't forward to
	 * user's email address.  This kind of address can be checked using a 
	 * web page and no password is required for such check.  Hence, data sent
	 * to such address is not protected.  Typically users use these addresses
	 * to signup for a service, and then they never check it again.
	 *
	 * @param $p_email  The email address to check.
	 * @returns true: disposable trash mail, false: otherwise.
	 */
	public static function is_trash_email( $p_email ) {
		$t_domain = DisposableEmailChecker::_get_domain_from_address( $p_email );

		if ( DisposableEmailChecker::$trash_domains_array === null ) {
			DisposableEmailChecker::$trash_domains_array = DisposableEmailChecker::_load_file( 'trash_domains' );
		}

		return in_array( $t_domain, DisposableEmailChecker::$trash_domains_array );
	}

	/**
	 * Determines if the email address is a shredder email address.  Shredder
	 * email address delete all received emails without forwarding them or 
	 * making them available for a user to check.
	 *
	 * @param $p_email  The email address to check.
	 * @returns true: shredded disposable email, false: otherwise.
	 */
	public static function is_shredder_email( $p_email ) {
		$t_domain = DisposableEmailChecker::_get_domain_from_address( $p_email );

		if ( DisposableEmailChecker::$shredder_domains_array === null ) {
			DisposableEmailChecker::$shredder_domains_array = DisposableEmailChecker::_load_file( 'shredder_domains' );
		}

		return in_array( $t_domain, DisposableEmailChecker::$shredder_domains_array );
	}

	/**
	 * Determines if the email address is time bound, these are the disposable
	 * addresses that auto expire after a pre-configured time.  For example,
	 * 10 minutes, 1 hour, 2 hours, 1 day, 1 month, etc.  These address can
	 * also be trash emails or forwarding emails.
	 *
	 * @param $p_email  The email address to check.
	 * @returns true: time bound disposable email, false: otherwise.
	 */
	public static function is_time_bound_email( $p_email ) {
		$t_domain = DisposableEmailChecker::_get_domain_from_address( $p_email );

		if ( DisposableEmailChecker::$time_bound_domains_array === null ) {
			DisposableEmailChecker::$time_bound_domains_array = DisposableEmailChecker::_load_file( 'time_bound_domains' );
		}

		return in_array( $t_domain, DisposableEmailChecker::$time_bound_domains_array );
	}

	/**
	 * See is_open_email() for details.
	 */
	public static function is_free_email( $p_email ) {
		return DisposableEmailChecker::is_open_email( $p_email );
	}

	/**
	 * Determines if the email address is an email address in an open domain.  These are
	 * addresses that users can sign up for, typically free.  They then has to login to
	 * these address to get the emails.  These are not considered to be
	 * disposable emails, however, if the application is providing a free
	 * trial for an expensive server, then users can signup for more accounts
	 * to get further trials.
	 *
	 * If applications are to block these addresses, it is important to be aware
	 * that some users use open webmail as their primary email and that such
	 * service providers include hotmail, gmail, and yahoo.
	 *
	 * @param $p_email  The email address to check.
	 * @returns true: open domain email, false: otherwise.
	 */
	public static function is_open_email( $p_email ) {
		$t_domain = DisposableEmailChecker::_get_domain_from_address( $p_email );

		if ( DisposableEmailChecker::$open_domains_array === null ) {
			DisposableEmailChecker::$open_domains_array = DisposableEmailChecker::_load_file( 'open_domains' );
		}

		return in_array( $t_domain, DisposableEmailChecker::$open_domains_array );
	}

	/**
	 * A debugging function that takes in an email address and dumps out the
	 * details for such email.
	 * 
	 * @param $p_email  The email address to echo results for.  This must be a 
	 *                  safe script (i.e. no javascript, etc).
	 */
	public static function echo_results( $p_email ) {		
		echo 'email address = ', htmlspecialchars( $p_email ), '<br />';
		echo 'is_disposable_email = ', DisposableEmailChecker::is_disposable_email( $p_email ) ? 'true' : 'false', '<br />'; 
		echo 'is_forwarding_email = ', DisposableEmailChecker::is_forwarding_email( $p_email ) ? 'true' : 'false', '<br />'; 
		echo 'is_trash_email = ', DisposableEmailChecker::is_trash_email( $p_email ) ? 'true' : 'false', '<br />'; 
		echo 'is_time_bound_email = ', DisposableEmailChecker::is_time_bound_email( $p_email ) ? 'true' : 'false', '<br />'; 
		echo 'is_shredder_email = ', DisposableEmailChecker::is_shredder_email( $p_email ) ? 'true' : 'false', '<br />'; 
		echo 'is_free_email = ', DisposableEmailChecker::is_free_email( $p_email ) ? 'true' : 'false', '<br />'; 
	}

	/**
	 * A debugging function that outputs some statistics about the number of domains in
	 * each category.
	 */
	public static function echo_stats() {
		// Trigger loading of all domains
		$domain = 'example.com';
		DisposableEmailChecker::is_forwarding_email( $domain );
		DisposableEmailChecker::is_open_email( $domain );
		DisposableEmailChecker::is_free_email( $domain );
		DisposableEmailChecker::is_time_bound_email( $domain );
		DisposableEmailChecker::is_shredder_email( $domain );
		DisposableEmailChecker::is_trash_email( $domain );
		DisposableEmailChecker::is_forwarding_email( $domain );

		echo 'Forwarding Domains: ' . count( DisposableEmailChecker::$forwarding_domains_array ) . '<br />';
		echo 'Free Domains: ' . count( DisposableEmailChecker::$open_domains_array ) . '<br />';
		echo 'Shredded Domains: ' . count( DisposableEmailChecker::$shredder_domains_array ) . '<br />';
		echo 'Time Bound: ' . count( DisposableEmailChecker::$time_bound_domains_array ) . '<br />';
		echo 'Trash Domains: ' . count( DisposableEmailChecker::$trash_domains_array ) . '<br />';
	}

	//
	// Private functions, shouldn't be called from outside the class
	//

	/**
	 * Load the specified file given its name.
	 *
	 * @param $p_type The name of the file not including the path or extension (e.g. open_domains).
	 * @returns array An array of domains matching the specified file name.
	 */
	private static function _load_file( $p_type ) {
		$t_array = file( dirname( dirname( __FILE__ ) ) . '/data/' . $p_type . '.txt' );
		$t_result_array = array();

		foreach ( $t_array as $t_line ) {
			$t_entry = trim( $t_line );
			if ( empty( $t_entry ) ) {
				continue;
			}

			$t_result_array[] = strtolower( $t_entry );
		}

		return $t_result_array;
	}

	/**
	 * A helper function that takes in an email address and returns a lower case
	 * domain.
	 *
	 * @param $p_email  The email address to extra the domain from.
	 * @returns The lower case domain or empty string if email not valid.
	 */
	private static function _get_domain_from_address( $p_email ) {
		$t_domain_pos = strpos( $p_email, '@' );

		// If no @ sign, assume domain was passed in and return as is.
		if ( $t_domain_pos === false ) {
			return $p_email;
		}

		return strtolower( substr( $p_email, $t_domain_pos + 1 ) );
	}
}
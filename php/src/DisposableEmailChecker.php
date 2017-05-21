<?php
# Disposable Email Checker - a static php based check for spam emails
# Copyright (C) 2007-2017 Victor Boctor

# This program is distributed under the terms and conditions of the MIT
# See the README and LICENSE files for details

namespace VBoctor\Email;

/**
 * A class that checks an email address to determine if it is a disposable
 * email address or not.  The data that is used to make such decision is
 * bundled with this library, hence, avoiding a round trip to a remote service.
 */
class DisposableEmailChecker
{
	private static $domains_array = null;

	/**
	 * Determines if the email address is disposable.
	 *
	 * @param string $p_email  The email address to validate.
	 * @return boolean true: disposable, false: non-disposable.
	 */
	public static function is_disposable_email( $p_email ) {
		$t_domain = DisposableEmailChecker::_get_domain_from_address( $p_email );

		if ( DisposableEmailChecker::$domains_array === null ) {
			DisposableEmailChecker::$domains_array = DisposableEmailChecker::_load_file( 'domains' );
		}

		return in_array( $t_domain, DisposableEmailChecker::$domains_array );
	}

	/**
	 * Determines whether a given email address is subaddressed or not.
	 * Subaddressed email addresses, also known as plus addresses or tagged
	 * addresses, have the form username+tag@domain.tld.
	 *
	 * @param string $address  An email address to test.
	 * @return boolean true: subaddressed email, false: otherwise.
	 *
	 * @see https://en.wikipedia.org/wiki/Email_address#Sub-addressing
	 */
	public static function is_subaddressed_email($address) {
		// A subaddressed email address must contain a username and a plus sign.
		// Match any string that begins with one or more characters other than
		// an at sign (@), followed by a plus sign (+).
		return preg_match('/^[^@]+\+/', $address) == 1;
	}

	//
	// Private functions, shouldn't be called from outside the class
	//

	/**
	 * Load the specified file given its name.
	 *
	 * @param string $p_type The name of the file not including the path or extension (e.g. open_domains).
	 * @return array An array of domains matching the specified file name.
	 */
	private static function _load_file( $p_type ) {
		$t_array = file( __DIR__ . '/../../data/' . $p_type . '.txt' );
		$t_result_array = array();

		foreach ( $t_array as $t_line ) {
			$t_entry = trim( $t_line );
			if ( empty( $t_entry ) ) {
				continue;
			}

			# Exclude commented lines
			if ( strpos( $t_entry, '#' ) === 0 ) {
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
	 * @param string $p_email  The email address to extra the domain from.
	 * @return string The lower case domain or empty string if email not valid.
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
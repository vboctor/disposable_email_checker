<?php
# Disposable Email Checker - a static php based check for spam emails
# Copyright (C) 2007-2017 Victor Boctor

# This program is distributed under the terms and conditions of the MIT
# See the README and LICENSE files for details

require_once( __DIR__ . '/../src/DisposableEmailChecker.php' );

use VBoctor\Email\DisposableEmailChecker as DisposableEmailChecker;

class DisposableEmailCheckerTests extends PHPUnit_Framework_TestCase
{
	/**
	 * @dataProvider providerIsSubaddressedEmail
	 */
	public function testSubaddressedEmail($expected, $address) {
		$this->assertEquals( $expected, DisposableEmailChecker::is_subaddressed_email( $address ) );
	}

	public function providerIsSubaddressedEmail() {
		// Subaddressed
		$tests[] = array( TRUE, 'username+tag@example.com' );
		$tests[] = array( TRUE, 'username+tag+@example.com' );
		$tests[] = array( TRUE, 'username++tag@example.com' );
		$tests[] = array( TRUE, 'username+@example.com' );
		$tests[] = array( TRUE, 'username++@example.com' );

		// Non-subaddressed
		$tests[] = array( FALSE, 'username@example.com' );
		$tests[] = array( FALSE, '+tag@example.com' );
		$tests[] = array( FALSE, 'username@sub+domain.example.com' );
		$tests[] = array( FALSE, '' );

		return $tests;
	}

	public function testDisposableDomain() {
		$this->assertTrue( DisposableEmailChecker::is_disposable_email( 'someone@xmaily.com' ) );
		$this->assertTrue( DisposableEmailChecker::is_disposable_email( 'xmaily.com' ) );
	}

	public function testAddDomains() {
		DisposableEmailChecker::add_domains( array( 'abc.com', 'XYZ.com' ) );
		$this->assertTrue( DisposableEmailChecker::is_disposable_email( 'xyz.com' ) );
		$this->assertTrue( DisposableEmailChecker::is_disposable_email( 'someone@xyz.com' ) );
		$this->assertTrue( DisposableEmailChecker::is_disposable_email( 'abc.com' ) );
		$this->assertTrue( DisposableEmailChecker::is_disposable_email( 'someone@ABC.com' ) );
	}

	public function testRemoveDomains() {
		DisposableEmailChecker::remove_domains( array( 'XMAILY.com', 'not-existence-domain.com' ) );
		$this->assertFalse( DisposableEmailChecker::is_disposable_email( 'someone@xmaily.com' ) );
		$this->assertFalse( DisposableEmailChecker::is_disposable_email( 'xmaily.com' ) );
	}

	public function testDisposableDomainNoMatch() {
		$this->assertFalse( DisposableEmailChecker::is_disposable_email( 'someone@outlook.com' ) );
		$this->assertFalse( DisposableEmailChecker::is_disposable_email( 'outlook.com' ) );

		$this->assertFalse( DisposableEmailChecker::is_disposable_email( 'someone@mantishub.com' ) );
		$this->assertFalse( DisposableEmailChecker::is_disposable_email( 'mantishub.com' ) );
	}
}


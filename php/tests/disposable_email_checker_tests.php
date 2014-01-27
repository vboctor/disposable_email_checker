<?php
require_once( dirname( dirname( __FILE__ ) ) . '/disposable.php' );

class DisposableEmailCheckerTests extends PHPUnit_Framework_TestCase
{
	public function testOpenDomain() {
		$this->assertTrue( DisposableEmailChecker::is_open_email( 'someone@outlook.com' ) );
		$this->assertTrue( DisposableEmailChecker::is_free_email( 'someone@outlook.com' ) );

		$this->assertTrue( DisposableEmailChecker::is_open_email( 'outlook.com' ) );
		$this->assertTrue( DisposableEmailChecker::is_free_email( 'outlook.com' ) );
	}

	public function testOpenDomainNoMatch() {
		$this->assertFalse( DisposableEmailChecker::is_open_email( 'someone@mantishub.com' ) );
		$this->assertFalse( DisposableEmailChecker::is_free_email( 'someone@mantishub.com' ) );

		$this->assertFalse( DisposableEmailChecker::is_open_email( 'mantishub.com' ) );
		$this->assertFalse( DisposableEmailChecker::is_free_email( 'mantishub.com' ) );
	}
}


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

	public function testForwardingDomain() {
		$this->assertTrue( DisposableEmailChecker::is_forwarding_email( 'someone@xmaily.com' ) );
		$this->assertTrue( DisposableEmailChecker::is_forwarding_email( 'xmaily.com' ) );
	}

	public function testForwardingDomainNoMatch() {
		$this->assertFalse( DisposableEmailChecker::is_forwarding_email( 'someone@mantishub.com' ) );
		$this->assertFalse( DisposableEmailChecker::is_forwarding_email( 'mantishub.com' ) );
	}

	public function testTrashDomain() {
		$this->assertTrue( DisposableEmailChecker::is_trash_email( 'someone@fakeinbox.com' ) );
		$this->assertTrue( DisposableEmailChecker::is_trash_email( 'fakeinbox.com' ) );
	}

	public function testTrashDomainNoMatch() {
		$this->assertFalse( DisposableEmailChecker::is_trash_email( 'someone@mantishub.com' ) );
		$this->assertFalse( DisposableEmailChecker::is_trash_email( 'mantishub.com' ) );
	}

	public function testShredderDomain() {
		$this->assertTrue( DisposableEmailChecker::is_shredder_email( 'someone@spambob.org' ) );
		$this->assertTrue( DisposableEmailChecker::is_shredder_email( 'spambob.org' ) );
	}

	public function testShredderDomainNoMatch() {
		$this->assertFalse( DisposableEmailChecker::is_shredder_email( 'someone@mantishub.com' ) );
		$this->assertFalse( DisposableEmailChecker::is_shredder_email( 'mantishub.com' ) );
	}

	public function testTimeBoundDomain() {
		$this->assertTrue( DisposableEmailChecker::is_time_bound_email( 'someone@getonemail.com' ) );
		$this->assertTrue( DisposableEmailChecker::is_time_bound_email( 'getonemail.com' ) );
	}

	public function testTimeBoundDomainNoMatch() {
		$this->assertFalse( DisposableEmailChecker::is_time_bound_email( 'someone@mantishub.com' ) );
		$this->assertFalse( DisposableEmailChecker::is_time_bound_email( 'mantishub.com' ) );
	}

	public function testDisposableDomain() {
		// Forwarding
		$this->assertTrue( DisposableEmailChecker::is_disposable_email( 'someone@xmaily.com' ) );
		$this->assertTrue( DisposableEmailChecker::is_disposable_email( 'xmaily.com' ) );

		// Trash
		$this->assertTrue( DisposableEmailChecker::is_disposable_email( 'someone@fakeinbox.com' ) );
		$this->assertTrue( DisposableEmailChecker::is_disposable_email( 'fakeinbox.com' ) );

		// Shredder
		$this->assertTrue( DisposableEmailChecker::is_disposable_email( 'someone@spambob.org' ) );
		$this->assertTrue( DisposableEmailChecker::is_disposable_email( 'spambob.org' ) );

		// Time Bound
		$this->assertTrue( DisposableEmailChecker::is_disposable_email( 'someone@getonemail.com' ) );
		$this->assertTrue( DisposableEmailChecker::is_disposable_email( 'getonemail.com' ) );
	}

	public function testDisposableDomainNoMatch() {
		$this->assertFalse( DisposableEmailChecker::is_disposable_email( 'someone@outlook.com' ) );
		$this->assertFalse( DisposableEmailChecker::is_disposable_email( 'outlook.com' ) );

		$this->assertFalse( DisposableEmailChecker::is_disposable_email( 'someone@mantishub.com' ) );
		$this->assertFalse( DisposableEmailChecker::is_disposable_email( 'mantishub.com' ) );
	}
}


<?php
/**
 * Tests for Forms\EmailCredit.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Tests\Unit;

use Brain\Monkey;
use PHPUnit\Framework\TestCase;
use BluePeakClimate\Core\Forms\EmailCredit;

final class EmailCreditTest extends TestCase {

	protected function setUp(): void {
		parent::setUp();
		Monkey\setUp();
	}

	protected function tearDown(): void {
		Monkey\tearDown();
		parent::tearDown();
	}

	public function test_registers_on_the_fluent_forms_footer_credit_filter(): void {
		$credit = new EmailCredit();
		$credit->register();

		$this->assertNotFalse( has_filter( 'fluentform/email_template_footer_credit', array( $credit, 'remove' ) ) );
	}

	public function test_the_credit_is_emptied(): void {
		$this->assertSame( '', ( new EmailCredit() )->remove( 'Powered by <a href="https://wordpress.org/plugins/fluentform/">FluentForm</a>.' ) );
	}
}

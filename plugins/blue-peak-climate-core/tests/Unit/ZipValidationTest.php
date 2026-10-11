<?php
/**
 * Tests for Forms\ZipValidation.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Tests\Unit;

use Brain\Monkey;
use Brain\Monkey\Functions;
use PHPUnit\Framework\TestCase;
use BluePeakClimate\Core\Forms\ZipValidation;

final class ZipValidationTest extends TestCase {

	protected function setUp(): void {
		parent::setUp();
		Monkey\setUp();
		Functions\when( '__' )->returnArg();
	}

	protected function tearDown(): void {
		Monkey\tearDown();
		parent::tearDown();
	}

	/**
	 * @return array<string, array{0: string, 1: bool}>
	 */
	public function zip_provider(): array {
		return array(
			'five digits'         => array( '78701', true ),
			'zip plus four'       => array( '78701-1234', true ),
			'spaces around'       => array( ' 78701 ', true ),
			'four digits'         => array( '7870', false ),
			'six digits'          => array( '787011', false ),
			'letters'             => array( 'ABCDE', false ),
			'canadian style'      => array( 'K1A 0B1', false ),
			'plus four, no dash'  => array( '787011234', false ),
			'dash with no suffix' => array( '78701-', false ),
			'empty'               => array( '', false ),
		);
	}

	/**
	 * @dataProvider zip_provider
	 */
	public function test_is_valid( string $zip, bool $expected ): void {
		$this->assertSame( $expected, ZipValidation::is_valid( $zip ) );
	}

	public function test_a_bad_zip_gets_a_message(): void {
		$result = ( new ZipValidation() )->validate( '', array( 'name' => 'zip' ), array( 'zip' => '7870' ) );

		$this->assertSame( 'Enter a 5-digit ZIP code, like 78701.', $result );
	}

	public function test_a_good_or_empty_zip_keeps_the_error_it_came_in_with(): void {
		$check = new ZipValidation();

		$this->assertSame( '', $check->validate( '', array( 'name' => 'zip' ), array( 'zip' => '78701' ) ) );
		$this->assertSame( '', $check->validate( '', array( 'name' => 'zip' ), array( 'zip' => '  ' ) ) );
		$this->assertSame( 'x', $check->validate( 'x', array( 'name' => 'zip' ), array( 'zip' => '78701' ) ) );
	}

	public function test_other_fields_and_non_text_values_are_left_alone(): void {
		$check = new ZipValidation();

		$this->assertSame( '', $check->validate( '', array( 'name' => 'phone' ), array( 'phone' => 'abc', 'zip' => '1' ) ) );
		$this->assertSame( '', $check->validate( '', array( 'name' => 'zip' ), array( 'zip' => array( '1' ) ) ) );
		$this->assertSame( '', $check->validate( '', array(), array() ) );
	}
}

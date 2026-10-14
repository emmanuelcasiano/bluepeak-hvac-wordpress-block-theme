<?php
/**
 * Tests for Settings\Bindings.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Tests\Unit;

use Brain\Monkey;
use Brain\Monkey\Functions;
use PHPUnit\Framework\TestCase;
use BluePeakClimate\Core\Settings\Bindings;

final class BindingsTest extends TestCase {

	/**
	 * @var Bindings
	 */
	private $bindings;

	protected function setUp(): void {
		parent::setUp();
		Monkey\setUp();
		Functions\stubTranslationFunctions();
		Functions\stubEscapeFunctions();
		Functions\when( 'esc_url_raw' )->returnArg();
		Functions\when( 'antispambot' )->returnArg();
		Functions\when( 'get_option' )->justReturn(
			array(
				'phone'       => '(214) 555-0100',
				'email'       => 'office@example.com',
				'hours'       => 'Mon & Tue',
				'demo_notice' => 'Sample site & portfolio piece',
				'facebook'    => 'https://facebook.com/bluepeak',
				'instagram'   => '',
			)
		);
		$this->bindings = new Bindings();
	}

	protected function tearDown(): void {
		Monkey\tearDown();
		parent::tearDown();
	}

	public function test_text_in_html_attributes_is_escaped(): void {
		$this->assertSame( 'Mon &amp; Tue', $this->bindings->get_value( array( 'key' => 'hours' ), null, 'content' ) );
		$this->assertSame( '(214) 555-0100', $this->bindings->get_value( array( 'key' => 'phone' ), null, 'text' ) );
	}

	public function test_demo_notice_can_be_read_by_a_block(): void {
		$this->assertSame( 'Sample site &amp; portfolio piece', $this->bindings->get_value( array( 'key' => 'demo_notice' ), null, 'content' ) );
	}

	public function test_text_in_plain_attributes_is_left_for_wordpress_to_escape(): void {
		$this->assertSame( 'Mon & Tue', $this->bindings->get_value( array( 'key' => 'hours' ), null, 'alt' ) );
	}

	public function test_url_attribute_defaults_to_a_link_target(): void {
		$this->assertSame( 'tel:2145550100', $this->bindings->get_value( array( 'key' => 'phone' ), null, 'url' ) );
		$this->assertSame( 'mailto:office@example.com', $this->bindings->get_value( array( 'key' => 'email' ), null, 'url' ) );
		$this->assertSame( 'https://facebook.com/bluepeak', $this->bindings->get_value( array( 'key' => 'facebook' ), null, 'url' ) );
	}

	public function test_link_format_builds_an_anchor(): void {
		$this->assertSame(
			'<a href="tel:2145550100">(214) 555-0100</a>',
			$this->bindings->get_value( array( 'key' => 'phone', 'format' => 'link' ), null, 'content' )
		);
		$this->assertSame(
			'<a href="mailto:office@example.com">office@example.com</a>',
			$this->bindings->get_value( array( 'key' => 'email', 'format' => 'link' ), null, 'content' )
		);
	}

	public function test_template_wraps_the_value_so_a_button_can_read_call_and_the_number(): void {
		$this->assertSame(
			'Call (214) 555-0100',
			$this->bindings->get_value( array( 'key' => 'phone', 'template' => 'Call %s' ), null, 'text' )
		);
	}

	public function test_template_is_escaped_and_the_value_is_not_escaped_twice(): void {
		$this->assertSame(
			'Open &amp; ready: Mon &amp; Tue',
			$this->bindings->get_value( array( 'key' => 'hours', 'template' => 'Open & ready: %s' ), null, 'content' )
		);
	}

	public function test_template_without_a_placeholder_is_ignored(): void {
		$this->assertSame( '(214) 555-0100', $this->bindings->get_value( array( 'key' => 'phone', 'template' => 'Call us' ), null, 'text' ) );
	}

	public function test_template_does_not_change_link_targets(): void {
		$this->assertSame( 'tel:2145550100', $this->bindings->get_value( array( 'key' => 'phone', 'template' => 'Call %s' ), null, 'url' ) );
	}

	public function test_returns_null_so_the_block_keeps_its_saved_content(): void {
		$this->assertNull( $this->bindings->get_value( array( 'key' => 'instagram' ), null, 'content' ), 'Empty value.' );
		$this->assertNull( $this->bindings->get_value( array( 'key' => 'offices' ), null, 'content' ), 'Key not offered to blocks.' );
		$this->assertNull( $this->bindings->get_value( array( 'key' => 'emergency_banner' ), null, 'content' ), 'Key not offered to blocks.' );
		$this->assertNull( $this->bindings->get_value( array(), null, 'content' ), 'No key.' );
		$this->assertNull( $this->bindings->get_value( array( 'key' => 'hours', 'format' => 'nope' ), null, 'content' ), 'Unknown format.' );
		$this->assertNull( $this->bindings->get_value( array( 'key' => 'hours', 'format' => 'link' ), null, 'content' ), 'No link form.' );
		$this->assertNull( $this->bindings->get_value( array( 'key' => 'hours' ), null, 'url' ), 'No URL form.' );
	}
}

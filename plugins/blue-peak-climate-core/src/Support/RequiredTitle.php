<?php
/**
 * Refuses to publish a post of one type without a title.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Support;

defined( 'ABSPATH' ) || exit;

/**
 * The plain edit forms (no block editor) have no title warning of their own. This is the server-side check that the
 * Service screen has too: a post with no title is saved as a draft, and the page that follows says why. The browser
 * check (assets/admin/required-title.js) covers the normal case; this covers Quick Edit and REST.
 *
 * One instance guards one post type.
 */
final class RequiredTitle {

	/**
	 * Statuses that need a title. Drafts and auto-drafts may be saved without one.
	 */
	private const PUBLIC_STATUSES = array( 'publish', 'future', 'pending', 'private' );

	/**
	 * Address flag that tells the next page to show the notice.
	 */
	private const FLAG = 'bpch_title_required';

	/**
	 * Post type key.
	 *
	 * @var string
	 */
	private $post_type;

	/**
	 * Returns the notice text. A callable so the string is translated when it is shown, not while plugins load (too early).
	 *
	 * @var callable
	 */
	private $message;

	/**
	 * Set when a save was changed to draft because the title was empty.
	 *
	 * @var bool
	 */
	private $title_missing = false;

	/**
	 * Constructor.
	 *
	 * @param string   $post_type Post type to guard.
	 * @param callable $message Returns the notice shown after a save was turned into a draft, e.g. `static fn() => __( '...' )`.
	 */
	public function __construct( string $post_type, callable $message ) {
		$this->post_type = $post_type;
		$this->message   = $message;
	}

	/**
	 * Hooks the check, the redirect flag, the notice and the browser script.
	 */
	public function register(): void {
		add_filter( 'wp_insert_post_data', array( $this, 'require_title' ) );
		add_filter( 'redirect_post_location', array( $this, 'flag_missing_title' ) );
		add_action( 'admin_notices', array( $this, 'show_notice' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_script' ) );
	}

	/**
	 * A post without a title is saved as a draft instead of being published.
	 *
	 * @param array<string, mixed> $data Post data about to be saved.
	 * @return array<string, mixed>
	 */
	public function require_title( $data ) {
		$type = $data['post_type'] ?? '';

		if ( $this->post_type !== $type ) {
			return $data;
		}

		if ( in_array( $data['post_status'] ?? '', self::PUBLIC_STATUSES, true ) && '' === trim( (string) ( $data['post_title'] ?? '' ) ) ) {
			$data['post_status'] = 'draft';
			$this->title_missing = true;
		}

		return $data;
	}

	/**
	 * Adds a flag to the address WordPress returns to after saving, when the title was missing.
	 *
	 * @param string $location Address to redirect to.
	 * @return string
	 */
	public function flag_missing_title( $location ) {
		return $this->title_missing ? add_query_arg( self::FLAG, '1', $location ) : $location;
	}

	/**
	 * Explains why the post was saved as a draft.
	 */
	public function show_notice(): void {
		// Display only: the flag changes what is printed, not what is saved.
		if ( empty( $_GET[ self::FLAG ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}

		$screen = get_current_screen();
		if ( ! $screen || $this->post_type !== $screen->post_type ) {
			return;
		}

		printf( '<div class="notice notice-error"><p>%s</p></div>', esc_html( (string) call_user_func( $this->message ) ) );
	}

	/**
	 * Loads the script that makes the title field required in the browser.
	 *
	 * @param string $hook_suffix Current admin page.
	 */
	public function enqueue_script( $hook_suffix ): void {
		if ( ! in_array( $hook_suffix, array( 'post.php', 'post-new.php' ), true ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( ! $screen || $this->post_type !== $screen->post_type ) {
			return;
		}

		wp_enqueue_script( 'bpch-required-title', BPCH_CORE_URL . 'assets/admin/required-title.js', array(), BPCH_CORE_VERSION, true );
	}
}

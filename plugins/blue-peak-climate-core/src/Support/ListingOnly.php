<?php
/**
 * Sends the single page and the archive of a "listing only" post type to the page that lists it.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Support;

defined( 'ABSPATH' ) || exit;

/**
 * Reviews and offers are shown as cards on the Reviews and Offers pages. They have no page of their own, but the
 * Query Loop block can only list post types that are queryable, which also makes /?p=ID&post_type=bpch_offer work.
 * This sends such an address to the listing page instead of showing a thin one-card page.
 */
final class ListingOnly {

	/**
	 * Post type key => path of the page that lists it, e.g. 'bpch_offer' => '/offers/'.
	 *
	 * @var array<string, string>
	 */
	private $listing_paths;

	/**
	 * Constructor.
	 *
	 * @param array<string, string> $listing_paths Post type key => path of its listing page.
	 */
	public function __construct( array $listing_paths ) {
		$this->listing_paths = $listing_paths;
	}

	/**
	 * Hooks the redirect.
	 */
	public function register(): void {
		add_action( 'template_redirect', array( $this, 'redirect' ) );
	}

	/**
	 * Which listing page should a request for this post type go to?
	 *
	 * @param string $post_type Post type being shown, or an empty string.
	 * @return string|null Path such as "/offers/", or null when the post type shows normally.
	 */
	public function listing_path( string $post_type ): ?string {
		return $this->listing_paths[ $post_type ] ?? null;
	}

	/**
	 * Redirects a single or archive request for one of the guarded post types.
	 */
	public function redirect(): void {
		if ( ! is_singular( array_keys( $this->listing_paths ) ) && ! is_post_type_archive( array_keys( $this->listing_paths ) ) ) {
			return;
		}

		$path = $this->listing_path( (string) get_query_var( 'post_type' ) );

		if ( null === $path ) {
			$path = $this->listing_path( (string) get_post_type() );
		}

		if ( null === $path ) {
			return;
		}

		wp_safe_redirect( home_url( $path ), 301 );
		exit;
	}
}

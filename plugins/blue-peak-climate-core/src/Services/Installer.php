<?php
/**
 * Creates the terms and the 22 starting services, once.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Services;

defined( 'ABSPATH' ) || exit;

/**
 * Safe to run any number of times: it only adds what is missing and never changes an existing service, so the
 * client's edits are kept. A service the client has trashed is not brought back either.
 *
 * It runs on activation, and from admin_init when the stored db version is older than DB_VERSION (for example when
 * the plugin was activated before this feature existed). Raise DB_VERSION to run it again after adding services.
 */
final class Installer {

	/**
	 * Version of the data this code installs.
	 */
	public const DB_VERSION = '1';

	/**
	 * Option that remembers the installed version.
	 */
	public const VERSION_OPTION = 'bpch_core_db_version';

	/**
	 * Hooks the upgrade check and the activation routine.
	 */
	public function register(): void {
		register_activation_hook( BPCH_CORE_FILE, array( self::class, 'activate' ) );
		add_action( 'admin_init', array( $this, 'maybe_upgrade' ) );
	}

	/**
	 * Plugin activation: register the content types first so the rewrite rules include them, then install.
	 */
	public static function activate(): void {
		( new PostTypes() )->register_all();

		( new self() )->upgrade();
	}

	/**
	 * Runs the install when the stored version is older. Admins only: this creates posts.
	 */
	public function maybe_upgrade(): void {
		if ( get_option( self::VERSION_OPTION ) === self::DB_VERSION || ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$this->upgrade();
	}

	/**
	 * Installs, refreshes the permalinks so /services/ works at once, and stores the version.
	 */
	private function upgrade(): void {
		$this->install();

		flush_rewrite_rules( false );
		update_option( self::VERSION_OPTION, self::DB_VERSION );
	}

	/**
	 * Adds the missing terms and services.
	 *
	 * @return int Number of services created.
	 */
	public function install(): int {
		$categories = $this->ensure_terms( Catalog::categories(), PostTypes::CATEGORY );
		$types      = $this->ensure_terms( Catalog::types(), PostTypes::TYPE );
		$created    = 0;

		foreach ( Catalog::services() as $index => $service ) {
			if ( get_page_by_path( $service['slug'], OBJECT, PostTypes::POST_TYPE ) ) {
				continue;
			}

			$post_id = wp_insert_post(
				array(
					'post_type'    => PostTypes::POST_TYPE,
					'post_status'  => 'publish',
					'post_title'   => $service['title'],
					'post_name'    => $service['slug'],
					'post_excerpt' => $service['excerpt'],
					'menu_order'   => $index + 1,
				)
			);

			if ( ! $post_id || is_wp_error( $post_id ) ) {
				continue;
			}

			if ( ! empty( $categories[ $service['category'] ] ) ) {
				wp_set_object_terms( $post_id, array( $categories[ $service['category'] ] ), PostTypes::CATEGORY );
			}
			if ( ! empty( $types[ $service['type'] ] ) ) {
				wp_set_object_terms( $post_id, array( $types[ $service['type'] ] ), PostTypes::TYPE );
			}

			++$created;
		}

		return $created;
	}

	/**
	 * Makes sure each term exists.
	 *
	 * @param array<string, string> $terms    Slug => name.
	 * @param string                $taxonomy Taxonomy.
	 * @return array<string, int> Slug => term ID (0 when the term could not be created).
	 */
	private function ensure_terms( array $terms, string $taxonomy ): array {
		$ids = array();

		foreach ( $terms as $slug => $name ) {
			$existing = term_exists( $slug, $taxonomy );

			if ( is_array( $existing ) ) {
				$ids[ $slug ] = (int) $existing['term_id'];
				continue;
			}

			$new          = wp_insert_term( $name, $taxonomy, array( 'slug' => $slug ) );
			$ids[ $slug ] = is_wp_error( $new ) ? 0 : (int) $new['term_id'];
		}

		return $ids;
	}
}

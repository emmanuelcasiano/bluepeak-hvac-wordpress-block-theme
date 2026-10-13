<?php
/**
 * Where a service category link goes.
 *
 * @package BluePeakClimate\Core
 */

namespace BluePeakClimate\Core\Services;

defined( 'ABSPATH' ) || exit;

/**
 * The category label on a single service ("Heating") is a link. WordPress would point it at
 * /services/category/heating/, but the post type's attachment rewrite rule (services/<slug>/<slug>) is registered
 * first and swallows that URL, so it returns a 404. The Services page already has one section per category with
 * the same slug as its anchor (/services/#heating), which is a better place to land than a thin archive, so the
 * link goes there. The same address is used by the Block Bindings source in CategoryBindings.
 */
final class TermLinks {

	/**
	 * Hooks the filter.
	 */
	public function register(): void {
		add_filter( 'term_link', array( $this, 'category_link' ), 10, 3 );
	}

	/**
	 * Points a service category at its section of the Services page. Other taxonomies are left alone.
	 *
	 * @param string   $url      Link WordPress built.
	 * @param \WP_Term $term     The term.
	 * @param string   $taxonomy Taxonomy slug.
	 * @return string
	 */
	public function category_link( $url, $term, $taxonomy ): string {
		if ( PostTypes::CATEGORY !== $taxonomy ) {
			return (string) $url;
		}

		$archive = get_post_type_archive_link( PostTypes::POST_TYPE );

		if ( ! $archive || ! isset( $term->slug ) || '' === $term->slug ) {
			return (string) $url;
		}

		return $archive . '#' . $term->slug;
	}
}

<?php
/**
 * Title: Home page
 * Slug: blue-peak-climate/page-home
 * Categories: blue-peak-climate
 * Block Types: core/post-content
 * Description: The home page from top to bottom: hero, service groups, steps, why us, a review, FAQ and a call to action.
 *
 * @package BluePeakClimate_Theme
 */

defined( 'ABSPATH' ) || exit;

// The "What's your system doing?" symptom picker goes between the hero and the service groups once it is built
// (plan item 14), and the review carousel replaces the single testimonial.
?>
<!-- wp:pattern {"slug":"blue-peak-climate/hero"} /-->

<!-- wp:pattern {"slug":"blue-peak-climate/service-groups"} /-->

<!-- wp:pattern {"slug":"blue-peak-climate/steps"} /-->

<!-- wp:pattern {"slug":"blue-peak-climate/feature-split"} /-->

<!-- wp:pattern {"slug":"blue-peak-climate/testimonial-band"} /-->

<!-- wp:pattern {"slug":"blue-peak-climate/faq"} /-->

<!-- wp:pattern {"slug":"blue-peak-climate/cta-band"} /-->

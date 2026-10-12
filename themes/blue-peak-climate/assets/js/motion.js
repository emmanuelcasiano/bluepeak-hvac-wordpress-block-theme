/**
 * Scroll animations for the Blue Peak Climate theme.
 *
 * What it does:
 * - Marks sections that start below the fold with data-bpch-motion, then adds `is-visible` when they scroll into view.
 *   motion.css turns that into a rise, a staggered rise, a drawn line or a photo reveal.
 * - Toggles `bpch-scrolled` on <html> so the sticky header can shrink (this runs for everyone, with or without motion).
 * - Moves the hero and testimonial background shapes a little while scrolling (parallax).
 *
 * What it never does: hide anything for visitors who ask for reduced motion, hide anything that is on screen when the
 * page loads, or run at all without JavaScript (the content is visible until this script marks it).
 */

/** Selector -> kind. The class names come from the theme's patterns, so pages already saved in the database animate too. */
const TARGETS = [
	[ '.bpch-section__head', 'reveal' ],
	[ '.bpch-split', 'reveal' ],
	[ '.bpch-testimonial', 'reveal' ],
	[ '.bpch-band__inner', 'reveal' ],
	[ '.bpch-faq__intro', 'reveal' ],
	[ '.bpch-cards', 'stagger' ],
	[ '.bpch-reasons', 'stagger' ],
	[ '.bpch-trust', 'stagger' ],
	[ '.bpch-faq__list', 'stagger' ],
	[ '.wp-block-post-template', 'stagger' ],
	[ '.bpch-steps', 'draw' ],
];

const MOTION_ATTRIBUTE = 'data-bpch-motion';
const OPT_OUT = '.bpch-no-motion';

/** The header shrinks past 120px and grows back under 40px. The gap stops it flickering when it changes height. */
export const SCROLLED_ON = 120;
export const SCROLLED_OFF = 40;

const MAX_STAGGER_STEP = 7;
const HERO_FACTOR = 0.35;
const BAND_FACTOR = 0.15;
const BAND_LIMIT = 80;

/**
 * @param {number}  scrollY Current scroll position.
 * @param {boolean} current Whether the header is already in its compact state.
 * @return {boolean} The compact state to use now.
 */
export function nextScrolled( scrollY, current ) {
	return current ? scrollY > SCROLLED_OFF : scrollY > SCROLLED_ON;
}

/**
 * @param {number} position Index of a child inside its group.
 * @return {number} Delay step for that child, capped so a long list does not wait seconds.
 */
export function staggerStep( position ) {
	return Math.min( position, MAX_STAGGER_STEP );
}

/**
 * @param {number} scrollY Current scroll position.
 * @param {number} height  Height of the hero.
 * @return {number} Pixels to move the hero shapes (negative is up).
 */
export function heroShift( scrollY, height ) {
	return -Math.min( Math.max( scrollY, 0 ), height ) * HERO_FACTOR;
}

/**
 * @param {number} distance Distance from the band's centre to the middle of the screen.
 * @return {number} Pixels to move the band shapes, within the limit.
 */
export function bandShift( distance ) {
	return Math.max(
		-BAND_LIMIT,
		Math.min( BAND_LIMIT, distance * BAND_FACTOR )
	);
}

/**
 * Starts the reveals and the parallax.
 *
 * @return {{frame: () => void, stop: () => void}} frame() runs on every animation frame while scrolling.
 */
function startMotion() {
	const viewport = window.innerHeight;
	const marked = [];
	const hero = document.querySelector( '.bpch-hero' );
	const band = document.querySelector( '.bpch-band--testimonial' );
	let heroHeight = hero ? hero.offsetHeight : 0;

	const reveal = ( element ) => {
		element.classList.add( 'is-visible' );
		observer.unobserve( element );
	};

	// The line sits 12% above the bottom edge, so a section starts moving just before it is fully on screen.
	const observer = new window.IntersectionObserver(
		( entries ) => {
			entries.forEach( ( entry ) => {
				if ( entry.isIntersecting ) {
					reveal( entry.target );
				}
			} );
		},
		{ rootMargin: '0px 0px -12% 0px' }
	);

	// Read every position first, then write, so the page is laid out once.
	const candidates = [];
	TARGETS.forEach( ( [ selector, kind ] ) => {
		document.querySelectorAll( selector ).forEach( ( element ) => {
			if (
				element.hasAttribute( MOTION_ATTRIBUTE ) ||
				element.closest( OPT_OUT )
			) {
				return;
			}
			candidates.push( {
				element,
				kind,
				top: element.getBoundingClientRect().top,
			} );
		} );
	} );

	candidates.forEach( ( { element, kind, top } ) => {
		// Anything already on screen (or above it, after a jump to an anchor) stays as it is: no flash, no waiting.
		if ( top < viewport ) {
			return;
		}
		if ( 'reveal' !== kind ) {
			Array.from( element.children ).forEach( ( child, position ) => {
				child.style.setProperty(
					'--i',
					String( staggerStep( position ) )
				);
			} );
		}
		element.setAttribute( MOTION_ATTRIBUTE, kind );
		observer.observe( element );
		marked.push( element );
	} );

	// A keyboard user who tabs into a section that is still hidden sees it at once, not after the scroll catches up.
	const onFocus = ( event ) => {
		const selector = `[${ MOTION_ATTRIBUTE }]:not(.is-visible)`;
		let element = event.target.closest
			? event.target.closest( selector )
			: null;
		while ( element ) {
			reveal( element );
			element = element.parentElement
				? element.parentElement.closest( selector )
				: null;
		}
	};
	document.addEventListener( 'focusin', onFocus );

	const onResize = () => {
		heroHeight = hero ? hero.offsetHeight : 0;
	};
	window.addEventListener( 'resize', onResize );

	return {
		frame() {
			// Reads before writes.
			const scrollY = window.scrollY;
			const bandBox = band ? band.getBoundingClientRect() : null;

			if ( hero && scrollY <= heroHeight ) {
				hero.style.setProperty(
					'--bpch-hero-shift',
					`${ heroShift( scrollY, heroHeight ).toFixed( 1 ) }px`
				);
			}
			if (
				bandBox &&
				bandBox.bottom > 0 &&
				bandBox.top < window.innerHeight
			) {
				const distance =
					bandBox.top + bandBox.height / 2 - window.innerHeight / 2;
				band.style.setProperty(
					'--bpch-band-shift',
					`${ bandShift( distance ).toFixed( 1 ) }px`
				);
			}
		},
		stop() {
			observer.disconnect();
			document.removeEventListener( 'focusin', onFocus );
			window.removeEventListener( 'resize', onResize );
			marked.forEach( ( element ) => {
				element.removeAttribute( MOTION_ATTRIBUTE );
				element.classList.remove( 'is-visible' );
				Array.from( element.children ).forEach( ( child ) =>
					child.style.removeProperty( '--i' )
				);
			} );
			if ( hero ) {
				hero.style.removeProperty( '--bpch-hero-shift' );
			}
			if ( band ) {
				band.style.removeProperty( '--bpch-band-shift' );
			}
		},
	};
}

function init() {
	const root = document.documentElement;
	const reduced = window.matchMedia( '(prefers-reduced-motion: reduce)' );
	let motion = null;
	let scrolled = false;
	let waiting = false;

	const update = () => {
		waiting = false;
		const next = nextScrolled( window.scrollY, scrolled );
		if ( next !== scrolled ) {
			scrolled = next;
			root.classList.toggle( 'bpch-scrolled', scrolled );
		}
		if ( motion ) {
			motion.frame();
		}
	};

	// One frame per scroll burst, whatever the number of scroll events.
	const schedule = () => {
		if ( ! waiting ) {
			waiting = true;
			window.requestAnimationFrame( update );
		}
	};

	// Follows the visitor's setting live: turning reduced motion on shows everything where it is; turning it off starts.
	const sync = () => {
		if ( reduced.matches ) {
			if ( motion ) {
				motion.stop();
				motion = null;
			}
		} else if ( ! motion && 'IntersectionObserver' in window ) {
			motion = startMotion();
			motion.frame();
		}
	};

	window.addEventListener( 'scroll', schedule, { passive: true } );
	reduced.addEventListener( 'change', sync );
	sync();
	update();
}

if ( typeof window !== 'undefined' && typeof document !== 'undefined' ) {
	init();
}

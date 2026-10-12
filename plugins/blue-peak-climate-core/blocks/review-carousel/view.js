import { store, getContext } from '@wordpress/interactivity';

// Time each review stays on screen while the carousel rotates by itself.
const AUTOPLAY_MS = 7000;

function step( by ) {
	const context = getContext();
	context.current = ( context.current + by + context.count ) % context.count;
}

// The server renders the derived state below too (see render.php), so the first paint already shows review 1.
store( 'bpch/review-carousel', {
	state: {
		get isSlideHidden() {
			const { index, current } = getContext();
			return index !== current;
		},
		get isCurrent() {
			const { index, current } = getContext();
			return index === current;
		},
		get trackTransform() {
			const { current } = getContext();
			return `translateX(-${ current * 100 }%)`;
		},
		// Screen readers hear a slide change only when the visitor causes it, never while it rotates by itself.
		get liveMode() {
			const { playing, hovering, focused } = getContext();
			return playing && ! hovering && ! focused ? 'off' : 'polite';
		},
		get playLabel() {
			const { playing, pauseLabel, playLabel } = getContext();
			return playing ? pauseLabel : playLabel;
		},
	},
	actions: {
		goTo() {
			const context = getContext();
			context.current = context.index;
		},
		previous() {
			step( -1 );
		},
		next() {
			step( 1 );
		},
		togglePlay() {
			const context = getContext();
			context.playing = ! context.playing;
		},
		hoverStart() {
			getContext().hovering = true;
		},
		hoverEnd() {
			getContext().hovering = false;
		},
		// Only keyboard focus pauses it: a mouse click on an arrow leaves focus there, and that must not stop it for good.
		focusStart( event ) {
			getContext().focused = event.target.matches( ':focus-visible' );
		},
		focusEnd( event ) {
			if ( ! event.currentTarget.contains( event.relatedTarget ) ) {
				getContext().focused = false;
			}
		},
	},
	callbacks: {
		init() {
			// Reduced motion: never start by itself. The visitor can still press play.
			if (
				window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches
			) {
				getContext().playing = false;
			}
		},
		// Runs again whenever one of these values changes, which also restarts the timer after a manual change.
		autoplay() {
			const context = getContext();
			const { playing, hovering, focused, current, count } = context;

			if ( ! playing || hovering || focused || count < 2 ) {
				return undefined;
			}

			const timer = setTimeout( () => {
				context.current = ( current + 1 ) % count;
			}, AUTOPLAY_MS );

			return () => clearTimeout( timer );
		},
	},
} );

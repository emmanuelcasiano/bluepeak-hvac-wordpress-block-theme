import { store, getContext, getElement } from '@wordpress/interactivity';
import { targetTabIndex } from './tab-keys';

// On a phone the tabs are one scrolling row: bring the chosen tab to the middle of it.
function centerTab( tab ) {
	const row = tab.parentElement;

	if ( ! row || row.scrollWidth <= row.clientWidth ) {
		return;
	}

	const reduce = window.matchMedia(
		'(prefers-reduced-motion: reduce)'
	).matches;

	row.scrollTo( {
		left: tab.offsetLeft - ( row.clientWidth - tab.offsetWidth ) / 2,
		behavior: reduce ? 'auto' : 'smooth',
	} );
}

// The server renders the derived state below too (see render.php), so the first paint already shows the first tab.
store( 'bpch/symptom-picker', {
	state: {
		get isActive() {
			const { id, active } = getContext();
			return id === active;
		},
		get tabIndex() {
			const { id, active } = getContext();
			return id === active ? 0 : -1;
		},
		get isPanelHidden() {
			const { id, active } = getContext();
			return id !== active;
		},
	},
	actions: {
		select() {
			const context = getContext();
			const { ref } = getElement();

			context.active = context.id;
			context.interacted = true;
			centerTab( ref );
		},
		// Arrow keys, Home and End move between the tabs and select the one they land on (WAI-ARIA tabs pattern).
		keydown( event ) {
			const context = getContext();
			const { order } = context;
			const target = targetTabIndex(
				event.key,
				order.indexOf( context.id ),
				order.length - 1
			);

			if ( target === null ) {
				return;
			}

			const { ref } = getElement();
			event.preventDefault();
			context.active = order[ target ];
			context.interacted = true;

			const tab =
				ref.parentElement.querySelectorAll( '[role="tab"]' )[ target ];
			tab.focus();
			centerTab( tab );
		},
	},
} );

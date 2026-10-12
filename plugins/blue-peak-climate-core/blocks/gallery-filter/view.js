import { store, getContext } from '@wordpress/interactivity';

// The server renders the same three values (see render.php), so the first paint already matches "All".
store( 'bpch/gallery-filter', {
	state: {
		get isChipActive() {
			const { category, active } = getContext();
			return category === active;
		},
		get isItemHidden() {
			const { category, active } = getContext();
			return active !== 'all' && category !== active;
		},
		get status() {
			const { active, status } = getContext();
			return status[ active ] ?? '';
		},
	},
	actions: {
		select() {
			const context = getContext();
			context.active = context.category;
		},
	},
} );

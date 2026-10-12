/**
 * Which tab a key moves to (WAI-ARIA tabs pattern): arrows step and wrap around, Home and End jump to the ends.
 *
 * @param {string} key     The KeyboardEvent key.
 * @param {number} current Index of the tab that has focus.
 * @param {number} last    Index of the last tab.
 * @return {number|null} Index of the tab to select, or null when the key does nothing.
 */
export function targetTabIndex( key, current, last ) {
	switch ( key ) {
		case 'ArrowRight':
		case 'ArrowDown':
			return current === last ? 0 : current + 1;
		case 'ArrowLeft':
		case 'ArrowUp':
			return current === 0 ? last : current - 1;
		case 'Home':
			return 0;
		case 'End':
			return last;
		default:
			return null;
	}
}

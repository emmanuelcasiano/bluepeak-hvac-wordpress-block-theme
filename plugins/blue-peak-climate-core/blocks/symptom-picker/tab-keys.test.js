import { describe, expect, it } from 'vitest';
import { targetTabIndex } from './tab-keys';

describe( 'targetTabIndex', () => {
	it( 'moves to the next tab with the right and down arrows', () => {
		expect( targetTabIndex( 'ArrowRight', 1, 4 ) ).toBe( 2 );
		expect( targetTabIndex( 'ArrowDown', 0, 4 ) ).toBe( 1 );
	} );

	it( 'moves to the previous tab with the left and up arrows', () => {
		expect( targetTabIndex( 'ArrowLeft', 3, 4 ) ).toBe( 2 );
		expect( targetTabIndex( 'ArrowUp', 4, 4 ) ).toBe( 3 );
	} );

	it( 'wraps from the last tab to the first and back', () => {
		expect( targetTabIndex( 'ArrowRight', 4, 4 ) ).toBe( 0 );
		expect( targetTabIndex( 'ArrowLeft', 0, 4 ) ).toBe( 4 );
	} );

	it( 'jumps to the ends with Home and End', () => {
		expect( targetTabIndex( 'Home', 3, 4 ) ).toBe( 0 );
		expect( targetTabIndex( 'End', 1, 4 ) ).toBe( 4 );
	} );

	it( 'ignores every other key, so Tab and typing still work', () => {
		expect( targetTabIndex( 'Tab', 2, 4 ) ).toBeNull();
		expect( targetTabIndex( 'Enter', 2, 4 ) ).toBeNull();
		expect( targetTabIndex( 'a', 2, 4 ) ).toBeNull();
	} );
} );

import { describe, expect, it, vi } from 'vitest';
import {
	handleClick,
	handleFormSuccess,
	linkLocation,
	sendEvent,
} from './analytics';

/**
 * A stand-in for an element: `closest` answers from a map of selector to result.
 *
 * @param {Object} matches Selector (exact string) to returned value.
 * @return {{closest: (selector: string) => ?Object}} Fake element.
 */
const fakeElement = ( matches ) => ( {
	closest: ( selector ) => matches[ selector ] ?? null,
} );

const AREA = 'header, footer, main';
const TEL = 'a[href^="tel:" i]';

describe( 'linkLocation', () => {
	it( 'names the header and the footer', () => {
		expect(
			linkLocation( fakeElement( { [ AREA ]: { tagName: 'HEADER' } } ) )
		).toBe( 'header' );
		expect(
			linkLocation( fakeElement( { [ AREA ]: { tagName: 'FOOTER' } } ) )
		).toBe( 'footer' );
	} );

	it( 'calls the main area and anything else content', () => {
		expect(
			linkLocation( fakeElement( { [ AREA ]: { tagName: 'MAIN' } } ) )
		).toBe( 'content' );
		expect( linkLocation( fakeElement( {} ) ) ).toBe( 'content' );
	} );
} );

describe( 'sendEvent', () => {
	it( 'calls gtag with the event name and parameters', () => {
		const gtag = vi.fn();

		expect(
			sendEvent( { gtag }, 'click_to_call', { link_location: 'header' } )
		).toBe( true );
		expect( gtag ).toHaveBeenCalledWith( 'event', 'click_to_call', {
			link_location: 'header',
		} );
	} );

	it( 'does nothing and does not throw when gtag is missing', () => {
		expect( sendEvent( {}, 'click_to_call', {} ) ).toBe( false );
		expect( sendEvent( undefined, 'click_to_call', {} ) ).toBe( false );
	} );
} );

describe( 'handleClick', () => {
	it( 'sends click_to_call for a click inside a tel link, with the place but not the number', () => {
		const gtag = vi.fn();
		const link = fakeElement( { [ AREA ]: { tagName: 'FOOTER' } } );
		const icon = fakeElement( { [ TEL ]: link } );

		expect( handleClick( { target: icon }, { gtag } ) ).toBe( true );
		expect( gtag ).toHaveBeenCalledTimes( 1 );
		expect( gtag ).toHaveBeenCalledWith( 'event', 'click_to_call', {
			link_location: 'footer',
		} );
	} );

	it( 'ignores clicks that are not on a tel link', () => {
		const gtag = vi.fn();

		expect( handleClick( { target: fakeElement( {} ) }, { gtag } ) ).toBe(
			false
		);
		expect( handleClick( { target: null }, { gtag } ) ).toBe( false );
		expect( handleClick( { target: {} }, { gtag } ) ).toBe( false );
		expect( gtag ).not.toHaveBeenCalled();
	} );
} );

describe( 'handleFormSuccess', () => {
	it( 'sends generate_lead with the form ID from the config', () => {
		const gtag = vi.fn();

		handleFormSuccess(
			{ detail: { config: { id: 3 }, form: {} } },
			{ gtag }
		);

		expect( gtag ).toHaveBeenCalledWith( 'event', 'generate_lead', {
			form_id: '3',
		} );
	} );

	it( 'falls back to the form data attribute', () => {
		const gtag = vi.fn();
		const form = {
			getAttribute: ( name ) => ( 'data-form_id' === name ? '7' : null ),
		};

		handleFormSuccess( { detail: { form } }, { gtag } );

		expect( gtag ).toHaveBeenCalledWith( 'event', 'generate_lead', {
			form_id: '7',
		} );
	} );

	it( 'still sends the event, without parameters, when the ID is unknown', () => {
		const gtag = vi.fn();

		expect( handleFormSuccess( { detail: {} }, { gtag } ) ).toBe( true );
		expect( handleFormSuccess( {}, { gtag } ) ).toBe( true );
		expect( gtag ).toHaveBeenLastCalledWith( 'event', 'generate_lead', {} );
	} );

	it( 'does nothing when gtag is missing', () => {
		expect(
			handleFormSuccess( { detail: { config: { id: 3 } } }, {} )
		).toBe( false );
	} );
} );

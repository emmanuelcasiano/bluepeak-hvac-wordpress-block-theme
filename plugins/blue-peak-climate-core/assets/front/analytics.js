/**
 * GA4 events for the two actions that matter to the business: a tap on a phone link and a sent quote form.
 *
 * Events go through the global gtag() that Site Kit prints, so Site Kit owns the measurement ID and Complianz can
 * hold events back with Consent Mode. When gtag is missing (Site Kit off or not connected) nothing happens.
 * No phone number, email, ZIP or field value is ever sent.
 *
 * Loaded as a script module. The functions are exported so Vitest can test them without a browser; the listeners
 * start only when a document exists.
 */

const TEL_LINK = 'a[href^="tel:" i]';

/**
 * Where on the page a link sits, from the template part around it.
 *
 * @param {{closest: (selector: string) => ?Object}} link The tel link.
 * @return {string} header, footer or content.
 */
export function linkLocation( link ) {
	const area = link.closest( 'header, footer, main' );

	if ( ! area ) {
		return 'content';
	}

	return 'MAIN' === area.tagName ? 'content' : area.tagName.toLowerCase();
}

/**
 * Sends one event if gtag exists. Returns whether it was sent.
 *
 * @param {Object} win    The window (or a stand-in).
 * @param {string} name   GA4 event name.
 * @param {Object} params Event parameters.
 * @return {boolean} True when gtag was called.
 */
export function sendEvent( win, name, params ) {
	if ( ! win || 'function' !== typeof win.gtag ) {
		return false;
	}

	win.gtag( 'event', name, params );
	return true;
}

/**
 * Handles a click: sends click_to_call when it landed on a tel link.
 *
 * @param {{target: ?Object}} event Click event.
 * @param {Object}            win   The window (or a stand-in).
 * @return {boolean} True when an event was sent.
 */
export function handleClick( event, win ) {
	const target = event.target;

	if ( ! target || 'function' !== typeof target.closest ) {
		return false;
	}

	const link = target.closest( TEL_LINK );

	if ( ! link ) {
		return false;
	}

	return sendEvent( win, 'click_to_call', {
		link_location: linkLocation( link ),
	} );
}

/**
 * Handles Fluent Forms' success event: sends generate_lead with the numeric form ID only.
 *
 * @param {{detail: ?Object}} event Fluent Forms `fluentform_submission_success` event.
 * @param {Object}            win   The window (or a stand-in).
 * @return {boolean} True when an event was sent.
 */
export function handleFormSuccess( event, win ) {
	const detail = event.detail || {};
	const form = detail.form;
	const id =
		( detail.config && detail.config.id ) ||
		( form &&
			'function' === typeof form.getAttribute &&
			form.getAttribute( 'data-form_id' ) );

	return sendEvent(
		win,
		'generate_lead',
		id ? { form_id: String( id ) } : {}
	);
}

if ( 'undefined' !== typeof document ) {
	document.addEventListener( 'click', ( event ) =>
		handleClick( event, window )
	);
	document.addEventListener( 'fluentform_submission_success', ( event ) =>
		handleFormSuccess( event, window )
	);
}

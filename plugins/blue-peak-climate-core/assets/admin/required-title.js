/**
 * Edit screens without the block editor (offers, reviews): makes the title required, so the browser stops a save
 * with no title. The server also refuses to publish without one, so this is a convenience, not the only check.
 */
( function () {
	const title = document.getElementById( 'title' );

	if ( ! title ) {
		return;
	}

	title.required = true;
	title.setAttribute( 'aria-required', 'true' );
} )();

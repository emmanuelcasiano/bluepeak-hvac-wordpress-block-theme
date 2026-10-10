/**
 * Site Settings page: the "Add row" button for the ZIP → office table.
 * The page still works without this script, it just shows the blank rows already printed.
 */
( function () {
	const rows = document.getElementById( 'bpch-office-rows' );
	const template = document.getElementById( 'bpch-office-template' );
	const addButton = document.getElementById( 'bpch-add-office' );

	if ( ! rows || ! template || ! addButton ) {
		return;
	}

	let nextIndex =
		parseInt( rows.dataset.nextIndex, 10 ) || rows.children.length;

	addButton.addEventListener( 'click', function () {
		rows.insertAdjacentHTML(
			'beforeend',
			template.innerHTML.replace( /__INDEX__/g, String( nextIndex ) )
		);
		nextIndex += 1;

		const firstField = rows.lastElementChild.querySelector( 'input' );
		if ( firstField ) {
			firstField.focus();
		}
	} );
} )();

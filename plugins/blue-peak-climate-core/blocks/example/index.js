import { registerBlockType } from '@wordpress/blocks';
import { RichText, useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import metadata from './block.json';

function Edit( { attributes, setAttributes } ) {
	return (
		<RichText
			{ ...useBlockProps() }
			tagName="p"
			value={ attributes.message }
			onChange={ ( message ) => setAttributes( { message } ) }
			placeholder={ __( 'Write a notice…', 'blue-peak-climate-core' ) }
		/>
	);
}

registerBlockType( metadata.name, {
	edit: Edit,
	// Dynamic block: render.php builds the front-end markup.
	save: () => null,
} );

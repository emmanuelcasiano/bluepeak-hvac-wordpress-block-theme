import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import metadata from './block.json';
import './style.css';

function Edit() {
	return (
		<div { ...useBlockProps() }>
			<p className="bpch-picker__note">
				{ __(
					'Symptom picker: five tabs with causes, what we would do, a tip and call buttons. The text is edited in the plugin code.',
					'blue-peak-climate-core'
				) }
			</p>
		</div>
	);
}

registerBlockType( metadata.name, {
	edit: Edit,
	// Dynamic block: render.php builds the tabs and panels from Symptoms\Catalog.
	save: () => null,
} );

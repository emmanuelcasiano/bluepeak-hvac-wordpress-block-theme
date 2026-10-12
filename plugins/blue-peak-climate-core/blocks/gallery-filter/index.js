import { registerBlockType } from '@wordpress/blocks';
import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import metadata from './block.json';
import './style.css';

function Edit() {
	return (
		<div { ...useBlockProps() }>
			<p className="bpch-filter__note">
				{ __(
					'Gallery filter: the category chips appear above the photos on the live page.',
					'blue-peak-climate-core'
				) }
			</p>
			<InnerBlocks />
		</div>
	);
}

registerBlockType( metadata.name, {
	edit: Edit,
	// Dynamic block: render.php adds the chips and the filter directives around the saved inner blocks.
	save: () => <InnerBlocks.Content />,
} );

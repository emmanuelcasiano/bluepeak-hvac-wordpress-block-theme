import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import metadata from './block.json';
import './style.css';

function Edit() {
	return (
		<div { ...useBlockProps() }>
			<p className="bpch-carousel__note">
				{ __(
					'Review carousel: shows your newest published reviews (Reviews in the admin menu) one at a time.',
					'blue-peak-climate-core'
				) }
			</p>
		</div>
	);
}

registerBlockType( metadata.name, {
	edit: Edit,
	// Dynamic block: render.php builds the slides from the Review posts.
	save: () => null,
} );

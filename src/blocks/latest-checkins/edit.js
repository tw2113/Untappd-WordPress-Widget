import {__} from '@wordpress/i18n';

/**
 * @see https://developer.wordpress.org/block-editor/packages/packages-block-editor/#useBlockProps
 */
import {useBlockProps, InspectorControls} from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';
import './editor.scss';

import {
	PanelBody,
	ToggleControl,
	TextControl,
	SelectControl,
} from '@wordpress/components';

export default function Edit(props) {
	const {
		attributes: {
			title,
			userName,
		},
		setAttributes,
		isSelected,
	} = props;

	function onChangeShowFeaturedImage() {
		setAttributes({featured_image: !featured_image});
	}

	return (
		<div {...useBlockProps()}>
			{isSelected ? (
				<div>
					<TextControl
						label={__('Title', 'cptui-extended')}
						help={__(
							'Title to show',
							'mb_untappd'
						)}
						value={title}
						onChange={(title) => setAttributes({title})}
					/>
					<TextControl
						label={__('User name', 'cptui-extended')}
						help={__(
							'User to show checkins for',
							'mb_untappd'
						)}
						value={userName}
						onChange={(userName) => setAttributes({userName})}
					/>
				</div>
			) : (
				<div>
					<p>{title}: {userName}</p>
				</div>
			)
			}
		</div>
	);
}

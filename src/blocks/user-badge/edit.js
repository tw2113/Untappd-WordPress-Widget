import {__} from '@wordpress/i18n';

/**
 * @see https://developer.wordpress.org/block-editor/packages/packages-block-editor/#useBlockProps
 */
import {useBlockProps} from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';
import './editor.scss';

import Title from '../../components/title';
import Username from '../../components/username';

export default function Edit(props) {
	const {
		attributes: {
			title,
			username,
			limit,
		},
		setAttributes,
		isSelected,
	} = props;

	return (
		<div {...useBlockProps()}>
			{isSelected ? (
				<div>
					<Title setAttributes={setAttributes} title={title} />
					<Username setAttributes={ setAttributes } username={ username } />
				</div>
			) : (
				<div>
					<h3>{ __( 'Untapped Latest User Badge Block', 'mb_untappd' ) }</h3>
				</div>
			)
			}
		</div>
	);
}

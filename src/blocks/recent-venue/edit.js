import {__} from '@wordpress/i18n';

/**
 * @see https://developer.wordpress.org/block-editor/packages/packages-block-editor/#useBlockProps
 */
import {useBlockProps} from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';
import './editor.scss';

import Title from '../../components/title';
import Venue from '../../components/venue';
import Limit from '../../components/limit';

export default function Edit(props) {
	const {
		attributes: {
			title,
			venue,
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
					<Venue setAttributes={ setAttributes } venue={ venue } />
					<Limit setAttributes={setAttributes} limit={limit} />
				</div>
			) : (
				<div>
					<h3>{ __( 'Untapped Recent Venue Checkins Block', 'mb_untappd' ) }</h3>
				</div>
			)
			}
		</div>
	);
}

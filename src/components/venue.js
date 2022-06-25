import {__} from '@wordpress/i18n';

import {
	TextControl,
} from '@wordpress/components';

export default function Venue(props) {
	const {
		venue,
		setAttributes,
	} = props;

	return (
		<TextControl
			label={__('Venue Numeral ID', 'cptui-extended')}
			value={venue}
			onChange={(venue) => setAttributes({venue})}
		/>
	)
}

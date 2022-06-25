import {__} from '@wordpress/i18n';

import {
	TextControl,
} from '@wordpress/components';

export default function Limit( props ) {
	const {
		limit,
		setAttributes,
	} = props;

	return (
		<TextControl
			label={__('Limit', 'cptui-extended')}
			help={__(
				'How many to show. Default 25',
				'mb_untappd'
			)}
			type="number"
			value={limit}
			onChange={(limit) => setAttributes({limit})}
		/>
	)
}

import {__} from '@wordpress/i18n';

import {
	TextControl,
} from '@wordpress/components';

export default function Brewery(props) {
	const {
		brewery,
		setAttributes,
	} = props;

	return (
		<TextControl
			label={__('Brewery Numeral ID', 'cptui-extended')}
			value={brewery}
			onChange={(brewery) => setAttributes({brewery})}
		/>
	)
}

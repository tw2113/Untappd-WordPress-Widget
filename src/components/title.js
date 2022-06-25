import {__} from '@wordpress/i18n';

import {
	TextControl,
} from '@wordpress/components';

export default function Title( props ) {
	const {
		title,
		setAttributes,
	} = props;

	return (
		<TextControl
			label={__('Title', 'cptui-extended')}
			help={__(
				'Title to show',
				'mb_untappd'
			)}
			value={title}
			onChange={(title) => setAttributes({title})}
		/>
	)
}

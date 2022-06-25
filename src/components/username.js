import {__} from '@wordpress/i18n';

import {
	TextControl,
} from '@wordpress/components';

export default function UserName( props ) {
	const {
		username,
		setAttributes,
	} = props;

	return (
		<TextControl
			label={ __( 'User name', 'cptui-extended' ) }
			help={ __(
				'User to show checkins for',
				'mb_untappd'
			) }
			value={username}
			onChange={(username) => setAttributes({username})}
		/>
	)
}

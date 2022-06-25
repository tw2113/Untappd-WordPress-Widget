import {__} from '@wordpress/i18n';

import {
	ToggleControl,
} from '@wordpress/components';

/**
 * Featured image toggle.
 * @since 1.9.0
 * @author WebDevStudios
 * @param {string} props Properties
 * @return {JSX.Element} ToggleControl
 * @constructor
 */
export default function ShowUserTotalFriends(props) {
	const {
		showtotal_friends,
		setAttributes,
	} = props;

	return (
		<ToggleControl
			label={__('Show Total Friends', 'cptuiext')}
			checked={!!showtotal_friends}
			onChange={() => setAttributes({showtotal_friends: !showtotal_friends})}
		/>
	);
}

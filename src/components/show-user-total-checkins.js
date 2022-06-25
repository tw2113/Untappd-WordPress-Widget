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
export default function ShowUserTotalCheckins(props) {
	const {
		showtotal_checkins,
		setAttributes,
	} = props;

	return (
		<ToggleControl
			label={__('Show Total Checkins', 'cptuiext')}
			checked={!!showtotal_checkins}
			onChange={() => setAttributes({showtotal_checkins: !showtotal_checkins})}
		/>
	);
}

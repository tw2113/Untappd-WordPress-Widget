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
export default function ShowUserTotalBadges(props) {
	const {
		showtotal_badges,
		setAttributes,
	} = props;

	return (
		<ToggleControl
			label={__('Show Total Badges', 'cptuiext')}
			checked={!!showtotal_badges}
			onChange={() => setAttributes({showtotal_badges: !showtotal_badges})}
		/>
	);
}

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
export default function ShowUserLocation(props) {
	const {
		showlocation,
		setAttributes,
	} = props;

	return (
		<ToggleControl
			label={__('Show User Location', 'cptuiext')}
			checked={!!showlocation}
			onChange={() => setAttributes({showlocation: !showlocation})}
		/>
	);
}

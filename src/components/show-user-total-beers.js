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
export default function ShowUserTotalBeers(props) {
	const {
		showtotal_beers,
		setAttributes,
	} = props;

	return (
		<ToggleControl
			label={__('Show Total Beers', 'cptuiext')}
			checked={!!showtotal_beers}
			onChange={() => setAttributes({showtotal_beers: !showtotal_beers})}
		/>
	);
}

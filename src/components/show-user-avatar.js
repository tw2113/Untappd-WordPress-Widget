import { __ } from '@wordpress/i18n';

import {
	ToggleControl,
} from '@wordpress/components';

/**
 * Show User Avatar toggle.
 * @since 1.4.0
 * @author Michael Beckwith
 * @param {string} props Properties
 * @return {JSX.Element} ToggleControl
 * @constructor
 */
export default function ShowUserAvatar(props) {
	const {
		showavatar,
		setAttributes,
	} = props;

	return (
		<ToggleControl
			label={ __( 'Show User Avatar', 'cptuiext' ) }
			checked={ !! showavatar }
			onChange={ () => setAttributes( { showavatar: ! showavatar } ) }
		/>
	);
}

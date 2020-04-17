import attributes from './attributes';

const { __ } = wp.i18n;
const {
	registerBlockType,
} = wp.blocks;

import LatestCheckins from '../components/latest-checkins';

export default registerBlockType('untappd-mb-gutenberg/latest-checkins', {
	title: __( 'Untappd Latest User Checkins', 'mb_untappd' ),
	category: 'widgets',
	keywords: [
		__( 'Beer', 'mb_untappd' ),
		__( 'Checkin', 'mb_untappd' )
	],
	attributes,
	edit: props => {
		const { setAttributes } = props;
		return(
			<LatestCheckins { ...{ setAttributes, ...props }} />
		);
	},
	save: props => {
		const { attributes } = props;
		return (
			<div>
				attributes
			</div>
		)
	}
});

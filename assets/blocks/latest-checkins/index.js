const { __ } = wp.i18n;
const {
	registerBlockType,
} = wp.blocks;

import LatestCheckins from '../components/latest-checkins';

export default registerBlockType('untappd-mb-gutenberg/latest-checkins', {
	title: __('Untappd Latest User Checkins', 'mb_untappd'),
	category: 'widgets',
	keywords: [
		__( 'Beer', 'mb_untappd' ),
		__( 'Checkin', 'mb_untappd' )
	],
	attributes: {
		username: {
			type: 'string',
		},
		displayTotal: {
			type: 'string'
		}
	},
	edit: LatestCheckins,
	save: props => {
		const { attributes } = props;
		return (
			{attributes.username}
		)
	}
});

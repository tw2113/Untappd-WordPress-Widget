const { __ } = wp.i18n;
const {
	registerBlockType,
} = wp.blocks;

import LatestCheckins from '../components/latest-badge';

export default registerBlockType('untappd-mb-gutenberg/latest-badge', {
	title: __('Untappd Latest User Badge', 'mb_untappd'),
	category: 'widgets',
	attributes: {
		selectedForm: {
			type: 'string',
		}
	},
	edit: LatestCheckins,
	save: () => null
});

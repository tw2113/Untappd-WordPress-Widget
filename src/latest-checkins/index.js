import attributes from './attributes';
import edit from '../components/latest-checkins-edit';
import save from '../components/latest-checkins-save';

const { __ } = wp.i18n;
const {
	registerBlockType,
} = wp.blocks;

export default registerBlockType('untappd-mb-gutenberg/latest-checkins', {
	title: __( 'Untappd Latest User Checkins', 'mb_untappd' ),
	category: 'widgets',
	keywords: [
		__( 'Beer', 'mb_untappd' ),
		__( 'Checkin', 'mb_untappd' )
	],
	attributes,
	edit,
	save,
});

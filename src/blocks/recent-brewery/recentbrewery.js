import attributes from './attributes'
import edit from './edit'
import './style.scss'
import {__} from '@wordpress/i18n'
import {registerBlockType} from '@wordpress/blocks'

export default registerBlockType( 'untappd-mb-gutenberg/recent-breweries', {
	title   : __( 'Untappd Latest Recent Brewery', 'mb_untappd' ),
	category: 'widgets',
	keywords: [
		__( 'Beer', 'mb_untappd' ),
		__( 'Checkin', 'mb_untappd' ),
		__( 'Brewery', 'mb_untappd' ),
	],
	attributes,
	edit,
	save    : () => {
		// Server side rendering via template function.
		return null;
	},
});

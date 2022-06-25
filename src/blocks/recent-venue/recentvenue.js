import attributes from './attributes'
import edit from './edit'
import './style.scss'
import {__} from '@wordpress/i18n'
import {registerBlockType} from '@wordpress/blocks'

export default registerBlockType('untappd-mb-gutenberg/recent-venues', {
	title   : __('Untappd Recent Venue Checkins', 'mb_untappd'),
	category: 'widgets',
	keywords: [
		__('Beer', 'mb_untappd'),
		__('Checkin', 'mb_untappd'),
	],
	attributes,
	edit,
	save    : () => {
		// Server side rendering via template function.
		return null;
	},
});
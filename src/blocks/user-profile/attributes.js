const attributes = {
	title: {
		type: 'string',
	},
	username: {
		type: 'string',
	},
	showavatar: {
		type: 'boolean',
		default: false,
	},
	showlocation: {
		type: 'boolean',
		default: false,
	},
	showtotal_badges: {
		type: 'boolean',
		default: false,
	},
	showtotal_checkins: {
		type: 'boolean',
		default: false,
	},
	showtotal_beers: {
		type: 'boolean',
		default: false,
	},
	showtotal_friends: {
		type: 'boolean',
		default: false,
	}
};
export default attributes;

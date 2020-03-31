const path = require( 'path' );
const pluginConfig = require('./plugin-config');
const isProduction = 'production' === process.env.NODE_ENV;
const host = isProduction ? pluginConfig.localURL : pluginConfig.watchURL;

const config = {
	mode     : isProduction ? 'production' : 'development',
	entry    : {
		'./build'    : ['./assets/blocks/latest-checkins/index.js']
	},
	output   : {
		filename  : isProduction ? '[name].min.js' : '[name].js',
		path: path.resolve( __dirname ),
	},
	module   : {
		rules: [
			{
				test   : /\.jsx?$/,
				exclude: /(node_modules)/,
				use    : {
					loader : 'babel-loader',
					options: {
						presets: [
							[
								'@babel/preset-env',
								{
									'targets': {
										'browsers': ['last 2 versions', 'ie 11']
									}
								}
							],
							'@babel/preset-react'
						]
					}
				}
			}
		]
	},
	plugins  : [],
	devtool  : isProduction ? 'source-map' : 'cheap-module-eval-source-map',
	externals: {
		$     : 'jQuery',
		jQuery: 'jQuery',
		jquery: 'jQuery',
		lodash: 'lodash'
	}
};

module.exports = config;

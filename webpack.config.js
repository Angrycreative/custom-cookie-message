const
	 MiniCssExtractPlugin       = require( 'mini-css-extract-plugin' ),
	 WebpackBuildNotifierPlugin = require( 'webpack-build-notifier' ),
	path                       = require( 'path' );

module.exports={
	entry: {
		'custom-cookie-message-popup': './src/javascript/custom-cookie-message-popup.js',
		'custom-cookie-message-backend': './src/javascript/custom-cookie-message-backend.js',
		'ccm-suggest': './src/javascript/ccm-suggest.js'
	},
	output: {
		path: __dirname + "/assets/js",
		filename: "[name].js"
	},
	module: {
		rules: [
			{
				test: /.jsx?$/,
				exclude: /node_modules/,
				use: {
					loader: 'babel-loader',
					options: {
						presets: [ '@babel/preset-env' ],
						cacheDirectory: true,
					},
				},
			},
			// compile all .scss files to plain old css
			{
				test: /\.(sass|scss|css)$/,
				// exclude: /node_modules/,
				use: [ MiniCssExtractPlugin.loader, 'css-loader', 'postcss-loader', 'sass-loader' ],
			}

		]
	},
	plugins: [
		// extract css into dedicated file
		new MiniCssExtractPlugin({
			filename: '../css/custom-cookie-message-popup.css',
		}),
		// Notify with a browser notification whenever a file has been processed
		new WebpackBuildNotifierPlugin({
			title: 'Custom cookie message Builder',
			logo: path.join( __dirname, 'build-icons/logo.png' ),
			successIcon: path.join( __dirname, 'build-icons/success.png' ),
			warningIcon: path.join( __dirname, 'build-icons/warning.png' ),
			failureIcon: path.join( __dirname, 'build-icons/failure.png' ),
			warningSound: 'Bottle',
			failureSound: 'Sosumi',
		})
	]
};

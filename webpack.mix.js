const mix = require( 'laravel-mix' );
mix.setPublicPath( './assets' );

const sassConfig = {
	processCssUrls: false,
};


// Custom webpack configuration.
mix.webpackConfig( {
	// Externals - Load React and ReactDOM so we can use react dependent npm packages.
	externals: {
		react: 'React',
		'react-dom': 'ReactDOM',
		jquery: 'jQuery',
	},
} );

// Javascript - Compile JS
mix.js( './src/javascript/custom-cookie-message-popup.js', '/js' );
mix.js( './src/javascript/custom-cookie-message-backend.js', '/js' );
mix.js( './src/javascript/ccm-suggest.js', '/js' );

// Sass - Compile CSS
mix.sass( './src/sass/style.scss', '/css/custom-cookie-message-popup.css' ).options( sassConfig );

// Always sourcemaps!
mix.sourceMaps();

// Disable notification in production.
if ( mix.inProduction() ) {
	mix.disableNotifications();
}

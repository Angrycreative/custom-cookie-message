const autoprefixer = require( 'autoprefixer' );

module.exports = {
	plugins: [
		autoprefixer({
			flexbox: 'no-2009', // Only add prefixes for final and IE versions of specification.
		}),
	],
};

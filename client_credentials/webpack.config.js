module.exports = {
	entry: './app.js',
	output: {
		path: __dirname,
		filename: 'bundle.js'
	},
	node: {
    net: "empty",
    tls: "empty",
		fs: "empty"
  }
}

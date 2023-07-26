module.exports = {
  plugins: {
    'postcss-import': {},
    'postcss-cssnext': {},
    'cssnano': {
    	discardComments: {removeAll: true}
    }
  }
}
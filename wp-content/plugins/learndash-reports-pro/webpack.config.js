var ExtractTextPlugin = require("extract-text-webpack-plugin");
const path = require('path');
var webpack = require('webpack');

const config = {
  entry: {
    'admin':'./assets/admin/js/index.js',
    'public':'./assets/public/js/index.js',
    'common':'./assets/common/js/index.js',
  },
  output: {
    path: path.resolve(__dirname, 'assets/dist/js'),
    filename: '[name].js'
  },
  module: {
        rules: [
            //for es6 and react support
            {
              test: /.jsx?$/,
              loader: 'babel-loader',
              exclude: /node_modules/,
              query: {
                presets: ['es2015']
              }
            },

            //loader for sass support
            {
              test: /\.scss$/,
              loaders: ExtractTextPlugin.extract(
                {
                  fallback:"style-loader",
                  use:[
                    {loader: 'css-loader',  options: {url: false}},
                    {loader: 'postcss-loader', options: {zindex: false}},
                    'sass-loader'
                  ]
                }
              )
            },
            { test: /\.(png|woff|woff2|eot|ttf|svg)$/, loader: 'url-loader?limit=100000' }
        ]
    },
    plugins: [
        //webpack plugin that creates a new css file in specified directory
        new ExtractTextPlugin("../css/[name].css"),
        new webpack.ProvidePlugin({
            "Tether": 'tether',
            "DataTable": 'datatables.net',
            Popper: ['popper.js', 'default']
        }),
       /* new webpack.optimize.UglifyJsPlugin({
            compress: {
                warnings: false
            },
            output: {
              comments: false
            }
        }),*/

    ],
    optimization: {
                minimize: false //Update this to true or false
    },
};

module.exports = config;

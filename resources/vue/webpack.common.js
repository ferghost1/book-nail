const HtmlWebpackPlugin = require('html-webpack-plugin');
const VueLoaderPlugin = require('vue-loader/lib/plugin');
const path = require('path');

module.exports = {
  entry: {
  	frontend: path.resolve(__dirname, 'src/frontend.js'),
    admin: path.resolve(__dirname, 'src/admin.js')
  },
  output: {
  	filename: '[name].js',
    path: path.resolve(__dirname, '../../public/js')
  },
  resolve: {
        alias: {
            'vue$': 'vue/dist/vue.esm.js',
            Components: path.resolve(__dirname, 'src/components/'),
            AdComponents: path.resolve(__dirname, 'src/ad_components/'),
            Src: path.resolve(__dirname, 'src/'),
            Api: path.resolve(__dirname, 'api/'),
 
        },
        extensions: ['*', '.js', '.vue', '.json']
    }, 
  module: {
    rules: [
      { test: /\.js$/, use: 'babel-loader' },
      { test: /\.vue$/, use: 'vue-loader' },
      { test: /\.css$/, use: ['vue-style-loader', 'css-loader']},
      {test: /\.(png|svg|jpg|gif)$/,use: ['file-loader']},
    ]
  },
  plugins: [
    // new HtmlWebpackPlugin({
    //   template: './src/index.html',
    // }),
    new VueLoaderPlugin(),
  ]
};
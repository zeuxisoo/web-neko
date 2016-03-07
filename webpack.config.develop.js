var webpack = require('webpack');
var WebpackDevServer = require('webpack-dev-server');
var ExtractTextPlugin = require("extract-text-webpack-plugin");

var config    = require('./webpack.config');

config.devtool = 'eval';

config.entry.unshift(
    'webpack-dev-server/client?http://localhost:9090',
    'webpack/hot/only-dev-server'
);

config.output.publicPath = 'http://localhost:9090/' + config.output.publicPath.replace(/^\//g, '');

config.plugins.push(
    new webpack.HotModuleReplacementPlugin(),
    new webpack.NoErrorsPlugin(),
    new ExtractTextPlugin("bundle.css", {
        disable: false
    })
);

var app = new WebpackDevServer(webpack(config), {
    publicPath: config.output.publicPath,
    historyApiFallback: true,
    debug: true,
    hot: true,
    inline: true,
    progress: true,
    stats: {
        colors: true
    },
});

app.listen(9090, '0.0.0.0', function (err, result) {
    if (err) {
        console.log(err);
    }else{
        console.log('http://localhost:9090');
    }
});


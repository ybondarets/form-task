const Encore = require('@symfony/webpack-encore');
const HtmlWebpackPlugin = require('html-webpack-plugin');
const webpack = require('webpack');
require('dotenv').config();

Encore
    .setOutputPath('public/')
    .setPublicPath('/')
    .cleanupOutputBeforeBuild()
    .addEntry('app', './src/app.js')
    .enableReactPreset()
    .enableSingleRuntimeChunk()
    .enableSassLoader()
    .addPlugin(
        new HtmlWebpackPlugin(
            {
                template: 'src/index.ejs',
                alwaysWriteToDisk: true
            }
        )
    )
    .addPlugin(new webpack.DefinePlugin({
        'ENV_API_ENDPOINT': JSON.stringify(process.env.API_ENDPOINT),
    }))
;

module.exports = Encore.getWebpackConfig();

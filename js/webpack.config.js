module.exports = {
    entry: './press.js',
 
    output: {
        path: __dirname,
        filename: 'bundle.js'
    },
 
    // devServer: {
    //     inline: true,
    //     port: 7777,
    //     contentBase: __dirname + '/public/'
    // },
 
    module: {
            loaders: [
                {
                    test: /\.js$/,
                    loader: 'babel',
                    exclude: /node_modules/,
                    query: {
                        cacheDirectory: true,
                        presets: ['es2015', 'react']
                    }
                },
                {
                    test: /\.css$/,
                    loader: 'style!css-loader?modules&importLoaders=1&localIdentName=[name]__[local]___[hash:base64:5]',
                    exclude: /node_modules/
                }
            ]
        }
};
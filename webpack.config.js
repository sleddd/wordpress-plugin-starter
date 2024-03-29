// WordPress webpack config.
const defaultConfig = require("@wordpress/scripts/config/webpack.config");
const { getWebpackEntryPoints } = require("@wordpress/scripts/utils/config");
const magicImporter = require("node-sass-magic-importer");
const IgnoreEmitPlugin = require('ignore-emit-webpack-plugin');

const isProduction = false;

// Plugins.
const RemoveEmptyScriptsPlugin = require("webpack-remove-empty-scripts");

// Utilities.
const path = require("path");

// Add any a new entry point by extending the webpack config.
module.exports = {
  ...defaultConfig,
  ...{
    entry: {
      ...getWebpackEntryPoints(), 
      "css/frontend": path.resolve(__dirname, "src/assets/scss", "frontend.scss"),
      "css/backend": path.resolve(__dirname, "src/assets/scss", "backend.scss"),
      "js/frontend": path.resolve(__dirname, "src/assets/js", "frontend.js"),
      "js/backend": path.resolve(__dirname, "src/assets/js", "backend.js")
    },
    module: {
      ...defaultConfig.module,
      rules: [
        ...defaultConfig.module.rules,

        {
            test: /\.(sc|sa)ss$/,
            use: [
                {
                    loader: require.resolve( 'sass-loader' ),
                    options: {
                        sourceMap: false,
                        sassOptions: {
                            importer: magicImporter()
                        }
                    },
                },
            ],
        },
      ],
    },
    plugins: [
      ...defaultConfig.plugins,
      new IgnoreEmitPlugin(/\.map$/),
      // Removes the empty `.js` files generated by webpack but
      // sets it after WP has generated its `*.asset.php` file.
      new RemoveEmptyScriptsPlugin({
        stage: RemoveEmptyScriptsPlugin.STAGE_AFTER_PROCESS_PLUGINS,
      }),
    ],
  }
};

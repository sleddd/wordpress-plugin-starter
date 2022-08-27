const mix           = require("laravel-mix");
const stylelint     = require("laravel-mix-stylelint");
const eslint        = require("laravel-mix-eslint");
const path          = require("path");
const magicImporter = require("node-sass-magic-importer");
const purgeCss      = require("laravel-mix-purgecss");

const basePath       = "src/assets/";
const blocksBasePath = "src/blocks/";

mix
  .stylelint({
    configFile: path.join("default.js"),
    files: [
      "src/asssets/sass/*.scss",
      "src/assets/postcss/*.pcss",
      "src/js/*.js",
      "src/blocks/*.js",
    ],
  })
  .eslint({
    fix: true,
    cache: false,
    extensions: ["js", "vue", "tsx", "jsx"],
    exclude: ["node_modules", "vendor"],
  })
  .purgeCss()
  .js(basePath + "js/backend.js", "dist/js/backend.js")
  .js(basePath + "js/frontend.js", "dist/js/frontend.js")
  .js(blocksBasePath + "*.js", "dist/js/blocks.js")
  .react()
  //.ts( blocksBasePath + '*.tsx', 'dist/js/blocks.js')
  .sass(basePath + "sass/frontend.scss", "dist/css/frontend.css", {
    sassOptions: {
      importer: magicImporter(),
    },
  })
  .sass(basePath + "sass/backend.scss", "dist/css/backend.css", {
    sassOptions: {
      importer: magicImporter(),
    },
  })
  .sass(blocksBasePath + "scss/blocks.scss", "dist/css/blocks.css", {
    sassOptions: {
      importer: magicImporter(),
    },
  })
  .sass(blocksBasePath + "scss/blocks-editor.scss", "dist/css/blocks-editor.css", {
    sassOptions: {
      importer: magicImporter(),
    },
  })
  .postCss(basePath + "postcss/backend.pcss", "dist/css/backend.css")
  .postCss(basePath + "postcss/frontend.pcss", "dist/css/frontend.css")
  .options({
    postCss: [require("postcss-custom-properties"), require("postcss-import")],
  });

const mix = require("laravel-mix");
const fs = require("fs");
const path = require("path");

const sassFilesDir = path.resolve(__dirname, "gutenberg-styles");
const outputCssDir = path.resolve(__dirname, "css-blocks");
// // Get an array of all SCSS files in the directory
const scssFiles = fs
  .readdirSync(sassFilesDir)
  .filter((file) => file.endsWith(".scss"));

// // Compile each SCSS file separately
scssFiles.forEach((file) => {
  const filePath = path.join(sassFilesDir, file);
  const outputFileName = file.replace(".scss", ".css");
  const outputFilePath = path.join(outputCssDir, outputFileName);

  mix.sass(filePath, outputFilePath);
});

mix.js("src/index.js", "dist/app.js");
mix
  .sass("sass/index.scss", "src/index.css")
  .options({
    processCssUrls: false,
    postCss: [
      require("autoprefixer")({
        overrideBrowserslist: ["last 2 versions"],
        cascade: false,
      }),
      require("cssnano")({
        preset: "default",
      }),
    ],
  })
  .minify("dist/app.js")
  .webpackConfig({
    module: {
      rules: [
        {
          test: /\.scss/,
          loader: "import-glob-loader",
        },
      ],
    },
  })
  .browserSync({
    proxy: "hochusala.local",
    open: false,
    injectChanges: true,
    files: [
      // tylko źródła, NIE dist
      "**/*.php",
      "blocks//*.php",
      "inc//*.php",
      "src//*.js",
      "src//*.css",
      "css-blocks//*.css",
      "assets//*.scss",
      "assets//*.css",
    ],

    // watchOptions: {
    //   ignored: ["/node_modules/", "/dist/", "/.git/"],
    //   aggregateTimeout: 300,
    //   poll: 500, // użyj tylko jeśli watch nie łapie zmian na Windows/WSL
    // },
    // reloadDebounce: 500, // (opcjonalnie) anti-„bounce”
    // reloadDelay: 200, // (opcjonalnie)
  });

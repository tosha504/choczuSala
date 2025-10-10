const mix = require("laravel-mix");
const fs = require("fs");
const path = require("path");

const sassFilesDir = "gutenberg-styles";
const outputCssDir = "css-blocks";

// Get an array of all SCSS files in the directory
const scssFiles = fs
  .readdirSync(sassFilesDir)
  .filter((file) => file.endsWith(".scss"));

// Compile each SCSS file separately
scssFiles.forEach((file) => {
  const filePath = path.join(sassFilesDir, file);
  const outputFileName = file.replace(".scss", ".css");
  const outputFilePath = path.join(outputCssDir, outputFileName);

  mix.sass(filePath, outputFilePath);
});

mix
  .sass("sass/index.scss", "src")
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
    proxy: "http://shop.local",
    files: [`**/*.php`, `**/*.js`, `**/*.css`],
  });


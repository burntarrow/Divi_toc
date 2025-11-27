const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = {
  entry: {
    // 🔹 Divi 5 extension entry (required so build/index.js exists)
    index: './src/index.ts',

    // 🔹 Visual Builder bundle (used by your module’s React UI)
    'divi-toc-builder': './src/builder.tsx',

    // 🔹 Front-end runtime (handles scrolling, anchors, etc.)
    'divi-toc-frontend': './src/frontend.ts',
  },
  output: {
    path: path.resolve(__dirname, 'build'),
    filename: '[name].js', // → index.js, divi-toc-builder.js, divi-toc-frontend.js
  },
  resolve: {
    extensions: ['.tsx', '.ts', '.js', '.jsx'],
  },
  module: {
    rules: [
      {
        test: /\.tsx?$/,
        use: 'ts-loader',
        exclude: /node_modules/,
      },
      {
        test: /\.scss$/,
        use: [MiniCssExtractPlugin.loader, 'css-loader', 'sass-loader'],
      },
    ],
  },
  plugins: [
    new MiniCssExtractPlugin({
      // This matches your PHP enqueue: assets/css/divi-toc.css
      filename: '../assets/css/divi-toc.css',
    }),
  ],
};

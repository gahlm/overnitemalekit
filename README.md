# Timber Starter Theme

## Installation

Optional: set up a local WP server. My preferred method is [this Docker image](https://gist.github.com/igloude/0fd62d4fc83c8d12c1bd289e27aea831)

- Install gulp CLI `npm install --global gulp-cli`
- Install and activate Timber plugin
- Install and activate ACF plugin and enter Pro license key
- Copy `.env.txt` file to a `.env` file
  - Update the `PORT` variable to match the port your localhost is serving to
- _If using modular templating:_ Once the admin is running, go to the custom fields and sync with the available groups in the `acf-json` directory.

## Development

- Start WP host server
- Run `gulp watch` in the command line from theme root

## Troubleshooting

<details>
    <summary>Gulp Error: "forget to signal async completion?"</summary>
    If you're seeing error without editing the `gulpfile`, this is easily resolved by copying the `.env.txt` file to a `.env` file. The `browserSync` task is expecting a port variable from the `.env` file and without one the process hangs and throws the error expecting a completion. 
</details>

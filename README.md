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

# Modular Templating

`template-modular.php` serves the `modular.twig` file which is associated with the `modular` flexible content field in the theme's ACF. To create a new module:

- Create a new ACF field group titled after your module e.g. `BasicText`
- Using the same module name, create a `.twig` file in the `templates/modules` directory e.g. `_basicText.twig`

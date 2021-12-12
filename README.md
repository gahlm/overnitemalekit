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

### Fresh WordPress Install Setup

1. Ensure the `Common Settings` radio on Settings -> Permalinks is set to `Post name`
2. In `Appearance -> Menus`, create the following menus and populate with appropriate links: `header_nav`, `footer_nav`, `footer_copy_nav`

## Resources

- [Timber Docs](https://timber.github.io/docs/)
- [ACF Docs](https://www.advancedcustomfields.com/resources/)

## Troubleshooting

#### Custom post types not showing up in menu editor

1. Check your arguments and make sure that `show_in_nav_menus` is set to true.
2. Go to the Appearance -> Menus page and at the very top, click on Screen Options. In the panel that opens, make sure that your custom post types are checked.

#### Gulp Error: "forget to signal async completion"

If you're seeing error without editing the `gulpfile`, this is easily resolved by copying the `.env.txt` file to a `.env` file. The `browserSync` task is expecting a port variable from the `.env` file and without one the process hangs and throws the error expecting a completion.

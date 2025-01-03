# Enable Core Button Icons

> [!NOTE]
> Forked from [ndiego/enable-button-icons](https://github.com/ndiego/enable-button-icons)

Easily add [Feather](https://github.com/feathericons/feather) icons to core Button blocks in WordPress.

This example plugin serves to demonstrate how you can extend core WordPress blocks. Feel free to tweak, modify, and make it your own.

## Fork details
- Uses Feather Icons json to generate a SCSS file with base64 encoded SVGs upon `build`.
- Generates the array of icons dynamically from Feather, formatting them for use in the block editor.
- Wraps the icon grid in a Scrollable component.
- Adds a fuzzy-search field using the tags info from Feather, cribbed from the [feathericons.com](https://github.com/feathericons/feathericons.com) repo
- Adds an "Icon only" toggle
- Icons are referenced on the front end from the Feather Icons svg sprite, like so: `<svg xmlns="http://www.w3.org/2000/svg" class="wp-block-button__link-icon"><use xlink:href="#zoom-in"></use></svg>`
- Adds a service worker to preload the Feather icon sprite on the front end, so the icons are all loaded just once.

## Installation

Download a zip with the latest release from the [releases page](https://github.com/rynokins/enable-core-button-icons/releases/tag/v0.1.0-beta), pre-built and bundled with the Feather Icons library.

Or, if you want to build it yourself:

1. Clone this repo into your WordPress plugins directory.
2. run `npm install` to install dependencies.
3. run `npm build` to build the plugin.
4. Activate the plugin in your WordPress admin.

### To-do
- [ ] Store any text entered into buttons upon "Icon only" toggle, so it can be replaced after "Icon only" is toggled off.
- [ ] Figure out how to get front end enqueues to work in Playground. _(Playground link removed for now)_
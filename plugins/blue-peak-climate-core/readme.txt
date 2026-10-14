=== Blue Peak Climate Core ===
Requires at least: 7.0
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 0.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Site-specific functionality for Blue Peak Climate.

== Description ==

Holds everything that must keep working if the theme changes: custom post types, blocks, integrations and settings.

== Development ==

* `composer install` then `composer test` (PHPUnit + Brain Monkey) and `composer lint` (WPCS).
* `npm install` then `npm run start` while developing blocks, `npm run build` before committing.
* `npm run plugin-zip` creates a zip of only the files listed in package.json "files". That list leaves out `assets/`, so deploy with Git or rsync instead.

== Changelog ==

= 0.1.0 =
* Initial scaffold.

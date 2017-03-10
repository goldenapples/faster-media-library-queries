=== Faster Media Library Queries ===
Contributors:      Nathaniel Taintor
Donate link:
Tags:
Requires at least: 4.7
Tested up to:      4.7.3
Stable tag:        0.1.0
License:           GPLv2 or later
License URI:       http://www.gnu.org/licenses/gpl-2.0.html

By default, the Media Library search functionality performs a meta query. This plugin removes that join, to prevent database overload on sites with large meta tables.

== Description ==

A simple drop-in fix that can fix sites which notice slowness in the Insert Media frame, especially when the database is under heavy load. Works best as an mu-plugin - there is no interface exposed.

== Installation ==

= Manual Installation =

1. Upload the entire `/` directory to the `/wp-content/plugins/` directory.
2. Activate Faster Media Library Queries through the 'Plugins' menu in WordPress.

== Frequently Asked Questions ==


== Screenshots ==


== Changelog ==

= 0.1.0 =
* First release

== Upgrade Notice ==

= 0.1.0 =
First Release

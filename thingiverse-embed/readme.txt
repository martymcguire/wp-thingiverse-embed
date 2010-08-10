=== Thingiverse Shortcode Plugin ===
Contributors: schmarty
Requires at least: 3.0
Tested up to: 3.0.1
Stable tag: trunk
Tags: thingiverse, shortcode, embed, object, stream, rss

Allows the use of a [thingiverse] shortcode to embed <a href="http://www.thingiverse.com">Thingiverse</a> Things in a post or page.  Also includes a Thingiverse Stream widget for embedding Thingiverse streams in sidebars.

NOTE: I am not affiliated with Thingiverse, but I do want to embed the Things that I make there into my blog from time-to-time. :)

== Description ==

Instead of copy-pasting info about a [Thingiverse](http://thingiverse.com/) object into your posts, embed them with a simple [thingiverse] shortcode.

Want to display your most recently uploaded, created, or liked objects? You can do that, too, with the Thingiverse Stream widget!

= Thing Embedding =

For example, to embed [thing:1842](http://www.thingiverse.com/thing:1842) enter this in a post or page:

  [thingiverse thing=1842]

It is that simple.

= Stream Embedding =

The Thingiverse Stream widget allows you to embed Thingiverse streams into your sidebars.  To use it, simply drag-and-drop the Thingiverse Stream widget to a sidebar and configure it.

There are two types of streams: *Global* and *User*.  *User* streams require you to specify a Thingiverse username.

*User Streams*

- `designed` - content from http://www.thingiverse.com/<User>/things
- `like` - content from http://www.thingiverse.com/<User>/favorites
- `made` - content from http://www.thingiverse.com/<User>/made

*Global Streams*

- `featured` content from http://www.thingiverse.com/featured
- `newest` content from http://www.thingiverse.com/newest
- `popular` content from http://www.thingiverse.com/popular
- `derivatives` content from http://www.thingiverse.com/derivatives
- `made-things` content from http://www.thingiverse.com/made-things

= Custom Formatting =

Once installed, you can customize the look of your Things on the following files:

- `styles.css` - CSS for both Streams and individual Things.
- `thingiverse-stream-widget.php` - The `widget` method renders the stream.
- `templates/thing.php` - Template for [thingiverse] shortcode embeds.

== CHANGELOG ==

View the [CHANGELOG](http://svn.wp-plugins.org/thingiverse-embed/trunk/CHANGELOG) to see what has changed between versions.

== Installation ==

1) Download the plugin zip file.

2) Unzip.

3) Upload the `thingiverse-embed` directory to your wordpress plugin directory (`/wp-content/plugins`).

4) Activate the plugin.

5) Use the [thingiverse] shortcode in your posts or pages, or add Thingiverse Stream widgets to your sidebars!

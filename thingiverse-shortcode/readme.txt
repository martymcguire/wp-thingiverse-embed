=== Thingiverse Shortcode Plugin ===
Contributors: schmarty
Requires at least: 3.0
Tested up to: 3.0.1
Stable tag: trunk
Tags: thingiverse, shortcode, embed, object, stream, rss

Allows the use of simple shortcodes to embed Thingiverse objects or streams in a post or page.

== Description ==

Instead of copy-pasting info about a [Thingiverse](http://thingiverse.com/) object into your posts, embed them with a simple [thingiverse] shortcode.

Want to display your most recently uploaded, created, or liked objects? You can do that, too!

= Object Embedding =

For example, to embed [thing:1842](http://www.thingiverse.com/thing:1842) enter this in a post or page:

  [thingiverse thing=1842]

It is that simple.

= Stream Embedding =

TODO

  [thingiverse stream=made user=schmarty max=3]

Stream types:  
  - designed (user)(thingiverse.com/Starno/things, thingiverse.com/rss/user:872)
  - like (user)(/Starno/favorites, thingiverse.com/rss/user:872/likes)
  - made (user)(/Starno/made, thingiverse.com/rss/user:872/made)
  - featured (/featured, thingiverse.com/rss/featured)
  - newest (/newest, thingiverse.com/rss/newest)
  - popular (/popular, thingiverse.com/rss/popular)
  - derivatives (/derivatives, thingiverse.com/rss/derivatives)
  - made-things (/made-things, thingiverse.com/rss/instances)

= Custom Formatting =

TODO.

CSS info.

Include your own HTML inside the [thingiverse]...[/thingiverse] tags.
 - What special vars?

== CHANGELOG ==

FIXME: View the [CHANGELOG](http://svn.wp-plugins.org/FIXME/trunk/CHANGELOG) to see what has changed between versions.

== Installation ==

1) Download the plugin zip file.

2) Unzip.

3) Upload the FIXME directory to your wordpress plugin directory (/wp-content/plugins).

4) Activate the plugin.

5) Use the [thingiverse] shortcode in your posts or pages

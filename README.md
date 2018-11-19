# Site-Mirror
Response to a DevRant question [Link](https://devrant.com/rants/1883436/pardon-my-ignorance-but-is-what-im-trying-to-do-even-possible-i-have-a-wordpress)

Basically, the gist is that someone wants to add a new address to their multisite network and continue using both it and the old address without redirecting.  Having spent a lot of time in WP Multisite, I decided to take it on as a challenge and see what I came up with.  There are a couple of known bugs; feel free to contribute with your own ideas on how to fix them!

## Manual Configuration Required
Right now you have to set the `$primary_site_id` and any `$secondary_site_ids` by editing the plugin file itself.  A future update will probably add this to the Network Settings menu.

## Known Bugs
* Switching the blog works on its own, however the filter on `alloptions` seems to be creating an endless loop, even though it's not re-added until after the qwp_load_alloptions()` call is run on the current site.

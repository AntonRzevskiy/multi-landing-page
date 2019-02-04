# MLP
This is a plugin that implements the basic functionality of processing GET requests under the control of cms WordPress.

### What are tracks?
The track is special parameters in the address of the page, which are usually transmitted from your advertisements ([learn more](https://support.google.com/analytics/answer/1033863)). To process these parameters on your site, you can use this plugin. By defaults two types of tracks are available for use in WP: _taxonomy_ and _metafield_.

## Quick start
Add this code to your theme (_mostly in **functions.php**_).
```php
add_filter( 'mlp_register_url_tracks', 'my_new_tracks' );
function my_new_tracks( $tracks ) {
	$tracks[] = array(
		'type'      => 'taxonomy',
		'track_id'  => 'utm_source',
		'post_type' => array( 'post' ),
	);
	$tracks[] = array(
		'type'      => 'meta',
		'track_id'  => 'utm_term',
		'post_type' => array( 'post' ),
	);
	return $tracks;
}
```
And now, in any template of your theme.
```php
$query = mlp_query( 'post_type=post' );

while ( $query->have_posts() ) {
	$query->the_post();

	the_title(); // print post title
}
```

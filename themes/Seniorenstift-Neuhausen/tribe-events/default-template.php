<?php
/**
 * Default Events Template
 * This file is the basic wrapper template for all the views if 'Default Events Template'
 * is selected in Events -> Settings -> Display -> Events Template.
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/default-template.php
 *
 * @package TribeEventsCalendar
 * @version 4.6.23
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

get_header();
?>

<main class="main tribe-events-pg-template" id="main tribe-events-pg-template" content="true">
	<?php
	tribe_events_before_html();

	tribe_get_view();

	the_title( '<h2>', '</h2>' );
	echo get_second_lang();
	the_flexible_content( 'flexible' );
		the_content();
	tribe_events_after_html();
	?>
</main>
</div>
<?php
get_footer();

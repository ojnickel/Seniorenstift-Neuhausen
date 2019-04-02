<?php
/**
 * Einzelansicht
 *
 * @package CAB
 **/

get_header();
?>

<main class="main" id="main" content="true">
	<?php
	the_flexible_content( 'flexible' );
		the_content();
		?>
</main>
<?php
get_footer();

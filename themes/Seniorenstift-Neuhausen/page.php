<?php
/**
 * Seiten
 *
 * @package CAB
 **/

get_header();
?>

<main class="main" id="main" content="true">
	<?php
	the_title( '<h2>', '</h2>' );
	echo get_second_lang();
	the_flexible_content( 'flexible' );
		the_content();
	?>
</main>
</div>
<?php
get_footer();

<?php
/**
 * Seiten
 *
 * @package CAB
 **/

get_header();
the_breadcrumb();
?>

<main class="main content" id="main" content="true">
<?php
the_title( '<h1>', '</h1>' );
the_content();
?>
</main>
<?php
get_footer();

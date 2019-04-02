<!DOCTYPE html>
<html id="totop" <?php language_attributes(); ?> class="no-js" data-path="<?php echo esc_url( get_template_directory_uri() ); ?>">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<?php wp_head(); ?>
	</head>

	<body class="<?php the_body_class_index( 'main-menu' ); ?>">
		<div class="outer">
			<header class="header">

				<nav class="main-nav">
					<button class="menu-button toggle active" data-for="main-menu" title="Menü ein-/ausblenden" aria-haspopup="true" aria-expanded="false">
	          <svg role="img" class="symbol" aria-hidden="true" focusable="false">
	            <use xlink:href="https://www.beans-books.de/wp-content/themes/beansbooks/img/icons.svg#menu"></use>
	          </svg>
	          <span class="menu-button-label">Menü</span>
	        </button>
					<ul id="main-menu">
								<?php
										wp_nav_menu(
											array(
												'theme_location' => 'main-menu',
												'walker' => new Main_Nav_Walker(),
												'container' => '',
												'items_wrap' => '%3$s',
												'depth'  => 2,
											)
										);
								?>
				  </ul>
				</nav>
				<nav class="social-nav">
				<ul id="social-menu">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'social-menu',
						'walker'         => new Social_Nav_Walker(),
						'container'      => '',
						'items_wrap'     => '%3$s',
						'depth'          => 1,
					)
				);
				?>
				</ul>
			  </nav>
				<div class="logos">
	        <img class="beans" src="https://www.beans-books.de/wp-content/themes/beansbooks/img/beans-a-books-logo.svg" alt="">
	        <img class="pfennigparade" src="https://www.beans-books.de/wp-content/themes/beansbooks/img/pp-logo.svg" alt="">
	      </div>
			</header>

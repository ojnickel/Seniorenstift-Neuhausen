
<footer>

	  <nav class="footer-nav">/
		<ul id="footer-menu">
		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'footer-menu',
				'walker'         => new Footer_Nav_Walker(),
				'container'      => '',
				'items_wrap'     => '%3$s',
				'depth'          => 1,
			)
		);
		?>
</ul>
		</ul>
	  </nav>


  </div>
</footer>
</div>
</body>
</html>

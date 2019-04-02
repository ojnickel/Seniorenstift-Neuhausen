<?php
/**
 * Gibt eine aufsteigende Zahl zurück
 */
function counter() {
	global $counter;
	++$counter;
	return $counter;
}

/**
 * Gibt die Inhalte einer ACF Galerie zurück
 *
 * @param string $flexible   ACF Feld, das verwendet werden soll.
 */
function get_acf_gallery( $flexible ) {
	$gallery = '';
	if ( $flexible['gallery'] ) {
		$get_tag = new HtmlMaker();
		$images  = $flexible['gallery'];
		foreach ( $images as $image ) {
			$gallery_item = get_picture_tag( $image['ID'], array( 0 => 'gallery' ), $image['alt'], null, 'g_image' );
			$gallery     .= $get_tag->li( $gallery_item );
		}
		$gallery = $get_tag->ul( $gallery, 'gallery' );
	}
	return $gallery;
}

/**
 * Gibt die Downloads zurück
 *
 * @param string $flexible   ACF Feld, das verwendet werden soll.
 */
function get_acf_downloads( $flexible ) {
	$content   = '<section class="download-boxen">';
	$downloads = $flexible['downloads'];
	if ( $downloads ) {
		foreach ( $downloads as $download_item ) {
			$file    = $download_item['file'];
			$title   = $download_item['title'];
			$subtype = $file['subtype'];

			if ( strpos( $subtype, 'word' ) ) {
				$filetype = 'word';
			} elseif ( strpos( $subtype, 'excel' ) || strpos( $subtype, 'spreadsheet' ) ) {
				$filetype = 'excel';
			} elseif ( strpos( $subtype, 'presentation' ) || strpos( $subtype, 'powerpoint' ) ) {
				$filetype = 'powerpoint';
			} elseif ( 'pdf' === $subtype ) {
				$filetype = 'pdf';
			} elseif ( 'zip' === $subtype ) {
				$filetype = 'zip';
			} elseif ( 'image' === substr( $file['mime_type'], 0, 5 ) ) {
				$filetype = 'image';
			} else {
				$filetype = 'document';
			}

			$content .= '<article class="download-box">
<a class="download-link" href="' . $file['url'] . '">
<div class="download-icon file-' . $filetype . '"></div>
<div class="download-text"><span>' . $title . '</span></div></a></article>';
		}
	}
	$content .= '</section>';
	return $content;
}
// **
// * Lädt eine als HTML Datei gewählt in die Ausgabe
// *
// * @param string $flexible ACF Objekt des Feldes.
// */
// function get_acf_html( $flexible ) {
// if ( isset( $flexible['file'] ) && $flexible['file'] ) {
// $src_path = get_attached_file( $flexible['file'] );
// if ( file_exists( $src_path ) ) {
// $file_contents = file_get_contents( $src_path );
// if ( $file_contents !== strip_tags( $file_contents ) ) {
// return str_ireplace( '%%%path%%%', get_template_directory_uri(), $file_contents );
// }
// }
// }
// return false;
// }
// /**
// * Gibt den Banner zurück
// *
// * @param string $flexible ACF Objekt des Feldes.
// */
// function get_acf_banner( $flexible ) {
// if ( isset( $flexible['headline'] ) ) {
// $content = '<header class="header" id="header"><h1>' . $flexible['headline'] . '</h1><span class="sub-title">' . $flexible['subline'] . '</span><img alt="" src="' . get_template_directory_uri() . '/img/aves-banner.svg"></header>';
// return $content;
// }
// return false;
// }
/**
 * Gibt die Kacheln zurück
 *
 * @param string $flexible ACF Objekt des Feldes.
 */
function get_acf_tiles( $flexible ) {
	if ( isset( $flexible['tiles'] ) ) {
		$content = '<div class="tiles">';
		foreach ( $flexible['tiles'] as $tile ) {
			$img   = '<img src=' . get_template_directory_uri() . '/img/icon-' . $tile['logo'] . '.svg alt="">';
			$class = $tile['color'] ? ' ' . $tile['color'] : null;
			if ( $tile['subtitle'] ) {
				$subtitle = $tile['subtitle'];
			} else {
				$subtitle = '';
			}
			$content .= '<article class="tile-item"><a class="tile-link" href="' . $tile['link'] . '"><div class="tile-icon' . $class . '">' . $img . '</div><h2>' . $tile['title'] . '</h2><p>' . $subtitle . '</p></a></article>';
		}
		$content .= '</div>';
		return $content;
	}
	return false;
}
/**
 * Gibt das Text Überschrift
 *
 * @param string $flexible ACF Objekt des Feldes.
 */
// function get_acf_headline( $flexible ) {
// $headline = '';
// if ( isset( $flexible['headline'] ) ) {
// $headline = $flexible['headline'];
// $content  = '<header class="header article-header" ><h2>' . $headline . ' </h2></header> ';
// return $content;
// }
//
// return false;
// }
/**
 * Gibt das Text mit Bild Feld zurück
 *
 * @param string $flexible ACF Objekt des Feldes.
 */
function get_acf_text_and_image( $flexible ) {
	$text     = '';
	$img      = '';
	$headline = '';
	$img_dir  = $flexible['img_dir'];
	if ( $flexible['headline'] ) {
		$headline = '<header><h2>' . $flexible['headline'] . '</header></h2>';
	}
	switch ( $img_dir ) {
		case 'none':
			$text_dir = 'none';
			break;

		case 'left':
			$text_dir = 'right';
			break;

		case 'right':
			$text_dir = 'left';
			break;
	}
	if ( $flexible['text'] ) {
		$text = $flexible['text'];
		$text = '<div class="align-' . $text_dir . '">' . $headline . $text . '</div> ';

		if ( $flexible['img'] ) {
			$img = get_picture_tag( $flexible['img']['ID'], array( 0 => 'tiles' ), $flexible['img']['alt'] );
			$img = '<div class="align-' . $img_dir . '">' . $img . '</div>';
		}
		$content = '<div class="section text-and-image">' . $text . $img . '</div>';
		return $content;
	}
	return false;
}
/**
 * Gibt das Video zurück
 *
 * @param string $flexible ACF Objekt des Feldes.
 */
function get_acf_video( $flexible ) {

	if ( isset( $flexible['caption'] ) ) {

		global $acf_js;
		$counter       = counter();
		$video_caption = $flexible['caption'];

		if ( 'online' === $flexible['type'] ) {

			$iframe = $flexible['url'];
			preg_match( '/src="(.+?)"/', $iframe, $matches );
			$src = $matches[1];
			if ( strpos( $src, '?feature=oembed' ) ) {
				$src = str_replace( '?feature=oembed', '', $src ) . '?autoplay=1';
			}

			$video_code = '<iframe id="player' . $counter . '" src="" frameborder="0" style="display: none; z-index:2"></iframe>';

			$acf_js .= '$(document).ready(function() {
				$(".responsive-video #rv-switch' . $counter . ' > a").click(function(e) {
					e.preventDefault();
					$("#player' . $counter . '").attr("src", $(this).attr("href")).show();
				});
			});';
		} else {
			$video_file = $flexible['file'];
			$src        = '#';
			$video_code = '<video id="player' . $counter . '" class="video-js vjs-fluid" controls preload="auto" data-setup="{}" style="display: none; z-index:2">
				  <source src="' . $video_file['url'] . '" type="video/mp4">
						<p class="vjs-no-js">Ihr Browser kann dieses Video nicht wiedergeben.<br/>
				    <a href="' . $video_file['url'] . '">Sie können diesen Film hier herunterladen.</a>
						</p>
				</video>';

			$acf_js .= '$(document).ready(function() {
				$(".responsive-video #rv-switch' . $counter . ' > a").click(function(e) {
				e.preventDefault();
				$("#player' . $counter . '").show();
				$("#player' . $counter . '_html5_api").removeAttr("style");
				videojs("player' . $counter . '").play();
				});
				});';

			wp_enqueue_script( 'videojs', get_template_directory_uri() . '/js/video.min.js', array(), false, true );
			wp_enqueue_script( 'videojsde', get_template_directory_uri() . '/js/lang/de.js', array( 'videojs' ), false, true );
			wp_enqueue_script( 'videojsls', get_template_directory_uri() . '/js/lang/ls.js', array( 'videojs' ), false, true );
			wp_enqueue_style( 'videojs', get_template_directory_uri() . '/css/video-js.min.css', array(), false );
		}

		if ( $flexible['poster'] ) {
			$video_code .= '<div class="rv-switch" id="rv-switch' . $counter . '"><a href="' . $src . '" title="Hier klicken, um Video abzuspielen">' . get_picture_tag( $flexible['poster']['ID'], array( '0' => 'video' ) ) . '<div class="headline-overlay"><h2>' . $video_caption . '</h2><svg role="img" class="symbol" aria-hidden="true" focusable="false"><use xlink:href="' . get_template_directory_uri() . '/img/icons.svg#circle"></use></svg></div></a></div>';
		} else {
			$video_code .= '<div class="rv-switch" id="rv-switch' . $counter . '" style="background-color:#22BDBF;"><a href="' . $src . '" title="Hier klicken, um Video abzuspielen"><div class="headline-overlay"><h2>' . $video_caption . '</h2><svg role="img" class="symbol" aria-hidden="true" focusable="false"><use xlink:href="' . get_template_directory_uri() . '/img/icons.svg#circle"></use></svg></div></a></div>';
		}

		$content = '<div class="responsive-video">' . $video_code . '</div>';

		return $content;

	}
	return false;
}
/**
 * Gibt den Editor zurück
 *
 * @param string $flexible ACF Objekt des Feldes.
 */
function get_acf_editor( $flexible ) {

	if ( isset( $flexible['editor'] ) ) {
		$content = $flexible['editor'];

		return $content;
	}
	return false;
}
/**
 * Gibt die Inhalte eines Abschnitts der flexiblen ACF Felds zurück
 *
 * @param string $flexible ACF Objekt des Abschnitts.
 */
function get_flexible_row( $flexible ) {
	$get_tag = new HtmlMaker();
	$content = false;
	foreach ( $flexible as $row ) {
		if ( isset( $row['acf_fc_layout'] ) && function_exists( 'get_acf_' . $row['acf_fc_layout'] ) ) {
			$content .= call_user_func( 'get_acf_' . $row['acf_fc_layout'], $row );
		}
	}
	return $content;
}
/**
 * Gibt die Inhalte eines flexiblen ACF Felds zurück
 *
 * @param string $field ACF Feld, das verwendet werden soll.
 */
function get_flexible_content( $field ) {
	$flexible = get_field_object( $field );
	$content  = false;
	if ( is_array( $flexible ) && isset( $flexible['value'] ) && is_array( $flexible['value'] ) ) {
		$content = get_flexible_row( $flexible['value'] );
		return $content;
	}
	return false;
}
	/**
	 * Gibt die Inhalte eines flexiblen ACF Felds aus
	 *
	 * @param string $field ACF Feld, das verwendet werden soll.
	 */
function the_flexible_content( $field ) {
	echo get_flexible_content( $field );
}
function hook_acf_css() {
	global $acf_css;
	echo '<style>' . $acf_css . '</style>';
	unset( $GLOBALS['acf_css'] );
}
function hook_acf_js() {
	global $acf_js;
	echo '<script>' . $acf_js . '</script>';
	unset( $GLOBALS['acf_js'] );
}
add_action( 'wp_footer', 'hook_acf_js', 100 );

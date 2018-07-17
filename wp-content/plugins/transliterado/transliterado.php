<?php
/*
Plugin Name: Transliterado
Description: Better transliteration of non-ASCII characters in slugs. Currently covers Esperanto, Swedish, Finnish, Danish, Norwegian, German, Russian and Bulgarian, but it's easy to add new languages (contact "bertilow" at "gmail.com"). Significant parts of the code comes from the plugins Rustolat by Anton Skorobogatov, BGtoLat by Ognyan Mladenov, and Slugger by Hans Christian Saustrup. / Pli bona transliterado de ne-Askiaj literoj en URL-nomoj. Nun prizorgataj estas Esperanto, la Sveda, la Finna, la Dana, la Norvega, la Germana, la Rusa kaj la Bulgara, sed estas facile aldoni pliajn lingvojn (skribu al "bertilow" ĉe "gmail.com"). Gravaj partoj de la kodo venas el la kromprogramoj Rustolat de Anton Skorobogatov, BGtoLat de Ognyan Mladenov, kaj Slugger de Hans Christian Saustrup.
Author: Bertilo Wennergren <bertilow@gmail.com>
Author URI: http://bertilow.com
Version: 0.8
*/

load_plugin_textdomain('transliterado', 'wp-content/plugins/transliterado');

$transliterado_lingvoj = populate_transliterado_lingvoj();

$transliterado_sistemoj = array();

function populate_transliterado_lingvoj() {
	$transliterado_lingvoj = array(
		'specialaj' => array(
			'NOMO' => __("Specialaj signoj", "transliterado"),
			'specialaj_surogatoj' => __("Simpligo de haltostrekoj kaj aliaj specialaj signoj", "transliterado")
		),
		'eo' => array(
			'NOMO' => 'Esperanto',
			'eo_h' => 'H-sistemo',
			'eo_x' => 'X-sistemo'
		),
		'sv' => array(
			'NOMO' => 'Svenska, Suomi',
			'sv_aaaeoe' => 'å, ä, ö &rarr; aa, ae, oe'
		),
		'da' => array(
			'NOMO' => 'Dansk, Norsk',
			'da_aeoeaa' => 'æ, ø, å &rarr; ae, oe, aa'
		),
		'de' => array(
			'NOMO' => 'Deutsch',
			'de_aeoeuess' => 'ä, ö, ü, ß &rarr; ae, oe, ue, ss'
		),
		'ru' => array(
			'NOMO' => 'Русский',
			'ru_gost' => 'ГОСТ 16876-71', 
			'ru_iso' => 'ISO 9-95' 
		),
		'bg' => array(
			'NOMO' => 'Български',
			'bg_gost' => 'ГОСТ (руска)', 
			'bg_iso' => 'ISO' 
		)
	);
	return $transliterado_lingvoj;
}

function sanitize_title_transliterado($titolo) {
	global $transliterado_lingvoj, $transliterado_sistemoj;
	if (!$transliterado_lingvoj) {
		$transliterado_lingvoj = populate_transliterado_lingvoj();
	}
	if (!mb_check_encoding($titolo, 'ASCII')) { 
		foreach (array_keys($transliterado_lingvoj) as $lingvo) {
			$elekto = get_option('transliterado_' . $lingvo);
			if ($elekto != '') {
				Include_once('transliterado_' . $lingvo . '.php');
			}
		}
		foreach ($transliterado_sistemoj as $sistemo) {
			$titolo = strtr($titolo, $sistemo);
		}
	}
	return $titolo;
}

function transliterado_options_page() {
	global $wpdb, $transliterado_lingvoj, $transliterado_sistemoj;
	echo '<div class="wrap">' . "\n";
	echo '<h2>Transliterado</h2>' . "\n";
	echo '<p>';
	echo __("Ĉi tie vi povas elekti, kiuj transliteradaj sistemoj estu uzataj en la URL-nomoj de novaj afiŝoj kaj paĝoj. Ne elektu pli da sistemoj ol vi vere bezonas. Atentu, ke iuj sistemoj koncernas la samajn signojn, kaj do povas interkolizii.", "transliterado");
	echo '</p>' . "\n";
	$ghisdatigo = FALSE;
	foreach (array_keys($transliterado_lingvoj) as $lingvo) {
		if ($_POST['transliterado_' . $lingvo]) {
			update_option('transliterado_' . $lingvo, $_POST['transliterado_' . $lingvo]);
			$ghisdatigo = TRUE;
		}
	}
	if ($ghisdatigo) {
		echo '<div class="updated">';
		echo '<ul>';
		echo '<li>' . __("La elektoj estas konservitaj.", "transliterado") . '</li>' . "\n";
		if ($_POST['shanghiafishojnpaghojn'] == 'shanghiafishojnpaghojn') {
			if ($posts = $wpdb->get_results("SELECT id,post_name,post_title FROM `$wpdb->posts` WHERE post_name != ''")) {
				foreach($posts as $post) {
					$novaurlnomo = sanitize_title($post->post_title);
					if ($novaurlnomo != $post->post_name) {
						// Konservi la novan url-nomon
						$wpdb->query( "UPDATE `$wpdb->posts` SET post_name='$novaurlnomo' WHERE `id`=$post->id" );

						$old_slugs = (array) get_post_meta($post->id, '_wp_old_slug');

						// if we haven't added this old slug before, add it now
						if ( !count($old_slugs) || !in_array($post->post_name, $old_slugs) )
							add_post_meta($post->id, '_wp_old_slug', $post->post_name);

						// if the new slug was used previously, delete it from the list
						if ( in_array($post->post_name, $old_slugs) )
							delete_post_meta($post->id, '_wp_old_slug', $post->post_name);

					}
				}
			}
			echo '<li>' .  __("La malnovaj URL-nomoj de afiŝoj kaj paĝoj estas ŝanĝitaj laŭ tiuj elektoj.", "transliterado") . '</li>' . "\n";
		}
		if ($_POST['shanghietikedojnkategoriojn'] == 'shanghietikedojnkategoriojn') {
			if ($etikedojkategorioj = $wpdb->get_results("SELECT term_id,name,slug FROM `$wpdb->terms`")) {
				foreach($etikedojkategorioj as $etkat) {
					$novaetkaturlnomo = sanitize_title($etkat->name);
					if ($novaetkaturlnomo != $etkat->slug) {
						// Konservi la novan url-nomon
						$wpdb->query( "UPDATE `$wpdb->terms` SET slug='$novaetkaturlnomo' WHERE `term_id`=$etkat->term_id" );
					}
				}
			}
			echo '<li>' . __("La malnovaj URL-nomoj de etikedoj kaj kategorioj (ankaŭ ligilo-kategorioj) estas ŝanĝitaj laŭ tiuj elektoj.", "transliterado") . '</li>' . "\n";
		}
		echo '</ul>' . "\n";
		echo '</div>' . "\n";
	}

	echo '<form method="post" class="wp_themeSkin">' . "\n";
	foreach (array_keys($transliterado_lingvoj) as $lingvo) {
		echo '<fieldset class="options mceStatusbar">' . "\n" . '<legend><strong>';
		echo $transliterado_lingvoj[$lingvo]['NOMO'];
		echo '</strong></legend>' . "\n";
		$elekto = get_option('transliterado_' . $lingvo);
		echo '<select name="transliterado_' . $lingvo . '">' . "\n";
		echo '<option value="ne"';
		if ($elekto == 'ne' OR $elekto == '') {
			echo ' selected="selected"';
		}
		echo '>-</option>' . "\n";
		foreach (array_keys($transliterado_lingvoj[$lingvo]) as $sistemo) {
			if ($sistemo != 'NOMO') {
				echo '<option value="' . $sistemo . '"';
				if ($elekto == $sistemo) {
					echo ' selected="selected"';
				}
				echo '>' . $transliterado_lingvoj[$lingvo][$sistemo] . '</option>' . "\n";
			}
		}
		echo '</select>' . "\n";
		echo '</fieldset>' . "\n";
	}
	echo '<fieldset><div><input type="checkbox" id="shanghiafishojnpaghojn" name="shanghiafishojnpaghojn" value="shanghiafishojnpaghojn" />';
	echo ' <label for="shanghiafishojnpaghojn"><strong>' . __("Ŝanĝi malnovajn afiŝojn/paĝojn", "transliterado") . '</strong></label></div>';
	echo '<p class="wrap">';
	echo __("Se vi elektos <em>Ŝanĝi malnovajn afiŝojn/paĝojn</em>, estos ŝanĝitaj la URL-nomoj de ĉiuj jam publikigitaj afiŝoj kaj paĝoj laŭ la novaj elektoj. Ligiloj, kiuj uzas la malnovajn URL-nomojn, tamen restos plu funkciantaj.", "transliterado");
	echo '</p></fieldset>' . "\n";

	echo '<fieldset><div><input type="checkbox" id="shanghietikedojnkategoriojn" name="shanghietikedojnkategoriojn" value="shanghietikedojnkategoriojn" />';
	echo ' <label for="shanghietikedojnkategoriojn"><strong>' . __("Ŝanĝi malnovajn etikedojn/kategoriojn", "transliterado") . '</strong></label></div>';
	echo '<p class="wrap">';
	echo __("Se vi elektos <em>Ŝanĝi malnovajn etikedojn/kategoriojn</em>, estos ŝanĝitaj la URL-nomoj de ĉiuj jam ekzistantaj etikedoj kaj kategorioj (ankaŭ ligilo-kategorioj) laŭ la novaj elektoj. <strong>Ligiloj, kiuj uzas la malnovajn URL-nomojn de tiuj etikedoj kaj kategorioj, <em>ne plu funkcios</em></strong>. Pripensu do bone antaŭ ol elekti tiun ĉi eblon.", "transliterado");
	echo '</p></fieldset>' . "\n";

	echo '<p><input type="submit" value="';
	echo __("Konservi", "transliterado");
	echo '" /></p>' . "\n";
	echo '</form>' . "\n";
	echo '</div>' . "\n";
}

function transliterado_aldoni_menuon() {
	add_options_page('Transliterado', 'Transliterado', 8, __FILE__, 'transliterado_options_page');
}

add_action('admin_menu', 'transliterado_aldoni_menuon');
add_action('sanitize_title', 'sanitize_title_transliterado', 0);
?>

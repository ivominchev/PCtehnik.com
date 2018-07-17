=== Transliterado ===
Contributors: bertilow
Tags: slugs, internationalization, i18n
Requires at least: 2.5
Tested up to: 2.5
Stable tag: 0.8

This plugin gives better transliteration of non-ASCII characters in slugs.

== Description ==

This plugin gives better transliteration of non-ASCII characters in slugs. Currently covers Esperanto, Swedish, Finnish, Danish, Norwegian, German, Russian and Bulgarian. It can also handle dashes.

When WordPress creates slugs for the titles of posts and pages, and for tags and categories, it makes them all-ASCII, which among other things means removing diacritics (e.g. "é" becomes "e", "ö" becomes "o", "ĉ" becomes "c", etc.). For some languages the default filtering is OK, but for others it gives very bad results. The results for languages that don't even use a Latin script are especially unsatisfactory. The plugin Transliterado provides better transliteration systems for some languages. You can choose which of those systems should be used for your slugs. For some languages more than one transliteration system is available. Transliterado can also go through already existing posts and pages, and redo their slugs, if the user chooses that option. Another option does the same for tags and categories.

Significant parts of the code comes from the plugins Rustolat by Anton Skorobogatov, BGtoLat by Ognyan Mladenov, and Slugger by Hans Christian Saustrup.

Maybe you want to add systems for other languages. It's very easy to do. Have a look at the files for the languages that are in the plugin package already. I'll be happy to include any additions that are sent to me: "bertilow" at "gmail.com".

Priskribo:

Ĉi tiu kromprogramo donas pli bonan transliteradon de ne-Askiaj literoj en URL-nomoj. Nun prizorgataj estas Esperanto, la Sveda, la Finna, la Dana, la Norvega, la Germana, la Rusa kaj la Bulgara.

Kiam WordPress kreas URL-nomojn por afiŝoj kaj paĝoj, kaj por etikedoj kaj kategorioj, ĝi transformas ilin al Askio, kio interalie signifas, ke ĝi forigas ĉiujn kromsignojn (ekz. "é" fariĝas "e", "ö" fariĝas "o", "ĉ" fariĝas "c", k.t.p.). Por iuj lingvoj la defaŭlta filtrado estas en ordo, sed por aliaj ĝi donas tre malbonan rezulton. Precipe nekontentigaj estas la rezultoj por lingvoj, kiuj eĉ ne uzas la Latinan alfabeton. La kromprogramo Transliterado provizas pli bonajn transliteradajn sistemojn por kelkaj lingvoj. Oni povas elekti, kiuj el tiuj sistemoj estu uzataj por URL-nomoj. Por iuj lingvoj haveblas pli ol unu transliterada sistemo. Transliterado ankaŭ povas prilabori jam ekzistantajn afiŝojn kaj paĝoj, refarante ties URL-nomojn, se la uzanto elektas tiun eblon. Alia eblo faras la samon pri etikedoj kaj kategorioj.

Gravaj partoj de la kodo venas el la kromprogramoj Rustolat de Anton Skorobogatov, BGtoLat de Ognyan Mladenov, kaj Slugger de Hans Christian Saustrup.

Eble vi volas aldoni sistemojn por aliaj lingvoj. Tio estas tre facila. Rigardu la dosierojn de tiuj lingvoj, kiuj jam estas en la kromprograma paketo. Mi volonte enmetos aldonojn, kiujn vi sendos al mi: "bertilow" ĉe "gmail.com".

== Installation ==

1. Upload all files to the `/wp-content/plugins/transliterado/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

Instalado:

1. Alŝutu ĉiujn dosierojn al la dosierujo `/wp-content/plugins/transliterado/`
1. Aktivigu la kromprogramon en la Kromprograma menuo en WordPress

== Screenshots ==

1. The interface of Transliterado 0.5 / La interfaco de Transliterado 0.5

== Licence / Licenco ==

This plugin is released under the GPL licence. You can use it free of charge.

Tiu ĉi kromprogramo estas publikigita sub la licenco GPL. Vi rajtas uzi ĝin senkoste.

== Translations / Tradukoj ==

The basic language of Transliterado is Esperanto. There are translations into English, Swedish, Finnish, German, Russian and Bulgarian. More translations are of course welcome: "bertilow" at "gmail.com".

La baza lingvo de Transliterado estas Esperanto. Ekzistas tradukoj en la Anglan, la Svedan, la Finnan, la Germanan, la Rusan kaj la Bulgaran. Pliaj tradukoj estos bonvenaj: "bertilow" ĉe "gmail.com".


=== ANPC Display ===
Contributors: constantinonu
Author URI:  https://www.onu.ro
Tags: anpc, sal, sol, romania, ecommerce
Requires at least: 5.0
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 1.3.5
License: GPLv2 or later

Automatically displays the mandatory SAL and optionally the SOL links and icons for online stores in Romania.

== Description ==

This plugin automatically adds the mandatory icons for online merchants in Romania to the website footer, according to ANPC Order no. 449/2022:
1. SAL (Alternative Dispute Resolution)
2. SOL (Online Dispute Resolution) - *Optional as of July 2025*

The European Online Dispute Resolution (SOL) Platform has been discontinued as of 20 July 2025. This plugin allows you to hide the SOL link and provides informational links regarding the new regulations.

---

**Descriere în Română**

Acest plugin adaugă automat în subsolul site-ului (footer) pictogramele obligatorii pentru comercianții online din România, conform Ordinului ANPC nr. 449/2022:
1. SAL (Soluționarea Alternativă a Litigiilor)
2. SOL (Soluționarea Online a Litigiilor) - *Opțional din iulie 2025*

Platforma Europeană de Soluționare Online a Litigiilor (SOL) a fost întreruptă din 20 iulie 2025. Plugin-ul permite ascunderea link-ului SOL și oferă link-uri informative despre noile reglementări.

== Installation ==

1. Upload the `anpc-display` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to Settings -> ANPC Display to configure options (optional).

**Instalare în Română**

1. Încărcați folderul `anpc-display` în directorul `/wp-content/plugins/`.
2. Activați plugin-ul din meniul 'Implicite' în WordPress.
3. Mergeți la Setări -> ANPC Display pentru a configura opțiunile (opțional).

== Screenshots ==

1. Exemplu de afișare "Una lângă alta" (pe aceeași linie) / "Side by side" layout example.
2. Exemplu de afișare "Una peste alta" (pe coloană) / "Stacked" column layout example.
3. Interfața de administrare (Setări) cu opțiunile de afișare / Admin settings interface with layout options.

== Changelog ==

= 1.3.5 =
* Fix: Reordonare capturi de ecran pe pagina plugin-ului / Fix: Reordered screenshots on the plugin page.

= 1.3.4 =
* Fix: Corectarea ordinii capturilor de ecran pe pagina plugin-ului / Fix: Corrected screenshot ordering on the plugin page.

= 1.3.3 =
* Actualizare: Adăugate capturi de ecran relevante pe pagina plugin-ului / Update: Added relevant screenshots to the plugin page.

= 1.3.2 =
* Nou: Opțiune nouă "Mod Afișare" pentru a forța afișarea pictogramelor una lângă alta sau una peste alta / New: "Layout" option added to force side-by-side or stacked display of icons.
* Nou: Opțiune "Afișare Automată" care permite dezactivarea afișării implicite din footer, utilă când dorești să folosești exclusiv shortcode-ul sau widget-ul Elementor / Added option to disable default footer display, useful when exclusively using the shortcode or Elementor widget.
* UX: Adăugat o notificare în pagina de setări cu informații despre utilizarea shortcode-ului / Added a notice in the settings page with info on how to use the shortcode.

= 1.3.1 =
* Fix: Corectarea unor probleme de securitate și linting (escaping și protecție acces fișiere) / Fixed security and linting issues (escaping and file access protection).
* Optimizare: Încărcarea scripturilor în footer pentru o mai bună performanță / Loading scripts in the footer for better performance.

= 1.3.0 =
* Nou: Integrare nativă pentru Elementor - acum poți adăuga pictogramele SAL/SOL folosind un widget dedicat / Added native Elementor integration with a dedicated widget.
* Optimizare: Îmbunătățirea structurii fișierelor pentru o mai bună modularitate / Improved file structure for better modularity.

= 1.2.1 =
* Nou: Suport pentru site-uri multilingve (WPML/Polylang) - link-urile se ajustează automat în funcție de limba site-ului / Added multi-language support (WPML/Polylang) - URLs adjust automatically based on site language.
* Nou: Adăugat filtere pentru programatori pentru personalizarea URL-urilor / Added developer filters for custom URL overrides.

= 1.2.0 =
* Nou: Adăugat bloc Gutenberg nativ pentru inserarea ușoară a pictogramelor SAL/SOL în pagini și postări / Added native Gutenberg block for easy SAL/SOL icons insertion in pages and posts.
* Optimizare: Îmbunătățiri de performanță și suport pentru noul editor WordPress / Performance improvements and support for the new WordPress editor.

= 1.1.2 =
* CI/CD: Testarea fluxului automat de publicare pe GitHub și WordPress.org / Testing the automated deployment workflow for GitHub and WordPress.org.

= 1.1.1 =
* UX: Opțiunea "Afișează SOL" nu mai este bifată implicit după instalare, având în vedere încetarea platformei SOL / The "Show SOL" option is no longer checked by default after installation, following the discontinuation of the SOL platform.

= 1.1.0 =
* Securitate: Escaparea corectă a output-ului prin `wp_kses_post()` pentru conformitate cu standardele WordPress.org / Securely escaped output using `wp_kses_post()` for WordPress.org compliance.
* Conformitate: Eliminat apelul `load_plugin_textdomain()` conform noilor specificații (WordPress se ocupă automat de traduceri) / Removed discouraged `load_plugin_textdomain()` call.

= 1.0.9 =
* Simplificare: Eliminat câmpul redundant "Activează Plugin" din setări. Dacă plugin-ul este activ, acesta va fi afișat automat / Removed redundant "Enable Plugin" setting. If active, it works automatically.

= 1.0.8 =
* Optimizare Mobil: Adăugată setare pentru mărimea pictogramelor pe ecrane mici / Added mobile icon size setting for better responsive design.
* Personalizare: Adăugat câmp CSS Personalizat pentru stilizare avansată / Added Custom CSS field for advanced styling.
* Traduceri Oficiale: Adăugat suport pentru limbi (RO/EN) prin fișiere .po/.mo / Full translation support (Romanian/English) via .po/.mo files.

= 1.0.7 =
* Previzualizare: Afișarea pictogramelor direct în pagina de setări din admin / Admin image previews in the settings page.
* Poziționare: Opțiune de aliniere (Stânga/Centru/Dreapta) pentru pictograme / Alignment option (Left/Center/Right) for icons.
* Flexibilitate: Adăugat shortcode `[anpc_display]` pentru afișarea pictogramelor oriunde în site / Added `[anpc_display]` shortcode for custom positioning.

= 1.0.6 =
* Profesionalism: Adăugat link direct spre setări în lista de plugin-uri / Added direct settings link in the plugins list.
* Curățenie: Creat fișierul `uninstall.php` pentru ștergerea automată a setărilor din baza de date / Created `uninstall.php` for automatic cleanup of settings from the database.
* Versiune: Sărit de la 1.0.2 direct la 1.0.6 conform cerinței / Version jump from 1.0.2 to 1.0.6 as requested.

= 1.0.5 =
* Nou: Adăugat link informativ despre încetarea platformei SOL și noile reglementări UE / Added informational link explaining SOL platform discontinuation and new EU regulations.

= 1.0.4 =
* Nou: Opțiune pentru a activa/dezactiva link-ul SOL (întrerupt de UE din iulie 2025) / Added option to toggle SOL link (discontinued by EU as of July 2025).

= 1.0.3 =
* Optimizare: Documentație completă PHPDoc și îmbunătățiri de cod / Full PHPDoc documentation and code improvements.

= 1.0.2 =
* UX: Regruparea setărilor din admin în secțiuni SAL și SOL pentru o configurare mai ușoară / Regrouped admin settings into SAL and SOL sections for easier configuration.

= 1.0.1 =
* Optimizare: Hook-urile de admin sunt încărcate doar în contextul administrativ / Optimization: Admin hooks are only loaded in the administrative context.
* Curățare: Simplificarea instanțierii plugin-ului / Cleanup: Simplified plugin instantiation.

= 1.0.0 =
* Versiunea inițială / Initial release.
* Respectă regulile WordPress.org Plugin Guidelines / Follows WordPress.org Plugin Guidelines.
* Include resurse locale pentru SAL/SOL (fără hotlinking) / Includes local resources for SAL/SOL (no hotlinking).
* Pregătit pentru traducere (I18n) / Translation ready (I18n).

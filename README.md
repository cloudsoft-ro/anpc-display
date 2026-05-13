# ANPC Display WordPress Plugin

Acest plugin afișează automat link-urile și pictogramele SAL (Soluționarea Alternativă a Litigiilor) și SOL (Soluționarea Online a Litigiilor), obligatorii pentru magazinele online din România conform Ordinului ANPC nr. 449/2022.

## Caracteristici (v1.3.1)

- **Respectă legislația**: Afișează pictogramele oficiale conform specificațiilor ANPC.
- **Conform GDPR**: Imaginile sunt stocate local în plugin, eliminând riscul de tracking prin hotlinking extern.
- **Previzualizare Admin**: Vezi pictogramele direct în pagina de setări pentru feedback instant.
- **Control Poziționare**: Opțiuni de aliniere, Shortcode `[anpc_display]`, bloc Gutenberg și widget Elementor incluse.
- **Optimizare Mobil**: Setări dedicate pentru mărimea pictogramelor pe ecrane mici și câmp CSS Personalizat.
- **Curățenie la Dezinstalare**: Șterge automat toate setările din baza de date la dezinstalarea plugin-ului.
- **Bilingv Out-of-the-Box**: Suport complet pentru Română și Engleză.
- **Securitate Sporită**: Output escapat corespunzător conform standardelor de revizuire WordPress.org.

## Instalare

1. Descărcați arhiva plugin-ului.
2. Încărcați folderul `anpc-display` în directorul `/wp-content/plugins/`.
3. Activați plugin-ul din meniul `Plugin-uri`.
4. Configurați opțiunile în `Setări -> ANPC Display`.

## Structura Fișierelor

```
anpc-display/
├── assets/          # Pictograme și stiluri (CSS)
├── languages/       # Fișiere de traducere (PO/MO/POT)
├── anpc-display.php # Fișierul principal al plugin-ului
├── uninstall.php    # Script de curățare la dezinstalare
└── readme.txt       # Informații WordPress.org
```

## Licență

GPLv2 or later.

---

# ANPC Display WordPress Plugin (English)

This plugin automatically displays the SAL (Alternative Dispute Resolution) and SOL (Online Dispute Resolution) links and icons, which are mandatory for online stores in Romania according to ANPC Order no. 449/2022.

## Features (v1.3.1)

- **Legal Compliance**: Displays official icons according to ANPC specifications.
- **GDPR Compliant**: Images are stored locally, eliminating tracking risks from external hotlinking.
- **Admin Previews**: See icons directly in the settings page for instant feedback.
- **Positioning Control**: Alignment options, `[anpc_display]` shortcode, Gutenberg block, and Elementor widget included.
- **Mobile Optimized**: Settings for icon size on small screens and a Custom CSS field.
- **Clean Uninstall**: Automatically removes all settings from the database upon uninstallation.
- **Bilingual Out-of-the-Box**: Full support for Romanian and English.
- **Enhanced Security**: Properly escaped output compliant with WordPress.org review standards.

## Installation

1. Download the plugin archive.
2. Upload the `anpc-display` folder to the `/wp-content/plugins/` directory.
3. Activate the plugin from the `Plugins` menu.
4. Configure options in `Settings -> ANPC Display`.

## File Structure

```
anpc-display/
├── assets/          # Icons and styles (CSS)
├── languages/       # Translation files (PO/MO/POT)
├── anpc-display.php # Main plugin file
├── uninstall.php    # Uninstall cleanup script
└── readme.txt       # WordPress.org information
```

## License

GPLv2 or later.

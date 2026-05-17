<?php
/**
 * Plugin Name: ANPC Display
 * Plugin URI:  https://wordpress.org/plugins/anpc-display
 * Description: Automatically displays the mandatory SAL and SOL links and icons for online stores in Romania. (Afișează automat link-urile și pictogramele SAL și SOL obligatorii pentru magazinele online din România).
 * Version:     1.3.7
 * Requires at least: 5.0
 * Requires PHP: 7.4
 * Author:      Constantin Onu
 * Author URI:  https://www.onu.ro
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: anpc-display
 *
 * This plugin helps Romanian online merchants comply with ANPC Order no. 449/2022
 * by automatically displaying the required SAL (Soluționarea Alternativă a Litigiilor)
 * and SOL (Soluționarea Online a Litigiilor) icons and links in the website footer.
 *
 * @package ANPC_Display
 * @since   1.0.0
 */

// Prevent direct file access.
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Main plugin class responsible for rendering SAL/SOL icons and managing settings.
 *
 * Registers an admin settings page under Settings → ANPC Display where users
 * can enable/disable the plugin output and customise the image and link URLs
 * for both SAL and SOL badges. On the front-end it injects the badges into
 * the site footer via the `wp_footer` action.
 *
 * @since 1.0.0
 */
class ANPC_Display
{

	/**
	 * Plugin options retrieved from the database.
	 *
	 * Keys:
	 *  - enable_plugin  (int)    1 = enabled, 0 = disabled.
	 *  - enable_sol     (int)    1 = enabled, 0 = disabled.
	 *  - sal_image_url  (string) Custom URL for the SAL badge image.
	 *  - sol_image_url  (string) Custom URL for the SOL badge image.
	 *  - sal_link_url   (string) Custom destination URL for the SAL link.
	 *  - sol_link_url   (string) Custom destination URL for the SOL link.
	 *
	 * @since 1.0.0
	 * @var array|false
	 */
	private $options;

	/**
	 * Constructor — registers all WordPress hooks used by the plugin.
	 *
	 * Hooks:
	 *  - admin_menu          → Adds the settings page.
	 *  - admin_init          → Registers settings, sections and fields.
	 *  - wp_footer           → Outputs SAL/SOL badges on the front-end.
	 *  - wp_enqueue_scripts  → Enqueues the plugin stylesheet.
	 *
	 * @since 1.0.0
	 */
	public function __construct()
	{
		if (is_admin()) {
			add_action('admin_menu', array($this, 'add_plugin_page'));
			add_action('admin_init', array($this, 'page_init'));
			add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'add_settings_link'));
		}
		add_action('wp_footer', array($this, 'display_footer_content'));
		add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
		add_shortcode('anpc_display', array($this, 'shortcode_callback'));
		add_action('init', array($this, 'register_gutenberg_block'));
		add_action('elementor/widgets/register', array($this, 'register_elementor_widgets'));
	}


	/**
	 * Register the plugin settings page under the WordPress Settings menu.
	 *
	 * Creates a sub-menu item "ANPC Display" accessible to users with
	 * the `manage_options` capability (administrators by default).
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function add_plugin_page()
	{
		add_options_page(
			esc_html__('Setări ANPC Display', 'anpc-display'),
			esc_html__('ANPC Display', 'anpc-display'),
			'manage_options',
			'anpc-display-setting-admin',
			array($this, 'create_admin_page'));
	}

	/**
	 * Add "Settings" link to the plugin action links.
	 *
	 * @since 1.0.6
	 *
	 * @param array $links Array of plugin action links.
	 * @return array Modified array of plugin action links.
	 */
	public function add_settings_link($links)
	{
		$settings_link = '<a href="options-general.php?page=anpc-display-setting-admin">' . esc_html__('Setări', 'anpc-display') . '</a>';
		array_unshift($links, $settings_link);
		return $links;
	}

	/**
	 * Render the admin settings page HTML.
	 *
	 * Loads the current plugin options and outputs a standard WordPress
	 * settings form with a title, description, registered fields, and
	 * a submit button.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function create_admin_page()
	{
		$this->options = get_option('anpc_display_option_name');
?>
<div class="wrap">
	<h1>
		<?php esc_html_e('Setări ANPC Display', 'anpc-display'); ?>
	</h1>
	<p>
		<?php esc_html_e('Acest plugin afișează pictogramele SAL și SOL în subsolul site-ului, conform legislației ANPC.', 'anpc-display'); ?>
	</p>
	<div class="notice notice-info" style="margin-top: 15px; padding: 10px;">
		<p>
			<strong><?php esc_html_e('Sfat:', 'anpc-display'); ?></strong>
			<?php esc_html_e('Dacă dorești să plasezi pictogramele manual (într-un widget personalizat, o pagină specifică sau via Elementor/Gutenberg), poți folosi shortcode-ul:', 'anpc-display'); ?>
			<code>[anpc_display]</code>
		</p>
	</div>
	<form method="post" action="options.php">
		<?php
		settings_fields('anpc_display_option_group');
		do_settings_sections('anpc-display-setting-admin');
		submit_button();
?>
	</form>
</div>
<?php
	}

	/**
	 * Register plugin settings, sections, and individual fields.
	 *
	 * Registers the option group `anpc_display_option_group` stored in
	 * the database under the key `anpc_display_option_name`. Adds one
	 * settings section and five fields:
	 *  1. Enable Plugin   — checkbox to toggle output on/off.
	 *  2. SOL Enabled     — checkbox to toggle SOL output on/off (optional since ODR platform discontinued).
	 *  3. SAL Image URL   — custom image URL for the SAL badge.
	 *  4. SOL Image URL   — custom image URL for the SOL badge.
	 *  5. SAL Link URL    — destination URL when SAL badge is clicked.
	 *  6. SOL Link URL    — destination URL when SOL badge is clicked.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function page_init()
	{
		register_setting(
			'anpc_display_option_group',
			'anpc_display_option_name',
			array($this, 'sanitize')
		);

		add_settings_section(
			'setting_section_id',
			esc_html__('Configurare Generală', 'anpc-display'),
			array($this, 'print_section_info'),
			'anpc-display-setting-admin'
		);

		add_settings_field(
			'disable_footer',
			esc_html__('Afișare Automată', 'anpc-display'),
			array($this, 'disable_footer_callback'),
			'anpc-display-setting-admin',
			'setting_section_id'
		);

		add_settings_field(
			'alignment',
			esc_html__('Aliniere', 'anpc-display'),
			array($this, 'alignment_callback'),
			'anpc-display-setting-admin',
			'setting_section_id'
		);

		add_settings_field(
			'layout',
			esc_html__('Mod Afișare', 'anpc-display'),
			array($this, 'layout_callback'),
			'anpc-display-setting-admin',
			'setting_section_id'
		);

		add_settings_field(
			'mobile_icon_size',
			esc_html__('Mărime Pictograme Mobil (px)', 'anpc-display'),
			array($this, 'mobile_size_callback'),
			'anpc-display-setting-admin',
			'setting_section_id'
		);

		add_settings_field(
			'custom_css',
			esc_html__('CSS Personalizat', 'anpc-display'),
			array($this, 'custom_css_callback'),
			'anpc-display-setting-admin',
			'setting_section_id'
		);

		// Secțiunea SAL
		add_settings_section(
			'sal_section_id',
			esc_html__('Setări SAL (Alternative Dispute Resolution)', 'anpc-display'),
			null,
			'anpc-display-setting-admin'
		);

		add_settings_field(
			'sal_image_url',
			esc_html__('Imagine SAL URL', 'anpc-display'),
			array($this, 'sal_image_callback'),
			'anpc-display-setting-admin',
			'sal_section_id'
		);

		add_settings_field(
			'sal_link_url',
			esc_html__('Link SAL URL', 'anpc-display'),
			array($this, 'sal_link_callback'),
			'anpc-display-setting-admin',
			'sal_section_id'
		);

		// Secțiunea SOL
		add_settings_section(
			'sol_section_id',
			esc_html__('Setări SOL (Online Dispute Resolution)', 'anpc-display'),
			array($this, 'print_sol_info'),
			'anpc-display-setting-admin'
		);

		add_settings_field(
			'enable_sol',
			esc_html__('Afișează SOL', 'anpc-display'),
			array($this, 'enable_sol_callback'),
			'anpc-display-setting-admin',
			'sol_section_id'
		);

		add_settings_field(
			'sol_image_url',
			esc_html__('Imagine SOL URL', 'anpc-display'),
			array($this, 'sol_image_callback'),
			'anpc-display-setting-admin',
			'sol_section_id'
		);

		add_settings_field(
			'sol_link_url',
			esc_html__('Link SOL URL', 'anpc-display'),
			array($this, 'sol_link_callback'),
			'anpc-display-setting-admin',
			'sol_section_id'
		);
	}

	/**
	 * Render the disable footer checkbox field.
	 *
	 * @since 1.3.3
	 * @return void
	 */
	public function disable_footer_callback()
	{
		$is_disabled = isset($this->options['disable_footer']) ? $this->options['disable_footer'] : 0;
		printf(
			'<input type="checkbox" id="disable_footer" name="anpc_display_option_name[disable_footer]" value="1" %s />',
			$is_disabled ? 'checked' : ''
		);
		echo ' <label for="disable_footer">' . esc_html__('Dezactivează afișarea automată în subsolul site-ului (Footer)', 'anpc-display') . '</label>';
		echo '<p class="description">' . esc_html__('Bifează doar dacă folosești exclusiv shortcode-ul, blocul Gutenberg sau widget-ul Elementor, pentru a evita afișarea de două ori a pictogramelor.', 'anpc-display') . '</p>';
	}

	/**
	 * Render the layout select field.
	 *
	 * @since 1.3.2
	 * @return void
	 */
	public function layout_callback()
	{
		$layout = isset($this->options['layout']) ? $this->options['layout'] : 'auto';
?>
<select id="layout" name="anpc_display_option_name[layout]">
	<option value="auto" <?php selected($layout, 'auto' ); ?>>
		<?php esc_html_e('Automat (Implicit)', 'anpc-display'); ?>
	</option>
	<option value="row" <?php selected($layout, 'row' ); ?>>
		<?php esc_html_e('Una lângă alta (pe aceeași linie)', 'anpc-display'); ?>
	</option>
	<option value="column" <?php selected($layout, 'column' ); ?>>
		<?php esc_html_e('Una peste alta (pe coloană)', 'anpc-display'); ?>
	</option>
</select>
<?php
	}

	/**
	 * Render the alignment select field.
	 *
	 * @since 1.0.7
	 * @return void
	 */
	public function alignment_callback()
	{
		$alignment = isset($this->options['alignment']) ? $this->options['alignment'] : 'center';
?>
<select id="alignment" name="anpc_display_option_name[alignment]">
	<option value="left" <?php selected($alignment, 'left' ); ?>>
		<?php esc_html_e('Stânga', 'anpc-display'); ?>
	</option>
	<option value="center" <?php selected($alignment, 'center' ); ?>>
		<?php esc_html_e('Centru', 'anpc-display'); ?>
	</option>
	<option value="right" <?php selected($alignment, 'right' ); ?>>
		<?php esc_html_e('Dreapta', 'anpc-display'); ?>
	</option>
</select>
<?php
	}

	/**
	 * Render the mobile icon size field.
	 *
	 * @since 1.0.8
	 * @return void
	 */
	public function mobile_size_callback()
	{
		$value = isset($this->options['mobile_icon_size']) ? $this->options['mobile_icon_size'] : '150';
?>
<input type="number" id="mobile_icon_size" name="anpc_display_option_name[mobile_icon_size]"
	value="<?php echo esc_attr($value); ?>" min="20" max="1000" />
<p class="description">
	<?php esc_html_e('Lățimea pictogramelor pe ecrane mai mici de 600px.', 'anpc-display'); ?>
</p>
<?php
	}

	/**
	 * Render the custom CSS field.
	 *
	 * @since 1.0.8
	 * @return void
	 */
	public function custom_css_callback()
	{
		$value = isset($this->options['custom_css']) ? $this->options['custom_css'] : '';
?>
<textarea id="custom_css" name="anpc_display_option_name[custom_css]"
	style="width: 100%; max-width: 400px; height: 100px; font-family: monospace;"><?php echo esc_textarea($value); ?></textarea>
<?php
	}

	/**
	 * Sanitize and validate each settings field before saving.
	 *
	 * - `enable_plugin` is cast to an absolute integer (0 or 1).
	 * - `enable_sol` is cast to an absolute integer (0 or 1).
	 * - All URL fields are sanitised with `esc_url_raw()`.
	 *
	 * @since 1.0.0
	 *
	 * @param array $input Raw form values submitted by the user.
	 * @return array Sanitised values safe to store in the database.
	 */
	public function sanitize($input)
	{
		$new_input = array();

		if (isset($input['disable_footer']))
			$new_input['disable_footer'] = absint($input['disable_footer']);
		else
			$new_input['disable_footer'] = 0;

		if (isset($input['enable_sol']))
			$new_input['enable_sol'] = absint($input['enable_sol']);
		else
			$new_input['enable_sol'] = 0;

		if (isset($input['alignment']))
			$new_input['alignment'] = sanitize_text_field($input['alignment']);

		if (isset($input['layout']))
			$new_input['layout'] = sanitize_text_field($input['layout']);

		if (isset($input['mobile_icon_size']))
			$new_input['mobile_icon_size'] = absint($input['mobile_icon_size']);

		if (isset($input['custom_css']))
			$new_input['custom_css'] = wp_strip_all_tags($input['custom_css']);

		if (isset($input['sal_image_url']))
			$new_input['sal_image_url'] = esc_url_raw($input['sal_image_url']);

		if (isset($input['sol_image_url']))
			$new_input['sol_image_url'] = esc_url_raw($input['sol_image_url']);

		if (isset($input['sal_link_url']))
			$new_input['sal_link_url'] = esc_url_raw($input['sal_link_url']);

		if (isset($input['sol_link_url']))
			$new_input['sol_link_url'] = esc_url_raw($input['sol_link_url']);

		return $new_input;
	}

	/**
	 * Output the explanatory text at the top of the settings section.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function print_section_info()
	{
		print esc_html__('Configurați URL-urile pentru imaginile și link-urile SAL/SOL. Lăsați câmpurile goale pentru a folosi valorile implicite.', 'anpc-display');
	}

	/**
	 * Output the explanatory text for the SOL settings section.
	 *
	 * @since 1.0.4
	 *
	 * @return void
	 */
	public function print_sol_info()
	{
		print '<p style="color: #d63638;">' . esc_html__('Notă: Platforma Europeană de Soluționare Online a Litigiilor (SOL) a fost întreruptă din 20 iulie 2025. Afișarea acestui link este acum opțională.', 'anpc-display') . '</p>';
		print '<p>' . sprintf(
			/* translators: %s: URL to EU dispute resolution bodies */
			esc_html__('Mai multe informații despre încetarea platformei SOL și noile reglementări pot fi găsite aici: %s', 'anpc-display'),
			'<a href="https://consumer-redress.ec.europa.eu/dispute-resolution-bodies" target="_blank" rel="noopener noreferrer">https://consumer-redress.ec.europa.eu/dispute-resolution-bodies</a>'
		) . '</p>';
	}

	/**
	 * Render the "Enable Plugin" checkbox field.
	 *
	 * When checked the plugin outputs SAL/SOL badges in the footer.
	 * The plugin is enabled by default (when no option has been saved yet).
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function enable_callback()
	{
	// Removed: redundant "Enable Plugin" setting.
	}

	/**
	 * Render the "Enable SOL" checkbox field.
	 *
	 * When checked the plugin outputs the SOL badge in the footer.
	 *
	 * @since 1.0.4
	 *
	 * @return void
	 */
	public function enable_sol_callback()
	{
		// Default to 1 if not set yet (for backward compatibility)
		$is_sol_enabled = isset($this->options['enable_sol']) ? $this->options['enable_sol'] : 0;
		printf(
			'<input type="checkbox" id="enable_sol" name="anpc_display_option_name[enable_sol]" value="1" %s />',
			$is_sol_enabled ? 'checked' : ''
		);
	}

	/**
	 * Render the SAL image URL text input field.
	 *
	 * Defaults to the bundled `assets/sal.png` when value is empty.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function sal_image_callback()
	{
		$default_sal = plugin_dir_url(__FILE__) . 'assets/sal.png';
		$value = isset($this->options['sal_image_url']) && !empty($this->options['sal_image_url']) ? $this->options['sal_image_url'] : $default_sal;
		printf(
			'<input type="text" id="sal_image_url" name="anpc_display_option_name[sal_image_url]" value="%s" style="width: 100%%; max-width: 400px;" />',
			esc_attr($value)
		);
		printf(
			'<div style="margin-top: 10px;"><img src="%s" alt="Preview SAL" style="max-height: 50px; border: 1px solid #ccc; padding: 2px;"></div>',
			esc_url($value)
		);
	}

	/**
	 * Render the SOL image URL text input field.
	 *
	 * Defaults to the bundled `assets/sol.png` when value is empty.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function sol_image_callback()
	{
		$default_sol = plugin_dir_url(__FILE__) . 'assets/sol.png';
		$value = isset($this->options['sol_image_url']) && !empty($this->options['sol_image_url']) ? $this->options['sol_image_url'] : $default_sol;
		printf(
			'<input type="text" id="sol_image_url" name="anpc_display_option_name[sol_image_url]" value="%s" style="width: 100%%; max-width: 400px;" />',
			esc_attr($value)
		);
		printf(
			'<div style="margin-top: 10px;"><img src="%s" alt="Preview SOL" style="max-height: 50px; border: 1px solid #ccc; padding: 2px;"></div>',
			esc_url($value)
		);
	}

	/**
	 * Render the SAL link URL text input field.
	 *
	 * Defaults to the official ANPC SAL page: https://anpc.ro/ce-este-sal/
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function sal_link_callback()
	{
		$default_sal_url = 'https://anpc.ro/ce-este-sal/';
		$value = isset($this->options['sal_link_url']) && !empty($this->options['sal_link_url']) ? $this->options['sal_link_url'] : $default_sal_url;
		printf(
			'<input type="text" id="sal_link_url" name="anpc_display_option_name[sal_link_url]" value="%s" style="width: 100%%; max-width: 400px;" />',
			esc_attr($value)
		);
	}

	/**
	 * Render the SOL link URL text input field.
	 *
	 * Defaults to the EU Online Dispute Resolution platform:
	 * https://ec.europa.eu/consumers/odr
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function sol_link_callback()
	{
		$default_sol_url = 'https://ec.europa.eu/consumers/odr';
		$value = isset($this->options['sol_link_url']) && !empty($this->options['sol_link_url']) ? $this->options['sol_link_url'] : $default_sol_url;
		printf(
			'<input type="text" id="sol_link_url" name="anpc_display_option_name[sol_link_url]" value="%s" style="width: 100%%; max-width: 400px;" />',
			esc_attr($value)
		);
	}

	/**
	 * Enqueue the plugin front-end stylesheet.
	 *
	 * Only loads `assets/anpc-display.css` when the plugin output is enabled.
	 * The stylesheet controls the layout and appearance of the footer badges.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function enqueue_styles()
	{
		$options = get_option('anpc_display_option_name');

		wp_enqueue_style('anpc-display-style', plugin_dir_url(__FILE__) . 'assets/anpc-display.css', array(), '1.3.7');

		$mobile_size = isset($options['mobile_icon_size']) ? absint($options['mobile_icon_size']) : 150;
		$custom_css = isset($options['custom_css']) ? $options['custom_css'] : '';
		$layout = isset($options['layout']) ? $options['layout'] : 'auto';

		$dynamic_css = '';
		if ($layout === 'column') {
			$dynamic_css .= ".anpc-display-container { flex-direction: column !important; }";
		} elseif ($layout === 'row') {
			$dynamic_css .= ".anpc-display-container { flex-direction: row !important; }";
		}

		if ($mobile_size !== 150) {
			$dynamic_css .= "@media (max-width: 600px) { .anpc-display-container .anpc-item img { width: {$mobile_size}px !important; height: auto !important; } }";
		}
		if (!empty($custom_css)) {
			$dynamic_css .= $custom_css;
		}

		if (!empty($dynamic_css)) {
			wp_add_inline_style('anpc-display-style', $dynamic_css);
		}
	}

	/**
	 * Shortcode callback to display SAL/SOL icons.
	 *
	 * @since 1.0.7
	 * @return string HTML output of the icons.
	 */
	public function shortcode_callback()
	{
		return $this->get_anpc_content();
	}

	/**
	 * Register the Gutenberg block.
	 *
	 * @since 1.2.0
	 * @return void
	 */
	public function register_gutenberg_block()
	{
		if (!function_exists('register_block_type')) {
			return;
		}

		wp_register_script(
			'anpc-display-block-js',
			plugin_dir_url(__FILE__) . 'assets/js/block.js',
			array('wp-blocks', 'wp-i18n', 'wp-element', 'wp-server-side-render'),
			'1.3.7',
			true
		);

		register_block_type('anpc-display/badges', array(
			'editor_script' => 'anpc-display-block-js',
			'render_callback' => array($this, 'shortcode_callback'),
		));
	}

	/**
	 * Register the Elementor widget.
	 *
	 * @since 1.3.0
	 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
	 * @return void
	 */
	public function register_elementor_widgets($widgets_manager)
	{
		require_once plugin_dir_path(__FILE__) . 'elementor-widget.php';
		$widgets_manager->register(new \ANPC_Elementor_Widget());
	}

	/**
	 * Wrapper for wp_footer hook.
	 *
	 * @since 1.0.7
	 * @return void
	 */
	public function display_footer_content()
	{
		$options = get_option('anpc_display_option_name');
		$is_disabled = isset($options['disable_footer']) ? $options['disable_footer'] : 0;

		if ($is_disabled) {
			return; // The user chose to hide it from the footer automatically.
		}

		echo wp_kses_post($this->get_anpc_content());
	}

	/**
	 * Generates the HTML for SAL and SOL badges.
	 *
	 * Renders two linked images inside a container `<div>`:
	 *  - SAL badge linking to the ANPC alternative dispute resolution page.
	 *  - SOL badge linking to the EU online dispute resolution platform (if enabled).
	 *
	 * Both images and URLs can be customised from the admin settings page.
	 * If no custom values are set, bundled defaults are used.
	 *
	 * Output is skipped entirely when the plugin is disabled via settings.
	 *
	 * @since 1.0.7
	 * @return string HTML content for the badges.
	 */
	public function get_anpc_content()
	{
		$options = get_option('anpc_display_option_name');

		$isSolEnabled = isset($options['enable_sol']) ? $options['enable_sol'] : 0;
		$alignment = isset($options['alignment']) ? $options['alignment'] : 'center';

		// Multi-language support (WPML, Polylang or native locale)
		$locale = determine_locale();
		$lang = substr($locale, 0, 2);

		$default_sal_url = 'https://anpc.ro/ce-este-sal/';
		$default_sol_url = 'https://ec.europa.eu/consumers/odr';

		// Adjust default URLs based on language if no custom URL is provided
		if ($lang !== 'ro') {
			// There isn't a direct EN page for ANPC SAL, but the EU SOL platform is multilingual.
			$default_sol_url = 'https://ec.europa.eu/consumers/odr/main/?event=main.home2.show';
		}

		$sal_url = isset($options['sal_link_url']) && !empty($options['sal_link_url']) ? $options['sal_link_url'] : $default_sal_url;
		$sol_url = isset($options['sol_link_url']) && !empty($options['sol_link_url']) ? $options['sol_link_url'] : $default_sol_url;

		// Allow translation plugins to filter URLs
		$sal_url = apply_filters('anpc_display_sal_url', $sal_url, $lang);
		$sol_url = apply_filters('anpc_display_sol_url', $sol_url, $lang);

		$default_sal_img = plugin_dir_url(__FILE__) . 'assets/sal.png';
		$default_sol_img = plugin_dir_url(__FILE__) . 'assets/sol.png';

		$sal_img = isset($options['sal_image_url']) && !empty($options['sal_image_url']) ? $options['sal_image_url'] : $default_sal_img;
		$sol_img = isset($options['sol_image_url']) && !empty($options['sol_image_url']) ? $options['sol_image_url'] : $default_sol_img;

		$layout = isset($options['layout']) ? $options['layout'] : 'auto';
		$layout_style = '';
		if ($layout === 'column') {
			$layout_style = 'flex-direction: column !important; ';
		} elseif ($layout === 'row') {
			$layout_style = 'flex-direction: row !important; ';
		}

		$flex_align = 'center';
		if ($alignment === 'left') {
			$flex_align = 'flex-start';
		} elseif ($alignment === 'right') {
			$flex_align = 'flex-end';
		}
		
		$layout_style .= 'justify-content: ' . $flex_align . '; align-items: ' . $flex_align . ';';

		ob_start();
?>
<div class="anpc-display-container" style="text-align: <?php echo esc_attr($alignment); ?>; <?php echo esc_attr($layout_style); ?>">
	<a href="<?php echo esc_url($sal_url); ?>" target="_blank" rel="nofollow noopener noreferrer" class="anpc-item"
		title="ANPC - Soluționarea Alternativă a Litigiilor">
		<img src="<?php echo esc_url($sal_img); ?>" alt="ANPC SAL">
	</a>
	<?php if ($isSolEnabled): ?>
	<a href="<?php echo esc_url($sol_url); ?>" target="_blank" rel="nofollow noopener noreferrer" class="anpc-item"
		title="Comisia Europeană - Soluționarea Online a Litigiilor">
		<img src="<?php echo esc_url($sol_img); ?>" alt="ANPC SOL">
	</a>
	<?php
		endif; ?>
</div>
<?php
		return ob_get_clean();
	}
}

// Instantiate the plugin.
$anpc_display = new ANPC_Display();
<?php
/**
 * Elementor Widget for ANPC Display
 * 
 * Provides a native Elementor widget to display SAL/SOL badges.
 *
 * @since 1.3.0
 */

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

class ANPC_Elementor_Widget extends \Elementor\Widget_Base
{

	/**
	 * Get widget name.
	 *
	 * @return string Widget name.
	 */
	public function get_name()
	{
		return 'anpc_display_widget';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title()
	{
		return esc_html__('ANPC Display', 'anpc-display');
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 */
	public function get_icon()
	{
		return 'eicon-shield';
	}

	/**
	 * Get widget categories.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories()
	{
		return array('general');
	}

	/**
	 * Get widget keywords.
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords()
	{
		return array('anpc', 'sal', 'sol', 'badges', 'romania');
	}

	/**
	 * Register widget controls.
	 */
	protected function register_controls()
	{

		$this->start_controls_section(
			'content_section',
			array(
				'label' => esc_html__('Content', 'anpc-display'),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'info_text',
			array(
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw'  => esc_html__('Pictogramele SAL/SOL sunt configurate în setările generale ale plugin-ului (Setări -> ANPC Display).', 'anpc-display'),
			)
		);

		$this->add_responsive_control(
			'alignment',
			array(
				'label'     => esc_html__('Alignment', 'anpc-display'),
				'type'      => \Elementor\Controls_Manager::CHOOSE,
				'options'   => array(
					'left'   => array(
						'title' => esc_html__('Left', 'anpc-display'),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__('Center', 'anpc-display'),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__('Right', 'anpc-display'),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'   => 'center',
				'selectors' => array(
					'{{WRAPPER}} .anpc-display-container' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output on the frontend.
	 */
	protected function render()
	{
		$settings = $this->get_settings_for_display();
		
		// Use the existing logic from the main plugin class
		global $anpc_display;
		if (isset($anpc_display) && method_exists($anpc_display, 'get_anpc_content')) {
			echo $anpc_display->get_anpc_content();
		}
	}
}

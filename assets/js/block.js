/**
 * Gutenberg Block for ANPC Display
 * 
 * Registers an interactive block that renders the SAL/SOL badges 
 * using the server-side callback and supports custom settings.
 */
(function (blocks, element, serverSideRender, blockEditor, components, i18n) {
	var el = element.createElement;
	var __ = i18n.__;
	var InspectorControls = blockEditor.InspectorControls;
	var PanelBody = components.PanelBody;
	var SelectControl = components.SelectControl;
	var ToggleControl = components.ToggleControl;

	blocks.registerBlockType('anpc-display/badges', {
		title: __('ANPC Display', 'anpc-display'),
		description: __('Automatically displays the mandatory SAL and optionally the SOL links and icons for online stores in Romania.', 'anpc-display'),
		icon: 'shield-alt',
		category: 'widgets',
		keywords: [__('anpc', 'anpc-display'), __('sal', 'anpc-display'), __('sol', 'anpc-display')],
		
		attributes: {
			alignment: {
				type: 'string',
				default: '',
			},
			layout: {
				type: 'string',
				default: '',
			},
			enable_sol: {
				type: 'boolean',
				default: true,
			}
		},

		// The block is server-side rendered to ensure consistency with the shortcode.
		edit: function (props) {
			var attributes = props.attributes;
			var setAttributes = props.setAttributes;

			var sidebar = el(InspectorControls, {},
				el(PanelBody, { title: __('ANPC Display Settings', 'anpc-display'), initialOpen: true },
					el(SelectControl, {
						label: __('Alignment', 'anpc-display'),
						value: attributes.alignment,
						options: [
							{ label: __('Default (General Settings)', 'anpc-display'), value: '' },
							{ label: __('Left', 'anpc-display'), value: 'left' },
							{ label: __('Center', 'anpc-display'), value: 'center' },
							{ label: __('Right', 'anpc-display'), value: 'right' }
						],
						onChange: function (value) {
							setAttributes({ alignment: value });
						}
					}),
					el(SelectControl, {
						label: __('Display Mode', 'anpc-display'),
						value: attributes.layout,
						options: [
							{ label: __('Default (General Settings)', 'anpc-display'), value: '' },
							{ label: __('Automatic', 'anpc-display'), value: 'auto' },
							{ label: __('Side by side (row)', 'anpc-display'), value: 'row' },
							{ label: __('Stacked (column)', 'anpc-display'), value: 'column' }
						],
						onChange: function (value) {
							setAttributes({ layout: value });
						}
					}),
					el(ToggleControl, {
						label: __('Display SOL', 'anpc-display'),
						checked: attributes.enable_sol,
						onChange: function (value) {
							setAttributes({ enable_sol: value });
						}
					})
				)
			);

			if (!serverSideRender) {
				return el('div', { className: props.className },
					sidebar,
					el('p', {}, __('The ServerSideRender component is not available.', 'anpc-display'))
				);
			}

			return el('div', { className: props.className },
				sidebar,
				el(serverSideRender, {
					block: 'anpc-display/badges',
					attributes: attributes,
				})
			);
		},
		
		// We return null because the rendering is handled in PHP.
		save: function () {
			return null;
		},
	});
})(
	window.wp.blocks,
	window.wp.element,
	window.wp.serverSideRender,
	window.wp.blockEditor || window.wp.editor,
	window.wp.components,
	window.wp.i18n
);

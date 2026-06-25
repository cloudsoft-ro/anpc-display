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
		description: __('Afișează pictogramele SAL și SOL conform legislației ANPC.', 'anpc-display'),
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
				el(PanelBody, { title: __('Setări ANPC Display', 'anpc-display'), initialOpen: true },
					el(SelectControl, {
						label: __('Aliniere', 'anpc-display'),
						value: attributes.alignment,
						options: [
							{ label: __('Implicit (Setări Generale)', 'anpc-display'), value: '' },
							{ label: __('Stânga', 'anpc-display'), value: 'left' },
							{ label: __('Centru', 'anpc-display'), value: 'center' },
							{ label: __('Dreapta', 'anpc-display'), value: 'right' }
						],
						onChange: function (value) {
							setAttributes({ alignment: value });
						}
					}),
					el(SelectControl, {
						label: __('Mod Afișare', 'anpc-display'),
						value: attributes.layout,
						options: [
							{ label: __('Implicit (Setări Generale)', 'anpc-display'), value: '' },
							{ label: __('Automat', 'anpc-display'), value: 'auto' },
							{ label: __('Una lângă alta (linie)', 'anpc-display'), value: 'row' },
							{ label: __('Una peste alta (coloană)', 'anpc-display'), value: 'column' }
						],
						onChange: function (value) {
							setAttributes({ layout: value });
						}
					}),
					el(ToggleControl, {
						label: __('Afișează SOL', 'anpc-display'),
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
					el('p', {}, __('Componentul ServerSideRender nu este disponibil.', 'anpc-display'))
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

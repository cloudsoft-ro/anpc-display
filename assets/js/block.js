/**
 * Gutenberg Block for ANPC Display
 * 
 * Registers a simple block that renders the SAL/SOL badges 
 * using the server-side callback.
 */
(function (blocks, element, serverSideRender, i18n) {
	var el = element.createElement;
	var __ = i18n.__;

	blocks.registerBlockType('anpc-display/badges', {
		title: __('ANPC Display', 'anpc-display'),
		description: __('Afișează pictogramele SAL și SOL conform legislației ANPC.', 'anpc-display'),
		icon: 'shield-alt',
		category: 'widgets',
		keywords: [__('anpc', 'anpc-display'), __('sal', 'anpc-display'), __('sol', 'anpc-display')],
		
		// The block is server-side rendered to ensure consistency with the shortcode.
		edit: function (props) {
			return el('div', { className: props.className },
				el(serverSideRender, {
					block: 'anpc-display/badges',
					attributes: props.attributes,
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
	window.wp.i18n
);

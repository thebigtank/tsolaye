export default function styles() {
	setTimeout(() => {
		// wp.blocks.unregisterBlockStyle('core/button', ['fill', 'outline']);
	}, 1000);

	wp.blocks.unregisterBlockStyle('core/image', 'rounded');
	wp.blocks.unregisterBlockStyle('core/heading', 'asterisk');

	// wp.blocks.registerBlockStyle('core/button', {
	// 	name: 'primary',
	// 	label: 'Primary',
	// });

	// wp.blocks.registerBlockStyle('core/button', {
	// 	name: 'secondary',
	// 	label: 'Secondary',
	// });
}

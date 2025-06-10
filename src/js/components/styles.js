export default function styles() {
	setTimeout(() => {
	}, 1000);

	wp.blocks.unregisterBlockStyle('core/image', 'rounded');
	wp.blocks.unregisterBlockStyle('core/heading', 'asterisk');
}

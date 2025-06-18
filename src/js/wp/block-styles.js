export default function blockStyles() {
	setTimeout(() => {
		wp.blocks.unregisterBlockStyle('core/image', 'rounded');
		wp.blocks.unregisterBlockStyle('core/heading', 'asterisk');
	}, 1000);


}

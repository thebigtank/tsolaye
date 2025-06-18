export default function blockStyles() {
	setTimeout(() => {
		wp.blocks.unregisterBlockStyle('core/image', 'rounded');
		wp.blocks.unregisterBlockStyle('core/heading', 'asterisk');
		// wp.blocks.unregisterBlockStyle('core/button', 'outline');
		// wp.blocks.unregisterBlockStyle('core/button', 'fill');
	}, 1000);


}

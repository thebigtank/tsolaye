import mainMenu from './components/main-menu';
import blockStyles from './wp/block-styles';
import blockVariations from './wp/block-variations';

wp.domReady(function () {
	mainMenu();
	blockStyles();
	blockVariations();
});

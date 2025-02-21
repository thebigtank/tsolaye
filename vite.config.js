import { defineConfig } from 'vite';
import path from 'path';
import fs from 'fs';

// Custom plugin to remove the dist folder
function cleanDist() {
	return {
		name: 'clean-dist',
		buildStart() {
			const distPath = path.resolve(__dirname, 'dist');
			if (fs.existsSync(distPath)) {
				fs.rmSync(distPath, { recursive: true, force: true });
				console.log('dist folder removed successfully');
			}
		},
	};
}

export default defineConfig({
	root: path.resolve(__dirname, 'src'),
	base: process.env.NODE_ENV === 'production' ? '/wp-content/themes/tsolaye/' : '/',
	build: {
		outDir: path.resolve(__dirname, 'dist'),
		emptyOutDir: true,
		manifest: true, // Enable manifest file generation
		rollupOptions: {
			input: path.resolve(__dirname, 'src/index.js'),
			output: {
				entryFileNames: 'assets/[name].[hash].js', // Add hash for cache-busting
				chunkFileNames: 'assets/[name].[hash].js',
				assetFileNames: 'assets/[name].[hash].[ext]',
			},
		},
	},
	server: {
		port: 3000,
		hot: true,
		proxy: {
			proxy: {
				'/': {
					target: 'http://bigtank-patterns.local',
					changeOrigin: true,
					secure: false,
					rewrite: (path) => path.replace(/^\/wp-content\/themes\/tsolaye\//, ''),
				},
			},
		},
		watch: {
			usePolling: true,
		},
	},
	plugins: [cleanDist()],
});

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
  base: process.env.NODE_ENV === 'production'
    ? '/wp-content/themes/tsolaye/dist/'    // ← include “dist/” here
    : '/',
  build: {
    outDir: path.resolve(__dirname, 'dist'),
    emptyOutDir: true,
    manifest: 'manifest.json',               // emit manifest.json to dist root
    rollupOptions: {
      input: path.resolve(__dirname, 'src/index.js'),
      output: {
        entryFileNames:   'assets/[name].[hash].js',
        chunkFileNames:   'assets/[name].[hash].js',
        assetFileNames:   'assets/[name].[hash].[ext]',
      },
    },
  },
	server: {
		port: 3000,
		hot: true,
		proxy: {
			// Proxy any request under your theme folder back to WP
			'^/wp-content/themes/tsolaye/.*': {
				target: '/',
				changeOrigin: true,
				secure: false,
				rewrite: path => path.replace(
					/^\/wp-content\/themes\/tsolaye\//,
					''
				),
			},
		},
		watch: {
			usePolling: true,
		},
	},
  plugins: [ cleanDist() ],
})

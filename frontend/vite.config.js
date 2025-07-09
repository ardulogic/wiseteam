import { fileURLToPath, URL } from 'node:url'
import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

export default defineConfig(({ mode }) => {
  const isDevBuild = mode === 'development'

  return {
    plugins: [vue()],
    resolve: {
      alias: {
        '@': fileURLToPath(new URL('./src', import.meta.url)),
      },
    },
    base: '/',
    build: {
      outDir: 'public',
      emptyOutDir: true,
      minify: !isDevBuild,         // minify only in production
      sourcemap: isDevBuild,       // enable source maps for dev
    },
    css: {
      preprocessorOptions: {
        scss: {
          additionalData: `@use "@/styles/_variables.scss" as *;`
        }
      }
    }
  }
})

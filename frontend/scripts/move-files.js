// scripts/clean-assets.js
// 1. Clean ../public/assets directory
// 2. Copy files from ./public to ../public
//    - Rename index.html -> spa.html during the copy

import { rmSync, existsSync, readdirSync, statSync, copyFileSync, mkdirSync, renameSync } from 'fs'
import { join, basename } from 'path'

const srcDir = join(process.cwd(), 'public')       // Vite output dir
const destDir = join(process.cwd(), '../public')   // Symfony public dir
const assetsDir = join(destDir, 'assets')

// Step 1: Clean ../public/assets
if (existsSync(assetsDir)) {
  rmSync(assetsDir, { recursive: true, force: true })
  console.log('✔ Cleaned /public/assets directory')
} else {
  console.log('ℹ No /public/assets directory to clean')
}

// Step 2: Copy files from ./public to ../public
const copyRecursive = (src, dest) => {
  const entries = readdirSync(src, { withFileTypes: true })
  mkdirSync(dest, { recursive: true })

  for (const entry of entries) {
    const srcPath = join(src, entry.name)
    const destPath = join(dest, entry.name === 'index.html' ? 'spa.html' : entry.name)

    if (entry.isDirectory()) {
      copyRecursive(srcPath, destPath)
    } else {
      copyFileSync(srcPath, destPath)
    }
  }
}

copyRecursive(srcDir, destDir)
console.log('✔ Copied build output to Symfony public directory (index.html → spa.html)')

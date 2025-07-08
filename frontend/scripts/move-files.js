// scripts/clean-assets.js
// We need to clean assets folder only, leaving index.php intact
// Default vite options only allow to clean build directory entirely

import { rmSync, existsSync } from 'fs'
import { join } from 'path'

const assetsDir = join(process.cwd(), '../public/assets')

if (existsSync(assetsDir)) {
  rmSync(assetsDir, { recursive: true, force: true })
  console.log('✔ Cleaned /public/assets directory')
} else {
  console.log('ℹ No /public/assets directory to clean')
}

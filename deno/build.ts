/**
 * Sinople Deno Build Script
 *
 * Bundles the Deno/Fresh application for production deployment.
 * Creates optimized bundles in the build/deno/ directory.
 *
 * Usage: deno task build
 */

import { ensureDir, copy, walk } from 'https://deno.land/std@0.208.0/fs/mod.ts';
import { join, basename } from 'https://deno.land/std@0.208.0/path/mod.ts';

const ROOT = new URL('..', import.meta.url).pathname;
const DENO_DIR = new URL('.', import.meta.url).pathname;
const BUILD_DIR = join(ROOT, 'build', 'deno');

interface BuildConfig {
  entryPoints: string[];
  outDir: string;
  minify: boolean;
}

const config: BuildConfig = {
  entryPoints: [
    join(DENO_DIR, 'main.ts'),
  ],
  outDir: BUILD_DIR,
  minify: true,
};

async function clean(): Promise<void> {
  console.log('🧹 Cleaning build directory...');
  try {
    await Deno.remove(BUILD_DIR, { recursive: true });
  } catch {
    // Directory doesn't exist, that's fine
  }
  await ensureDir(BUILD_DIR);
}

async function copyStaticAssets(): Promise<void> {
  console.log('📋 Copying static assets...');

  // Copy lib/ directory if it exists
  const libDir = join(DENO_DIR, 'lib');
  try {
    const libInfo = await Deno.stat(libDir);
    if (libInfo.isDirectory) {
      await copy(libDir, join(BUILD_DIR, 'lib'), { overwrite: true });
    }
  } catch {
    // lib/ doesn't exist, skip
  }
}

async function bundleTypeScript(): Promise<void> {
  console.log('📦 Bundling TypeScript files...');

  for (const entryPoint of config.entryPoints) {
    const fileName = basename(entryPoint, '.ts') + '.bundle.js';
    const outFile = join(config.outDir, fileName);

    console.log(`  Bundling ${basename(entryPoint)}...`);

    const result = await Deno.emit(entryPoint, {
      bundle: 'module',
      compilerOptions: {
        lib: ['dom', 'dom.iterable', 'esnext', 'deno.ns'],
        jsx: 'react-jsx',
        jsxImportSource: 'preact',
      },
    }).catch(() => null);

    if (result) {
      // Write the bundled output
      const bundled = result.files['deno:///bundle.js'] || '';
      await Deno.writeTextFile(outFile, bundled);
      console.log(`  ✅ Created ${fileName}`);
    } else {
      // Fallback: just copy the file
      console.log(`  ⚠️  Bundle failed, copying source...`);
      await Deno.copyFile(entryPoint, join(config.outDir, basename(entryPoint)));
    }
  }
}

async function generateManifest(): Promise<void> {
  console.log('📝 Generating build manifest...');

  const manifest = {
    version: '1.0.0',
    buildTime: new Date().toISOString(),
    files: [] as string[],
  };

  for await (const entry of walk(BUILD_DIR)) {
    if (entry.isFile) {
      manifest.files.push(entry.path.replace(BUILD_DIR, ''));
    }
  }

  await Deno.writeTextFile(
    join(BUILD_DIR, 'manifest.json'),
    JSON.stringify(manifest, null, 2)
  );
}

async function build(): Promise<void> {
  console.log('🚀 Building Sinople Deno Application');
  console.log('=====================================\n');

  const start = performance.now();

  try {
    await clean();
    await copyStaticAssets();
    await bundleTypeScript();
    await generateManifest();

    const elapsed = ((performance.now() - start) / 1000).toFixed(2);
    console.log(`\n✅ Build complete in ${elapsed}s`);
    console.log(`📁 Output: ${BUILD_DIR}`);

    // List output files
    console.log('\nBuild artifacts:');
    for await (const entry of walk(BUILD_DIR, { maxDepth: 2 })) {
      if (entry.isFile) {
        const stat = await Deno.stat(entry.path);
        const size = (stat.size / 1024).toFixed(1);
        console.log(`  ${entry.path.replace(BUILD_DIR, '.')} (${size}KB)`);
      }
    }
  } catch (error) {
    console.error('\n❌ Build failed:', error);
    Deno.exit(1);
  }
}

// Run build
await build();

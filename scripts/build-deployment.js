import {
    chmodSync,
    cpSync,
    existsSync,
    mkdirSync,
    readdirSync,
    readFileSync,
    rmSync,
    writeFileSync,
} from "fs";
import { dirname, join, resolve } from "path";
import JSZip from "jszip";

const projectRoot = resolve(process.cwd());
const args = process.argv.slice(2);
const noVendor = args.includes("--no-vendor");

// ── Config ──────────────────────────────────────────────────────────
const nuxtDir = join(projectRoot, "frontend");
const publicDirName = "public_html";
const buildDirName = noVendor
    ? "build-deployment-novendor"
    : "build-deployment";
const zipBaseName = noVendor
    ? "notaris-deployment-novendor.zip"
    : "notaris-deployment.zip";

const buildDir = join(projectRoot, buildDirName);
const zipFile = join(projectRoot, zipBaseName);

// ── Clean previous build ────────────────────────────────────────────
if (existsSync(buildDir)) {
    rmSync(buildDir, { recursive: true, force: true });
}
if (existsSync(zipFile)) {
    rmSync(zipFile, { force: true });
}

mkdirSync(buildDir, { recursive: true });

// ── Directory structure ─────────────────────────────────────────────
//
//  build-deployment/
//  ├── laravel-app/          ← Laravel backend (app, bootstrap, vendor, etc.)
//  └── public_html/          ← Web root (Nuxt SPA + Laravel /api entry)
//      ├── api/              ← Symlink or copy of Laravel public/, rewired index.php
//      │   └── index.php     ← Points to ../laravel-app/
//      ├── _nuxt/            ← Nuxt static assets
//      ├── index.html        ← Nuxt SPA entry
//      ├── .htaccess         ← Combined: /api → api/index.php, else → index.html
//      └── ...               ← Other Nuxt static files
//

const laravelAppDir = join(buildDir, "laravel-app");
mkdirSync(laravelAppDir, { recursive: true });

const publicHtmlDir = join(buildDir, publicDirName);
mkdirSync(publicHtmlDir, { recursive: true });

// ── Helper ──────────────────────────────────────────────────────────
const ensureDir = (path) => {
    if (!existsSync(path)) mkdirSync(path, { recursive: true });
};

const copyPath = (srcPath, destPath) => {
    if (!existsSync(srcPath)) return;
    ensureDir(dirname(destPath));
    cpSync(srcPath, destPath, { recursive: true });
};

// ── 1. Copy Laravel backend files ───────────────────────────────────
const laravelInclude = [
    "app",
    "bootstrap",
    "config",
    "database",
    "resources",
    "routes",
    "storage",
    ...(noVendor ? [] : ["vendor"]),
    "artisan",
    "composer.json",
    "composer.lock",
];

process.stdout.write("Copying Laravel files: ");
for (const entry of laravelInclude) {
    const src = join(projectRoot, entry);
    const dest = join(laravelAppDir, entry);
    if (!existsSync(src)) continue;
    copyPath(src, dest);
    process.stdout.write(".");
}
process.stdout.write("\n");

// ── 2. Copy .env.example as .env.production template ───────────────
const envExample = join(projectRoot, ".env.example");
const envProdDest = join(laravelAppDir, ".env.example");
if (existsSync(envExample)) {
    copyPath(envExample, envProdDest);
}

// ── 3. Ensure storage scaffold ──────────────────────────────────────
const storageDirs = [
    join(laravelAppDir, "storage", "app", "public"),
    join(laravelAppDir, "storage", "app", "private"),
    join(laravelAppDir, "storage", "framework", "cache", "data"),
    join(laravelAppDir, "storage", "framework", "sessions"),
    join(laravelAppDir, "storage", "framework", "views"),
    join(laravelAppDir, "storage", "logs"),
    join(laravelAppDir, "bootstrap", "cache"),
];

for (const dir of storageDirs) {
    ensureDir(dir);
    const gitignore = join(dir, ".gitignore");
    if (!existsSync(gitignore)) {
        writeFileSync(gitignore, "*\n!.gitignore\n");
    }
}

// ── 4. Copy Laravel public files into public_html/api/ ──────────────
const apiDir = join(publicHtmlDir, "api");
mkdirSync(apiDir, { recursive: true });

const laravelPublicFiles = [
    ".htaccess",
    "favicon.ico",
    "robots.txt",
    "index.php",
];

process.stdout.write("Copying Laravel public → public_html/api/: ");
for (const entry of laravelPublicFiles) {
    const src = join(projectRoot, "public", entry);
    if (!existsSync(src)) continue;
    copyPath(src, join(apiDir, entry));
    process.stdout.write(".");
}

// Also copy other public subfolders (assets, etc.)
const laravelPublicDirs = readdirSync(join(projectRoot, "public"), {
    withFileTypes: true,
})
    .filter((d) => d.isDirectory() && d.name !== ".")
    .map((d) => d.name);

for (const dir of laravelPublicDirs) {
    const src = join(projectRoot, "public", dir);
    copyPath(src, join(apiDir, dir));
}

process.stdout.write("\n");

// ── 5. Rewrite api/index.php to point to ../laravel-app/ ────────────
const apiIndexPath = join(apiDir, "index.php");
if (existsSync(apiIndexPath)) {
    let content = readFileSync(apiIndexPath, "utf8");
    // Rewrite paths: from __DIR__.'/../vendor/ to __DIR__.'/../laravel-app/vendor/
    content = content.replace(
        /require\s+__DIR__\s*\.\s*'\/\.\.\/vendor\/autoload\.php'/g,
        "require __DIR__.'/../laravel-app/vendor/autoload.php'",
    );
    content = content.replace(
        /require_once\s+__DIR__\s*\.\s*'\/\.\.\/bootstrap\/app\.php'/g,
        "require_once __DIR__.'/../laravel-app/bootstrap/app.php'",
    );
    writeFileSync(apiIndexPath, content);
}

// ── 6. Copy Nuxt build output into public_html/ ────────────────────
const nuxtOutputDir = join(nuxtDir, ".output", "public");

if (existsSync(nuxtOutputDir)) {
    process.stdout.write("Copying Nuxt output → public_html/: ");
    const entries = readdirSync(nuxtOutputDir, { withFileTypes: true });
    for (const entry of entries) {
        const src = join(nuxtOutputDir, entry.name);
        const dest = join(publicHtmlDir, entry.name);
        copyPath(src, dest);
        process.stdout.write(".");
    }
    process.stdout.write("\n");
} else {
    console.warn("⚠ Nuxt output not found at " + nuxtOutputDir);
    console.warn(
        '  Run "npx nuxt generate" in frontend/ first, or run build:production script.',
    );
}

// ── 7. Create combined .htaccess for public_html/ ───────────────────
const htaccessContent = `# Enable mod_rewrite
RewriteEngine On

# Handle Authorization Header
RewriteCond %{HTTP:Authorization} .
RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

# ── API routes: delegate to Laravel via /api/index.php ──
RewriteCond %{REQUEST_URI} ^/api(/|$) [NC]
RewriteRule ^api(/.*)?$ api/index.php [QSA,L]

# ── Don't rewrite real files/dirs (static assets, _nuxt, etc.) ──
RewriteCond %{REQUEST_FILENAME} -f
RewriteRule ^ - [L]

RewriteCond %{REQUEST_FILENAME} -d
RewriteRule ^ - [L]

# ── Everything else: SPA fallback to index.html ──
RewriteRule ^(.*)$ index.html [QSA,L]

# ── Cache Control for static assets ──
<FilesMatch "\\.(js|css|jpg|jpeg|png|gif|ico|svg|eot|ttf|woff|woff2|webmanifest|webp|pdf|zip|json|xml)$">
    Header set Cache-Control "public, max-age=31536000, immutable"
</FilesMatch>

<FilesMatch "\\.html$">
    Header set Cache-Control "public, max-age=3600"
</FilesMatch>

# ── Gzip compression ──
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/xml text/css text/javascript
    AddOutputFilterByType DEFLATE application/javascript application/json
    AddOutputFilterByType DEFLATE application/xml application/xhtml+xml
    AddOutputFilterByType DEFLATE application/manifest+json
</IfModule>

# ── Security headers ──
<IfModule mod_headers.c>
    Header always set X-Content-Type-Options nosniff
    Header always set X-Frame-Options DENY
    Header always set X-XSS-Protection "1; mode=block"
</IfModule>

Options +FollowSymLinks -Indexes
`;

writeFileSync(join(publicHtmlDir, ".htaccess"), htaccessContent);

// ── 8. Create deploy README ────────────────────────────────────────
const readmeContent = `# Deployment Structure

Upload the contents of this folder to your server so that:

\`\`\`
/home/username/
├── larapi/                  ← (optional, for dev)
├── laravel-app/             ← Laravel backend files
│   ├── app/
│   ├── bootstrap/
│   ├── config/
│   ├── database/
│   ├── resources/
│   ├── routes/
│   ├── storage/             ← MUST be writable (chmod -R 775 storage)
│   ├── vendor/
│   └── ...
└── public_html/             ← Web root (document root)
    ├── api/
    │   ├── .htaccess
    │   └── index.php        ← Laravel entry point (rewired paths)
    ├── _nuxt/               ← Nuxt static assets
    ├── index.html           ← Nuxt SPA entry
    ├── .htaccess            ← Routes /api → api/index.php, else → index.html
    └── ...

After uploading:
1. Copy .env.example to .env in laravel-app/ and configure it
2. Run: php artisan key:generate
3. Run: php artisan migrate --force
4. Run: chmod -R 775 laravel-app/storage laravel-app/bootstrap/cache
5. Configure your web server's document root to public_html/
`;

writeFileSync(join(buildDir, "README-DEPLOY.md"), readmeContent);

// ── 9. Create ZIP ───────────────────────────────────────────────────
const addDirectoryToZip = (zip, dirPath, zipFolder = "") => {
    const items = readdirSync(dirPath, { withFileTypes: true });
    for (const item of items) {
        const itemPath = join(dirPath, item.name);
        const zipPath = zipFolder ? `${zipFolder}/${item.name}` : item.name;
        if (item.isDirectory()) {
            addDirectoryToZip(zip, itemPath, zipPath);
            continue;
        }
        zip.file(zipPath, readFileSync(itemPath));
    }
};

console.log("Creating ZIP...");
const zip = new JSZip();
addDirectoryToZip(zip, buildDir);
const zipContent = await zip.generateAsync({
    type: "nodebuffer",
    compression: "DEFLATE",
    compressionOptions: { level: 6 },
});
writeFileSync(zipFile, zipContent);

try {
    chmodSync(zipFile, 0o644);
} catch {}

// Remove build directory after zip is created
rmSync(buildDir, { recursive: true, force: true });

console.log(`✅ ZIP ready: ${zipBaseName}`);
console.log(
    `   Upload and extract to server with public_html/ as document root.`,
);

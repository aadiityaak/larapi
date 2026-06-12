import fs from "fs";
import path from "path";
import { fileURLToPath } from "url";
import * as ftp from "basic-ftp";
import archiver from "archiver";

const __dirname = path.dirname(fileURLToPath(import.meta.url));

const args = new Set(process.argv.slice(2));
const forceUpload = args.has("--upload");
const disableUpload = args.has("--no-upload");

const configPath = path.resolve(__dirname, "../.vscode/sftp.json");
const zipFile = path.resolve(__dirname, "../dist.zip");

function getStaticOutputDir() {
  const nuxt3Public = path.resolve(__dirname, "../.output/public");
  if (fs.existsSync(nuxt3Public)) return nuxt3Public;

  const dist = path.resolve(__dirname, "../dist");
  if (fs.existsSync(dist)) return dist;

  throw new Error(
    `Output static tidak ditemukan. Jalankan "npm run generate" dulu (cek: ${nuxt3Public} atau ${dist}).`,
  );
}

function loadConfigIfExists() {
  if (!fs.existsSync(configPath)) return null;
  return JSON.parse(fs.readFileSync(configPath, "utf8"));
}

async function createZipFromDir(sourceDir, outputZipPath) {
  await fs.promises.rm(outputZipPath, { force: true });

  await new Promise((resolve, reject) => {
    const output = fs.createWriteStream(outputZipPath);
    const archive = archiver("zip", { zlib: { level: 9 } });

    output.on("close", resolve);
    output.on("error", reject);
    archive.on("error", reject);

    archive.pipe(output);
    archive.directory(sourceDir, false);
    archive.finalize();
  });
}

async function uploadFTP() {
  const config = loadConfigIfExists();
  if (!config) {
    throw new Error(
      `Config FTP tidak ditemukan: ${configPath}\nBuat file itu, atau jalankan dengan "--no-upload" untuk hanya buat ZIP.`,
    );
  }

  const client = new ftp.Client();
  client.ftp.verbose = config.verbose === true || process.env.FTP_VERBOSE === "1";

  try {
    await client.access({
      host: config.host,
      port: config.port || 21,
      user: config.username || config.user,
      password: config.password,
      secure: config.secure ?? false,
    });

    const remotePath = (config.remotePath || "/").replaceAll("\\", "/");
    const remoteName = config.remoteFileName || path.basename(zipFile);

    if (remotePath) await client.ensureDir(remotePath);

    console.log(`[+] Uploading ${zipFile} -> ${remotePath}/${remoteName} ...`);
    await client.uploadFrom(zipFile, remoteName);
    console.log(`[✓] Upload selesai: ${remotePath}/${remoteName}`);
  } catch (err) {
    console.error("[✗] Gagal upload via FTP:", err?.message || String(err));
  }
  client.close();
}

async function main() {
  const outputDir = getStaticOutputDir();

  console.log(`[+] Membuat ZIP dari: ${outputDir}`);
  await createZipFromDir(outputDir, zipFile);
  console.log(`[✓] ZIP siap: ${zipFile}`);

  const hasConfig = fs.existsSync(configPath);
  const shouldUpload = !disableUpload && (forceUpload || hasConfig);

  if (!shouldUpload) return;

  await uploadFTP();
}

main().catch((err) => {
  console.error("[✗] Deploy gagal:", err?.message || String(err));
  process.exitCode = 1;
});

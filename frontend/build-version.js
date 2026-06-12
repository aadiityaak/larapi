import { writeFileSync, mkdirSync } from "fs";
import { fileURLToPath } from "url";
import { dirname, join } from "path";

console.log("🔧 Start generating...");

const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);

const buildDate = new Date().toISOString();
const buildTimestamp = Date.now();
const version = `v${new Date()
  .toISOString()
  .replace(/[:\-T.Z]/g, "")
  .slice(0, 12)}`;

const data = {
  version,
  buildDate,
  buildTimestamp,
};

const targetPath = join(__dirname, "public/build-version.json");
console.log("📄 Writing to:", targetPath);

mkdirSync(join(__dirname, "public"), { recursive: true });
writeFileSync(targetPath, JSON.stringify(data, null, 2));

console.log("✅ Done:", data);

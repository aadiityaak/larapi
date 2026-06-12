export default function ({ store, redirect }) {
  const user = useSanctumUser(); // Ambil pengguna saat ini

  // Periksa apakah pengguna ada dan merupakan admin
  if (user && user.value && user.value.is_admin === "1") {
    // Pengguna adalah admin, lanjutkan ke halaman yang diminta
    return;
  }

  // Jika bukan admin, redirect ke halaman beranda
  return redirect("/");
}

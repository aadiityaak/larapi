<?php

/**
 * Admin Tools for Notaris Application - Versi untuk public_html
 * 
 * WARNING: Hapus file ini di production jika tidak diperlukan!
 * Atau tambahkan proteksi IP/Password.
 */

define('LARAVEL_START', microtime(true));

// Path ke folder larapi (sejajar dengan public_html)
$larapiPath = __DIR__ . '/../larapi';

require $larapiPath . '/vendor/autoload.php';

$app = require_once $larapiPath . '/bootstrap/app.php';

$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Cek apakah model Role kustom ada, jika tidak gunakan Spatie Role
if (class_exists('App\Models\Role')) {
    class_alias('App\Models\Role', 'RoleModel');
} else {
    class_alias('Spatie\Permission\Models\Role', 'RoleModel');
}

$message = '';
$messageType = 'info';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    try {
        switch ($action) {
            case 'seed_administrator':
                $email = $_POST['email'] ?? 'admin@notarisyossi.com';
                $name = $_POST['name'] ?? 'Administrator';
                $password = $_POST['password'] ?? 'password';

                if (empty($email) || empty($name) || empty($password)) {
                    throw new Exception('Semua field harus diisi!');
                }

                $user = User::where('email', $email)->first();
                if ($user) {
                    throw new Exception('User dengan email tersebut sudah ada!');
                }

                // Buat user baru
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make($password),
                    'is_admin' => 1,
                ]);

                // Pastikan role admin ada
                $role = RoleModel::where('name', 'admin')->first();
                if (!$role) {
                    $role = RoleModel::create([
                        'name' => 'admin',
                        'guard_name' => 'web',
                    ]);
                }

                // Berikan role admin ke user
                $user->assignRole($role);

                $message = "User administrator berhasil dibuat! <br> Email: $email <br> Password: $password";
                $messageType = 'success';
                break;

            case 'update_is_admin':
                $userId = intval($_POST['user_id'] ?? 0);
                $isAdmin = isset($_POST['is_admin']) ? 1 : 0;

                if (!$userId) {
                    throw new Exception('User ID harus diisi!');
                }

                $user = User::find($userId);
                if (!$user) {
                    throw new Exception('User tidak ditemukan!');
                }

                $user->is_admin = $isAdmin;
                $user->save();

                $message = "Status admin user berhasil diubah!";
                $messageType = 'success';
                break;

            case 'assign_role':
                $userId = intval($_POST['user_id_assign'] ?? 0);
                $roleName = $_POST['role_name'] ?? '';

                if (!$userId || empty($roleName)) {
                    throw new Exception('Semua field harus diisi!');
                }

                $user = User::find($userId);
                if (!$user) {
                    throw new Exception('User tidak ditemukan!');
                }

                $role = RoleModel::where('name', $roleName)->first();
                if (!$role) {
                    throw new Exception('Role tidak ditemukan!');
                }

                $user->assignRole($role);
                $message = "Role '$roleName' berhasil diberikan ke user!";
                $messageType = 'success';
                break;

            case 'change_password':
                $userId = intval($_POST['user_id_password'] ?? 0);
                $newPassword = $_POST['new_password'] ?? '';
                $newPasswordConfirm = $_POST['new_password_confirm'] ?? '';

                if (!$userId) {
                    throw new Exception('User ID harus diisi!');
                }

                if (trim($newPassword) === '') {
                    throw new Exception('Password baru harus diisi!');
                }

                if ($newPassword !== $newPasswordConfirm) {
                    throw new Exception('Konfirmasi password tidak sama!');
                }

                $user = User::find($userId);
                if (!$user) {
                    throw new Exception('User tidak ditemukan!');
                }

                $user->password = Hash::make($newPassword);
                $user->save();

                $message = "Password berhasil diubah untuk user: {$user->email}";
                $messageType = 'success';
                break;

            case 'clear_cache':
                \Illuminate\Support\Facades\Artisan::call('cache:clear');
                \Illuminate\Support\Facades\Artisan::call('config:clear');
                \Illuminate\Support\Facades\Artisan::call('route:clear');
                \Illuminate\Support\Facades\Artisan::call('view:clear');
                $message = "Cache berhasil dibersihkan!";
                $messageType = 'success';
                break;
        }
    } catch (Exception $e) {
        $message = 'Error: ' . $e->getMessage();
        $messageType = 'error';
    }
}

// Ambil data untuk ditampilkan
$users = User::with('roles')->get();
$roles = RoleModel::all();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Tools - Notaris</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .header {
            background: white;
            padding: 30px;
            border-radius: 16px;
            margin-bottom: 30px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            color: #667eea;
            margin-bottom: 10px;
            font-size: 28px;
        }

        .header p {
            color: #666;
            font-size: 14px;
        }

        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #6ee7b7;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .alert-info {
            background: #dbeafe;
            color: #1e40af;
            border: 1px solid #93c5fd;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 24px;
            margin-bottom: 30px;
        }

        .card {
            background: white;
            padding: 28px;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }

        .card h2 {
            color: #667eea;
            margin-bottom: 20px;
            font-size: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #374151;
            font-size: 14px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px 14px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.2s;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .table-card {
            grid-column: 1 / -1;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            padding: 14px 16px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        table th {
            background: #f9fafb;
            font-weight: 600;
            color: #374151;
        }

        table td {
            color: #4b5563;
        }

        table tr:hover {
            background: #f9fafb;
        }

        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .badge-admin {
            background: #667eea;
            color: white;
        }

        .badge-user {
            background: #e5e7eb;
            color: #374151;
        }

        .warning-box {
            background: #fef3c7;
            border: 1px solid #fcd34d;
            padding: 16px;
            border-radius: 12px;
            margin-bottom: 20px;
            display: flex;
            gap: 12px;
        }

        .warning-box strong {
            color: #92400e;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>🔧 Admin Tools</h1>
            <p>Tool administratif untuk manajemen user dan sistem</p>
        </div>

        <div class="warning-box">
            <span style="font-size: 24px;">⚠️</span>
            <div>
                <strong>PERINGATAN KEAMANAN!</strong><br>
                Hapus file ini setelah selesai digunakan atau lindungi dengan password/.htaccess di production!
            </div>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-<?php echo $messageType; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <div class="grid">
            <!-- Seed Administrator -->
            <div class="card">
                <h2>👤 Buat Administrator Baru</h2>
                <form method="POST">
                    <input type="hidden" name="action" value="seed_administrator">
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" name="name" value="Administrator" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" value="admin@notarisyossi.com" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="text" name="password" value="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Buat Administrator</button>
                </form>
            </div>

            <!-- Update Is Admin -->
            <div class="card">
                <h2>✅ Ubah Status Admin</h2>
                <form method="POST">
                    <input type="hidden" name="action" value="update_is_admin">
                    <div class="form-group">
                        <label>Pilih User</label>
                        <select name="user_id" required>
                            <option value="">-- Pilih User --</option>
                            <?php foreach ($users as $user): ?>
                                <option value="<?php echo $user->id; ?>">
                                    <?php echo $user->name; ?> (<?php echo $user->email; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                            <input type="checkbox" name="is_admin" value="1">
                            Jadikan sebagai Admin
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </form>
            </div>

            <!-- Assign Role -->
            <div class="card">
                <h2>🎭 Berikan Role</h2>
                <form method="POST">
                    <input type="hidden" name="action" value="assign_role">
                    <div class="form-group">
                        <label>Pilih User</label>
                        <select name="user_id_assign" required>
                            <option value="">-- Pilih User --</option>
                            <?php foreach ($users as $user): ?>
                                <option value="<?php echo $user->id; ?>">
                                    <?php echo $user->name; ?> (<?php echo $user->email; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Pilih Role</label>
                        <select name="role_name" required>
                            <option value="">-- Pilih Role --</option>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?php echo $role->name; ?>">
                                    <?php echo $role->name; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Berikan Role</button>
                </form>
            </div>

            <!-- Change Password -->
            <div class="card">
                <h2>🔑 Ganti Password</h2>
                <form method="POST">
                    <input type="hidden" name="action" value="change_password">
                    <div class="form-group">
                        <label>Pilih User</label>
                        <select name="user_id_password" required>
                            <option value="">-- Pilih User --</option>
                            <?php foreach ($users as $user): ?>
                                <option value="<?php echo $user->id; ?>">
                                    <?php echo $user->name; ?> (<?php echo $user->email; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Password Baru</label>
                        <input type="password" name="new_password" required>
                    </div>
                    <div class="form-group">
                        <label>Konfirmasi Password Baru</label>
                        <input type="password" name="new_password_confirm" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Ganti Password</button>
                </form>
            </div>

            <!-- Clear Cache -->
            <div class="card">
                <h2>🧹 Clear Cache</h2>
                <form method="POST">
                    <input type="hidden" name="action" value="clear_cache">
                    <p style="margin-bottom: 20px; color: #666; font-size: 14px;">
                        Bersihkan semua cache aplikasi (route, config, view, dll.)
                    </p>
                    <button type="submit" class="btn btn-success">Clear Cache</button>
                </form>
            </div>
        </div>

        <!-- Daftar Users -->
        <div class="card table-card">
            <h2>📋 Daftar Semua User</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Admin</th>
                        <th>Roles</th>
                        <th>Dibuat</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td>#<?php echo $user->id; ?></td>
                            <td><strong><?php echo $user->name; ?></strong></td>
                            <td><?php echo $user->email; ?></td>
                            <td>
                                <span class="badge <?php echo $user->is_admin ? 'badge-admin' : 'badge-user'; ?>">
                                    <?php echo $user->is_admin ? 'ADMIN' : 'USER'; ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($user->roles->count() > 0): ?>
                                    <?php foreach ($user->roles as $role): ?>
                                        <span class="badge badge-admin" style="margin-right: 4px; background: #10b981;">
                                            <?php echo $role->name; ?>
                                        </span>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span style="color: #999;">-</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $user->created_at->format('d M Y'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>
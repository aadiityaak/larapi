<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class LoginHistorySeeder extends Seeder
{
  public function run(): void
  {
    if (!Schema::hasTable('login_histories')) {
      return;
    }

    DB::table('login_histories')->delete();

    $userAgents = [
      'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
      'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.4 Safari/605.1.15',
      'Mozilla/5.0 (Linux; Android 14; Pixel 7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Mobile Safari/537.36',
      'Mozilla/5.0 (iPhone; CPU iPhone OS 17_4 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.4 Mobile/15E148 Safari/604.1',
    ];

    $rows = [];
    $now = now();

    foreach (User::query()->select('id')->cursor() as $user) {
      $loginCount = random_int(0, 60);

      for ($i = 0; $i < $loginCount; $i++) {
        $daysAgo = random_int(0, 60);
        $minutesAgo = random_int(0, 24 * 60);
        $createdAt = $now->copy()->subDays($daysAgo)->subMinutes($minutesAgo);

        $rows[] = [
          'user_id' => $user->id,
          'ip_address' => '192.168.' . random_int(0, 255) . '.' . random_int(1, 254),
          'user_agent' => $userAgents[array_rand($userAgents)],
          'guard' => 'web',
          'session_id' => Str::random(40),
          'created_at' => $createdAt,
          'updated_at' => $createdAt,
        ];

        if (count($rows) >= 1000) {
          DB::table('login_histories')->insert($rows);
          $rows = [];
        }
      }
    }

    if (count($rows) > 0) {
      DB::table('login_histories')->insert($rows);
    }
  }
}


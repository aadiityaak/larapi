<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class PostSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $faker = Faker::create('id_ID'); // Indonesian locale

    // Get all users and categories
    $users = User::all();
    $categories = Category::all();

    // If no categories exist, create some basic ones
    if ($categories->isEmpty()) {
      $this->call(CategorySeeder::class);
      $categories = Category::all();
    }

    // If no users exist, we can't create posts
    if ($users->isEmpty()) {
      $this->command->info('No users found. Please seed users first.');
      return;
    }

    // Sample article titles and content in Indonesian
    $articleTitles = [
      'Tips Memulai Bisnis Online di Era Digital',
      'Panduan Lengkap Belajar Programming untuk Pemula',
      'Resep Masakan Indonesia yang Mudah dan Lezat',
      'Destinasi Wisata Tersembunyi di Indonesia',
      'Manfaat Olahraga Rutin untuk Kesehatan Mental',
      'Tren Fashion Terkini di Indonesia',
      'Cara Mengoptimalkan SEO Website untuk Bisnis',
      'Review Gadget Terbaru 2025',
      'Tips Mengelola Keuangan Pribadi dengan Baik',
      'Perkembangan Teknologi AI di Indonesia',
      'Wisata Kuliner Nusantara yang Wajib Dicoba',
      'Strategi Marketing Digital yang Efektif',
      'Pentingnya Literasi Digital di Era Modern',
      'Cara Memulai Investasi untuk Generasi Milenial',
      'Tren Arsitektur Modern di Indonesia',
    ];

    $contentTemplates = [
      'Di era digital seperti sekarang ini, banyak peluang yang bisa dimanfaatkan untuk mengembangkan bisnis atau karier. Artikel ini akan membahas berbagai aspek penting yang perlu diketahui, mulai dari dasar-dasar hingga tips praktis yang bisa langsung diterapkan.',
      'Perkembangan teknologi yang pesat telah mengubah cara kita bekerja dan berinteraksi. Dalam konteks ini, penting untuk memahami berbagai tools dan strategi yang dapat membantu kita beradaptasi dengan perubahan zaman.',
      'Indonesia memiliki kekayaan budaya dan tradisi yang luar biasa. Hal ini tercermin dalam berbagai aspek kehidupan, mulai dari kuliner, seni, hingga cara hidup masyarakatnya. Mari kita eksplorasi lebih dalam tentang keunikan Indonesia.',
      'Kesehatan dan well-being menjadi prioritas utama di masa modern ini. Dengan gaya hidup yang semakin sibuk, penting untuk menemukan keseimbangan antara produktivitas dan kesehatan mental serta fisik.',
      'Dunia bisnis terus berkembang dengan munculnya berbagai inovasi dan teknologi baru. Para entrepreneur perlu memahami tren terkini dan strategi yang efektif untuk dapat bersaing di pasar yang kompetitif.',
    ];

    // Create 50 sample posts
    for ($i = 0; $i < 50; $i++) {
      $title = $faker->randomElement($articleTitles) . ' - ' . $faker->words(2, true);
      $slug = Str::slug($title);

      // Make sure slug is unique
      $originalSlug = $slug;
      $counter = 1;
      while (Post::where('slug', $slug)->exists()) {
        $slug = $originalSlug . '-' . $counter;
        $counter++;
      }

      $content = $faker->randomElement($contentTemplates);
      $content .= "\n\n" . $faker->paragraphs(4, true);
      $content .= "\n\n## Kesimpulan\n\n" . $faker->paragraph(3);

      Post::create([
        'title' => $title,
        'content' => $content,
        'slug' => $slug,
        'user_id' => $users->random()->id,
        'category_id' => $categories->random()->id,
        'featured_image' => $faker->optional(0.7)->imageUrl(800, 400, 'business'),
        'created_at' => $faker->dateTimeBetween('-6 months', 'now'),
        'updated_at' => now(),
      ]);
    }

    $this->command->info('50 posts have been created successfully!');
  }
}

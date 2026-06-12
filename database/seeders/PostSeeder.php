<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
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

    $additionalParagraphs = [
      'Salah satu hal yang penting untuk diperhatikan adalah konsistensi dalam menjalankan setiap langkah. Tanpa konsistensi, bahkan rencana terbaik pun tidak akan memberikan hasil yang optimal.',
      'Banyak orang seringkali menghadapi tantangan di awal perjalanan mereka. Namun, dengan semangat yang tidak mudah menyerah dan kemauan untuk terus belajar, hambatan tersebut bisa diatasi.',
      'Teknologi telah membawa banyak perubahan positif dalam berbagai sektor kehidupan. Mulai dari akses informasi yang lebih mudah hingga cara berkomunikasi yang lebih efektif.',
      'Penting untuk selalu membuka wawasan dan mengikuti perkembangan terbaru. Hal ini akan membantu kita untuk tetap relevan dan kompetitif di tengah persaingan yang ketat.',
      'Kolaborasi dan kerja sama tim menjadi kunci kesuksesan dalam banyak bidang. Dengan bekerja bersama, kita bisa mencapai hal-hal yang mungkin sulit dilakukan sendirian.',
    ];

    // Create sample posts
    $postCount = 0;
    foreach ($articleTitles as $index => $title) {
      $slug = Str::slug($title);
      
      // Make sure slug is unique
      $originalSlug = $slug;
      $counter = 1;
      while (Post::where('slug', $slug)->exists()) {
        $slug = $originalSlug . '-' . $counter;
        $counter++;
      }

      $content = $contentTemplates[$index % count($contentTemplates)];
      $content .= "\n\n" . $additionalParagraphs[$index % count($additionalParagraphs)];
      $content .= "\n\n" . $additionalParagraphs[($index + 1) % count($additionalParagraphs)];
      $content .= "\n\n## Kesimpulan\n\n" . $additionalParagraphs[($index + 2) % count($additionalParagraphs)];

      Post::firstOrCreate(
        ['slug' => $slug],
        [
          'title' => $title,
          'content' => $content,
          'user_id' => $users[$index % count($users)]->id,
          'category_id' => $categories[$index % count($categories)]->id,
          'featured_image' => null,
          'created_at' => now()->subDays($index),
          'updated_at' => now()->subDays($index),
        ]
      );
      $postCount++;
    }

    $this->command->info($postCount . ' posts have been created successfully!');
  }
}

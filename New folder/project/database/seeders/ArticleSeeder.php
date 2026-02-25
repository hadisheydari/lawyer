<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Lawyer;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $babak = Lawyer::where('slug', 'babak-abdali')->first();
        $zahra = Lawyer::where('slug', 'zahra-joshghani')->first();

        $articles = [
            [
                'title'        => 'راهنمای جامع مطالبه مهریه در سال ۱۴۰۴',
                'slug'         => 'mahrieh-1404',
                'category'     => 'حقوق خانواده',
                'excerpt'      => 'مهریه یکی از حقوق قانونی زن در نکاح است. در این مقاله مراحل مطالبه، اجرائیه ثبت و دادگاه را کامل توضیح می‌دهیم.',
                'body'         => '<p>مهریه عبارت است از مالی که به هنگام عقد ازدواج، زوج به زوجه تملیک می‌کند...</p><p>زن می‌تواند مهریه را از دو طریق مطالبه کند: از طریق اجرای ثبت (بدون دادگاه) یا از طریق دادگاه خانواده.</p><h2>مرحله اول: تهیه مدارک</h2><p>مدارک لازم: سند نکاحیه، کپی شناسنامه‌ها و اگر مهریه عین معین باشد، سند مالکیت.</p>',
                'lawyer_id'    => $zahra?->id,
                'read_minutes' => 8,
                'is_published' => true,
                'published_at' => now()->subDays(12),
                'views'        => 342,
            ],
            [
                'title'        => 'قانون جدید چک‌های صیادی و روش رفع سوءاثر',
                'slug'         => 'sayyadi-check',
                'category'     => 'چک و برات',
                'excerpt'      => 'با اجرای کامل قانون جدید چک، نحوه ثبت، واگذاری و اعتراض به چک‌های برگشتی تغییر کرده. راهنمای کامل اینجاست.',
                'body'         => '<p>سیستم صیاد (سامانه یکپارچه صدور چک) از سال ۱۴۰۰ اجرایی شد...</p><p>چک‌های قدیمی تا پایان سال ۱۴۰۳ اعتبار قانونی داشتند.</p>',
                'lawyer_id'    => $babak?->id,
                'read_minutes' => 5,
                'is_published' => true,
                'published_at' => now()->subDays(19),
                'views'        => 218,
            ],
            [
                'title'        => 'راهنمای خرید ملک: نکاتی که قبل از امضا باید بدانید',
                'slug'         => 'real-estate-guide',
                'category'     => 'دعاوی ملکی',
                'excerpt'      => 'خرید ملک از پیچیده‌ترین معاملات است. ۱۵ نکته‌ای که هر خریدار باید قبل از امضای قرارداد چک کند.',
                'body'         => '<p>بررسی وضعیت ثبتی ملک اولین و مهم‌ترین قدم است...</p>',
                'lawyer_id'    => $babak?->id,
                'read_minutes' => 6,
                'is_published' => true,
                'published_at' => now()->subDays(27),
                'views'        => 189,
            ],
            [
                'title'        => 'دیه سال ۱۴۰۴: مبلغ رسمی و نحوه محاسبه',
                'slug'         => 'dieh-1404',
                'category'     => 'حقوق کیفری',
                'excerpt'      => 'قوه قضاییه مبلغ دیه سال ۱۴۰۴ را اعلام کرد. نحوه محاسبه دیه کامل، نقص عضو و دیه ماه‌های حرام را بخوانید.',
                'body'         => '<p>دیه سال ۱۴۰۴ بر اساس مصوبه قوه قضاییه یک میلیارد تومان تعیین شد...</p>',
                'lawyer_id'    => $babak?->id,
                'read_minutes' => 4,
                'is_published' => true,
                'published_at' => now()->subDays(40),
                'views'        => 521,
            ],
            [
                'title'        => 'انحصار وراثت: مراحل، مدارک و هزینه‌ها',
                'slug'         => 'inheritance-steps',
                'category'     => 'ارث و ترکه',
                'excerpt'      => 'پس از فوت هر شخص، وراث باید گواهی انحصار وراثت اخذ کنند. مراحل کامل از مراجعه اول تا دریافت گواهی.',
                'body'         => '<p>انحصار وراثت فرآیندی قانونی است که طی آن دادگاه وراث متوفی را مشخص می‌کند...</p>',
                'lawyer_id'    => $zahra?->id,
                'read_minutes' => 7,
                'is_published' => true,
                'published_at' => now()->subDays(65),
                'views'        => 275,
            ],
        ];

        foreach ($articles as $data) {
            Article::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }
}

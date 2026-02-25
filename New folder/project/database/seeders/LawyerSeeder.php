<?php

namespace Database\Seeders;

use App\Models\Lawyer;
use Illuminate\Database\Seeder;

class LawyerSeeder extends Seeder
{
    public function run(): void
    {
        $lawyers = [
            [
                'name'                 => 'بابک ابدالی',
                'slug'                 => 'babak-abdali',
                'title'                => 'وکیل پایه یک دادگستری',
                'bar_number'           => '۱۲۳۴۵',
                'bar_association'      => 'کانون وکلای دادگستری اصفهان',
                'bio'                  => 'بابک ابدالی با بیش از ۲۸ سال سابقه وکالت، متخصص در دعاوی تجاری، ملکی، کیفری و اداری است. ایشان فارغ‌التحصیل دکترای حقوق خصوصی از دانشگاه تهران بوده و دارای سابقه مشاوره به شرکت‌های بزرگ صنعتی و تجاری در اصفهان و تهران می‌باشند. وی به دلیل دقت حرفه‌ای، صداقت و پشتکار در دفاع از موکلین شهرت گسترده‌ای دارد.',
                'phone'                => '09131146888',
                'whatsapp'             => '09131146888',
                'email'                => 'babak@abdali-law.ir',
                'photo'                => 'lawyers/babak.jpg',
                'experience_years'     => 28,
                'cases_count'          => 1200,
                'satisfaction_percent' => 97,
                'specialties'          => ['دعاوی تجاری', 'دعاوی ملکی', 'حقوق کیفری', 'حقوق اداری'],
                'is_active'            => true,
                'sort_order'           => 1,
            ],
            [
                'name'                 => 'زهرا جوشقانی',
                'slug'                 => 'zahra-joshghani',
                'title'                => 'وکیل پایه یک دادگستری',
                'bar_number'           => '۶۷۸۹۰',
                'bar_association'      => 'کانون وکلای دادگستری اصفهان',
                'bio'                  => 'زهرا جوشقانی با ۲۰ سال تجربه در حوزه حقوق خانواده، یکی از برجسته‌ترین وکلای متخصص در دعاوی مهریه، طلاق، حضانت، ارث و انحصار وراثت در اصفهان است. ایشان با رویکردی همدلانه و حرفه‌ای، بیش از ۸۰۰ پرونده خانوادگی را با موفقیت به سرانجام رسانده‌اند. خانم جوشقانی همچنین از مشاوران حقوقی مراکز حمایت از زنان اصفهان می‌باشند.',
                'phone'                => '09132888859',
                'whatsapp'             => '09132888859',
                'email'                => 'zahra@abdali-law.ir',
                'photo'                => 'lawyers/zahra.jpg',
                'experience_years'     => 20,
                'cases_count'          => 800,
                'satisfaction_percent' => 99,
                'specialties'          => ['حقوق خانواده', 'ارث و ترکه', 'نفقه', 'انحصار وراثت'],
                'is_active'            => true,
                'sort_order'           => 2,
            ],
        ];

        foreach ($lawyers as $data) {
            Lawyer::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }
}

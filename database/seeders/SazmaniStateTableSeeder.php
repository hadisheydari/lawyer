<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SazmaniStateTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('sazmaniState')->delete();
        
        \DB::table('sazmaniState')->insert(array (
            0 => 
            array (
                'ID' => 1,
                'sazmaniStateXID' => '10',
                'sazmaniStateName' => 'تهران',
                'created_at' => '2024-05-07 12:00:01',
            ),
            1 => 
            array (
                'ID' => 2,
                'sazmaniStateXID' => '62',
                'sazmaniStateName' => 'قم',
                'created_at' => '2024-05-07 12:00:01',
            ),
            2 => 
            array (
                'ID' => 3,
                'sazmaniStateXID' => '63',
                'sazmaniStateName' => 'قزوین',
                'created_at' => '2024-05-07 12:00:01',
            ),
            3 => 
            array (
                'ID' => 4,
                'sazmaniStateXID' => '12',
                'sazmaniStateName' => 'مازندران',
                'created_at' => '2024-05-07 12:00:01',
            ),
            4 => 
            array (
                'ID' => 5,
                'sazmaniStateXID' => '49',
                'sazmaniStateName' => 'البرز',
                'created_at' => '2024-05-07 12:00:01',
            ),
            5 => 
            array (
                'ID' => 6,
                'sazmaniStateXID' => '20',
                'sazmaniStateName' => 'اصفهان',
                'created_at' => '2024-05-07 12:00:01',
            ),
            6 => 
            array (
                'ID' => 7,
                'sazmaniStateXID' => '13',
                'sazmaniStateName' => 'آذربایجان شرقی',
                'created_at' => '2024-05-07 12:00:01',
            ),
            7 => 
            array (
                'ID' => 8,
                'sazmaniStateXID' => '19',
                'sazmaniStateName' => 'خراسان رضوی',
                'created_at' => '2024-05-07 12:00:01',
            ),
            8 => 
            array (
                'ID' => 9,
                'sazmaniStateXID' => '21',
                'sazmaniStateName' => 'خراسان شمالی',
                'created_at' => '2024-05-07 12:00:01',
            ),
            9 => 
            array (
                'ID' => 10,
                'sazmaniStateXID' => '31',
                'sazmaniStateName' => 'خراسان جنوبی',
                'created_at' => '2024-05-07 12:00:01',
            ),
            10 => 
            array (
                'ID' => 11,
                'sazmaniStateXID' => '16',
                'sazmaniStateName' => 'خوزستان',
                'created_at' => '2024-05-07 12:00:01',
            ),
            11 => 
            array (
                'ID' => 12,
                'sazmaniStateXID' => '17',
                'sazmaniStateName' => 'فارس',
                'created_at' => '2024-05-07 12:00:01',
            ),
            12 => 
            array (
                'ID' => 13,
                'sazmaniStateXID' => '18',
                'sazmaniStateName' => 'کرمان',
                'created_at' => '2024-05-07 12:00:01',
            ),
            13 => 
            array (
                'ID' => 14,
                'sazmaniStateXID' => '44',
                'sazmaniStateName' => 'مرکزی',
                'created_at' => '2024-05-07 12:00:01',
            ),
            14 => 
            array (
                'ID' => 15,
                'sazmaniStateXID' => '55',
                'sazmaniStateName' => 'گیلان',
                'created_at' => '2024-05-07 12:00:01',
            ),
            15 => 
            array (
                'ID' => 16,
                'sazmaniStateXID' => '14',
                'sazmaniStateName' => 'آذربایجان غربی',
                'created_at' => '2024-05-07 12:00:01',
            ),
            16 => 
            array (
                'ID' => 17,
                'sazmaniStateXID' => '66',
                'sazmaniStateName' => 'سیستان و بلوچستان',
                'created_at' => '2024-05-07 12:00:01',
            ),
            17 => 
            array (
                'ID' => 18,
                'sazmaniStateXID' => '23',
                'sazmaniStateName' => 'هرمزگان',
                'created_at' => '2024-05-07 12:00:01',
            ),
            18 => 
            array (
                'ID' => 19,
                'sazmaniStateXID' => '29',
                'sazmaniStateName' => 'زنجان',
                'created_at' => '2024-05-07 12:00:01',
            ),
            19 => 
            array (
                'ID' => 20,
                'sazmaniStateXID' => '15',
                'sazmaniStateName' => 'کرمانشاه',
                'created_at' => '2024-05-07 12:00:01',
            ),
            20 => 
            array (
                'ID' => 21,
                'sazmaniStateXID' => '22',
                'sazmaniStateName' => 'کردستان',
                'created_at' => '2024-05-07 12:00:01',
            ),
            21 => 
            array (
                'ID' => 22,
                'sazmaniStateXID' => '24',
                'sazmaniStateName' => 'همدان',
                'created_at' => '2024-05-07 12:00:01',
            ),
            22 => 
            array (
                'ID' => 23,
                'sazmaniStateXID' => '25',
                'sazmaniStateName' => 'چهار محال و بختیاری',
                'created_at' => '2024-05-07 12:00:01',
            ),
            23 => 
            array (
                'ID' => 24,
                'sazmaniStateXID' => '26',
                'sazmaniStateName' => 'لرستان',
                'created_at' => '2024-05-07 12:00:01',
            ),
            24 => 
            array (
                'ID' => 25,
                'sazmaniStateXID' => '27',
                'sazmaniStateName' => 'ایلام',
                'created_at' => '2024-05-07 12:00:01',
            ),
            25 => 
            array (
                'ID' => 26,
                'sazmaniStateXID' => '28',
                'sazmaniStateName' => 'کهگیلویه و بویراحمد',
                'created_at' => '2024-05-07 12:00:01',
            ),
            26 => 
            array (
                'ID' => 27,
                'sazmaniStateXID' => '99',
                'sazmaniStateName' => 'سمنان',
                'created_at' => '2024-05-07 12:00:01',
            ),
            27 => 
            array (
                'ID' => 28,
                'sazmaniStateXID' => '61',
                'sazmaniStateName' => 'اردبیل',
                'created_at' => '2024-05-07 12:00:01',
            ),
            28 => 
            array (
                'ID' => 29,
                'sazmaniStateXID' => '77',
                'sazmaniStateName' => 'یزد',
                'created_at' => '2024-05-07 12:00:01',
            ),
            29 => 
            array (
                'ID' => 30,
                'sazmaniStateXID' => '88',
                'sazmaniStateName' => 'بوشهر',
                'created_at' => '2024-05-07 12:00:01',
            ),
            30 => 
            array (
                'ID' => 31,
                'sazmaniStateXID' => '64',
                'sazmaniStateName' => 'گلستان',
                'created_at' => '2024-05-07 12:00:01',
            ),
            31 => 
            array (
                'ID' => 32,
                'sazmaniStateXID' => '97',
                'sazmaniStateName' => 'ترکیه',
                'created_at' => '2024-05-07 12:00:01',
            ),
            32 => 
            array (
                'ID' => 33,
                'sazmaniStateXID' => '98',
                'sazmaniStateName' => 'روسیه',
                'created_at' => '2024-05-07 12:00:01',
            ),
        ));
        
        
    }
}
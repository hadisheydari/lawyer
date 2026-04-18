<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CaseSeeder extends Seeder
{
    public function run(): void
    {
        $users = DB::table('users')->pluck('id')->toArray();
        $lawyers = DB::table('lawyers')->pluck('id')->toArray();
        $services = DB::table('services')->pluck('id')->toArray();

        for ($i = 1; $i <= 5; $i++) {

            $caseId = DB::table('cases')->insertGetId([
                'case_number' => 'LAW-1404-00'.$i,
                'user_id' => $users[array_rand($users)],
                'lawyer_id' => $lawyers[array_rand($lawyers)],
                'service_id' => $services ? $services[array_rand($services)] : null,
                'title' => 'پرونده نمونه شماره '.$i,
                'description' => 'توضیحات تست برای پرونده '.$i,
                'current_status' => 'active',
                'total_fee' => 50000000,
                'paid_amount' => 20000000,
                'opened_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // ─── status logs ───
            $status1 = DB::table('case_status_logs')->insertGetId([
                'case_id' => $caseId,
                'lawyer_id' => $lawyers[0],
                'status_title' => 'ثبت اولیه پرونده',
                'description' => 'پرونده در سیستم ثبت شد.',
                'status_date' => Carbon::now()->subDays(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $status2 = DB::table('case_status_logs')->insertGetId([
                'case_id' => $caseId,
                'lawyer_id' => $lawyers[0],
                'status_title' => 'ارجاع به شعبه',
                'description' => 'پرونده به شعبه ۱۲ ارجاع شد.',
                'status_date' => Carbon::now()->subDays(5),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // ─── documents ───
            DB::table('case_documents')->insert([
                [
                    'case_id' => $caseId,
                    'status_log_id' => $status1,
                    'title' => 'دادخواست',
                    'file_path' => 'documents/sample1.pdf',
                    'file_type' => 'pdf',
                    'file_size' => 120000,
                    'uploader_type' => 'lawyer',
                    'uploader_id' => $lawyers[0],
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'case_id' => $caseId,
                    'status_log_id' => $status2,
                    'title' => 'ابلاغیه دادگاه',
                    'file_path' => 'documents/sample2.pdf',
                    'file_type' => 'pdf',
                    'file_size' => 150000,
                    'uploader_type' => 'lawyer',
                    'uploader_id' => $lawyers[0],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);

            // ─── installments ───
            DB::table('case_installments')->insert([
                [
                    'case_id' => $caseId,
                    'user_id' => $users[0],
                    'installment_number' => 1,
                    'amount' => 20000000,
                    'due_date' => Carbon::now()->addDays(10),
                    'status' => 'paid',
                    'paid_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'case_id' => $caseId,
                    'user_id' => $users[0],
                    'installment_number' => 2,
                    'amount' => 15000000,
                    'due_date' => Carbon::now()->addDays(30),
                    'status' => 'pending',
                    'paid_at' => null,

                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'case_id' => $caseId,
                    'user_id' => $users[0],
                    'installment_number' => 3,
                    'amount' => 15000000,
                    'due_date' => Carbon::now()->addDays(60),
                    'status' => 'pending',
                    'paid_at' => null,

                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
        }
    }
}

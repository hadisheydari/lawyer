<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    public function index()
    {
        // دریافت تمام خدمات فعال به ترتیب فیلد order
        $services = Service::active()->get();

        return view('public.services.index', compact('services'));
    }

    public function show(string $slug)
    {
        // دریافت تکی خدمت بر اساس slug
        $service = Service::where('slug', $slug)->where('is_active', true)->firstOrFail();

        // دریافت شماره تماس پشتیبانی از دیتابیس (جدول settings) - برای جلوگیری از هاردکد کردن
        // اگر در دیتابیس نبود، فعلا یک مقدار پیش‌فرض برمی‌گرداند
        $supportPhone = DB::table('settings')->where('key', 'contact.support_phone')->value('value') ?? '۰۹۱۳۱۱۴۶۸۸۸';

        return view('public.services.show', compact('service', 'supportPhone'));
    }
}
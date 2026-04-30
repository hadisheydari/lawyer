<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // دریافت اطلاعات وکیل لاگین شده (با استفاده از گارد اختصاصی lawyer)
        $lawyer = Auth::guard('lawyer')->user(); 

        // -- ۱. آمار بالای صفحه --
        // پرونده‌های فعال وکیل
        $activeCasesCount = $lawyer->cases()->active()->count();
        
        // مشاوره‌های امروز
        $todayConsultationsCount = $lawyer->consultations()
            ->whereDate('scheduled_at', Carbon::today())
            ->whereIn('status', ['confirmed', 'in_progress'])
            ->count();
            
        // درخواست‌های جدید مشاوره که هنوز تایید نشدن
        $pendingRequestsCount = $lawyer->consultations()
            ->where('status', 'pending')
            ->count();

        // محاسبه کل پیام‌های نخوانده برای این وکیل (از طریق مکالمات)
        $unreadMessagesCount = $lawyer->conversations()
            ->with('latestMessage')
            ->get()
            ->sum(fn($c) => $c->getUnreadCountFor('lawyer', $lawyer->id));

        // -- ۲. لیست مشاوره‌های پیش رو (امروز و روزهای آینده) --
        $upcomingConsultations = $lawyer->consultations()
            ->with('user') // برای نمایش اسم موکل
            ->whereDate('scheduled_at', '>=', Carbon::today())
            ->whereIn('status', ['confirmed', 'pending'])
            ->orderBy('scheduled_at', 'asc')
            ->take(5)
            ->get();

        // -- ۳. آخرین پرونده‌های فعال --
        $recentActiveCases = $lawyer->cases()
            ->with('user')
            ->active()
            ->latest()
            ->take(4)
            ->get();

        return view('lawyer.dashboard', compact(
            'lawyer',
            'activeCasesCount',
            'todayConsultationsCount',
            'pendingRequestsCount',
            'unreadMessagesCount',
            'upcomingConsultations',
            'recentActiveCases'
        ));
    }
}
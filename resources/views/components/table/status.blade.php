@php
    $colors = [
        'approved' => 'bg-green-600 text-white',
        'pending' => 'bg-yellow-500 text-white',
        'rejected' => 'bg-red-600 text-white',
        'reserve' => 'bg-blue-600 text-white',
        'free' => 'bg-indigo-600 text-white',
        'rqf' => 'bg-green-600 text-white',
        'reserved' => 'bg-blue-600 text-white',
        'havale' => 'bg-yellow-600 text-white',
        'barnameh' => 'bg-green-600 text-white',
        'delivered' => 'bg-gray-600 text-white',
        'read'=> 'bg-green-600 text-white' ,
        'not_read'=> 'bg-red-600 text-white' ,


    ];
    $labels = [
        'approved' => 'تأیید شده',
        'pending' => 'در انتظار',
        'rejected' => 'رد شده',
        'unknown' => 'تنظیم نشده',
        'reserve' => 'رزرو شده',
        'free' => 'آزاد',
        'reserved' => 'رزرو شده',
        'havale' => 'حواله شده',
        'barnameh' => 'بارنامه شده',
        'delivered' => 'تحویل داده شده',
        'read'=> 'بررسی شده' ,
        'not_read'=> 'بررسی نشده' ,

    ];

    $key = $value ?? 'unknown';
    $class = $colors[$key] ?? 'bg-gray-400 text-white';
    $label = $labels[$key] ?? $key;
@endphp

<span class="px-3 py-1 rounded-full text-xs font-semibold {{ $class }}">
    {{ $label }}
</span>

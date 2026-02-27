@extends('layouts.public')
@section('title', 'حوزه‌های تخصصی وکالت | دفتر ابدالی و جوشقانی')

@push('styles')
<style>
    /* ─── صفحه اصلی خدمات ────────────────────────────────────────── */
    .services-page { 
        padding: 70px 20px 100px; 
        max-width: 1200px; 
        margin: 0 auto; 
    }
    
    /* ─── هدر بخش ────────────────────────────────────────── */
    .services-header {
        text-align: center; 
        max-width: 700px; 
        margin: 0 auto 60px;
        position: relative;
    }
    .services-header h2 {
        font-size: clamp(1.8rem, 4vw, 2.5rem); 
        font-weight: 900;
        color: var(--navy); 
        margin-bottom: 20px; 
        line-height: 1.4;
    }
    .services-header p {
        font-size: 1.05rem; 
        color: var(--text-body); 
        line-height: 1.9;
    }
    .services-header::after {
        content: '';
        display: block;
        width: 80px;
        height: 4px;
        background: linear-gradient(90deg, var(--gold-main), var(--gold-dark));
        margin: 30px auto 0;
        border-radius: 10px;
    }

    /* ─── گرید کارت‌ها ────────────────────────────────────────── */
    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 35px;
    }
    
    /* ─── طراحی کارت لوکس ────────────────────────────────────────── */
    .premium-card {
        background: #fff;
        border-radius: 28px;
        padding: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.04);
        border: 1px solid rgba(0,0,0,0.03);
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        display: flex;
        flex-direction: column;
        text-decoration: none;
        position: relative;
    }
    
    .premium-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 25px 50px rgba(207,168,110,0.12);
        border-color: rgba(207,168,110,0.4);
    }
    
    /* بخش عکس */
    .pc-img-box {
        height: 230px;
        border-radius: 20px;
        overflow: hidden;
        position: relative;
    }
    .pc-img-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.7s ease;
    }
    .premium-card:hover .pc-img-box img {
        transform: scale(1.1);
    }
    
    /* آیکون شناور با افکت گلس */
    .pc-icon {
        position: absolute;
        bottom: 15px;
        right: 15px;
        width: 55px;
        height: 55px;
        background: rgba(255,255,255,0.85);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 1.4rem;
        color: var(--navy);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        transition: all 0.4s ease;
    }
    .premium-card:hover .pc-icon {
        background: linear-gradient(135deg, var(--navy), #1e3a5f);
        color: var(--gold-main);
        transform: scale(1.05);
    }

    /* محتوای کارت */
    .pc-content {
        padding: 25px 15px 10px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }
    .pc-title {
        font-size: 1.3rem;
        font-weight: 900;
        color: var(--text-heading);
        margin-bottom: 12px;
        transition: color 0.3s;
    }
    .premium-card:hover .pc-title {
        color: var(--gold-main);
    }
    .pc-desc {
        font-size: 0.92rem;
        color: var(--text-body);
        line-height: 1.8;
        margin-bottom: 25px;
        /* محدود کردن متن به دقیقاً 3 خط برای زیبایی یکدست */
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    /* فوتر کارت (قیمت و دکمه) */
    .pc-footer {
        margin-top: auto;
        padding-top: 15px;
        border-top: 1.5px dashed #f0ece5;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .pc-price {
        font-size: 0.88rem;
        font-weight: 800;
        color: var(--navy);
        background: #fdfbf7;
        padding: 8px 14px;
        border-radius: 10px;
        border: 1px solid rgba(207,168,110,0.25);
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .pc-price i { color: var(--gold-main); }
    
    .pc-btn {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: #f0f4f8;
        color: var(--navy);
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 1.1rem;
        transition: all 0.3s ease;
    }
    .premium-card:hover .pc-btn {
        background: var(--gold-main);
        color: #fff;
        transform: translateX(-5px);
        box-shadow: 0 5px 15px rgba(207,168,110,0.4);
    }

    /* ─── بنر تماس پایینی ────────────────────────────────────────── */
    .services-cta {
        margin-top: 80px;
        background: linear-gradient(135deg, #0a1c2e, #102a43);
        border-radius: 30px;
        padding: 60px 40px;
        text-align: center;
        color: #fff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(10,28,46,0.25);
        border: 1px solid rgba(207,168,110,0.2);
    }
    .services-cta::after {
        content: '';
        position: absolute;
        top: -50%; right: -10%;
        width: 400px; height: 400px;
        background: radial-gradient(circle, rgba(207,168,110,0.1) 0%, transparent 70%);
        border-radius: 50%; pointer-events: none;
    }
    .services-cta h3 { 
        font-size: 1.8rem; font-weight: 900; 
        color: var(--gold-main); margin-bottom: 15px; 
    }
    .services-cta p { 
        font-size: 1.05rem; color: rgba(255,255,255,0.7); 
        margin-bottom: 35px; max-width: 650px; margin-inline: auto; line-height: 1.8;
    }
    .cta-btn-gold {
        background: linear-gradient(135deg, var(--gold-main), var(--gold-dark)); 
        color: #fff;
        padding: 16px 35px; border-radius: 14px; font-weight: 800; font-size: 1.05rem;
        display: inline-flex; align-items: center; gap: 10px;
        transition: 0.3s; box-shadow: 0 10px 25px rgba(207,168,110,0.35);
        text-decoration: none; position: relative; z-index: 2;
    }
    .cta-btn-gold:hover { 
        transform: translateY(-4px); 
        box-shadow: 0 15px 30px rgba(207,168,110,0.5); 
        color: #fff; 
    }
    
    @media (max-width: 768px) {
        .services-grid { grid-template-columns: 1fr; }
        .services-cta { padding: 40px 20px; border-radius: 20px; }
        .services-header h2 { font-size: 1.8rem; }
    }
</style>
@endpush

@section('content')

{{-- بنر بالای صفحه --}}
<div class="page-banner">
    <div class="page-banner-inner">
        <h1><i class="fas fa-balance-scale" style="color:var(--gold-main);margin-left:12px;"></i>خدمات تخصصی حقوقی</h1>
        <div class="breadcrumb">
            <a href="{{ route('home') }}">صفحه اصلی</a>
            <i class="fas fa-chevron-left"></i>
            <span>حوزه‌های وکالت</span>
        </div>
    </div>
</div>

<div class="services-page">
    
    {{-- هدر توضیحات --}}
    <div class="services-header">
        <h2>دفاع از حقوق شما، تخصص و رسالت ماست</h2>
        <p>موسسه حقوقی ابدالی و جوشقانی با بهره‌گیری از دانش روز و سال‌ها تجربه موفق در محاکم دادگستری، آماده ارائه خدمات تخصصی در حوزه‌های زیر می‌باشد. روی هر خدمت کلیک کنید تا جزئیات و شرایط آن را مطالعه فرمایید.</p>
    </div>

    {{-- گرید خدمات --}}
    <div class="services-grid">
        @forelse($services as $service)
            <a href="{{ route('services.show', $service->slug) }}" class="premium-card">
                
                {{-- عکس و آیکون شناور --}}
                <div class="pc-img-box">
                    <img src="{{ $service->image ? asset('storage/' . $service->image) : 'https://images.unsplash.com/photo-1589829085413-56de8ae18c73?auto=format&fit=crop&w=600&q=80' }}" 
                         alt="{{ $service->title }}" loading="lazy"
                         onerror="this.src='https://images.unsplash.com/photo-1589829085413-56de8ae18c73?auto=format&fit=crop&w=600&q=80'">
                    
                    <div class="pc-icon">
                        <i class="{{ $service->icon ?? 'fas fa-gavel' }}"></i>
                    </div>
                </div>
                
                {{-- محتوای متنی --}}
                <div class="pc-content">
                    <h3 class="pc-title">{{ $service->title }}</h3>
                    <p class="pc-desc">
                        {{ $service->short_description ?? strip_tags($service->description) }}
                    </p>
                    
                    {{-- بخش قیمت و دکمه --}}
                    <div class="pc-footer">

                        <div class="pc-btn">
                            <i class="fas fa-arrow-left"></i>
                        </div>
                    </div>
                </div>
                
            </a>
        @empty
            {{-- در صورت خالی بودن دیتابیس --}}
            <div style="grid-column: 1 / -1; text-align: center; padding: 80px 20px; background: #fff; border-radius: 28px; border: 1px dashed #ddd; box-shadow: 0 10px 30px rgba(0,0,0,0.02);">
                <i class="fas fa-folder-open" style="font-size: 4rem; color: rgba(207,168,110,0.3); margin-bottom: 20px;"></i>
                <h3 style="color: var(--text-heading); margin-bottom: 10px; font-size: 1.4rem; font-weight: 800;">هنوز خدمتی ثبت نشده است!</h3>
                <p style="color: var(--text-body);">در حال حاضر دسته‌بندی خدمات در سیستم وارد نشده است.</p>
            </div>
        @endforelse
    </div>

    {{-- بنر دعوت به اقدام پایین صفحه --}}
    @if($services->isNotEmpty())
    <div class="services-cta">
        <h3>موضوع پرونده شما در لیست بالا نیست؟</h3>
        <p>دنیای حقوق بسیار گسترده است. اگر مشکل حقوقی شما در دسته‌بندی‌های بالا قرار نمی‌گیرد یا نیاز به بررسی تخصصی و اورژانسی دارید، نگران نباشید. همین حالا برای بررسی شرایط پرونده خود با ما در ارتباط باشید.</p>
        <a href="{{ route('reserve.index') }}" class="cta-btn-gold">
            <i class="fas fa-calendar-check"></i> درخواست وقت مشاوره اختصاصی
        </a>
    </div>
    @endif

</div>

@endsection
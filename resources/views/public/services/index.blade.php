@extends('layouts.public')
@section('title', $service->title . ' | دفتر ابدالی و جوشقانی')

@push('styles')
<style>
    .service-hero {
        background: linear-gradient(135deg, var(--navy) 0%, #1a3a58 100%);
        padding: 80px 20px; text-align: center; color: #fff;
        border-bottom: 4px solid var(--gold-main);
    }
    .service-hero-icon {
        width: 80px; height: 80px; background: rgba(207,168,110,0.1);
        border: 2px solid var(--gold-main); border-radius: 20px; transform: rotate(45deg);
        display: flex; align-items: center; justify-content: center; margin: 0 auto 30px;
    }
    .service-hero-icon i { transform: rotate(-45deg); font-size: 2.5rem; color: var(--gold-main); }
    
    .service-container {
        max-width: 1200px; margin: 50px auto 100px; padding: 0 20px;
        display: grid; grid-template-columns: 2fr 1fr; gap: 40px; align-items: start;
    }
    
    .service-content-box {
        background: var(--bg-white); padding: 40px; border-radius: var(--radius-md);
        box-shadow: var(--shadow-card); border: 1px solid rgba(0,0,0,0.03);
    }
    .service-content-box h2 { color: var(--navy); margin-bottom: 20px; font-size: 1.6rem; }
    .service-content-box .content-body { line-height: 2; color: var(--text-body); font-size: 1.05rem; }
    
    .service-sidebar {
        position: sticky; top: 100px; display: flex; flex-direction: column; gap: 20px;
    }
    
    .price-card {
        background: var(--bg-white); padding: 30px; border-radius: var(--radius-md);
        box-shadow: var(--shadow-card); text-align: center;
        border-top: 4px solid var(--gold-main);
    }
    .price-card .price-label { color: var(--text-body); font-size: 0.9rem; margin-bottom: 10px; display: block; }
    .price-card .price-amount { color: var(--navy); font-size: 1.5rem; font-weight: 900; margin-bottom: 20px; }
    .price-card .btn-reserve {
        display: block; width: 100%; padding: 15px; border-radius: 12px;
        background: linear-gradient(135deg, var(--gold-main), var(--gold-dark));
        color: #fff; font-weight: 800; font-size: 1.1rem; box-shadow: 0 8px 20px rgba(207,168,110,0.3);
    }
    .price-card .btn-reserve:hover { transform: translateY(-3px); box-shadow: 0 12px 25px rgba(207,168,110,0.5); }
    
    @media (max-width: 900px) {
        .service-container { grid-template-columns: 1fr; }
        .service-sidebar { position: static; order: -1; } /* در موبایل کارت قیمت می‌آید بالا */
    }
</style>
@endpush

@section('content')

<section class="service-hero">
    <div class="service-hero-icon"><i class="{{ $service->icon ?? 'fas fa-balance-scale' }}"></i></div>
    <h1 style="font-size: 2.5rem; font-weight: 900; margin-bottom: 15px;">{{ $service->title }}</h1>
    <p style="font-size: 1.1rem; opacity: 0.8; max-width: 700px; margin: 0 auto;">{{ $service->short_description }}</p>
</section>

<div class="service-container">
    
    <div class="service-content-box">
        <h2>شرح خدمات در این حوزه</h2>
        <div class="content-body">
            {!! nl2br(e($service->description)) !!}
        </div>
        
        @if($service->features && is_array($service->features))
        <div style="margin-top: 40px; padding: 25px; background: var(--bg-body); border-radius: 12px; border-right: 4px solid var(--gold-main);">
            <h3 style="color: var(--navy); margin-bottom: 15px; font-size: 1.2rem;">ویژگی‌های این خدمت</h3>
            <ul style="list-style: none; padding: 0; display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                @foreach($service->features as $feature)
                    <li style="display: flex; align-items: center; gap: 10px; color: var(--text-body);">
                        <i class="fas fa-check-circle" style="color: var(--gold-main);"></i> {{ $feature }}
                    </li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>

    <div class="service-sidebar">
        <div class="price-card">
            <span class="price-label">هزینه پایه خدمات</span>
            <div class="price-amount">
                {{ $service->formatted_price }}
            </div>
            
            <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
            
            <p style="font-size: 0.85rem; color: #777; margin-bottom: 20px; line-height: 1.6;">
                برای بررسی دقیق‌تر پرونده و اعلام هزینه نهایی، نیاز به مشاوره اولیه داریم.
            </p>
            
            <a href="{{ route('reserve.index') }}?service_id={{ $service->id }}" class="btn-reserve">
                رزرو وقت مشاوره <i class="fas fa-calendar-check" style="margin-right: 8px;"></i>
            </a>
        </div>

        <div class="price-card" style="border-top-color: var(--navy); background: var(--navy); color: #fff; text-align: right;">
            <h3 style="color: var(--gold-main); font-size: 1.2rem; margin-bottom: 15px;">پشتیبانی فوری</h3>
            <p style="font-size: 0.9rem; opacity: 0.8; margin-bottom: 20px;">در صورت نیاز به اقدام حقوقی اورژانسی، مستقیماً تماس بگیرید.</p>
            <a href="tel:09131146888" style="display: flex; align-items: center; justify-content: center; gap: 10px; background: rgba(255,255,255,0.1); padding: 12px; border-radius: 8px; color: #fff; font-weight: bold; border: 1px solid rgba(255,255,255,0.2);">
                <i class="fas fa-phone-alt"></i> ۰۹۱۳۱۱۴۶۸۸۸
            </a>
        </div>
    </div>

</div>

@endsection
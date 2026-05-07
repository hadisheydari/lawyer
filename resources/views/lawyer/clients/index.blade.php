@extends('layouts.lawyer')
@section('title', 'نوبت‌های مشاوره')

@push('styles')
<style>
    /* --- Styles from plan-for-lawyers --- */
    .filter-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        gap: 15px;
        flex-wrap: wrap;
    }

    .status-tabs {
        display: flex;
        background: #fff;
        padding: 5px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    }

    .tab-link {
        padding: 10px 20px;
        border-radius: 10px;
        text-decoration: none;
        color: #64748b;
        font-weight: 700;
        font-size: 0.85rem;
        transition: 0.3s;
    }

    .tab-link.active {
        background: var(--navy);
        color: #fff;
    }

    /* --- Table Card --- */
    .table-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        overflow: hidden;
    }

    .custom-table { width: 100%; border-collapse: collapse; }
    .custom-table th {
        text-align: right; padding: 18px 20px;
        background: #f8fafc; color: #475569;
        font-size: 0.85rem; font-weight: 800;
        border-bottom: 1px solid #edf2f7;
    }
    .custom-table td {
        padding: 18px 20px; border-bottom: 1px solid #f1f5f9;
        color: var(--navy); font-size: 0.9rem; vertical-align: middle;
    }
    .custom-table tr:hover { background: #fdfbf7; }

    /* --- Status Badges --- */
    .badge {
        padding: 6px 12px; border-radius: 20px;
        font-size: 0.75rem; font-weight: 800;
    }
    .badge-pending { background: #fef3c7; color: #92400e; }
    .badge-confirmed { background: #d1fae5; color: #065f46; }
    .badge-completed { background: #e0e7ff; color: #3730a3; }
    .badge-cancelled { background: #fee2e2; color: #991b1b; }

    .client-cell { display: flex; align-items: center; gap: 12px; }
    .client-avatar {
        width: 40px; height: 40px; border-radius: 50%;
        background: var(--navy-light); color: var(--gold-main);
        display: flex; align-items: center; justify-content: center;
        font-weight: 900; font-size: 0.8rem;
    }

    .action-btn {
        width: 35px; height: 35px; border-radius: 8px;
        display: inline-flex; align-items: center; justify-content: center;
        text-decoration: none; transition: 0.3s; background: #f1f5f9; color: #64748b;
    }
    .action-btn:hover { background: var(--gold-main); color: #fff; }

    /* --- Mobile Responsive (The transformation) --- */
    @media (max-width: 768px) {
        .custom-table thead { display: none; }
        .custom-table tr {
            display: block; margin-bottom: 15px;
            border: 1px solid #edf2f7; border-radius: 12px;
            padding: 10px; position: relative;
        }
        .custom-table td {
            display: flex; justify-content: space-between; align-items: center;
            border: none; padding: 8px 10px; text-align: left;
        }
        .custom-table td::before {
            content: attr(data-label); font-weight: 800; color: #94a3b8; font-size: 0.8rem;
        }
        .client-cell { flex-direction: row-reverse; }
        .action-btn { width: 100%; margin-top: 10px; justify-content: center; gap: 8px; }
        .action-btn::after { content: 'مشاهده جزئیات'; font-size: 0.85rem; font-weight: bold; }
    }
</style>
@endpush

@section('content')
<div class="filter-section">
    <div class="status-tabs">
        <a href="{{ route('lawyer.consultations.index') }}" class="tab-link {{ !request('status') ? 'active' : '' }}">همه</a>
        <a href="{{ route('lawyer.consultations.index', ['status' => 'pending']) }}" class="tab-link {{ request('status') == 'pending' ? 'active' : '' }}">در انتظار</a>
        <a href="{{ route('lawyer.consultations.index', ['status' => 'confirmed']) }}" class="tab-link {{ request('status') == 'confirmed' ? 'active' : '' }}">تایید شده</a>
    </div>

    <form action="{{ route('lawyer.consultations.index') }}" method="GET" style="flex: 1; max-width: 300px;">
        <div style="position: relative;">
            <input type="text" name="search" value="{{ request('search') }}" 
                   placeholder="جستجوی نام موکل..." 
                   style="width:100%; padding: 10px 15px; border-radius: 10px; border: 1px solid #e2e8f0; font-family: inherit;">
        </div>
    </form>
</div>

<div class="table-card">
    @if($consultations->isEmpty())
        <div style="padding: 50px; text-align: center; color: #94a3b8;">
            <i class="fas fa-calendar-times" style="font-size: 3rem; margin-bottom: 15px; display: block;"></i>
            <p>هیچ نوبت مشاوره‌ای در این بخش یافت نشد.</p>
        </div>
    @else
        <table class="custom-table">
            <thead>
                <tr>
                    <th>موکل</th>
                    <th>نوع مشاوره</th>
                    <th>تاریخ و ساعت</th>
                    <th>وضعیت</th>
                    <th>عملیات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($consultations as $item)
                <tr>
                    <td data-label="موکل">
                        <div class="client-cell">
                            <div class="client-avatar">
                                {{ mb_substr($item->user->name ?? 'م', 0, 1) }}
                            </div>
                            <div>
                                <div style="font-weight: 800;">{{ $item->user->name ?? 'نامشخص' }}</div>
                                <div style="font-size: 0.75rem; color: #94a3b8;">{{ $item->user->phone ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    <td data-label="نوع">
                        @if($item->type == 'appointment')
                            <i class="fas fa-users" style="color: var(--gold-main);"></i> حضوری
                        @elseif($item->type == 'call')
                            <i class="fas fa-phone" style="color: #10b981;"></i> تلفنی
                        @else
                            <i class="fas fa-comments" style="color: #3b82f6;"></i> چت آنلاین
                        @endif
                    </td>
                    <td data-label="زمان">
                        <div style="font-weight: 700;">
                            {{ $item->scheduled_at ? \Morilog\Jalali\Jalalian::fromCarbon($item->scheduled_at)->format('Y/m/d') : '---' }}
                        </div>
                        <div style="font-size: 0.8rem; color: #64748b;">
                            ساعت {{ $item->scheduled_at ? $item->scheduled_at->format('H:i') : '---' }}
                        </div>
                    </td>
                    <td data-label="وضعیت">
                        <span class="badge badge-{{ $item->status }}">
                            @if($item->status == 'pending') در انتظار تایید
                            @elseif($item->status == 'confirmed') تایید شده
                            @elseif($item->status == 'completed') تکمیل شده
                            @elseif($item->status == 'cancelled') لغو شده
                            @else {{ $item->status }} @endif
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('lawyer.consultations.show', $item->id) }}" class="action-btn">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<div style="margin-top: 20px;">
    {{ $consultations->links() }}
</div>
@endsection
@extends('layouts.lawyer')
@section('title', 'مدیریت مالی و پرداخت‌ها')

@push('styles')
<style>
    .content-body { padding: 0 !important; background: #f8fafc !important; margin: 0 !important; }
    .page-container { width: 100% !important; max-width: none !important; padding: 30px 40px !important; }
    
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px; margin-bottom: 35px; }
    .stat-card { background: #fff; padding: 25px; border-radius: 16px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
    .stat-icon { width: 60px; height: 60px; border-radius: 15px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; }
    .stat-info h4 { font-size: 0.85rem; color: #64748b; font-weight: 800; margin-bottom: 8px; }
    .stat-info .value { font-size: 1.5rem; font-weight: 900; color: var(--navy); }

    .table-card { background: #fff; border-radius: 16px; border: 1px solid #e2e8f0; margin-bottom: 30px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.04); }
    .table-header { padding: 20px 25px; background: #fdfbf7; border-bottom: 2px solid #f9f1d8; display: flex; justify-content: space-between; align-items: center; }
    .table-header h3 { font-size: 1.1rem; font-weight: 900; color: var(--navy); margin: 0; }
    
    .custom-table { width: 100%; border-collapse: collapse; }
    .custom-table th { background: #f8fafc; padding: 15px 20px; text-align: right; font-size: 0.85rem; font-weight: 800; color: #475569; border-bottom: 1px solid #e2e8f0; }
    .custom-table td { padding: 18px 20px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; font-size: 0.95rem; font-weight: 600; color: var(--navy-dark); }
    .custom-table tr:hover td { background: #f8fafc; }

    .badge { padding: 6px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 800; }
    .badge-paid { background: #d1fae5; color: #065f46; }
    .badge-pending { background: #fef3c7; color: #92400e; }
    .badge-overdue { background: #fee2e2; color: #991b1b; }

    .btn-verify { background: none; border: 1.5px solid var(--gold-main); color: var(--gold-main); padding: 8px 16px; border-radius: 10px; font-size: 0.85rem; font-weight: 800; cursor: pointer; transition: 0.3s; font-family: inherit; }
    .btn-verify:hover { background: var(--gold-main); color: #fff; }

    @media (max-width: 1024px) { .page-container { padding: 20px !important; } }
</style>
@endpush

@section('content')
<div class="page-container">
    <div style="margin-bottom: 35px; border-bottom: 2px solid #e2e8f0; padding-bottom: 20px;">
        <h2 style="font-weight: 900; color: var(--navy); font-size: 2rem; margin: 0;">
            <i class="fas fa-credit-card" style="color: var(--gold-main); margin-left: 15px;"></i>
            مرکز مدیریت مالی
        </h2>
    </div>

    <div class="stats-grid">
        <div class="stat-card" style="border-bottom: 4px solid var(--gold-main);">
            <div class="stat-icon" style="background: #fdfbf7; color: var(--gold-main);"><i class="fas fa-hourglass-half"></i></div>
            <div class="stat-info">
                <h4>مجموع اقساط در انتظار</h4>
                <div class="value">{{ number_format($stats['total_pending_installments']) }} <span style="font-size:0.8rem; color:#94a3b8;">تومان</span></div>
            </div>
        </div>
        <div class="stat-card" style="border-bottom: 4px solid #10b981;">
            <div class="stat-icon" style="background: #ecfdf5; color: #10b981;"><i class="fas fa-check-double"></i></div>
            <div class="stat-info">
                <h4>دریافتی موفق (درگاه)</h4>
                <div class="value" style="color: #10b981;">{{ number_format($stats['total_online_paid']) }} <span style="font-size:0.8rem; color:#94a3b8;">تومان</span></div>
            </div>
        </div>
    </div>

    <div class="table-card" style="border-right: 8px solid var(--gold-main);">
        <div class="table-header">
            <h3><i class="fas fa-hand-holding-usd"></i> اقساط در انتظار پرداخت (تایید وصول دستی)</h3>
        </div>
        <div style="overflow-x: auto;">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>پرونده</th>
                        <th>نام موکل</th>
                        <th>مبلغ قسط</th>
                        <th>سررسید</th>
                        <th>وضعیت</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingInstallments as $inst)
                        @php $isOverdue = \Carbon\Carbon::parse($inst->due_date)->isPast(); @endphp
                        <tr>
                            <td><a href="{{ route('lawyer.cases.show', $inst->case->id) }}" style="color:var(--navy);font-weight:900;">{{ $inst->case->case_number }}</a></td>
                            <td>{{ $inst->case->user->name ?? '---' }}</td>
                            <td style="font-weight: 900;">{{ number_format($inst->amount) }}</td>
                            <td><span style="color: {{ $isOverdue ? '#ef4444' : '#64748b' }};">{{ \Morilog\Jalali\Jalalian::fromCarbon($inst->due_date)->format('%d %B %Y') }}</span></td>
                            <td>
                                @if($isOverdue) <span class="badge badge-overdue">سررسید گذشته</span>
                                @else <span class="badge badge-pending">در انتظار پرداخت</span> @endif
                            </td>
                            <td>
                                <form action="{{ route('lawyer.payments.installment.mark-paid', $inst->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="paid_at" value="{{ now()->format('Y-m-d H:i:s') }}">
                                    <button type="submit" class="btn-verify"><i class="fas fa-check"></i> تایید وصول</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" style="text-align:center;padding:30px;color:#94a3b8;">قسط پرداخت نشده‌ای وجود ندارد.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($pendingInstallments->hasPages()) <div style="padding: 15px;">{{ $pendingInstallments->appends(request()->except('installments_page'))->links() }}</div> @endif
    </div>

    <div class="table-card" style="border-right: 8px solid var(--navy);">
        <div class="table-header">
            <h3><i class="fas fa-history"></i> تاریخچه تراکنش‌های آنلاین (درگاه زرین‌پال)</h3>
        </div>
        <div style="overflow-x: auto;">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>کد پیگیری</th>
                        <th>کاربر</th>
                        <th>مبلغ (تومان)</th>
                        <th>بابت</th>
                        <th>وضعیت درگاه</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td style="font-family: monospace; font-weight: bold; color: #64748b;">{{ $payment->tracking_code }}</td>
                            <td>{{ $payment->user->name ?? '---' }}</td>
                            <td style="font-weight: 900;">{{ number_format($payment->amount) }}</td>
                            <td style="font-size: 0.85rem; color: #475569;">{{ mb_substr($payment->description, 0, 40) }}...</td>
                            <td>
                                @if($payment->status == 'paid') <span class="badge badge-paid">موفق</span>
                                @elseif($payment->status == 'failed') <span class="badge badge-overdue">ناموفق</span>
                                @else <span class="badge badge-pending">لغو شده / در انتظار</span> @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="text-align:center;padding:30px;color:#94a3b8;">تراکنش آنلاینی ثبت نشده است.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($payments->hasPages()) <div style="padding: 15px;">{{ $payments->appends(request()->except('payments_page'))->links() }}</div> @endif
    </div>
</div>
@endsection
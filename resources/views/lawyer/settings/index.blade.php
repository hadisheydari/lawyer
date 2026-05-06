@extends('layouts.lawyer')
@section('title', 'تنظیمات و پروفایل')

@push('styles')
<style>
    .settings-tabs { display:flex; gap:8px; margin-bottom:25px; flex-wrap:wrap; }
    .s-tab {
        padding:9px 22px; border-radius:8px; border:1.5px solid #e0e0e0;
        background:#fff; font-family:'Vazirmatn',sans-serif; font-size:0.88rem;
        font-weight:600; color:#888; cursor:pointer; text-decoration:none; transition:0.2s;
        display:flex; align-items:center; gap:8px;
    }
    .s-tab.active, .s-tab:hover { background:var(--navy); border-color:var(--navy); color:#fff; }
    .s-tab i { font-size:0.82rem; }

    .settings-section { display:none; }
    .settings-section.active { display:block; }

    .card { background:#fff; border-radius:14px; padding:28px; box-shadow:0 4px 15px rgba(0,0,0,0.05); margin-bottom:20px; }
    .card-title { font-size:1rem; font-weight:800; color:var(--navy); margin-bottom:22px; padding-bottom:12px; border-bottom:2px solid #f5f0ea; display:flex; align-items:center; gap:8px; }
    .card-title i { color:var(--gold-main); }

    .profile-top { display:flex; align-items:center; gap:20px; margin-bottom:24px; }
    .profile-avatar {
        width:80px; height:80px; border-radius:50%;
        background:linear-gradient(135deg,var(--navy),#1e3a5f);
        color:var(--gold-main); display:flex; align-items:center; justify-content:center;
        font-size:2rem; font-weight:900; flex-shrink:0;
        border:3px solid rgba(212,175,55,0.3); overflow:hidden;
    }
    .profile-avatar img { width:100%; height:100%; object-fit:cover; }
    .profile-info h3 { font-size:1.1rem; font-weight:800; color:var(--navy); margin:0 0 4px; }
    .profile-info p { font-size:0.82rem; color:#888; margin:0; }

    .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:18px; margin-bottom:18px; }
    .form-group { margin-bottom:18px; }
    .form-label { display:block; margin-bottom:8px; font-size:0.87rem; color:var(--navy); font-weight:600; }
    .form-input { width:100%; padding:11px 14px; border:1.5px solid #e0e0e0; border-radius:10px; font-family:'Vazirmatn',sans-serif; font-size:0.92rem; outline:none; transition:0.2s; color:var(--navy); background:#fcfcfc; }
    .form-input:focus { border-color:var(--gold-main); background:#fff; box-shadow:0 0 0 3px rgba(197,160,89,0.1); }
    .error-msg { color:#ef4444; font-size:0.78rem; margin-top:4px; display:block; }

    .btn-submit { padding:12px 28px; background:linear-gradient(135deg,var(--navy),#1e3a5f); color:#fff; border:none; border-radius:10px; font-family:'Vazirmatn',sans-serif; font-weight:800; font-size:0.92rem; cursor:pointer; display:inline-flex; align-items:center; gap:8px; transition:0.3s; }
    .btn-submit:hover { transform:translateY(-2px); }

    /* toggle */
    .toggle-group { display:flex; justify-content:space-between; align-items:center; padding:14px 0; border-bottom:1px solid #f5f5f5; }
    .toggle-group:last-child { border-bottom:none; }
    .toggle-label-wrap { flex:1; }
    .toggle-label-wrap strong { font-size:0.9rem; font-weight:700; color:var(--navy); display:flex; align-items:center; gap:8px; }
    .toggle-label-wrap small { font-size:0.78rem; color:#888; display:block; margin-top:3px; margin-right:24px; }
    .toggle-switch { position:relative; width:50px; height:26px; flex-shrink:0; }
    .toggle-switch input { opacity:0; width:0; height:0; }
    .toggle-slider { position:absolute; inset:0; background:#e0e0e0; border-radius:26px; cursor:pointer; transition:0.3s; }
    .toggle-slider::before { content:''; position:absolute; width:20px; height:20px; border-radius:50%; background:#fff; bottom:3px; right:3px; transition:0.3s; box-shadow:0 2px 4px rgba(0,0,0,0.2); }
    .toggle-switch input:checked + .toggle-slider { background:var(--gold-main); }
    .toggle-switch input:checked + .toggle-slider::before { transform:translateX(-24px); }

    /* ساعات کاری */
    .schedule-grid { display:flex; flex-direction:column; gap:10px; }
    .schedule-row { display:grid; grid-template-columns:130px 1fr; gap:16px; align-items:center; padding:14px 16px; background:#f8fafc; border-radius:10px; border:1px solid #f0f0f0; }
    .day-check-wrap { display:flex; align-items:center; gap:8px; cursor:pointer; }
    .day-check-wrap input[type="checkbox"] { width:16px; height:16px; accent-color:var(--gold-main); cursor:pointer; }
    .day-label { font-weight:700; color:var(--navy); font-size:0.9rem; }
    .time-inputs { display:flex; gap:10px; align-items:center; }
    .time-inputs input[type="time"] { padding:8px 12px; border:1.5px solid #e0e0e0; border-radius:8px; font-family:'Vazirmatn',sans-serif; font-size:0.85rem; outline:none; width:120px; transition:0.2s; }
    .time-inputs input[type="time"]:focus { border-color:var(--gold-main); }
    .time-sep { color:#888; font-size:0.85rem; font-weight:600; }

    /* استثناها */
    .exception-list { display:flex; flex-direction:column; gap:10px; margin-bottom:20px; }
    .exception-item { display:flex; justify-content:space-between; align-items:center; padding:12px 16px; background:#f8fafc; border-radius:10px; border:1px solid #e0e0e0; }
    .exception-info strong { font-size:0.9rem; color:var(--navy); display:block; margin-bottom:3px; }
    .exception-info span { font-size:0.78rem; color:#888; display:flex; align-items:center; gap:6px; }
    .exc-badge-avail   { background:#d1fae5; color:#065f46; padding:2px 8px; border-radius:10px; font-size:0.72rem; font-weight:700; }
    .exc-badge-unavail { background:#fee2e2; color:#b91c1c; padding:2px 8px; border-radius:10px; font-size:0.72rem; font-weight:700; }
    .btn-del { padding:6px 12px; background:#fee2e2; color:#b91c1c; border:none; border-radius:7px; font-family:'Vazirmatn',sans-serif; font-size:0.78rem; font-weight:700; cursor:pointer; transition:0.2s; display:inline-flex; align-items:center; gap:4px; }
    .btn-del:hover { background:#b91c1c; color:#fff; }

    .add-exc-grid { display:grid; grid-template-columns:1fr 1fr 1fr; gap:14px; margin-bottom:14px; }

    @media(max-width:768px) {
        .form-grid { grid-template-columns:1fr; }
        .add-exc-grid { grid-template-columns:1fr; }
        .schedule-row { grid-template-columns:1fr; }
        .time-inputs { flex-wrap:wrap; }
    }
</style>
@endpush

@section('content')

@if(session('success'))
    <div style="background:#ecfdf5;border:1px solid #a7f3d0;color:#065f46;padding:14px 18px;border-radius:10px;margin-bottom:20px;display:flex;align-items:center;gap:10px;font-weight:600;">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

<div class="settings-tabs">
    <a href="#" class="s-tab active" onclick="switchTab('profile',this)">
        <i class="fas fa-user-edit"></i> پروفایل
    </a>
    <a href="#" class="s-tab" onclick="switchTab('availability',this)">
        <i class="fas fa-toggle-on"></i> دسترسی‌پذیری
    </a>
    <a href="#" class="s-tab" id="tab-schedule" onclick="switchTab('schedule',this)">
        <i class="fas fa-calendar-alt"></i> ساعات کاری
    </a>
    <a href="#" class="s-tab" id="tab-exceptions" onclick="switchTab('exceptions',this)">
        <i class="fas fa-calendar-times"></i> روزهای استثنا
    </a>
</div>

{{-- ─── پروفایل ─── --}}
<div class="settings-section active" id="sec-profile">
    <div class="card">
        <div class="card-title"><i class="fas fa-user-circle"></i> ویرایش پروفایل</div>

        <div class="profile-top">
            <div class="profile-avatar">
                @if($lawyer->image)
                    <img src="{{ asset('storage/'.$lawyer->image) }}" alt="{{ $lawyer->name }}">
                @else
                    {{ mb_substr($lawyer->name, 0, 1) }}
                @endif
            </div>
            <div class="profile-info">
                <h3>{{ $lawyer->name }}</h3>
                <p>{{ $lawyer->email ?? $lawyer->phone }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('lawyer.settings.profile') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">نام کامل *</label>
                    <input type="text" name="name" class="form-input @error('name') is-error @enderror"
                           value="{{ old('name', $lawyer->name) }}" required>
                    @error('name')<span class="error-msg">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">شماره موبایل *</label>
                    <input type="tel" name="phone" class="form-input @error('phone') is-error @enderror"
                           value="{{ old('phone', $lawyer->phone) }}" required dir="ltr" style="text-align:right;">
                    @error('phone')<span class="error-msg">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">ایمیل</label>
                    <input type="email" name="email" class="form-input @error('email') is-error @enderror"
                           value="{{ old('email', $lawyer->email) }}" dir="ltr" style="text-align:right;">
                    @error('email')<span class="error-msg">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">سابقه کار (سال)</label>
                    <input type="number" name="experience_years" class="form-input"
                           value="{{ old('experience_years', $lawyer->experience_years) }}" min="0" max="60">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">تخصص‌ها (با ویرگول جدا کنید)</label>
                <input type="text" name="specializations" class="form-input"
                       value="{{ old('specializations', is_array($lawyer->specializations) ? implode(', ', $lawyer->specializations) : '') }}"
                       placeholder="مثال: حقوق خانواده, حقوق تجاری, حقوق کیفری">
            </div>

            <div class="form-group">
                <label class="form-label">بیوگرافی</label>
                <textarea name="bio" class="form-input" rows="5"
                          placeholder="معرفی کوتاه از تجربه و تخصص‌هایتان...">{{ old('bio', $lawyer->bio) }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">تحصیلات</label>
                <input type="text" name="education" class="form-input"
                       value="{{ old('education', $lawyer->education) }}"
                       placeholder="مثال: دکترای حقوق خصوصی - دانشگاه تهران">
            </div>

            <div class="form-group">
                <label class="form-label">تصویر پروفایل <span style="color:#aaa;font-weight:400;">(JPG/PNG — حداکثر ۲MB)</span></label>
                <input type="file" name="image" class="form-input" accept="image/jpeg,image/png" style="padding:8px;">
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-save"></i> ذخیره پروفایل
            </button>
        </form>
    </div>
</div>

{{-- ─── دسترسی‌پذیری ─── --}}
<div class="settings-section" id="sec-availability">
    <div class="card">
        <div class="card-title"><i class="fas fa-toggle-on"></i> تنظیمات دسترسی‌پذیری</div>
        <p style="font-size:0.85rem;color:#888;margin-bottom:20px;">مشخص کنید موکلین از چه طریق‌هایی می‌توانند با شما ارتباط بگیرند.</p>

        <form method="POST" action="{{ route('lawyer.settings.profile') }}" enctype="multipart/form-data">
            @csrf
            {{-- فیلدهای مخفی ضروری برای اعتبارسنجی --}}
            <input type="hidden" name="name"  value="{{ $lawyer->name }}">
            <input type="hidden" name="phone" value="{{ $lawyer->phone }}">

            <div class="toggle-group">
                <div class="toggle-label-wrap">
                    <strong><i class="fas fa-comment-dots" style="color:var(--gold-main);"></i> گفتگوی آنلاین (چت)</strong>
                    <small>موکلین می‌توانند پیام متنی ارسال کنند</small>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" name="available_for_chat" value="1"
                           {{ $lawyer->available_for_chat ? 'checked' : '' }}>
                    <span class="toggle-slider"></span>
                </label>
            </div>

            <div class="toggle-group">
                <div class="toggle-label-wrap">
                    <strong><i class="fas fa-phone-alt" style="color:var(--gold-main);"></i> تماس تلفنی</strong>
                    <small>موکلین می‌توانند درخواست تماس تلفنی بدهند</small>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" name="available_for_call" value="1"
                           {{ $lawyer->available_for_call ? 'checked' : '' }}>
                    <span class="toggle-slider"></span>
                </label>
            </div>

            <div class="toggle-group">
                <div class="toggle-label-wrap">
                    <strong><i class="fas fa-calendar-check" style="color:var(--gold-main);"></i> نوبت حضوری</strong>
                    <small>موکلین می‌توانند وقت ملاقات حضوری رزرو کنند</small>
                </div>
                <label class="toggle-switch">
                    <input type="checkbox" name="available_for_appointment" value="1"
                           {{ $lawyer->available_for_appointment ? 'checked' : '' }}>
                    <span class="toggle-slider"></span>
                </label>
            </div>

            <div style="margin-top:22px;">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> ذخیره تنظیمات
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ─── ساعات کاری ─── --}}
<div class="settings-section" id="sec-schedule">
    <div class="card">
        <div class="card-title"><i class="fas fa-business-time"></i> ساعات کاری هفتگی</div>
        <p style="font-size:0.85rem;color:#888;margin-bottom:20px;">روزها و ساعت‌های کاری خود را مشخص کنید. اسلات‌های ۳۰ دقیقه‌ای بین ساعت شروع و پایان در دسترس موکلین قرار می‌گیرد.</p>

        <form method="POST" action="{{ route('lawyer.settings.schedule') }}">
            @csrf

            <div class="schedule-grid">
                @foreach($days as $dayNum => $dayName)
                    @php $schedule = $schedules[$dayNum] ?? null; @endphp
                    <div class="schedule-row">
                        <label class="day-check-wrap">
                            <input type="checkbox"
                                   name="schedules[{{ $dayNum }}][is_available]" value="1"
                                   id="avail_{{ $dayNum }}"
                                   {{ ($schedule && $schedule->is_available) ? 'checked' : '' }}
                                   onchange="toggleDay({{ $dayNum }}, this.checked)">
                            <span class="day-label">{{ $dayName }}</span>
                        </label>
                        <input type="hidden" name="schedules[{{ $dayNum }}][day_of_week]" value="{{ $dayNum }}">
                        <div class="time-inputs"
                             id="times_{{ $dayNum }}"
                             style="{{ (!$schedule || !$schedule->is_available) ? 'opacity:0.35;pointer-events:none;' : '' }}">
                            <input type="time" name="schedules[{{ $dayNum }}][start_time]"
                                   value="{{ $schedule ? substr($schedule->start_time, 0, 5) : '09:00' }}">
                            <span class="time-sep">تا</span>
                            <input type="time" name="schedules[{{ $dayNum }}][end_time]"
                                   value="{{ $schedule ? substr($schedule->end_time, 0, 5) : '17:00' }}">
                        </div>
                    </div>
                @endforeach
            </div>

            <div style="margin-top:22px;">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> ذخیره ساعات کاری
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ─── روزهای استثنا ─── --}}
<div class="settings-section" id="sec-exceptions">
    <div class="card" id="calendar">
        <div class="card-title"><i class="fas fa-calendar-times"></i> روزهای استثنا</div>
        <p style="font-size:0.85rem;color:#888;margin-bottom:18px;">روزهای تعطیل اضافه یا روزهای کاری خارج از برنامه هفتگی را اینجا ثبت کنید.</p>

        @if($exceptions->isNotEmpty())
            <div class="exception-list">
                @foreach($exceptions as $exc)
                    <div class="exception-item">
                        <div class="exception-info">
                            <strong>{{ \Morilog\Jalali\Jalalian::fromDateTime($exc->exception_date)->format('l، d F Y') }}</strong>
                            <span>
                                <span class="{{ $exc->is_available ? 'exc-badge-avail' : 'exc-badge-unavail' }}">
                                    {{ $exc->is_available ? 'روز کاری اضافه' : 'روز تعطیل' }}
                                </span>
                                @if($exc->reason) — {{ $exc->reason }} @endif
                            </span>
                        </div>
                        {{-- {{ route('lawyer.settings.exception.delete', $exc) }} --}}
                        <form method="POST" action="#">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-del" onclick="return confirm('این روز استثنا حذف شود؟')">
                                <i class="fas fa-trash-alt"></i> حذف
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align:center;padding:20px;color:#aaa;background:#f8fafc;border-radius:10px;margin-bottom:18px;">
                <i class="fas fa-calendar-check" style="font-size:1.5rem;display:block;margin-bottom:8px;opacity:0.4;"></i>
                <p style="font-size:0.85rem;">هیچ روز استثنایی ثبت نشده است.</p>
            </div>
        @endif

        <div style="border-top:2px solid #f5f0ea;padding-top:20px;margin-top:8px;">
            <div style="font-size:0.92rem;font-weight:800;color:var(--navy);margin-bottom:16px;">
                <i class="fas fa-plus-circle" style="color:var(--gold-main);margin-left:6px;"></i>افزودن روز استثنا
            </div>
            {{-- {{ route('lawyer.settings.exception') }} --}}
            <form method="POST" action="#">
                @csrf
                <div class="add-exc-grid">
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">تاریخ</label>
                        <input type="date" name="exception_date" class="form-input"
                               required min="{{ date('Y-m-d') }}">
                        @error('exception_date')<span class="error-msg">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">نوع</label>
                        <select name="is_available" class="form-input" required>
                            <option value="0">روز تعطیل (غیر کاری)</option>
                            <option value="1">روز کاری اضافه</option>
                        </select>
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">توضیح <span style="color:#aaa;font-weight:400;">(اختیاری)</span></label>
                        <input type="text" name="reason" class="form-input" placeholder="مثال: تعطیل رسمی">
                    </div>
                </div>
                <button type="submit" class="btn-submit" style="margin-top:14px;padding:10px 22px;font-size:0.88rem;">
                    <i class="fas fa-plus"></i> ثبت
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function switchTab(tab, el) {
    event.preventDefault();
    document.querySelectorAll('.settings-section').forEach(s => s.classList.remove('active'));
    document.querySelectorAll('.s-tab').forEach(t => t.classList.remove('active'));
    document.getElementById('sec-' + tab).classList.add('active');
    el.classList.add('active');
}

function toggleDay(dayNum, isChecked) {
    const wrap = document.getElementById('times_' + dayNum);
    wrap.style.opacity       = isChecked ? '1'    : '0.35';
    wrap.style.pointerEvents = isChecked ? 'auto' : 'none';
}

// باز کردن تب مربوطه بر اساس hash
window.addEventListener('DOMContentLoaded', () => {
    const hash = window.location.hash;
    if (hash === '#calendar' || hash === '#exceptions') {
        switchTab('exceptions', document.getElementById('tab-exceptions'));
    } else if (hash === '#schedule') {
        switchTab('schedule', document.getElementById('tab-schedule'));
    }
});
</script>
@endpush

@endsection
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

    /* پروفایل */
    .profile-top { display:flex; align-items:center; gap:20px; margin-bottom:24px; }
    .profile-avatar {
        width:80px; height:80px; border-radius:50%;
        background:linear-gradient(135deg, var(--navy), #1e3a5f);
        color:var(--gold-main); display:flex; align-items:center; justify-content:center;
        font-size:2rem; font-weight:900; flex-shrink:0;
        border:3px solid rgba(212,175,55,0.3);
    }
    .profile-avatar img { width:100%; height:100%; object-fit:cover; border-radius:50%; }
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

    /* toggle switch */
    .toggle-group { display:flex; justify-content:space-between; align-items:center; padding:12px 0; border-bottom:1px solid #f5f5f5; }
    .toggle-group:last-child { border-bottom:none; }
    .toggle-label { font-size:0.9rem; font-weight:600; color:var(--navy); display:flex; align-items:center; gap:8px; }
    .toggle-label small { font-size:0.75rem; color:#888; font-weight:400; display:block; margin-top:2px; }
    .toggle-switch { position:relative; width:50px; height:26px; }
    .toggle-switch input { opacity:0; width:0; height:0; }
    .toggle-slider { position:absolute; inset:0; background:#e0e0e0; border-radius:26px; cursor:pointer; transition:0.3s; }
    .toggle-slider::before { content:''; position:absolute; width:20px; height:20px; border-radius:50%; background:#fff; bottom:3px; right:3px; transition:0.3s; box-shadow:0 2px 4px rgba(0,0,0,0.2); }
    .toggle-switch input:checked + .toggle-slider { background:var(--gold-main); }
    .toggle-switch input:checked + .toggle-slider::before { transform:translateX(-24px); }

    /* ساعات کاری */
    .schedule-grid { display:grid; gap:12px; }
    .schedule-row { display:grid; grid-template-columns:120px 1fr; gap:16px; align-items:center; padding:14px; background:#f8fafc; border-radius:10px; }
    .day-label { font-weight:700; color:var(--navy); font-size:0.88rem; }
    .time-inputs { display:flex; gap:10px; align-items:center; }
    .time-inputs input { padding:8px 12px; border:1.5px solid #e0e0e0; border-radius:8px; font-family:'Vazirmatn',sans-serif; font-size:0.85rem; outline:none; width:110px; }
    .time-inputs input:focus { border-color:var(--gold-main); }
    .time-sep { color:#888; font-size:0.85rem; }
    .avail-checkbox { display:flex; align-items:center; gap:8px; font-size:0.85rem; color:#666; cursor:pointer; }
    .avail-checkbox input { width:16px; height:16px; accent-color:var(--gold-main); }

    /* روزهای استثنا */
    .exception-list { display:flex; flex-direction:column; gap:10px; margin-bottom:16px; }
    .exception-item { display:flex; justify-content:space-between; align-items:center; padding:12px 16px; background:#f8fafc; border-radius:10px; border:1px solid #e0e0e0; }
    .exception-info { font-size:0.88rem; }
    .exception-info strong { color:var(--navy); }
    .exception-info span { color:#888; font-size:0.8rem; display:block; margin-top:2px; }
    .btn-del { padding:6px 12px; background:#fee2e2; color:#b91c1c; border:none; border-radius:7px; font-family:'Vazirmatn',sans-serif; font-size:0.78rem; font-weight:700; cursor:pointer; transition:0.2s; }
    .btn-del:hover { background:#b91c1c; color:#fff; }

    .exc-badge-avail   { background:#d1fae5; color:#065f46; padding:2px 8px; border-radius:10px; font-size:0.72rem; font-weight:700; }
    .exc-badge-unavail { background:#fee2e2; color:#b91c1c; padding:2px 8px; border-radius:10px; font-size:0.72rem; font-weight:700; }

    .form-grid-3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:14px; }

    .spec-tags { display:flex; flex-wrap:wrap; gap:6px; padding:10px; border:1.5px solid #e0e0e0; border-radius:10px; min-height:48px; cursor:text; }
    .spec-tag { background:rgba(212,175,55,0.1); border:1px solid rgba(212,175,55,0.3); color:var(--gold-dark); padding:4px 10px; border-radius:20px; font-size:0.78rem; font-weight:700; display:flex; align-items:center; gap:5px; }
    .spec-tag button { background:none; border:none; cursor:pointer; color:inherit; font-size:0.7rem; padding:0; }
    .spec-tags-input { border:none; outline:none; font-family:'Vazirmatn',sans-serif; font-size:0.88rem; flex:1; min-width:120px; background:transparent; }

    @media(max-width:600px) { .form-grid { grid-template-columns:1fr; } .form-grid-3 { grid-template-columns:1fr 1fr; } .schedule-row { grid-template-columns:1fr; } }
</style>
@endpush

@section('content')

@php $lawyer = Auth::guard('lawyer')->user(); @endphp

<div class="settings-tabs">
    <a href="#profile" class="s-tab active" onclick="switchTab('profile',this)" id="tab-profile">
        <i class="fas fa-user-edit"></i> پروفایل
    </a>
    <a href="#availability" class="s-tab" onclick="switchTab('availability',this)" id="tab-availability">
        <i class="fas fa-toggle-on"></i> دسترسی‌پذیری
    </a>
    <a href="#schedule" class="s-tab" onclick="switchTab('schedule',this)" id="tab-schedule">
        <i class="fas fa-calendar-alt"></i> ساعات کاری
    </a>
    <a href="#exceptions" class="s-tab" onclick="switchTab('exceptions',this)" id="tab-exceptions">
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
            @method('PUT')

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
                <label class="form-label">تخصص‌ها (با کاما جدا کنید)</label>
                <input type="text" name="specializations" id="specInput" class="form-input"
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
                <label class="form-label">تصویر پروفایل (JPG/PNG — حداکثر ۲MB)</label>
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
            @method('PUT')
            <input type="hidden" name="name" value="{{ $lawyer->name }}">
            <input type="hidden" name="phone" value="{{ $lawyer->phone }}">

            <div class="toggle-group">
                <label class="toggle-label" for="chat_toggle">
                    <i class="fas fa-comment-dots" style="color:var(--gold-main);"></i>
                    دسترسی از طریق چت آنلاین
                    <small>موکلین می‌توانند پیام متنی ارسال کنند</small>
                </label>
                <label class="toggle-switch">
                    <input type="checkbox" id="chat_toggle" name="available_for_chat" value="1"
                           {{ $lawyer->available_for_chat ? 'checked' : '' }}>
                    <span class="toggle-slider"></span>
                </label>
            </div>

            <div class="toggle-group">
                <label class="toggle-label" for="call_toggle">
                    <i class="fas fa-phone-alt" style="color:var(--gold-main);"></i>
                    دسترسی از طریق تماس تلفنی
                    <small>موکلین می‌توانند تماس تلفنی درخواست دهند</small>
                </label>
                <label class="toggle-switch">
                    <input type="checkbox" id="call_toggle" name="available_for_call" value="1"
                           {{ $lawyer->available_for_call ? 'checked' : '' }}>
                    <span class="toggle-slider"></span>
                </label>
            </div>

            <div class="toggle-group">
                <label class="toggle-label" for="appt_toggle">
                    <i class="fas fa-calendar-check" style="color:var(--gold-main);"></i>
                    دسترسی از طریق نوبت حضوری
                    <small>موکلین می‌توانند وقت ملاقات حضوری رزرو کنند</small>
                </label>
                <label class="toggle-switch">
                    <input type="checkbox" id="appt_toggle" name="available_for_appointment" value="1"
                           {{ $lawyer->available_for_appointment ? 'checked' : '' }}>
                    <span class="toggle-slider"></span>
                </label>
            </div>

            <div style="margin-top:20px;">
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
        <div class="card-title"><i class="fas fa-business-time"></i> تنظیم ساعات کاری هفتگی</div>

        <form method="POST" action="{{ route('lawyer.settings.schedule') }}">
            @csrf

            <div class="schedule-grid">
                @foreach($days as $dayNum => $dayName)
                    @php $schedule = $schedules[$dayNum] ?? null; @endphp
                    <div class="schedule-row">
                        <div style="display:flex;align-items:center;gap:10px;">
                            <label class="avail-checkbox">
                                <input type="checkbox" name="schedules[{{ $dayNum }}][is_available]" value="1"
                                       id="avail_{{ $dayNum }}"
                                       {{ $schedule && $schedule->is_available ? 'checked' : '' }}
                                       onchange="toggleDay({{ $dayNum }}, this.checked)">
                                <strong class="day-label">{{ $dayName }}</strong>
                            </label>
                        </div>
                        <input type="hidden" name="schedules[{{ $dayNum }}][day_of_week]" value="{{ $dayNum }}">
                        <div class="time-inputs" id="times_{{ $dayNum }}" style="{{ !($schedule && $schedule->is_available) ? 'opacity:0.4;pointer-events:none;' : '' }}">
                            <input type="time" name="schedules[{{ $dayNum }}][start_time]"
                                   value="{{ $schedule ? substr($schedule->start_time, 0, 5) : '09:00' }}">
                            <span class="time-sep">تا</span>
                            <input type="time" name="schedules[{{ $dayNum }}][end_time]"
                                   value="{{ $schedule ? substr($schedule->end_time, 0, 5) : '17:00' }}">
                        </div>
                    </div>
                @endforeach
            </div>

            <div style="margin-top:20px;">
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
        <div class="card-title"><i class="fas fa-calendar-times"></i> روزهای استثنا (تعطیل یا اضافه)</div>

        @if($exceptions->isNotEmpty())
            <div class="exception-list">
                @foreach($exceptions as $exc)
                    <div class="exception-item">
                        <div class="exception-info">
                            <strong>{{ \Morilog\Jalali\Jalalian::fromDateTime($exc->exception_date)->format('Y/m/d') }}</strong>
                            <span>
                                <span class="{{ $exc->is_available ? 'exc-badge-avail' : 'exc-badge-unavail' }}">
                                    {{ $exc->is_available ? 'روز کاری اضافه' : 'روز تعطیل' }}
                                </span>
                                @if($exc->reason) &nbsp; {{ $exc->reason }} @endif
                            </span>
                        </div>
                        <form method="POST" action="{{ route('lawyer.settings.exception.delete', $exc) }}">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-del" onclick="return confirm('حذف شود؟')">
                                <i class="fas fa-trash-alt"></i> حذف
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @else
            <p style="color:#aaa;font-size:0.85rem;margin-bottom:16px;">هیچ روز استثنایی ثبت نشده است.</p>
        @endif

        <div style="border-top:2px solid #f5f0ea;padding-top:20px;margin-top:16px;">
            <div style="font-size:0.92rem;font-weight:800;color:var(--navy);margin-bottom:16px;">
                <i class="fas fa-plus-circle" style="color:var(--gold-main);margin-left:6px;"></i>افزودن روز استثنا
            </div>
            <form method="POST" action="{{ route('lawyer.settings.exception') }}">
                @csrf
                <div class="form-grid-3" style="margin-bottom:14px;">
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">تاریخ</label>
                        <input type="date" name="exception_date" class="form-input" required min="{{ date('Y-m-d') }}">
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">نوع روز</label>
                        <select name="is_available" class="form-input" required>
                            <option value="0">روز تعطیل (غیر کاری)</option>
                            <option value="1">روز کاری اضافه</option>
                        </select>
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">توضیح (اختیاری)</label>
                        <input type="text" name="reason" class="form-input" placeholder="مثال: تعطیل رسمی">
                    </div>
                </div>
                @error('exception_date')<span class="error-msg">{{ $message }}</span>@enderror
                <button type="submit" class="btn-submit" style="padding:10px 22px;font-size:0.88rem;">
                    <i class="fas fa-plus"></i> ثبت روز استثنا
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
    const times = document.getElementById('times_' + dayNum);
    times.style.opacity = isChecked ? '1' : '0.4';
    times.style.pointerEvents = isChecked ? 'auto' : 'none';
}

// Hash routing for calendar anchor
if (window.location.hash === '#calendar') {
    switchTab('exceptions', document.getElementById('tab-exceptions'));
}
</script>
@endpush

@endsection
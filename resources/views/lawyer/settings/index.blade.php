@extends('layouts.lawyer')
@section('title', 'تنظیمات و پروفایل')

@push('styles')
<style>
    /* 🔴 شکستن محدودیت‌های لایوت اصلی برای تمام‌عرض شدن */
    .content-body { 
        padding: 0 !important; 
        background: #f8fafc !important; 
        margin: 0 !important; 
    }

    .form-wrapper {
        width: 100% !important;
        max-width: none !important;
        padding: 30px 40px !important;
    }

    /* 🔴 استایل تب‌ها (شیک و مینیمال) */
    .settings-tabs { display:flex; gap:12px; margin-bottom:30px; flex-wrap:wrap; border-bottom: 2px solid #e2e8f0; padding-bottom: 15px; }
    .s-tab {
        padding:12px 25px; border-radius:12px; border:2px solid transparent;
        background:#fff; font-family:'Vazirmatn',sans-serif; font-size:0.95rem;
        font-weight:700; color:#64748b; cursor:pointer; text-decoration:none; transition:0.3s;
        display:flex; align-items:center; gap:8px; box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }
    .s-tab.active, .s-tab:hover { background:var(--navy); border-color:var(--navy); color:#fff; box-shadow: 0 4px 15px rgba(15, 23, 42, 0.15); }
    .s-tab i { font-size:1rem; }

    .settings-section { display:none; }
    .settings-section.active { display:block; animation: fadeIn 0.4s ease; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

    /* 🔴 استایل کارت‌ها */
    .card { background:#fff !important; border-radius:16px !important; padding:35px !important; box-shadow:0 4px 25px rgba(0,0,0,0.03) !important; border:1px solid #eef2f6 !important; margin-bottom:25px; border-top: 6px solid var(--gold-main) !important; }
    .card-title { font-size:1.15rem; font-weight:900; color:var(--navy); margin-bottom:25px; padding-bottom:15px; border-bottom:2px solid #f9f1d8; display:flex; align-items:center; gap:10px; }
    .card-title i { color:var(--gold-main); font-size: 1.3rem; }

    /* 🔴 بخش پروفایل بالا */
    .profile-top { display:flex; align-items:center; gap:25px; margin-bottom:35px; background: #fdfbf7; padding: 25px; border-radius: 16px; border: 1px solid #f9f1d8; }
    .profile-avatar {
        width:90px; height:90px; border-radius:50%;
        background:var(--navy);
        color:var(--gold-main); display:flex; align-items:center; justify-content:center;
        font-size:2.5rem; font-weight:900; flex-shrink:0;
        border:4px solid #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.1); overflow:hidden;
    }
    .profile-avatar img { width:100%; height:100%; object-fit:cover; }
    .profile-info h3 { font-size:1.4rem; font-weight:900; color:var(--navy); margin:0 0 8px; }
    .profile-info p { font-size:0.95rem; font-weight: 600; color:#64748b; margin:0; }

    /* 🔴 اینپوت‌های لوکس با !important */
    .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:25px; margin-bottom:25px; }
    .form-group { margin-bottom:25px; }
    .form-label { display:block; margin-bottom:10px; font-size:0.9rem; color:#334155; font-weight:800; }
    
    .form-input { 
        width:100% !important; padding:14px 18px !important; border:2px solid #e2e8f0 !important; 
        border-radius:12px !important; font-family:'Vazirmatn',sans-serif !important; 
        font-size:0.95rem !important; outline:none !important; transition:0.3s !important; 
        color:var(--navy-dark) !important; background:#f8fafc !important; font-weight: 600 !important;
    }
    .form-input:focus { border-color:var(--gold-main) !important; background:#fff !important; box-shadow:0 0 0 5px rgba(212,175,55,0.1) !important; }
    .error-msg { color:#ef4444; font-size:0.85rem; font-weight: 800; margin-top:8px; display:block; }

    /* 🔴 دکمه‌ها */
    .btn-submit { padding:16px 35px; background:var(--navy); color:#fff; border:none; border-radius:12px; font-family:'Vazirmatn',sans-serif; font-weight:900; font-size:1.05rem; cursor:pointer; display:inline-flex; align-items:center; gap:10px; transition:0.3s; box-shadow: 0 4px 15px rgba(15, 23, 42, 0.15); }
    .btn-submit:hover { transform:translateY(-3px); background: var(--gold-main); box-shadow: 0 8px 25px rgba(212, 175, 55, 0.3); }

    /* 🔴 toggle (سوییچ‌ها) */
    .toggle-group { display:flex; justify-content:space-between; align-items:center; padding:20px 25px; border:1px solid #e2e8f0; border-radius: 12px; margin-bottom: 15px; background: #fdfbf7; }
    .toggle-label-wrap { flex:1; }
    .toggle-label-wrap strong { font-size:1rem; font-weight:900; color:var(--navy); display:flex; align-items:center; gap:10px; }
    .toggle-label-wrap small { font-size:0.85rem; font-weight: 600; color:#64748b; display:block; margin-top:6px; margin-right:28px; }
    .toggle-switch { position:relative; width:56px; height:30px; flex-shrink:0; }
    .toggle-switch input { opacity:0; width:0; height:0; }
    .toggle-slider { position:absolute; inset:0; background:#cbd5e1; border-radius:30px; cursor:pointer; transition:0.3s; }
    .toggle-slider::before { content:''; position:absolute; width:22px; height:22px; border-radius:50%; background:#fff; bottom:4px; right:4px; transition:0.3s; box-shadow:0 2px 5px rgba(0,0,0,0.2); }
    .toggle-switch input:checked + .toggle-slider { background:var(--gold-main); }
    .toggle-switch input:checked + .toggle-slider::before { transform:translateX(-26px); }

    /* 🔴 ساعات کاری */
    .schedule-grid { display:flex; flex-direction:column; gap:12px; }
    .schedule-row { display:grid; grid-template-columns:150px 1fr; gap:20px; align-items:center; padding:18px 25px; background:#f8fafc; border-radius:12px; border:1px solid #e2e8f0; transition: 0.3s; }
    .schedule-row:hover { border-color: var(--gold-main); background: #fff; }
    .day-check-wrap { display:flex; align-items:center; gap:10px; cursor:pointer; }
    .day-check-wrap input[type="checkbox"] { width:18px; height:18px; accent-color:var(--gold-main); cursor:pointer; }
    .day-label { font-weight:800; color:var(--navy); font-size:0.95rem; }
    .time-inputs { display:flex; gap:15px; align-items:center; }
    .time-inputs input[type="time"] { padding:10px 15px !important; border:2px solid #cbd5e1 !important; border-radius:10px !important; font-family:'Vazirmatn',sans-serif !important; font-size:0.95rem !important; outline:none !important; width:130px !important; transition:0.2s !important; font-weight: bold !important;}
    .time-inputs input[type="time"]:focus { border-color:var(--gold-main) !important; background: #fff !important; }
    .time-sep { color:#64748b; font-size:0.9rem; font-weight:800; }

    /* 🔴 استثناها */
    .exception-list { display:flex; flex-direction:column; gap:12px; margin-bottom:25px; }
    .exception-item { display:flex; justify-content:space-between; align-items:center; padding:18px 25px; background:#fff; border-radius:12px; border:2px solid #e2e8f0; }
    .exception-info strong { font-size:1rem; color:var(--navy); font-weight: 900; display:block; margin-bottom:6px; }
    .exception-info span { font-size:0.85rem; font-weight: 700; color:#64748b; display:flex; align-items:center; gap:10px; }
    .exc-badge-avail   { background:#d1fae5; color:#065f46; padding:4px 12px; border-radius:20px; font-size:0.75rem; font-weight:800; }
    .exc-badge-unavail { background:#fee2e2; color:#b91c1c; padding:4px 12px; border-radius:20px; font-size:0.75rem; font-weight:800; }
    .btn-del { padding:8px 16px; background:#fef2f2; color:#ef4444; border:1px solid #fca5a5; border-radius:10px; font-family:'Vazirmatn',sans-serif; font-size:0.85rem; font-weight:800; cursor:pointer; transition:0.2s; display:inline-flex; align-items:center; gap:6px; }
    .btn-del:hover { background:#ef4444; color:#fff; border-color: #ef4444; }

    .add-exc-grid { display:grid; grid-template-columns:1fr 1fr 2fr; gap:20px; margin-bottom:20px; }

    @media(max-width:1024px) {
        .form-wrapper { padding: 20px !important; }
        .form-grid { grid-template-columns:1fr; }
        .add-exc-grid { grid-template-columns:1fr; }
        .schedule-row { grid-template-columns:1fr; }
        .time-inputs { flex-wrap:wrap; }
    }
</style>
@endpush

@section('content')
<div class="form-wrapper">

    @if(session('success'))
        <div style="background:#ecfdf5;border:2px solid #a7f3d0;color:#065f46;padding:18px 25px;border-radius:12px;margin-bottom:25px;display:flex;align-items:center;gap:12px;font-weight:800; font-size: 0.95rem; box-shadow: 0 4px 10px rgba(16, 185, 129, 0.1);">
            <i class="fas fa-check-circle" style="font-size: 1.5rem;"></i> {{ session('success') }}
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
                    <label class="form-label">تصویر پروفایل <span style="color:#94a3b8;font-weight:600;font-size:0.8rem;">(JPG/PNG — حداکثر ۲MB)</span></label>
                    <input type="file" name="image" class="form-input" accept="image/jpeg,image/png" style="padding:10px 18px !important; cursor: pointer;">
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
            <p style="font-size:0.95rem;font-weight:600;color:#64748b;margin-bottom:25px;">مشخص کنید موکلین از چه طریق‌هایی می‌توانند با شما ارتباط بگیرند.</p>

            <form method="POST" action="{{ route('lawyer.settings.profile') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="name"  value="{{ $lawyer->name }}">
                <input type="hidden" name="phone" value="{{ $lawyer->phone }}">

                <div class="toggle-group">
                    <div class="toggle-label-wrap">
                        <strong><i class="fas fa-comment-dots" style="color:var(--gold-main);font-size:1.2rem;"></i> گفتگوی آنلاین (چت)</strong>
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
                        <strong><i class="fas fa-phone-alt" style="color:var(--gold-main);font-size:1.2rem;"></i> تماس تلفنی</strong>
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
                        <strong><i class="fas fa-calendar-check" style="color:var(--gold-main);font-size:1.2rem;"></i> نوبت حضوری</strong>
                        <small>موکلین می‌توانند وقت ملاقات حضوری رزرو کنند</small>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="available_for_appointment" value="1"
                               {{ $lawyer->available_for_appointment ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <div style="margin-top:30px;">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-check-double"></i> بروزرسانی دسترسی‌پذیری
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ─── ساعات کاری ─── --}}
    <div class="settings-section" id="sec-schedule">
        <div class="card">
            <div class="card-title"><i class="fas fa-business-time"></i> ساعات کاری هفتگی</div>
            <p style="font-size:0.95rem;font-weight:600;color:#64748b;margin-bottom:25px;">روزها و ساعت‌های کاری خود را مشخص کنید. اسلات‌های ۳۰ دقیقه‌ای بین ساعت شروع و پایان در دسترس موکلین قرار می‌گیرد.</p>

            <form method="POST" action="{{ route('lawyer.settings.schedule') }}">
                @csrf

                <div class="schedule-grid">
                    @foreach($days as $dayNum => $dayName)
                        @php $schedule = $schedules[$dayNum] ?? null; @endphp
                        <div class="schedule-row">
                            <label class="day-check-wrap">
                                <input type="hidden" name="schedules[{{ $dayNum }}][is_available]" value="0">
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

                <div style="margin-top:30px;">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-clock"></i> ثبت و تنظیم ساعات کاری
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ─── روزهای استثنا ─── --}}
    <div class="settings-section" id="sec-exceptions">
        <div class="card" id="calendar">
            <div class="card-title"><i class="fas fa-calendar-times"></i> روزهای استثنا</div>
            <p style="font-size:0.95rem;font-weight:600;color:#64748b;margin-bottom:25px;">روزهای تعطیل اضافه یا روزهای کاری خارج از برنامه هفتگی را اینجا ثبت کنید.</p>

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
                            <form method="POST" action="{{ route('lawyer.settings.exception.delete', $exc) }}">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-del" onclick="return confirm('این روز استثنا حذف شود؟')">
                                    <i class="fas fa-trash-alt"></i> حذف تاریخ
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align:center;padding:30px;color:#94a3b8;background:#f8fafc;border:2px dashed #e2e8f0;border-radius:12px;margin-bottom:25px;">
                    <i class="fas fa-calendar-check" style="font-size:3rem;display:block;margin-bottom:15px;color:#cbd5e1;"></i>
                    <p style="font-size:1rem;font-weight:800;">هیچ روز استثنایی در سیستم ثبت نشده است.</p>
                </div>
            @endif

            <div style="border-top:2px solid #f1f5f9;padding-top:25px;margin-top:15px;">
                <div style="font-size:1.05rem;font-weight:900;color:var(--navy);margin-bottom:20px;">
                    <i class="fas fa-plus-circle" style="color:var(--gold-main);margin-left:8px;"></i>افزودن تاریخ استثنا جدید
                </div>
                
                <form method="POST" action="{{ route('lawyer.settings.exception.add') }}">
                        @csrf
                    <div class="add-exc-grid">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">انتخاب تاریخ</label>
                            <input type="date" name="exception_date" class="form-input" required min="{{ date('Y-m-d') }}">
                            @error('exception_date')<span class="error-msg">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">نوع وضعیت</label>
                            <select name="is_available" class="form-input" required>
                                <option value="0">روز تعطیل (غیر کاری)</option>
                                <option value="1">روز کاری اضافه</option>
                            </select>
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">توضیحات <span style="color:#94a3b8;font-weight:600;">(اختیاری)</span></label>
                            <input type="text" name="reason" class="form-input" placeholder="مثال: مسافرت کاری، تعطیل رسمی...">
                        </div>
                    </div>
                    <button type="submit" class="btn-submit" style="margin-top:20px;">
                        <i class="fas fa-plus"></i> ثبت استثنا در تقویم
                    </button>
                </form>
            </div>
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
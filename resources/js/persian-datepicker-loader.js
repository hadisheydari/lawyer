export async function loadPersianDatepickerCDN() {
    // بارگذاری CSS
    if (!document.querySelector('link[href*="persian-datepicker.min.css"]')) {
        const cssLink = document.createElement('link');
        cssLink.rel = 'stylesheet';
        cssLink.href = 'https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css';
        document.head.appendChild(cssLink);
    }

    // بارگذاری کتابخانه‌ها
    await loadScriptOnce('https://cdn.jsdelivr.net/npm/persian-date@1.0.5/dist/persian-date.min.js');
    await loadScriptOnce('https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js');

    if (typeof $ !== 'undefined' && $.fn.persianDatepicker) {
        $('input.persian-datepicker').each(function () {
            const $displayInput = $(this);
            const hiddenId = $displayInput.data('pd-target');
            const $hiddenInput = $('#' + hiddenId);

            // تبدیل اعداد فارسی به انگلیسی
            function normalizeDigits(str) {
                const persianDigits = '۰۱۲۳۴۵۶۷۸۹';
                return str.replace(/[۰-۹]/g, d => persianDigits.indexOf(d));
            }

            // اگر hidden مقدار داشته باشد (میلادی)، آن را به شمسی نمایش دهیم
            const hiddenVal = $hiddenInput.val();
            if (hiddenVal) {
                try {
                    const pd = new persianDate(new Date(hiddenVal)).toCalendar('persian');
                    $displayInput.val(pd.format('YYYY/MM/DD'));
                } catch (e) {
                    console.warn('تاریخ نامعتبر برای تبدیل:', hiddenVal);
                }
            }

            // مقداردهی پلاگین
            $displayInput.persianDatepicker({
                format: 'YYYY/MM/DD',
                autoClose: true,
                calendar: {
                    persian: { enabled: true, locale: 'fa' },
                    gregorian: { enabled: false }
                },
                initialValue: !!$displayInput.val(),
                initialValueType: 'persian',
                onSelect: function (unix) {
                    let gregorianDate = new persianDate(unix)
                        .toCalendar('gregorian')
                        .format('YYYY-MM-DD HH:mm:ss');

                    gregorianDate = normalizeDigits(gregorianDate);

                    $hiddenInput.val(gregorianDate).trigger('change');
                }
            });
        });
    } else {
        console.error('❌ jQuery یا Persian Datepicker بارگذاری نشده‌اند!');
    }
}

function loadScriptOnce(src) {
    return new Promise((resolve, reject) => {
        if (document.querySelector(`script[src="${src}"]`)) {
            resolve();
            return;
        }
        const script = document.createElement('script');
        script.src = src;
        script.onload = resolve;
        script.onerror = () => reject(new Error(`خطا در بارگذاری اسکریپت: ${src}`));
        document.head.appendChild(script);
    });
}

/* --------------------------------------------------------
  1. select2
-------------------------------------------------------- */
$('.select2').select2({
    width: '100%',
    dir: 'rtl',
    placeholder: 'لطفاً یک گزینه را انتخاب کنید',
    language: {
        noResults: function() {
            return "نتیجه‌ای یافت نشد";
        }
    }
});
$('.select2').next('.select2-container').css({
    'border': '1px solid #2f73fa',
    'border-radius': '0.375rem',
    'box-shadow': '0 1px 3px rgba(0, 0, 0, 0.12)'
});
$('.select2').next('.select2-container').find('.select2-selection').css({
    'height': '45px',
    'padding': '0 0.75rem',
    'line-height': '45px',
    'font-size': '14px',
    'border-radius': '0.375rem',
    'display': 'flex',
    'align-items': 'center'
});
$('.select2').next('.select2-container').find('.select2-selection__arrow').css({
    'top': '50%',
    'transform': 'translateY(-50%)'
});

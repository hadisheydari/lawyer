import Swal from 'sweetalert2';

function confirmDelete(button) {
    Swal.fire({
        title: 'آیا مطمئن هستید؟',
        text: "این عملیات قابل بازگشت نیست!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'بله، حذف کن!',
        cancelButtonText: 'لغو'
    }).then((result) => {
        if (result.isConfirmed) {
            button.closest('form').submit();
        }
    });
}

// اگه می‌خوای به صورت global در دسترس باشه:
window.confirmDelete = confirmDelete;


function SweetAlert(action, title = null, icon = null, confirmButtonText = null, cancelButtonText = null, href = null ) {
    Swal.fire({
        title: title ?? 'آیا مطمئن هستید؟',
        text: "این عملیات قابل بازگشت نیست!",
        icon: icon ?? 'warning',
        showCancelButton: true,
        confirmButtonColor: '#26cc0e',
        cancelButtonColor: '#ec0722',
        confirmButtonText: confirmButtonText ?? 'بله!',
        cancelButtonText: cancelButtonText ?? 'لغو'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = href;
        }
    });
}
window.SweetAlert = SweetAlert;

function PropertyDriver(action, id) {
    Swal.fire({
        title: "نوع راننده را تعیین کنید",
        showDenyButton: true,
        showCancelButton: true,
        confirmButtonText: "ملکی",
        denyButtonText: `غیرملکی`,
        cancelButtonText: `لغو`,
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire("تغییرات با موفقیت اعمال شد!", "", "success").then(() => {
                window.location.href = '/partitions/driver/' + id + '/owned';
            });
        } else if (result.isDenied) {
            Swal.fire("تغییرات با موفقیت اعمال شد!", "", "success").then(() => {
                window.location.href = '/partitions/driver/' + id + '/non_owned';
            });
        }
    });
}
window.PropertyDriver = PropertyDriver;

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


function SweetAlert(action  , title = null , icon  = null , confirmButtonText = null , cancelButtonText = null) {
    Swal.fire({
        title: title ?? 'آیا مطمئن هستید؟',
        text: "این عملیات قابل بازگشت نیست!",
        icon: icon ?? 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: confirmButtonText ?? 'بله!',
        cancelButtonText: cancelButtonText ?? 'لغو'
    }).then((result) => {
        if (result.isConfirmed) {
            button.closest('form').submit();
        }
    });

}
window.SweetAlert = SweetAlert;

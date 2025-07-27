    $(document).ready(function () {
    $('#province').on('change', function () {

        let provinceId = $(this).val();
        let $citySelect = $('#city');
console.log(provinceId);
        $citySelect.empty().append('<option value="">در حال بارگذاری...</option>');

        if (provinceId) {
            $.ajax({
                url: '/get-cities/' + provinceId,
                type: 'GET',
                success: function (data) {
                    $citySelect.empty().append('<option value="">یک شهر انتخاب کنید</option>');
                    $.each(data, function (id, name) {
                        $citySelect.append('<option value="' + id + '">' + name + '</option>');
                    });
                    console.log(data);
                },
                error: function () {
                    alert('خطا در بارگذاری شهرها');
                }
            });
        } else {
            $citySelect.empty().append('<option value="">ابتدا استان را انتخاب کنید</option>');
        }
    });
});

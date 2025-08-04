function handleProvinceChange(provinceSelector, citySelector) {
    $(provinceSelector).on('change', function () {
        let provinceId = $(this).val();
        let $citySelect = $(citySelector);

        $citySelect.empty().append('<option value="">در حال بارگذاری...</option>');

        if (provinceId) {
            $.ajax({
                url: '/get-cities/' + provinceId,
                type: 'GET',
                success: function (data) {
                    $citySelect.empty().append('<option value="">یک شهر انتخاب کنید</option>');
                    $.each(data, function (id, name) {
                        $citySelect.append(`<option value="${id}">${name}</option>`);
                    });
                },
                error: function () {
                    alert('خطا در بارگذاری شهرها');
                }
            });
        } else {
            $citySelect.empty().append('<option value="">ابتدا استان را انتخاب کنید</option>');
        }
    });
}

function handleCityLatLng(citySelector, latSelector, lngSelector, responseKeys = { lat: 'lat', lng: 'lng' }) {
    console.log('citySelector exists?', $(citySelector).length);
    console.log('latSelector exists?', $(latSelector).length);
    console.log('lngSelector exists?', $(lngSelector).length);

    if (!$(citySelector).length || !$(latSelector).length || !$(lngSelector).length) return;
    console.log('1');

    $(citySelector).on('change', function () {
        let cityId = $(this).val();
        if (!cityId) return;

        $.ajax({
            url: '/get-city-scale/' + cityId,
            type: 'GET',
            success: function (data) {
                $(latSelector).val(data[responseKeys.lat]);
                $(lngSelector).val(data[responseKeys.lng]);
            },
            error: function () {
                alert('خطا در دریافت مختصات شهر');
            }
        });
    });
}

$(document).ready(function () {
    handleProvinceChange('#province', '#city');
    handleProvinceChange('#province1', '#city1');

    handleCityLatLng('#city', '#lat', '#lng', { lat: 'latitude', lng: 'longitude' });
    handleCityLatLng('#city1', '#lat1', '#lng1', { lat: 'latitude', lng: 'longitude' });
});

function ConvertNumberToPersian(element) {
    let persianNumbers = {
        0: '۰',
        1: '۱',
        2: '۲',
        3: '۳',
        4: '۴',
        5: '۵',
        6: '۶',
        7: '۷',
        8: '۸',
        9: '۹'
    };
    element.val(
        element.val().replace(/\d/g, function (digit) {
            return persianNumbers[digit];
        })
    );
    let placeholder = element.attr('placeholder');
    if (placeholder) {
        element.attr('placeholder', placeholder.replace(/\d/g, function (digit) {
            return persianNumbers[digit];
        }));
    }
}

function ConvertNumberToEnglish(element) {
    let persianNumbers = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
    let englishNumbers = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    element.val(
        element.val().replace(/[۰-۹]/g, function (digit) {
            return englishNumbers[persianNumbers.indexOf(digit)];
        })
    );
}

$(document).ready(function () {
    $("input.ConvertNumber").each(function () {
        ConvertNumberToPersian($(this));
        $(this).on('input paste', function () {
            ConvertNumberToPersian($(this));
        });
    });
    $('form').on('submit', function () {
        $("input.ConvertNumber").each(function () {
            ConvertNumberToEnglish($(this));
        });
    });
});

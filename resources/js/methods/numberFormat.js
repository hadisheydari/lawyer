document.querySelectorAll('.number-format').forEach(input => {
    if(input.value) {
        input.value = formatNumber(input.value);
    }

    input.addEventListener('input', (e) => {
        let caretPosition = input.selectionStart;
        let originalLength = input.value.length;

        let value = input.value.replace(/,/g, '');

        if(isNaN(value)) {
            input.value = '';
            return;
        }

        input.value = formatNumber(value);

        let newLength = input.value.length;
        caretPosition = caretPosition + (newLength - originalLength);
        input.setSelectionRange(caretPosition, caretPosition);
    });

    input.form && input.form.addEventListener('submit', () => {
        input.value = input.value.replace(/,/g, '');
    });
});

function formatNumber(value) {
    return Number(value).toLocaleString('en-US');
}

document.addEventListener('DOMContentLoaded', function () {
    const ownershipSelect = $('select[name="property"]'); // jQuery برای select2
    const companyField = document.getElementById('company-field');

    if (!ownershipSelect.length || !companyField) return;

    function toggleCompanyField(value) {
        if (value === 'owned') {
            companyField.classList.remove('hidden');
        } else {
            companyField.classList.add('hidden');
        }
    }

    // وقتی Select2 تغییر کند
    ownershipSelect.on('change', function () {
        toggleCompanyField(this.value);
    });

    // مقدار اولیه
    toggleCompanyField(ownershipSelect.val());
});

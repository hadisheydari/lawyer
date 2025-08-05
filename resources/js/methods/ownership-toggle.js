document.addEventListener('DOMContentLoaded', function () {
    const ownershipSelect = $('select[name="property"]');
    const companyField = document.getElementById('company-field');

    if (!ownershipSelect.length || !companyField) return;

    function toggleCompanyField(value) {
        if (value === 'owned') {
            companyField.classList.remove('hidden');
        } else {
            companyField.classList.add('hidden');
        }
    }

    ownershipSelect.on('change', function () {
        toggleCompanyField(this.value);
    });

    toggleCompanyField(ownershipSelect.val());
});

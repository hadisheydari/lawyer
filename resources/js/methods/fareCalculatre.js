document.addEventListener("DOMContentLoaded", function () {
    const insuranceSelect = document.querySelector('select[name="insurance_id"]');
    const insuranceValueInput = document.querySelector('input[name="insurance_value"]');
    const fareValueInput = document.querySelector('input[name="fare_value"]');
    const fareInput = document.querySelector('input[name="fare"]');

    const baseInsuranceRate = 0.001;

    const insuranceDataEl = document.getElementById("insurance-data");
    const insuranceCoefficients = insuranceDataEl ? JSON.parse(insuranceDataEl.textContent) : {};

    function cleanNumber(value) {
        return parseFloat(value.replace(/,/g, '')) || 0;
    }

    function formatNumber(value) {
        return value.toLocaleString('en-US');
    }

    function calculateFare() {
        const insuranceId = insuranceSelect.value;
        const insuranceValue = cleanNumber(insuranceValueInput.value);
        const fareValue = cleanNumber(fareValueInput.value);

        const coefficient = parseFloat(insuranceCoefficients[insuranceId]) || 1;

        const insuranceCost = insuranceValue * baseInsuranceRate * coefficient;
        const totalFare = fareValue + insuranceCost;

        fareInput.value = formatNumber(Math.round(totalFare));
    }

    insuranceSelect.addEventListener('change', calculateFare);
    insuranceValueInput.addEventListener('input', calculateFare);
    fareValueInput.addEventListener('input', calculateFare);

    calculateFare();
});

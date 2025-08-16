document.addEventListener('DOMContentLoaded', function () {
    const weightInput = document.querySelector('input[name="weight"]');
    const fareInput = document.querySelector('input[name="fare"]');
    const commissionInput = document.querySelector('input[name="commission"]');

    if (!weightInput || !fareInput || !commissionInput || !window.cargoData) return;

    const fareType = window.cargoData.fareType;
    const fare = parseFloat(window.cargoData.fare) || 0;
    const partitionWeightSum = parseInt(window.cargoData.partitionWeightSum) || 0;
    const maxPartitionWeight = parseInt(window.cargoData.maxPartitionWeight) || 0;

    const formatNumber = (num) => num.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    const cleanNumber = (str) => parseInt(str.replace(/,/g, '')) || 0;

    const calculateFare = () => {
        let enteredWeight = cleanNumber(weightInput.value);

        // جلوگیری از اضافه شدن وزن بیشتر از cargo.weight
        if (enteredWeight + partitionWeightSum > maxPartitionWeight) {
            weightInput.style.borderColor = 'red';
            fareInput.value = '';
            return;
        } else {
            weightInput.style.borderColor = '';
        }

        // وزن فقط عدد صحیح
        enteredWeight = Math.floor(enteredWeight);
        weightInput.value = enteredWeight;

        // کرایه پایه
        let baseFare = fareType === 'service'
            ? fare
            : (fare / maxPartitionWeight) * enteredWeight;

        // کمیسیون مبلغی
        let commission = parseFloat(commissionInput.value.replace(/,/g, '')) || 0;

        let finalFare = baseFare;
        if (fareType === 'service') {
            finalFare += commission / 100;
        } else if (fareType === 'tonnage') {
            finalFare +=  enteredWeight * commission / 100; // مبلغ ثابت بر اساس وزن
        }

        fareInput.value = formatNumber(finalFare);
    };

    weightInput.addEventListener('input', calculateFare);
    commissionInput.addEventListener('input', calculateFare);

    // محاسبه اولیه اگر مقادیر از قبل موجود باشد
    calculateFare();
});

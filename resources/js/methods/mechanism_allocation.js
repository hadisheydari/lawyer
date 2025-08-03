document.getElementById('assignForm').addEventListener('submit', function (e) {
    const selectedVehicleId = document.getElementById('vehicleSelect').value;
    if (!selectedVehicleId) {
        e.preventDefault();
        alert('لطفاً یک مکانیزم را انتخاب کنید');
        return;
    }

    this.action = `/vehicles/${selectedVehicleId}`;
});

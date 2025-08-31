document.getElementById('uploadBtn').addEventListener('click', () => {
    const fileInput = document.getElementById('qris');
    const file = fileInput.files[0];
    const errorEl = document.getElementById('error');
    errorEl.textContent = '';

    if (!file) {
        errorEl.textContent = 'Silakan pilih file QRIS.';
        return;
    }

    const formData = new FormData();
    formData.append('qris', file);
    formData.append('action', 'upload');

    fetch('ajax.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById('merchantData').style.display = 'block';
            document.getElementById('merchantName').textContent = data.merchant_name;
            document.getElementById('merchantCity').textContent = data.merchant_city;
            document.getElementById('merchantID').textContent = data.merchant_id;
            document.getElementById('merchantData').dataset.qris = data.qris_string;
        } else {
            errorEl.textContent = data.error;
        }
    });
});

document.getElementById('generateBtn').addEventListener('click', () => {
    const amount = document.getElementById('amount').value;
    const qris_string = document.getElementById('merchantData').dataset.qris;
    const errorEl = document.getElementById('error');
    errorEl.textContent = '';

    if (!amount) {
        errorEl.textContent = 'Masukkan nominal.';
        return;
    }

    const formData = new FormData();
    formData.append('action', 'generate');
    formData.append('qris_string', qris_string);
    formData.append('amount', amount);

    fetch('ajax.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            document.getElementById('qrResult').style.display = 'block';
            document.getElementById('dynamicQris').value = data.dynamic_qris;
            document.getElementById('qrImage').src = data.qr_image;
        } else {
            errorEl.textContent = 'Gagal membuat QRIS dinamis.';
        }
    });
});

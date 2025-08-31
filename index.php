<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>QRIS Generator SPA</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="card">
    <h2>Upload QRIS</h2>
    <input type="file" id="qris" accept="image/*">
    <button id="uploadBtn">Upload</button>

    <div id="merchantData" style="display:none;">
        <h3>Data QRIS</h3>
        <p><b>Merchant Name:</b> <span id="merchantName"></span></p>
        <p><b>Merchant City:</b> <span id="merchantCity"></span></p>
        <p><b>Merchant ID:</b> <span id="merchantID"></span></p>

        <label>Nominal:</label>
        <input type="number" id="amount">
        <button id="generateBtn">Buat QRIS Dinamis</button>
    </div>

    <div id="qrResult" style="display:none;">
        <h3>QRIS Dinamis</h3>
        <textarea id="dynamicQris" rows="6" readonly></textarea>
        <div class="qr-img"><img id="qrImage" src="" alt="QRIS"></div>
    </div>

    <p id="error" style="color:red;"></p>
</div>

<script src="assets/js/script.js"></script>
</body>
</html>

<?php
require 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['qris'])) {
    $fileTmp = $_FILES['qris']['tmp_name'];
    $qrisString = decodeQrisImage($fileTmp);

    if (!$qrisString) {
        die("<div class='card'><p style='color:red'>Gagal decode QRIS.</p></div>");
    }

    $parsed = parseEMV($qrisString);
?>
<div class="card">
    <h3>Data QRIS</h3>
    <p><b>Merchant Name:</b> <?= $parsed['59'] ?? '-' ?></p>
    <p><b>Merchant City:</b> <?= $parsed['60'] ?? '-' ?></p>
    <p><b>Merchant ID:</b> <?= $parsed['26'] ?? '-' ?></p>

    <form method="post">
        <input type="hidden" name="qris_string" value="<?= htmlspecialchars($qrisString) ?>">
        <label>Nominal:</label><input type="number" name="amount" required>
        <button type="submit" name="generate">Buat QRIS Dinamis</button>
    </form>
</div>
<?php
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate'])) {
    $qrisString = $_POST['qris_string'];
    $amount = $_POST['amount'];

    $dynamicQris = generateDynamicQris($qrisString, $amount);
?>
<div class="card">
    <h3>QRIS Dinamis</h3>
    <textarea rows="6" readonly><?= $dynamicQris ?></textarea>
    <div class='qr-img'>
        <img src='https://api.qrserver.com/v1/create-qr-code/?data=<?= urlencode($dynamicQris) ?>&size=200x200'>
    </div>
</div>
<?php } ?>

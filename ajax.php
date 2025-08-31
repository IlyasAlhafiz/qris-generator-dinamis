<?php
require 'functions.php';

header('Content-Type: application/json');

$response = ['success' => false];

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'upload' && isset($_FILES['qris'])) {
        $fileTmp = $_FILES['qris']['tmp_name'];
        $qrisString = decodeQrisImage($fileTmp);

        if (!$qrisString) {
            $response['error'] = 'Gagal decode QRIS.';
        } else {
            $parsed = parseEMV($qrisString);
            $response['success'] = true;
            $response['qris_string'] = $qrisString;
            $response['merchant_name'] = $parsed['59'] ?? '-';
            $response['merchant_city'] = $parsed['60'] ?? '-';
            $response['merchant_id'] = $parsed['26'] ?? '-';
        }
    }

    if ($action === 'generate' && isset($_POST['qris_string'], $_POST['amount'])) {
        $qrisString = $_POST['qris_string'];
        $amount = $_POST['amount'];

        $dynamicQris = generateDynamicQris($qrisString, $amount);
        $response['success'] = true;
        $response['dynamic_qris'] = $dynamicQris;
        $response['qr_image'] = 'https://api.qrserver.com/v1/create-qr-code/?data=' . urlencode($dynamicQris) . '&size=200x200';
    }
}

echo json_encode($response);

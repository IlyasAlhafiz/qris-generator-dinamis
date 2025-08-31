<?php
require __DIR__ . '/vendor/autoload.php';

use Zxing\QrReader;

function parseEMV($qris) {
    $data = [];
    $i = 0;
    while ($i < strlen($qris)) {
        $tag = substr($qris, $i, 2);
        $len = intval(substr($qris, $i + 2, 2));
        $val = substr($qris, $i + 4, $len);
        $data[$tag] = $val;
        $i += 4 + $len;
    }
    return $data;
}

function generateDynamicQris($qrisString, $amount) {
    $parsed = parseEMV($qrisString);
    $parsed['54'] = $amount;

    $newQris = '';
    foreach ($parsed as $tag => $val) {
        $newQris .= $tag . sprintf("%02d", strlen($val)) . $val;
    }

    $newQrisNoCRC = preg_replace('/6304.{4}$/', '', $newQris);
    $crc = strtoupper(dechex(crc_ccitt(hex2bin(bin2hex($newQrisNoCRC . '6304')))));
    $crc = str_pad($crc, 4, '0', STR_PAD_LEFT);

    return $newQrisNoCRC . '6304' . $crc;
}

function crc_ccitt($data) {
    $crc = 0xFFFF;
    for ($i = 0; $i < strlen($data); $i++) {
        $crc ^= (ord($data[$i]) << 8);
        for ($j = 0; $j < 8; $j++) {
            if ($crc & 0x8000) {
                $crc = ($crc << 1) ^ 0x1021;
            } else {
                $crc <<= 1;
            }
            $crc &= 0xFFFF;
        }
    }
    return $crc;
}

function decodeQrisImage($filePath) {
    $qrcode = new QrReader($filePath);
    return $qrcode->text();
}

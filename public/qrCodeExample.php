<?php

/**
 * Minimal example showing how to generate
 * and directly output a QR code.
 */

require_once '../vendor/autoload.php';

use Radlinger\Mealplan\QrCode\QrCodeBuilder;

/**
 * Generate QR code with static content.
 */
$result = QrCodeBuilder::generate(data: 'Custom QR code contents', lable: 'This is the label');

// Output QR code directly
header('Content-Type: ' . $result->getMimeType());
echo $result->getString();

<?php

/**
 * Interactive QR code generator page.
 *
 * Users can enter a meal ID and receive
 * a dynamically generated QR code.
 */

require_once '../vendor/autoload.php';

use Radlinger\Mealplan\QrCode\QrCodeBuilder;
use Radlinger\Mealplan\View\TemplateEngine;

$apiLink = "http://localhost:8080/api.php?mealID=";

/**
 * Default template data.
 */
$data = [
    'head' => <<<HEAD
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Form</title>
        <link rel="stylesheet" href="/styles/style.css">
    </head>
    HEAD,
    'header' => "Generate Meal QR Code",
    'action' => htmlspecialchars($_SERVER['PHP_SELF']),
    'qr_result' => "",
    'error' => ""
];

/**
 * Handle form submission.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Trim and sanitize user input
    $mealId = trim($_POST['meal_id'] ?? '');

    /**
     * Validate input:
     * Meal ID must be numeric
     */
    if (!preg_match('/^\d+$/', $mealId)) {
        $data['error'] = '<p style="color:red">Invalid Meal ID format. (Needs to be a number)</p>';
    } else {
        /**
         * Generate QR code for the entered meal ID
         */
        $qr = QrCodeBuilder::generate($apiLink . $mealId, 'Meal Nr.: ' . $mealId)->getDataUri();

        $data['qr_result'] = '<img src="' . $qr . '" alt="Generated QR Code">';
    }
}

/**
 * Render the form template.
 */
echo TemplateEngine::render('../templates/form.html', $data);

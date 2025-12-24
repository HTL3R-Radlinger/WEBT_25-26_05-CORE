<?php

/**
 * Entry point of the application.
 *
 * This file:
 * - loads demo meal plans
 * - generates QR codes for each meal plan
 * - prepares structured data for the template
 * - renders the final HTML using the TemplateEngine
 */

require_once '../vendor/autoload.php';

use Radlinger\Mealplan\QrCode\QrCodeBuilder;
use Radlinger\Mealplan\Seeder\MealSeeder;
use Radlinger\Mealplan\View\TemplateEngine;

/**
 * Generate demo meal plans.
 * This replaces a database for this prototype.
 */
$mealPlans = MealSeeder::generate();

/**
 * Base API link used inside QR codes.
 * Each QR code will point to a specific meal plan ID.
 */
$apiLink = "http://localhost:8080/api.php?mealID=";

/**
 * Data array passed to the template engine.
 * It contains all variables and loop structures
 * required by index.html.
 */
$data = [
    'head' => <<<HEAD
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>MealPlans</title>
        <link rel="stylesheet" href="/styles/style.css">
    </head>
    HEAD,

    // Page headline
    'header' => "All Meal Plans",

    // Container for dynamically rendered meal plans
    'plans' => [],
];

/**
 * Convert each MealPlan object into a template-friendly structure.
 * QR codes are generated dynamically for each plan.
 */
foreach ($mealPlans as $plan) {
    $data['plans'][] = (object)[
        'plan_name' => $plan->name,
        'school_name' => $plan->schoolName,
        'week_of_delivery' => $plan->weekOfDelivery,
        'plan_meals' => $plan->meals,

        // QR code linking to the API endpoint
        'qr_code' => QrCodeBuilder::generate($apiLink . $plan->id, 'MealPlan Nr.: ' . $plan->id)->getDataUri()
    ];
}

/**
 * Render the HTML template with all prepared data.
 */
echo TemplateEngine::render('../templates/index.html', $data);

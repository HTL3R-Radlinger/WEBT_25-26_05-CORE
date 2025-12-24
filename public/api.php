<?php

/**
 * Simple JSON API endpoint.
 *
 * This file:
 * - receives a mealplanID via GET
 * - returns the corresponding meal plan as JSON
 */

require_once '../vendor/autoload.php';

use Radlinger\Mealplan\Api\GetMeals;

// Ensure JSON response
header("Content-Type: application/json");

/**
 * Validate input and return meal data.
 */
if (isset($_GET["mealID"])) {
    echo GetMeals::getMealWithId((int)$_GET["mealID"]);
} else {
    echo json_encode(["error" => "No meal ID provided!"]);
}

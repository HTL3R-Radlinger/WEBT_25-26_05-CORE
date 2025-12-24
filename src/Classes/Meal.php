<?php

namespace Radlinger\Mealplan\Classes;

class Meal
{
    public int $id;
    public string $name;
    public string $allergens;
    public string $nutritionalInfo;
    public float $price;

    public function __construct(
        int    $id,
        string $name,
        string $allergens,
        string $nutritionalInfo,
        float  $price,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->allergens = $allergens;
        $this->nutritionalInfo = $nutritionalInfo;
        $this->price = $price;
    }
}

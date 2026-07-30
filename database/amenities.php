<?php
require __DIR__ . "/../vendor/autoload.php";
require __DIR__ . "/../Bootstrap.php";

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('amenities', function ($table) {
    $table->id();
    $table->string('name', 50);
    $table->timestamps();
});

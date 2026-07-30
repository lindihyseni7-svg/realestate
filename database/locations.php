<?php
require __DIR__ . "/../vendor/autoload.php";
require __DIR__ . "/../Bootstrap.php";

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('locations', function ($table) {
    $table->id();
    $table->string('name', 100);
    $table->string('type', 50)->nullable();
    $table->timestamps();
});

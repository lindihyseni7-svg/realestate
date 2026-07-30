<?php
require __DIR__ . "/../vendor/autoload.php";
require __DIR__ . "/../Bootstrap.php";

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('agents', function ($table) {
    $table->id();
    $table->string('name', 100);
    $table->string('email')->unique();
    $table->string('phone', 30)->nullable();
    $table->string('agency', 100)->nullable();
    $table->text('bio')->nullable();
    $table->timestamps();
});

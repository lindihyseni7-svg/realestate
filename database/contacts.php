<?php
require __DIR__ . "/../vendor/autoload.php";
require __DIR__ . "/../Bootstrap.php";

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('contacts', function ($table) {
    $table->id();
    $table->string('first_name', 30);
    $table->string('last_name', 30);
    $table->string('department', 100)->nullable();
    $table->text('message')->nullable();
    $table->string('email');
    $table->timestamps();
});

<?php
require __DIR__ . "/../vendor/autoload.php";
require __DIR__ . "/../Bootstrap.php";

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('listings', function ($table) {
    $table->id();
    $table->string('address');
    $table->decimal('price', 10, 2); // Assuming price is in decimal format with 10 digits in total and 2 decimal places
    $table->integer('bedrooms')->nullable();
    $table->integer('bathrooms')->nullable();
    $table->text('description')->nullable();
    $table->unsignedBigInteger('agent_id'); // Foreign key referencing the agent table
    $table->unsignedBigInteger('type_id'); // Foreign key referencing the property types table
    $table->timestamps();

    // Foreign key constraints
    $table->foreign('agent_id')->references('id')->on('agents')->onDelete('cascade');
    $table->foreign('type_id')->references('id')->on('property_types')->onDelete('cascade');
});

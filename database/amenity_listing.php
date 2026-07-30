<?php
require __DIR__ . "/../vendor/autoload.php";
require __DIR__ . "/../Bootstrap.php";

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('amenity_listing', function ($table) {
    $table->id();
    $table->unsignedBigInteger('amenity_id');
    $table->unsignedBigInteger('listing_id');
    $table->timestamps();

    $table->unique(['amenity_id', 'listing_id']);
    $table->foreign('amenity_id')->references('id')->on('amenities')->onDelete('cascade');
    $table->foreign('listing_id')->references('id')->on('listings')->onDelete('cascade');
});

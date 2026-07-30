<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function propertyType()
    {
        return $this->belongsTo(PropertyType::class, 'type_id');
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'amenity_listing');
    }
}

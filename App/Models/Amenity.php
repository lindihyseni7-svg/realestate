<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model ;

class Amenity extends Model
{
    public function listings()
    {
        return $this->belongsToMany(Listing::class, 'amenity_listing');
    }
}

<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyType extends Model
{
    protected $table = 'property_types';

    public function listings()
    {
        return $this->hasMany(Listing::class, 'type_id');
    }
}

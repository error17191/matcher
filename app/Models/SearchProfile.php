<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static SearchProfile make(array $attributes)
 */

class SearchProfile extends Model
{
    use HasFactory;

    protected $casts = [
        'searchFields' => 'array'
    ];

    protected $guarded = [];

    public function getSearchProfilePropertyType()
    {
        return $this->propertyType;
    }

    public function getSearchProfileFields()
    {
        return $this->searchFields;
    }


}

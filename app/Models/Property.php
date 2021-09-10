<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static Property make(array $attributes)
 */
class Property extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getPropertyType()
    {
        return $this->propertyType;
    }

    public function getPropertyFields()
    {
        return $this->fields;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $connection = 'pgsql_second';

    protected $table = 'courses';

    public function sections()
    {
        return $this->hasMany(Section::class);
    }
}

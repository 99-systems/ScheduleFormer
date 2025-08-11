<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $connection = 'pgsql_second';

    protected $table = 'sections';

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}

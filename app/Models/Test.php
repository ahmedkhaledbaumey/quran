<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;
    // protected $guarded = [] ;
    protected $fillable = [
        'name',
        'details',
    ]; 

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    
}

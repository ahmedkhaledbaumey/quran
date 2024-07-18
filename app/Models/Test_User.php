<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test_User extends Model
{
    use HasFactory;

    protected $table = 'test__users'; // اسم الجدول الذي يتم الارتباط به

    protected $fillable = [
        'user_id',
        'test_id',
        'teacher_name',
        'grade',
    ];

    // تعريف العلاقات
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function test()
    {
        return $this->belongsTo(Test::class);
    }
}

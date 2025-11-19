<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    /**
     * Bidang yang boleh diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama',
        'email',
        'user_id',
    ];

    /**
     * Mendapatkan data user yang terkait dengan admin.
     */
    public function user()
    {
        // Ini memberi tahu Laravel bahwa model Admin ini
        // terhubung ke model User melalui 'user_id'
        return $this->belongsTo(User::class);
    }
}

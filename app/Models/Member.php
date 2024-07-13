<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'user_id',
        'member_group_id',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function memberGroup() {
        return $this->belongsTo(MemberGroup::class);
    }
}

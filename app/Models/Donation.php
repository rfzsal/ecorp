<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'nominal',
        'type',
        'member_id',
        'member_group_id',
    ];

    public function member() {
        return $this->belongsTo(Member::class);
    }

    public function memberGroup() {
        return $this->belongsTo(MemberGroup::class);
    }
}

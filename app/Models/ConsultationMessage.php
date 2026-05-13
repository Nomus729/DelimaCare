<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultationMessage extends Model
{
    use HasFactory;

    // Pakai username sesuai dengan cara abang narik data rekam medis
    protected $fillable = ['username', 'sender', 'type', 'message'];
}

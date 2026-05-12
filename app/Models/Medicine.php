<?php

namespace App\Models;

use App\Traits\HybridSync;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory, HybridSync;

    protected $fillable = [
        'name',
        'brand',
        'category',
        'stock',
        'unit',
        'price',
        'min_stock',
        'expired_at',
    ];

    protected $casts = [
        'expired_at' => 'date',
    ];
    /**
     * Scope for searching medicines by name or brand.
     */
    public function scopeSearch($query, $search)
    {
        return $query->when($search, function ($q) use ($search) {
            $q->where(function ($q2) use ($search) {
                $q2->where('name', 'like', '%' . $search . '%')
                   ->orWhere('brand', 'like', '%' . $search . '%');
            });
        });
    }

    /**
     * Scope for sorting medicines.
     */
    public function scopeSort($query, $sort)
    {
        switch ($sort) {
            case 'name_asc':
                return $query->orderBy('name', 'asc');
            case 'name_desc':
                return $query->orderBy('name', 'desc');
            case 'stock_asc':
                return $query->orderBy('stock', 'asc');
            case 'stock_desc':
                return $query->orderBy('stock', 'desc');
            case 'price_asc':
                return $query->orderBy('price', 'asc');
            case 'price_desc':
                return $query->orderBy('price', 'desc');
            case 'expired_asc':
                return $query->orderBy('expired_at', 'asc');
            case 'expired_desc':
                return $query->orderBy('expired_at', 'desc');
            case 'latest':
                return $query->latest();
            default:
                return $query->orderBy('name', 'asc');
        }
    }

    /**
     * Scope for filtering medicines.
     */
    public function scopeFilter($query, $filter)
    {
        return $query->when($filter == 'low_stock', function ($q) {
            $q->whereRaw('stock <= min_stock');
        })->when($filter == 'expired', function ($q) {
            $q->where('expired_at', '<', now());
        })->when($filter == 'near_expiry', function ($q) {
            $q->whereBetween('expired_at', [now(), now()->addDays(30)]);
        });
    }

    /**
     * Get the expiration status.
     */
    public function getExpirationStatusAttribute()
    {
        if (!$this->expired_at) return 'safe';
        
        if ($this->expired_at->isPast()) {
            return 'expired';
        }
        
        if ($this->expired_at->diffInDays(now()) <= 30) {
            return 'near_expiry';
        }
        
        return 'safe';
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SiteVisit extends Model
{
    protected $table = 'site_visits';
    protected $fillable = ['total'];

    /** Incrémente le compteur global de façon atomique */
    public static function increment(): void
    {
        DB::table('site_visits')->where('id', 1)->increment('total');
    }

    /** Retourne le total de visites */
    public static function total(): int
    {
        return (int) DB::table('site_visits')->where('id', 1)->value('total') ?? 0;
    }
}

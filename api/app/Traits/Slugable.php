<?php

namespace App\Traits;

trait Slugable
{
    public static function boot()
    {
        parent::boot();

        self::creating(function($m){
            $m->slug = \Str::slug($m->title) . '-' . time();
        });

        self::updating(function($m){
            $m->slug = \Str::slug($m->title) . '-' . time();
        });
    }
}

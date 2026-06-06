<?php

namespace App\Traits;

use App\Jobs\SyncToRemoteJob;

trait HybridSync
{
    /**
     * Boot the trait and register Eloquent observers.
     */
    public static function bootHybridSync()
    {
        if (env('DISABLE_HYBRID_SYNC', false)) {
            return;
        }

        static::created(function ($model) {
            SyncToRemoteJob::dispatch(get_class($model), $model->getAttributes(), 'create');
        });

        static::updated(function ($model) {
            SyncToRemoteJob::dispatch(get_class($model), $model->getAttributes(), 'update');
        });

        static::deleted(function ($model) {
            SyncToRemoteJob::dispatch(get_class($model), $model->getAttributes(), 'delete');
        });
    }
}

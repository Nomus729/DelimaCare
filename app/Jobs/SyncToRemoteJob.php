<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncToRemoteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $modelClass;
    protected $attributes;
    protected $action;

    /**
     * Create a new job instance.
     */
    public function __construct($modelClass, $attributes, $action)
    {
        $this->modelClass = $modelClass;
        $this->attributes = $attributes;
        $this->action = $action;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        if (app()->environment('testing')) {
            return;
        }

        try {
            $remoteConn = DB::connection('mysql_remote');
            $tableName = (new $this->modelClass)->getTable();
            $primaryKey = (new $this->modelClass)->getKeyName();
            $id = $this->attributes[$primaryKey];

            switch ($this->action) {
                case 'create':
                case 'update':
                    // We use updateOrInsert to ensure idempotency
                    $remoteConn->table($tableName)->updateOrInsert(
                        [$primaryKey => $id],
                        $this->attributes
                    );
                    break;

                case 'delete':
                    $remoteConn->table($tableName)->where($primaryKey, $id)->delete();
                    break;
            }
        } catch (\Exception $e) {
            Log::error("Failed to sync {$this->modelClass} to remote: " . $e->getMessage());
            // Fail the job so it can be retried if configured
            throw $e;
        }
    }
}

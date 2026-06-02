<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

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
        if (app()->environment('testing') && !config('queue.sync_test_mode')) {
            return;
        }

        try {
            $remoteConn = DB::connection('mysql_remote');
            $tableName = (new $this->modelClass)->getTable();
            $primaryKey = (new $this->modelClass)->getKeyName();
            $id = $this->attributes[$primaryKey];

            // Filter attributes to only include columns that actually exist in the remote database table
            $remoteColumns = Schema::connection('mysql_remote')->getColumnListing($tableName);
            $filteredAttributes = array_intersect_key(
                $this->attributes,
                array_flip($remoteColumns)
            );

            switch ($this->action) {
                case 'create':
                case 'update':
                    // We use updateOrInsert to ensure idempotency
                    $remoteConn->table($tableName)->updateOrInsert(
                        [$primaryKey => $id],
                        $filteredAttributes
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

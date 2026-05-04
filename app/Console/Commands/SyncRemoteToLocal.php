<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Medicine;
use App\Models\Article;
use App\Models\RekamMedis;
use App\Models\Reservasi;

class SyncRemoteToLocal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:remote-to-local {--full : Perform a full sync instead of incremental}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync data from Remote MySQL to Local SQLite';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isFullSync = $this->option('full');
        $this->info($isFullSync ? 'Starting full sync from remote to local...' : 'Starting incremental sync from remote to local...');

        $models = [
            \App\Models\User::class,
            \App\Models\Doctor::class,
            \App\Models\Medicine::class,
            \App\Models\Article::class,
            \App\Models\RekamMedis::class,
            \App\Models\Reservasi::class,
            \App\Models\ResepMedis::class,
            \App\Models\ResepMedisItem::class,
        ];

        foreach ($models as $modelClass) {
            $tableName = (new $modelClass)->getTable();
            $this->info("Processing table: {$tableName}");

            try {
                $query = DB::connection('mysql_remote')->table($tableName);
                
                if (!$isFullSync) {
                    $lastSyncKey = "sync_last_updated_{$tableName}";
                    $lastUpdated = \Illuminate\Support\Facades\Cache::get($lastSyncKey);
                    
                    if ($lastUpdated) {
                        $query->where('updated_at', '>', $lastUpdated);
                        $this->comment("Fetching changes since {$lastUpdated}");
                    }
                }

                $remoteData = $query->get();
                $count = 0;
                $latestUpdatedInBatch = null;

                foreach ($remoteData as $data) {
                    $attributes = (array)$data;
                    $primaryKey = (new $modelClass)->getKeyName();
                    
                    // Filter attributes that actually exist in the local table to avoid SQL errors
                    // In a more robust implementation, we could check the table schema
                    
                    DB::connection('sqlite')->table($tableName)->updateOrInsert(
                        [$primaryKey => $attributes[$primaryKey]],
                        $attributes
                    );

                    if (isset($attributes['updated_at'])) {
                        if (!$latestUpdatedInBatch || $attributes['updated_at'] > $latestUpdatedInBatch) {
                            $latestUpdatedInBatch = $attributes['updated_at'];
                        }
                    }

                    $count++;
                }

                if ($latestUpdatedInBatch) {
                    \Illuminate\Support\Facades\Cache::forever("sync_last_updated_{$tableName}", $latestUpdatedInBatch);
                }

                $this->info("Successfully synced {$count} records for {$tableName}");
            } catch (\Exception $e) {
                $this->error("Error syncing {$tableName}: " . $e->getMessage());
            }
        }

        $this->info('Sync completed!');
    }
}

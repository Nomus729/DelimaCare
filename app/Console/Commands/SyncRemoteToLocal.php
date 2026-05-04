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
    protected $signature = 'sync:remote-to-local';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all data from Remote MySQL to Local SQLite for the first time';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting sync from remote to local...');

        $models = [
            User::class,
            Doctor::class,
            Medicine::class,
            Article::class,
            RekamMedis::class,
            Reservasi::class,
        ];

        foreach ($models as $modelClass) {
            $tableName = (new $modelClass)->getTable();
            $this->info("Processing table: {$tableName}");

            try {
                $remoteData = DB::connection('mysql_remote')->table($tableName)->get();
                $count = 0;

                foreach ($remoteData as $data) {
                    $attributes = (array)$data;
                    $primaryKey = (new $modelClass)->getKeyName();
                    
                    DB::connection('sqlite')->table($tableName)->updateOrInsert(
                        [$primaryKey => $attributes[$primaryKey]],
                        $attributes
                    );
                    $count++;
                }

                $this->info("Successfully synced {$count} records for {$tableName}");
            } catch (\Exception $e) {
                $this->error("Error syncing {$tableName}: " . $e->getMessage());
            }
        }

        $this->info('Sync completed!');
    }
}

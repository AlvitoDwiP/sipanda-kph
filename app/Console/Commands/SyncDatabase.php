<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:sync-sqlite-to-mysql';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all data from SQLite to MySQL';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Override MySQL connection config to use docker settings
        config([
            'database.connections.mysql.host' => '127.0.0.1',
            'database.connections.mysql.port' => '3306',
            'database.connections.mysql.database' => 'kepegawaian_db',
            'database.connections.mysql.username' => 'kepegawaian_user',
            'database.connections.mysql.password' => 'password',
        ]);

        $this->info('Starting database sync from SQLite to MySQL...');

        // Get all tables from SQLite
        $tables = DB::connection('sqlite')->select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");

        DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=0;');

        foreach ($tables as $tableInfo) {
            $table = $tableInfo->name;

            if ($table === 'migrations') {
                continue; // Skip migrations table to avoid confusion
            }

            $this->info("Syncing table: {$table}");

            // Clear destination table
            DB::connection('mysql')->table($table)->truncate();

            // Get data from source
            $rows = DB::connection('sqlite')->table($table)->get();

            // Insert data to destination in chunks to prevent memory issues
            $chunks = $rows->chunk(500);
            foreach ($chunks as $chunk) {
                // Convert objects to arrays
                $data = $chunk->map(function ($item) {
                    return (array) $item;
                })->toArray();

                DB::connection('mysql')->table($table)->insert($data);
            }
        }

        DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->info('Database sync completed successfully!');
    }
}

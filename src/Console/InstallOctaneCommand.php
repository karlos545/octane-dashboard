<?php

namespace Octane\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Octane\Seeds\ModuleManagerSeeder;
use Octane\Seeds\RolesAndPermissionsSeeder;

class InstallOctaneCommand extends Command
{
    protected $signature = 'octane:install';

    protected $userModel;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("Deleting default migrations");
        $this->deleteDefaultUserMigrations();
        $this->info("Seeding roles and default modules");
        $this->seedRolesAndPermissions();
        $this->info("Publishing assets for dashboard");
        $this->publishAssetsForDashboard();

        $this->info("Success! Octane dashboard has been installed!");
    }

    private function deleteDefaultUserMigrations()
    {
        collect(['migrations/2014_10_12_000000_create_users_table.php', 'migrations/2014_10_12_100000_create_password_resets_table.php'])->each(function ($path) {
            file_exists(database_path($path)) ? unlink(database_path($path)) : null;
        });
    }

    private function seedRolesAndPermissions()
    {
        collect([ModuleManagerSeeder::class, RolesAndPermissionsSeeder::class])->each(function ($seeder) {
            app($seeder)->run();
        });
    }

    private function publishAssetsForDashboard()
    {
        Artisan::call('vendor:publish', ['--provider' => 'JeroenNoten\LaravelAdminLte\ServiceProvider', '--tag' => 'assets']);
    }
}

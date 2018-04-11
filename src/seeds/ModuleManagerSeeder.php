<?php

namespace Octane\Seeds;

use Illuminate\Database\Seeder;
use Octane\Modules\ModuleModel;

class ModuleManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $modules = config('octane.modules');

        return collect($modules)->map(function ($module) {
            return ModuleModel::firstOrCreate([
                'module_class_name' => class_basename($module),
            ], ['visible_to' => ['roles' => ['superadmin']]]);
        });
    }
}

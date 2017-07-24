<?php
namespace Baytek\Laravel\Content\Types\Event\Commands;

use Baytek\Laravel\Content\Models\Content;
use Baytek\Laravel\Content\Commands\Installer;
use Baytek\Laravel\Content\Types\Event\Seeders\EventSeeder;
use Baytek\Laravel\Content\Types\Event\Seeders\FakeDataSeeder;
use Baytek\Laravel\Content\Types\Event\Event;
use Baytek\Laravel\Content\Types\Event\EventContentServiceProvider;
use Spatie\Permission\Models\Permission;

use Artisan;
use DB;

class EventInstaller extends Installer
{
    public $name = 'Event';
    protected $protected = ['Event', 'Event Category'];
    protected $provider = EventContentServiceProvider::class;
    protected $model = Event::class;
    protected $seeder = EventSeeder::class;
    protected $fakeSeeder = FakeDataSeeder::class;
    protected $migrationPath = __DIR__.'/../resources/Database/Migrations';

    public function shouldPublish()
    {
        return true;
    }

    public function shouldMigrate()
    {
        $pluginTables = [
            env('DB_PREFIX', '').'contents',
            env('DB_PREFIX', '').'content_meta',
            env('DB_PREFIX', '').'content_histories',
            env('DB_PREFIX', '').'content_relations',
        ];

        return collect(array_map('reset', DB::select('SHOW TABLES')))
            ->intersect($pluginTables)
            ->isEmpty();
    }

    public function shouldSeed()
    {
        $relevantRecords = [
            'event',
        ];

        return Content::whereIn('key', $relevantRecords)->count() === 0;
    }

    public function shouldProtect()
    {
        foreach ($protected as $model) {
            foreach(['view', 'create', 'update', 'delete'] as $permission) {

                // If the permission exists in any form do not reseed.
                if(Permission::where('name', title_case($permission.' '.$model)->exists()) {
                    return false;
                }
            }
        }

        return true;
    }
}

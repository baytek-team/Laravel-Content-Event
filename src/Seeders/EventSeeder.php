<?php
namespace Baytek\Laravel\Content\Types\Event\Seeders;

use Baytek\Laravel\Content\Seeder;

class EventSeeder extends Seeder
{
    private $data = [
        [
            'key' => 'event',
            'title' => 'Event',
            'content' => 'Baytek\Laravel\Content\Types\Event\Models\Event',
            'relations' => [
                ['parent-id', 'content-type']
            ]
        ],
        [
            'key' => 'event-category',
            'title' => 'Event Category',
            'content' => 'Baytek\Laravel\Content\Types\Event\Models\Category',
            'relations' => [
                ['parent-id', 'content-type'],
            ]
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seedStructure($this->data);
    }
}

<?php
namespace Baytek\Laravel\Content\Types\Event\Seeders;

use Baytek\Laravel\Content\Seeder;

class EventSeeder extends Seeder
{
    private $data = [
        [
            'key' => 'event',
            'title' => 'Event',
            'content' => \Baytek\Laravel\Content\Types\Event\Models\Event::class,
            'relations' => [
                ['parent-id', 'content-type']
            ]
        ],
        [
            'key' => 'event-category',
            'title' => 'Event Category',
            'content' => \Baytek\Laravel\Content\Types\Event\Models\Category::class,
            'relations' => [
                ['parent-id', 'content-type'],
            ]
        ],
        [
            'key' => 'event-menu',
            'title' => 'Event Navigation Menu',
            'content' => '',
            'relations' => [
                ['content-type', 'menu'],
                ['parent-id', 'admin-menu'],
            ]
        ],
        [
            'key' => 'event-index',
            'title' => 'Events',
            'content' => 'event.index',
            'meta' => [
                'type' => 'route',
                'class' => 'item',
                'append' => '</span>',
                'prepend' => '<i class="calendar left icon"></i><span class="collapseable-text">',
            ],
            'relations' => [
                ['content-type', 'menu-item'],
                ['parent-id', 'event-menu'],
            ]
        ]
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

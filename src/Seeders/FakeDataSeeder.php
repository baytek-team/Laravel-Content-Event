<?php

namespace Baytek\Laravel\Content\Types\Event\Seeders;

use Illuminate\Database\Seeder;

use Baytek\Laravel\Content\Types\Event\Models\Event;
use Baytek\Laravel\Content\Types\Event\Models\Category;

class FakeDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->generateEventCategories();
        $this->generateEvents();
    }

    protected function generateEventCategories($total = 10)
    {
        $content_type = content('content-type/event-category', false);

        foreach(range(1,$total) as $index) {
            $category = (factory(Category::class)->make());
            $category->save();

            //Add relationships
            $category->saveRelation('content-type', $content_type);
            $category->saveRelation('parent-id', $content_type);

            //Add metadata
            $category->saveMetadata('author_id', 1);
        }
    }

    protected function generateEvents($total = 100)
    {
        //Generate events
        //Assign them to a category
        $content_type = content('content-type/event', false);
        $categories = Category::all();

        $earliest_date = time() - (1*365*24*60*60); //1 year go
        $latest_date = time() + (1*365*24*60*60); //In 1 year

        foreach(range(1,$total) as $index) {
            //Choose a parent at random
            $category = $categories->random()->id;

            $event = (factory(Event::class)->make());
            $event->save();

            //Add relationships
            $event->saveRelation('content-type', $content_type);
            $event->saveRelation('category', $category);

            //Add metadata
            $event->saveMetadata('author_id', 1);
            $event->saveMetadata('event_date', $this->randomDate($earliest_date, $latest_date));
        }
    }

    protected function randomDate($start, $end)
    {
        return date('Y-m-d H:i:s', rand($start, $end));
    }
}

<?php

namespace Database\Seeders;

use App\Models\Club;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClubsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $resourcePath = 'https://resources.premierleague.com/premierleague/badges/25/';

        $clubsData = collect([
            ['name' => 'Arsenal', 'thumbnail' => 't3.png'],
            ['name' => 'Aston Villa', 'thumbnail' => 't7.png'],
            ['name' => 'Brighton and Hove Albion', 'thumbnail' => 't36.png'],
            ['name' => 'Burnley', 'thumbnail' => 't90.png'],
            ['name' => 'Chelsea', 'thumbnail' => 't8.png'],
            ['name' => 'Crystal Palace', 'thumbnail' => 't31.png'],
            ['name' => 'Everton', 'thumbnail' => 't11.png'],
            ['name' => 'Fulham', 'thumbnail' => 't54.png'],
            ['name' => 'Leeds United', 'thumbnail' => 't2.png'],
            ['name' => 'Leicester City', 'thumbnail' => 't13.png'],
            ['name' => 'Liverpool', 'thumbnail' => 't14.png'],
            ['name' => 'Manchester City', 'thumbnail' => 't43.png'],
            ['name' => 'Manchester United', 'thumbnail' => 't1.png'],
            ['name' => 'Newcastle United', 'thumbnail' => 't4.png'],
            ['name' => 'Sheffield United', 'thumbnail' => 't49.png'],
            ['name' => 'Southampton', 'thumbnail' => 't20.png'],
            ['name' => 'Tottenham Hotspur', 'thumbnail' => 't6.png'],
            ['name' => 'West Bromwich Albion', 'thumbnail' => 't35.png'],
            ['name' => 'West Ham United', 'thumbnail' => 't21.png'],
            ['name' => 'Wolverhampton Wanderers', 'thumbnail' => 't39.png'],
        ]);

        $clubsData->transform(function ($item, $key) use ($resourcePath) {
            $item['thumbnail_path'] = $resourcePath.$item['thumbnail'];
            unset($item['thumbnail']);

            return $item;
        });

        DB::beginTransaction();
        Club::query()->insert($clubsData->toArray());
        DB::commit();
    }
}

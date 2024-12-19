<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use SevenUpp\Models\Hotel;

class HotelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        return;
        // deprecated!
        if (app()->environment('production')) {
            echo "HotelsSeeder should not be run on prod server\n";
            return;
        }

        DB::table('hotels')->truncate();

        $file = fopen('hotels-data.csv', 'r');
        $hotelsData = [];
        $firstLine = true;

        while (($column = fgetcsv($file, 0, ',')) !== false) {
            if ($firstLine) {
                $firstLine = false;
                continue;
            }

            $hotelsData[] = [
                'name' => $column[1],
                'type' => $column[5],
                'active' => 1,
                'hotel_property_id' => $column[0],
                'description' => '',
                'location' => $column[2],
                'country' => $column[3],
                'region' => $column[4],
                'capacity' => 1,
                'tags' => '[]',
                'created_at' => '2022-04-14 10:42:20',
                'updated_at' => '2022-04-14 10:42:20',
                'deleted_at' => NULL,
            ];
        }

        Hotel::insert($hotelsData);
    }
}

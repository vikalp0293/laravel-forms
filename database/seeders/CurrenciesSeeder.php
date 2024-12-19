<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use SevenUpp\Models\Currency;

class CurrenciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * This based on smith api currencies ISO currency
     *
     * @return void
     */
    public function run()
    {
        $datas = [
            'GBP',
            'EUR',
            'USD',
            'AUD',
            'CAD',
            'HKD',
            'SGD',
            'SEK',
            'CHF',
            'NOK',
            'NZD',
            'DKK',
            'AED',
            'ZAR',
            'BRL',
        ];

        $currencyData = [];

        foreach ($datas as $data) {
            $currencyData[] = [
                'name' => $data,
                'meta' => NULL,
                'created_at' => '2022-04-14 10:42:20',
                'updated_at' => '2022-04-14 10:42:20',
            ];
        }

        Currency::insert($currencyData);
    }
}

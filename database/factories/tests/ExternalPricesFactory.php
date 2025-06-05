<?php

namespace Database\Factories\tests;

use Faker\Generator as Faker;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ExternalPricesFactory
{
    protected Faker $faker;

    public function __construct()
    {
        $this->faker = app(Faker::class);
    }

    public function make(): array
    {
        return [
            "symbol" => $this->faker->companySuffix,
            "price" => $this->faker->randomFloat(2,1,1000),
            "last_price_datetime" =>   Carbon::instance(fake()->dateTime())
                                    ->setTimezone('-04:00')
                                    ->toIso8601String()
        ];
    }

    public function makeMany(int $count): array
    {
        return collect(range(1, $count))
            ->map(fn () => $this->make())
            ->all();
    }

    public function makeBySymbol($symbol): array
    {
        return [
            "symbol" => $symbol,
            "price" => $this->faker->randomFloat(2,1,1000),
            "last_price_datetime" =>   Carbon::instance(fake()->dateTime())
                ->setTimezone('-04:00')
                ->toIso8601String()
        ];
    }

    public function makeManyByType(string $typeName): array
    {
        $symbols = config('app.securities_symbols')[$typeName];
        return collect(range(0, count($symbols)-1))
            ->map(fn ($v, $k) => $this->makeBySymbol($symbols[$k]))
            ->all();
    }
}

<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Models\Publication;
use App\Models\Scholar;
use App\Models\SupervisorProfile;
use App\Types\CitationIndex;
use App\Types\PublicationType;
use Faker\Generator as Faker;

$factory->define(Publication::class, function (Faker $faker) {
    return [
        'type' => $type = $faker->randomElement(PublicationType::values()),
        'paper_title' => $faker->sentence,
        'name' => $faker->sentence,
        'volume' => $faker->numberBetween(1, 20),
        'page_numbers' => static function () use ($faker) {
            $from = random_int(1, 10000);
            $pages = random_int(1, 10000);
            return [$from, $from + $pages];
        },
        'date' => $faker->date,
        'indexed_in' => static function () use ($faker) {
            $size = random_int(1, 3);
            $indexed_in = array_fill(0, $size, 'NULL');
            return array_map(function () use ($faker) {
                return $faker->randomElement(CitationIndex::values());
            }, $indexed_in);
        },
        'main_author_type' => $type = $faker->randomElement([Scholar::class, SupervisorProfile::class]),
        'main_author_id' => factory($type)->create()->id,
        'number' => function ($publication) use ($faker) {
            return $publication['type'] === PublicationType::JOURNAL
                ? $faker->randomNumber(2) : null;
        },
        'publisher' => function ($publication) use ($faker) {
            return $publication['type'] === PublicationType::JOURNAL
                ? $faker->name : null;
        },
        'city' => function ($publication) use ($faker) {
            return $publication['type'] === PublicationType::CONFERENCE
                ? $faker->city : null;
        },
        'country' => function ($publication) use ($faker) {
            return $publication['type'] === PublicationType::CONFERENCE
                ? $faker->country : null;
        },
    ];
});

<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/
use App\Group;
use App\School;
use App\Topic;
use App\User;
use App\Category;
use Illuminate\Support\Facades\Storage;
/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\School::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker -> unique()->country,
    ];
});

$factory->define(App\Group::class, function (Faker\Generator $faker) {
    return [
        'name'      => $faker -> unique() -> countryCode,
        'school_id' => $faker -> randomElement(School::pluck('id')->toArray()),
    ];
});

$factory->define(App\User::class, function (Faker\Generator $faker) {
    $group_id = $faker -> randomElement(Group::pluck('id')->toArray());
    $group = Group::where('id', $group_id) -> first();
    $school_id = $group -> school_id;
    return [
        'name'      => $faker -> name,
        'lastname'  => $faker -> lastName,
        'password'  => $faker -> password(3, 3),
        'email'     => $faker -> email,
        'type'      => $faker -> randomElement(['alumno', 'administrador']),
        'group_id'  => $group_id,
        'school_id' => $school_id,
    ];
});

$factory->define(App\Category::class, function (Faker\Generator $faker) {
    $folder_name = $faker -> unique() -> randomElement([
        'Estructuras de Datos',
        'Geometría Computacional',
        'Teoria de Graficas',
        'Teoria de Numeros',
        'Procesamiento de Cadenas',
        'Paradigmas y Tecnicas',
        'Avanzados']);
    Storage::disk('local')->makeDirectory('public/'.$folder_name);
    return [
        'name'      =>  $folder_name,
    ];
});

$factory->define(App\Topic::class, function (Faker\Generator $faker) {
    $folder_name = $faker -> unique() -> numberBetween(1, 20)."TEMA";
    $category_id = $faker -> numberBetween(1,7);
    $category = Category::where('id', '=', $category_id) -> get() -> first();
    Storage::disk('local')->makeDirectory('public/'. $category -> name. '/'.$folder_name);
    Storage::disk('local')->makeDirectory('public/'. $category -> name. '/'.$folder_name.'/Simulacion');
    Storage::disk('local')->makeDirectory('public/'. $category -> name. '/'.$folder_name.'/Simulacion/js');
    Storage::disk('local')->makeDirectory('public/'. $category -> name. '/'.$folder_name.'/Simulacion/css');
    Storage::disk('local')->makeDirectory('public/'. $category -> name. '/'.$folder_name.'/Teoria');
    Storage::disk('local')->makeDirectory('public/'. $category -> name. '/'.$folder_name.'/Cuestionario');
    return [
        'name'        => $folder_name,
        'category_id' => $category_id,
    ];
});

$factory->define(App\Reference::class, function (Faker\Generator $faker) {
    $topic_id = $faker->randomElement(Topic::pluck('id')->toArray());
    $topic = Topic::where('id', $topic_id) -> first();
    $category_id = $topic -> category_id;
    return [
        'type'          => $faker -> randomElement(['C','S','T']),
        'route'         => $faker -> url,
        'topic_id'      => $topic_id,
        'category_id'   => $category_id,
    ];
});

$factory->define(App\Link::class, function (Faker\Generator $faker) {
    $user_id = $faker->randomElement(User::pluck('id')->toArray());
    $user = User::where('id', $user_id) -> first();
    $group_id  = $user -> group_id;
    $school_id = $user -> school_id;
    $topic_id = $faker->randomElement(Topic::pluck('id')->toArray());
    return [
        'url'           => $faker -> url,
        'parameters'    => $faker -> realText(20),
        'user_id'       => $user_id,
        'group_id'      => $group_id,
        'school_id'     => $school_id,
        'topic_id'      => $topic_id,
    ];
});

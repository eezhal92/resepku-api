<?php

use App\User;
use App\Recipe;
use App\Category;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = factory(User::class, 3)->create(['password' => bcrypt('password')]);
        $categories = collect(config('categories.default'))->map(function ($category) {
            return Category::create([
                'name' => $category,
                'slug' => str_slug($category),
            ]);
        });

        $recipes = factory(Recipe::class, 15)->create();
        $recipes->each(function ($recipe) use ($users, $categories) {
            $categoryIds = $categories->take(2)->pluck('id')->toArray();
            $recipe->categories()->attach($categoryIds);
            $recipe->user()->associate($users->random())->save();
        });
    }
}

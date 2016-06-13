<?php

use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RecipeTest extends TestCase
{
    use DatabaseMigrations, WithoutMiddleware;    

    public function recipes()
    {
        return [
            [
                ['title' => 'Jagung Bakar', 'body' => 'lorem ipsum', 'categories' => [1,2]]
            ]
        ];
    }

    protected function basicAuthHeader()
    {
        $user = factory(User::class)->create([
            'email' => 'jajang@mail.com', 
            'password' => bcrypt('password')
        ]);
        
        return [
            'HTTP_Authorization' => 'Basic ' . base64_encode('jajang@mail.com:password')
        ];
    }

    /**
     * @dataProvider recipes
     */
    public function test_successfully_create_a_recipe($recipe)
    {
        $headers = $this->basicAuthHeader();

        $this->json('POST', '/api/v1/recipes', $recipe, $headers)
             ->seeJson([
                 'title' => $recipe['title'],
             ]);
    }
}

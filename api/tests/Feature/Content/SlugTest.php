<?php

namespace Tests\Feature\Content;

use App\Models\Content\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SlugTest extends TestCase
{
    use RefreshDatabase;

    protected $originalTitle = 'Тестовая строка з українською мовою з апострофом \' та 324';
    protected $slug = 'testovaya-stroka-z-ukrayinskoyu-movoyu-z-apostrofom-ta-324';

    /**
     * @test
     * @group content
     * @group slug
     */
    public function post_gets_slug_when_creating()
    {
        $user = User::factory()->create();

        $post = Post::factory()->create([
            'title' => $this->originalTitle,
            'user_id' => $user->id
        ]);

        $this->assertTrue(str_contains($post->slug, $this->slug));
    }

    /**
     * @test
     * @group content
     * @group slug
     */
    public function tag_gets_slug_when_creating()
    {
        $user = User::factory()->create();

        $post = Post::factory()->create([
            'title' => $this->originalTitle,
            'user_id' => $user->id
        ]);

        $this->assertTrue(str_contains($post->slug, $this->slug));
    }
}

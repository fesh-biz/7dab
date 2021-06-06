<?php

namespace Tests\Browser\Content;

use App\Models\Content\Post;
use App\Models\Content\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PostPreviewTest extends DuskTestCase
{
    private $author;
    private $post;
    private $postSelector;

    /**
     * @test
     * @group content
     * @group postPreview
     */
    public function any_sees_post_title_author_body()
    {
        $this->browse(function (Browser $browser) {
            $this->prepareTestData();

            $browser->visit('/')
                ->waitFor($this->postSelector)
                ->assertSeeIn($this->postSelector . '-title', $this->post->title)
                ->assertSeeIn($this->postSelector . '-author', $this->author->name)
                ->assertSeeIn($this->postSelector . '-body', $this->post->body);
        });
    }

    /**
     * @test
     * @group content
     * @group postPreview
     */
    public function any_sees_post_info_data()
    {
        $this->browse(function (Browser $browser) {
            $this->prepareTestData();

            $browser->visit('/')
                ->waitFor($this->postSelector)
                ->assertSeeIn($this->postSelector . '-info-rating', $this->post->rating)
                ->assertSeeIn($this->postSelector . '-info-views', $this->post->total_views)
                ->assertSeeIn($this->postSelector . '-info-comments', $this->post->total_comments);
        });
    }

    /**
     * @test
     * @group content
     * @group postPreview
     */
    public function any_sees_post_tags_list()
    {
        $this->browse(function (Browser $browser) {
            $this->prepareTestData();


            $tags = Tag::factory(10)->create([
                'user_id' => $this->author->id
            ]);
            $this->post->tags()->attach($tags->pluck('id'));

            $browser->visit('/')
                ->waitFor($this->postSelector);

            foreach ($tags as $tag){
                $browser->assertSeeIn($this->postSelector . '-info-tags', $tag->title);
            }
        });
    }

    private function prepareTestData()
    {
        $this->author = User::factory()->create();
        $this->post = Post::factory()->create([
            'user_id' => $this->author->id
        ]);
        $this->postSelector = '@post-' . $this->post->id;
    }
}

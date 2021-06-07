<?php

namespace Tests\Browser\Content;

use App\Models\Content\Post;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Extensions\RouteNamesExtension;
use Tests\DuskTestCase;

class PostInfiniteScrollTest extends DuskTestCase
{
    use RouteNamesExtension;

    /**
     * @test
     * @group content
     * @group postInfiniteScroll
     */
    public function infinite_scroll_adds_new_posts_to_feed()
    {
        $this->browse(function (Browser $browser) {
            $this->prepareTestData();

            $browser
                ->visit($this->routeByName('home'))
                ->waitFor('@post-1')
                ->assertNotPresent('@post-12')
                ->scrollTo('@page-bottom')
                ->waitUntilMissing('@main-new-posts-loading')
                ->assertPresent('@post-12')

                ->assertNotPresent('@post-30')
                ->scrollTo('@page-bottom')
                ->waitUntilMissing('@main-new-posts-loading')
                ->assertPresent('@post-30');
        });
    }

    /**
     * @test
     * @group content
     * @group postInfiniteScroll
     */
    public function infinite_scroll_shows_his_end()
    {
        $this->browse(function (Browser $browser) {
            $this->prepareTestData(10);

            $browser
                ->visit($this->routeByName('home'))
                ->waitFor('@post-1')
                ->scrollTo('@page-bottom')
                ->waitUntilMissing('@main-new-posts-loading')
                ->assertPresent('@main-no-more-posts');
        });
    }

    private function prepareTestData(int $amountOfPosts = 30)
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Post::truncate();
        Post::factory($amountOfPosts)->create();
    }
}

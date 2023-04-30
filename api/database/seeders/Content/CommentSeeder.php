<?php

namespace Database\Seeders\Content;

use App\Models\Content\Comment;
use App\Models\Content\Post;
use App\Repositories\Content\CommentRepository;
use App\Repositories\Content\PostRepository;
use Faker\Generator;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    protected Generator $faker;
    protected CommentRepository $repo;
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->faker = app()->make(Generator::class);
        $this->repo = app()->make(CommentRepository::class);
        
        
        $posts = Post::orderBy('id', 'desc')->limit(1)->get();
        
        $comments = $this->seedComments($posts->toArray(), Post::class);
        
        foreach (range(1, 4) as $level) {
            $comments = $this->seedComments($comments, Comment::class);
        }
    }
    
    private function seedComments(array $commentables, string $commentableType): array
    {
        $comments = [];
        $postId = null;
        foreach ($commentables as $commentable) {
            if (!$postId) {
                $postId = $commentableType === Post::class ? $commentable['id'] : $commentable['post_id'];
            }
            foreach (range(1, mt_rand(2, 3)) as $i) {
                $comment = $this->repo->create([
                    'user_id' => mt_rand(1, 7),
                    'commentable_id' => $commentable['id'],
                    'commentable_type' => $commentableType,
                    'body' => $this->faker->text,
                    'post_id' => $postId
                ]);
                
                $comment->rating()->create();
                
                $postRepo = app()->make(PostRepository::class);
                $postRepo->incrementCommentsCounter($postId);
                $comments[] = $comment;
            }
        }
        
        return $comments;
    }
}

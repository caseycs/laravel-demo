<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EloquentRelationshipsOneToManyController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __invoke()
    {
        \DB::table('posts')->delete();
        \DB::table('categories')->delete();

        $category1 = new \App\Category();
        $category1->title = 'category1';
        $category1->save();

        $category2 = new \App\Category();
        $category2->title = 'category2';
        $category2->save();

        // 1. saving models with relations

        // https://laravel.com/docs/5.7/eloquent-relationships#inserting-and-updating-related-models

        // approach 1
        $post1 = new \App\Post();
        $post1->title = 'post1';
        $post1->slug = 'slug1';
        $category1->posts()->save($post1);

        // approach 2
        $post2 = new \App\Post();
        $post2->title = 'post2';
        $post2->slug = 'slug2';
        $post2->category_id = $category1->id;
        $post2->save();

        // 2. updating model relations

        // approach 1
        $post1 = \App\Post::where('title', 'post1')->first();
        $post1->category()->associate($category2);
        $post1->save();

        // approach 2
        $post2 = \App\Post::where('title', 'post2')->first();
        $post2->category_id = $category2->id;
        $post2->save();

        dump($category2->posts()->get());

        // 3. query by relations
        dump($category2->posts()->get());
        dump($category2->posts()->where('slug', 'slug1')->get());
        dump(\App\Post::where('category_id', '=', $category1->id)->get());
        dump(\App\Post::where('category_id', $category1->id)->get());

        // 4. query models with  their relations

        // fetch relation with one by one
        foreach (\App\Post::all() as $post) {
            dump($post);
            dump($post->category);
        }

        // together with fixed amount of queries
        foreach (\App\Post::with('category') as $post) {
            dump($post);
            dump($post->category);
        }
    }
}

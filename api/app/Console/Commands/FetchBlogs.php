<?php

namespace App\Console\Commands;

use App\Src\Domain\Models\Blog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class FetchBlogs extends Command
{
    protected $signature = 'blogs:fetch';
    protected $description = 'Fetch blogs from an API and save them in the database';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $response = Http::post(config('app.blog_url'));
        

        if ($response->status() === 200) {
            $blogs = $response->json();

            foreach ($blogs as $blogData) {
                Blog::create([
                    'title' => $blogData['title'],
                    'body' => $blogData['body'],
                    'image' => $blogData['image'],
                ]);
            }

            Log::info('Blogs Added');
        } else {
            Log::error('Failed to fetch blogs from API with status code: ' . $response->status() . ' and message: ' . $response->body() . '');
        }
    }
}

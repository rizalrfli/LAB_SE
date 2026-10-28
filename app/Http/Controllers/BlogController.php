<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Throwable;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 9;
        $currentPage = max(1, (int) $request->query('page', 1));
        $offset = ($currentPage - 1) * $perPage;
        $blogs = [];
        $totalBlogs = 0;
        $totalPages = 0;

        try {
            $totalBlogs = Blog::where('is_published', true)->count();
            $totalPages = $totalBlogs > 0 ? (int) ceil($totalBlogs / $perPage) : 0;

            $blogs = Blog::where('is_published', true)
                ->orderByDesc('published_at')
                ->orderByDesc('created_at')
                ->offset($offset)
                ->limit($perPage)
                ->get()
                ->toArray();
        } catch (Throwable $e) {
            // Keep empty
        }

        return view('public.blog.blog-list', compact(
            'blogs',
            'totalBlogs',
            'totalPages',
            'currentPage',
            'perPage'
        ));
    }

    public function show(Request $request, ?string $slug = null)
    {
        $id = (int) $request->query('id', 0);
        $slugParam = $slug ?? $request->query('slug', '');
        $blog = null;

        try {
            if ($id > 0) {
                $blogModel = Blog::where('id', $id)->where('is_published', true)->first();
            } elseif (!empty($slugParam)) {
                $blogModel = Blog::where('slug', $slugParam)->where('is_published', true)->first();
                if (!$blogModel && is_numeric($slugParam)) {
                    $blogModel = Blog::where('id', (int) $slugParam)->where('is_published', true)->first();
                }
            } else {
                $blogModel = null;
            }

            if ($blogModel) {
                $blogModel->increment('views');
                $blog = $blogModel->toArray();
                $blog['tags_array'] = $blogModel->tags_array;
            }
        } catch (Throwable $e) {
            $blog = null;
        }

        return view('public.blog.blog-detail', compact('blog'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Services\MaterializedViewService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class BlogAdminController extends Controller
{
    public function index(Request $request, MaterializedViewService $mvService)
    {
        $action = $request->query('action', 'list');

        if ($action === 'hapus' && $request->has('id')) {
            $id = (int) $request->query('id', 0);
            $blog = Blog::find($id);
            if ($blog) {
                $blog->delete();
                $mvService->refreshViewsComplete();
                return redirect()->route('admin.blog.index')
                    ->with('pesan', 'Blog berhasil dihapus!')
                    ->with('tipe_pesan', 'success');
            }
            return redirect()->route('admin.blog.index')
                ->with('pesan', 'Blog tidak ditemukan!')
                ->with('tipe_pesan', 'error');
        }

        if ($action === 'tambah') {
            return view('admin.blog.index', [
                'current_page' => 'blog',
                'page_title' => 'Tambah Blog - Admin Lab SE',
                'action' => 'tambah',
                'blog_edit' => null,
                'blogs' => [],
                'total_blog' => 0,
                'published_blog' => 0,
                'draft_blog' => 0,
                'search_query' => '',
            ]);
        }

        if ($action === 'edit' && $request->has('id')) {
            $id = (int) $request->query('id', 0);
            $blogModel = Blog::find($id);
            if (!$blogModel) {
                return redirect()->route('admin.blog.index')
                    ->with('pesan', 'Blog tidak ditemukan!')
                    ->with('tipe_pesan', 'error');
            }
            $blogData = $blogModel->toArray();
            $blogData['tags_string'] = $blogModel->tags_string;

            return view('admin.blog.index', [
                'current_page' => 'blog',
                'page_title' => 'Edit Blog - Admin Lab SE',
                'action' => 'edit',
                'blog_edit' => $blogData,
                'blogs' => [],
                'total_blog' => 0,
                'published_blog' => 0,
                'draft_blog' => 0,
                'search_query' => '',
            ]);
        }

        // Default: list
        $searchQuery = trim((string) $request->query('search', ''));
        $totalBlog = Blog::count();
        $publishedBlog = Blog::where('is_published', true)->count();
        $draftBlog = max(0, $totalBlog - $publishedBlog);

        $query = DB::table('blog as b')
            ->leftJoin('users as u', 'b.author_id', '=', 'u.id')
            ->select('b.*', 'u.username as author_name')
            ->orderByDesc('b.created_at');

        if ($searchQuery !== '') {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('b.title', 'ILIKE', "%{$searchQuery}%")
                  ->orWhere('b.category', 'ILIKE', "%{$searchQuery}%")
                  ->orWhere('b.excerpt', 'ILIKE', "%{$searchQuery}%");
            });
        }

        $blogs = $query->get()->map(fn($r) => (array) $r)->all();

        return view('admin.blog.index', [
            'current_page' => 'blog',
            'page_title' => 'Kelola Blog - Admin Lab SE',
            'action' => 'list',
            'blog_edit' => null,
            'blogs' => $blogs,
            'total_blog' => $totalBlog,
            'published_blog' => $publishedBlog,
            'draft_blog' => $draftBlog,
            'search_query' => $searchQuery,
        ]);
    }

    public function handlePost(Request $request, MaterializedViewService $mvService)
    {
        $action = $request->input('action', 'tambah');
        $title = trim((string) $request->input('title', ''));
        $content = (string) $request->input('content', '');
        $excerpt = trim((string) $request->input('excerpt', ''));
        $category = trim((string) $request->input('category', ''));
        $tagsInput = trim((string) $request->input('tags', ''));
        $isPublished = $request->has('is_published') && in_array($request->input('is_published'), ['1', 1, true, 'true', 'on'], true);

        if ($title === '' || trim(strip_tags($content)) === '') {
            return redirect()->back()
                ->withInput()
                ->with('pesan', 'Judul dan konten blog wajib diisi!')
                ->with('tipe_pesan', 'error');
        }

        $featuredImagePath = $request->input('featured_image_old', $request->input('featured_image', ''));
        if ($request->hasFile('featured_image') && $request->file('featured_image')->isValid()) {
            $file = $request->file('featured_image');
            $ext = strtolower($file->getClientOriginalExtension());
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)) {
                return redirect()->back()
                    ->withInput()
                    ->with('pesan', 'Format gambar tidak didukung!')
                    ->with('tipe_pesan', 'error');
            }
            if ($file->getSize() > 5 * 1024 * 1024) {
                return redirect()->back()
                    ->withInput()
                    ->with('pesan', 'Ukuran gambar maksimal 5MB!')
                    ->with('tipe_pesan', 'error');
            }
            $uploadDir = public_path('assets/img/blog');
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0775, true);
            }
            $newFileName = uniqid('blog_', true) . '.' . $ext;
            $file->move($uploadDir, $newFileName);
            $featuredImagePath = 'assets/img/blog/' . $newFileName;
        }

        // Format PostgreSQL text[] literal: {"tag1","tag2"}
        $tagsPgArray = null;
        if ($tagsInput !== '') {
            $tagsArr = array_values(array_filter(array_map('trim', explode(',', $tagsInput))));
            if (!empty($tagsArr)) {
                $escapedTags = array_map(fn($t) => '"' . str_replace('"', '\"', $t) . '"', $tagsArr);
                $tagsPgArray = '{' . implode(',', $escapedTags) . '}';
            }
        }

        try {
            if ($action === 'edit') {
                $id = (int) $request->input('id', 0);
                $blog = Blog::find($id);
                if (!$blog) {
                    return redirect()->route('admin.blog.index')
                        ->with('pesan', 'Blog tidak ditemukan!')
                        ->with('tipe_pesan', 'error');
                }

                $newSlug = Str::slug($title);
                if ($newSlug !== $blog->slug) {
                    if (Blog::where('slug', $newSlug)->where('id', '!=', $id)->exists()) {
                        $newSlug .= '-' . time();
                    }
                } else {
                    $newSlug = $blog->slug;
                }

                $publishedAt = $blog->published_at;
                if ($isPublished && !$blog->is_published) {
                    $publishedAt = now();
                }

                $blog->update([
                    'title' => $title,
                    'slug' => $newSlug,
                    'content' => $content,
                    'excerpt' => $excerpt,
                    'featured_image' => $featuredImagePath,
                    'category' => $category,
                    'tags' => $tagsPgArray,
                    'is_published' => $isPublished,
                    'published_at' => $publishedAt,
                ]);

                $mvService->refreshViewsComplete();
                return redirect()->route('admin.blog.index')
                    ->with('pesan', 'Blog berhasil diperbarui!')
                    ->with('tipe_pesan', 'success');
            }

            // Create new blog
            $slug = Str::slug($title);
            if (Blog::where('slug', $slug)->exists()) {
                $slug .= '-' . time();
            }
            $authorId = (int) (Auth::id() ?? session('user_id', 1));

            Blog::create([
                'title' => $title,
                'slug' => $slug,
                'content' => $content,
                'excerpt' => $excerpt,
                'featured_image' => $featuredImagePath,
                'author_id' => $authorId ?: 1,
                'category' => $category,
                'tags' => $tagsPgArray,
                'is_published' => $isPublished,
                'published_at' => $isPublished ? now() : null,
            ]);

            $mvService->refreshViewsComplete();
            return redirect()->route('admin.blog.index')
                ->with('pesan', 'Blog berhasil ditambahkan!')
                ->with('tipe_pesan', 'success');
        } catch (Throwable $e) {
            return redirect()->back()
                ->withInput()
                ->with('pesan', 'Gagal menyimpan blog: ' . $e->getMessage())
                ->with('tipe_pesan', 'error');
        }
    }

    public function uploadGambar(Request $request)
    {
        if (!$request->hasFile('file') || !$request->file('file')->isValid()) {
            return response('Error: File tidak valid', 400);
        }

        $file = $request->file('file');
        $ext = strtolower($file->getClientOriginalExtension());
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)) {
            return response('Error: Format file tidak diizinkan', 400);
        }
        if ($file->getSize() > 5 * 1024 * 1024) {
            return response('Error: Ukuran file maksimal 5MB', 400);
        }

        $uploadDir = public_path('assets/img/blog');
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0775, true);
        }
        $newFileName = uniqid('blog_', true) . '.' . $ext;
        $file->move($uploadDir, $newFileName);

        return response(asset('assets/img/blog/' . $newFileName), 200);
    }
}

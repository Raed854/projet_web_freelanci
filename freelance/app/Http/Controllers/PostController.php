<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();
        return view('gestiondepost.gestionpost', ['posts' => $posts]);
    }

    public function index1()
    {
        $posts = Post::all();
        return view('post.post', ['posts' => $posts]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'titre' => 'required|string|max:255',
                'contenu' => 'required|string',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ], [
                'titre.required' => 'The titre field is required.',
                'contenu.required' => 'The contenu field is required.',
                'image.required' => 'The image field is required.',
                'image.image' => 'The image must be a valid image file.',
            ]);

            $validated['author_id'] = session('user_id'); // Use the user ID from the session

            if ($request->hasFile('image')) {
                try {
                    $imagePath = $request->file('image')->store('images', 'public');
                    $validated['image'] = $imagePath;
                    Log::info('Image successfully saved at: ' . $imagePath);
                } catch (\Exception $e) {
                    Log::error('Error saving image: ' . $e->getMessage());
                }
            } else {
                Log::warning('No image file found in the request.');
            }
            $post = Post::create($validated);
            return view('post.post', ['posts' => $posts]);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'An unexpected error occurred: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $post = Post::findOrFail($id);
        return response()->json($post);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'titre' => 'sometimes|required|string|max:255',
            'contenu' => 'sometimes|required|string',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            'creation_date' => 'sometimes|required|date',
            'author_id' => 'sometimes|required|exists:users,id',
        ]);

        $post = Post::findOrFail($id);

        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($post->image && Storage::disk('public')->exists($post->image)) {
                Storage::disk('public')->delete($post->image);
            }

            try {
                $imagePath = $request->file('image')->store('images', 'public');
                $validated['image'] = $imagePath;
                Log::info('Image successfully saved at: ' . $imagePath);
            } catch (\Exception $e) {
                Log::error('Error saving image: ' . $e->getMessage());
            }
        } else {
            Log::warning('No image file found in the request.');
        }

        $post->update($validated);
        return response()->json($post);
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return response()->json(['message' => 'Post deleted successfully']);
    }

    public function getAuthors()
    {
        $users = User::all(['id', 'nom', 'prenom']);
        return response()->json($users);
    }
}

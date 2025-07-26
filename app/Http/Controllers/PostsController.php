<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function PHPUnit\Framework\isFalse;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['posts'] = Post::all();
        return response()->json([
            'status' => true,
            'message' => 'All Posts Data.',
            'data' => $data,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatepost = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($validatepost->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validatepost->errors()->all(),
            ], 422);
        }
        // $user = $request->user();
        $img = $request->file('image');
        $ext = $img->getClientOriginalExtension();
        $imageName = time() . '_' . $ext;
        $img->move(public_path('/uploads'), $imageName);

        $post = Post::create([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imageName,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Post created successfully.',
            'post' => $post,
        ], 201);
        
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['post'] = Post::select(
            'id',
            'title',
            'description',
            'image'
        )->where(['id' => $id])->get();
        return response()->json([
            'status' => true,
            'message' => 'Your single post.',
            'data' => $data,
        ], 201);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $id)
    {
        $validatepost = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'image' => 'required|image',
        ]);
        if ($validatepost->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validatepost->errors()->all(),
            ], 422);
        }
        $post = Post::select('id', 'image')->find($id);
        if (!$post) {
            return response()->json([
                'status' => false,
                'message' => 'Post not found.',
            ], 404);
        }
        if ($request->hasFile('image')) {
            $path = public_path() . '/uploads';
            if ($post->image) {
                $old_file = $path . '/' . $post->image;
                if (file_exists($old_file)) {
                    unlink($old_file);
                }
            }

            $img = $request->file('image');
            $ext = $img->getClientOriginalExtension();
            $imageName = time() . '_' . $ext;
            $img->move($path, $imageName);
        } else {
            $imageName = $post->image;
        }

        Post::where('id', $id)->update([
            'title' => $request->title,
            'description' => $request->description,
            'image' => $imageName,
        ]);

        $updatedPost = Post::find($id);
        return response()->json([
            'status' => true,
            'message' => 'Post updated successfully.',
            'post' => $updatedPost,
        ], 200);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json([
                'status' => false,
                'message' => 'Post not found.'
            ], 404);
        }
        $filepath = public_path('uploads/' . $post->image);
        if (file_exists($filepath)) {
            unlink($filepath);
        }
        $post->delete();
        return response()->json([
            'status' => true,
            'message' => 'Data Removed successfully.',
            'post' => $post,
        ], 200);
    }
}

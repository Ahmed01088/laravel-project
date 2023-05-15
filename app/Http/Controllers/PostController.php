<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\StudentAffair;
use App\Models\Student;
use App\Models\Lecturer;

class PostController extends Controller
{

    public function store(Request $request)
    {
        $post = Post::create($request->all());
        $title = $request->input('title');
        $body = $request->input('content');
        $tokens = Student::whereNotNull('fcm_token')->pluck('fcm_token')->all();
        $data = [
            'title' => $title,
            'body' => $body,
        ];

        $payload = [
            'registration_ids' => $tokens,
            'notification' => $data,
            'data' => $data,
        ];

        $headers = [
            'authorization: key=' . 'AAAAjfF8Wec:APA91bEWxNWtrsJ99bucIsqsA_QCpga1OFNOBoOMRwiFZpkGE1F0oLO84hZNEYxWj3KuMcjlaO6_icPysdIeIBFjpAkxNns70u8focMYTzcrnNxfPqaNdd2i3rZRJOr_eMY5hOGE_K0T',
            'Content-Type: application/json',
        ];


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        $result = curl_exec($ch);
        curl_close($ch);
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('images/posts/', $filename);
            $post->image = $filename;
        }
        $post->save();
        return response()->json([
            'message' => 'Post created successfully.',
            'data' => $post,
            'result' => json_decode($result),
        ], 201);
    }
    public function update(Request $request, Post $post)
    {
        $post->update($request->all());
        return response()->json([
            'message' => 'Post updated successfully.',
            'data' => $post
        ], 200);
    }
    public function destroy(Post $post)
    {
        $post->delete();
        return response()->json([
            'message' => 'Post deleted successfully.'
        ], 200);
    }
    public function getAllPosts()
    {
        $posts = Post::all();
        foreach ($posts as $post) {
            if (!is_null($post->student_id)) {
                $student = Student::where('id', $post->student_id)->first();
                $post->person_name = $student->firstname . ' ' . $student->lastname;
                $post->person_image = $student->image;
            } else if (!is_null($post->lecturer_id)) {
                $lecturer = Lecturer::where('id', $post->lecturer_id)->first();
                $post->person_name = $lecturer->firstname . ' ' . $lecturer->lastname;
                $post->person_image = $lecturer->image;
            } else {
                $studentAffairs = StudentAffair::where('id', $post->student_affairs_id)->first();
                $post->person_name = $studentAffairs->firstname . ' ' . $studentAffairs->lastname;
                $post->person_image = $studentAffairs->image;
            }
        }
        return response()->json([
            'message' => 'Posts retrieved successfully.',
            'data' => $posts
        ], 200);
    }
    public function show(Post $post)
    {
        return response()->json([
            'message' => 'Post retrieved successfully.',
            'data' => $post
        ], 200);
    }
    public function getPostsByStudentId($student_id)
    {
        $posts = Post::where('student_id', $student_id)->get();
        return response()->json([
            'message' => 'Posts retrieved successfully.',
            'data' => $posts
        ], 200);
    }
    public function getPostsByStudentAffairsId($student_affairs_id)
    {
        $posts = Post::where('student_affairs_id', $student_affairs_id)->get();
        $studentAffairs = StudentAffair::where('id', $student_affairs_id)->first();
        foreach ($posts as $post) {
            $post->person_name = $studentAffairs->firstname . ' ' . $studentAffairs->lastname;
            $post->person_image = $studentAffairs->image;
        }
        return response()->json([
            'message' => 'Posts retrieved successfully.',
            'data' => $posts
        ], 200);
    }
    public function deletePostByStudentId($student_id)
    {
        $posts = Post::where('student_id', $student_id)->get();
        foreach ($posts as $post) {
            $post->delete();
        }
        return response()->json([
            'message' => 'Posts deleted successfully.',
            'data' => $posts
        ], 200);
    }
    public function deletePostByStudentAffairsId($student_affairs_id)
    {
        $posts = Post::where('student_affairs_id', $student_affairs_id)->get();
        foreach ($posts as $post) {
            $post->delete();
        }
        return response()->json([
            'message' => 'Posts deleted successfully.',
            'data' => $posts
        ], 200);
    }
    public function deletePostByIdAndStudentId($id, $student_id)
    {
        $postExists = Post::where('id', $id)->where('student_id', $student_id)->exists();
        if (!$postExists) {
            return response()->json([
                'message' => 'Post not found.',
            ], 404);
        }
        $post = Post::where('id', $id)->where('student_id', $student_id)->first();
        $post->delete();
        return response()->json([
            'message' => 'Post deleted successfully.',
            'data' => $post
        ], 200);
    }
    public function checkStudentIsPostStudentAndDelete($id, $student_id)
    {
        $postExists = Post::where('id', $id)->where('student_id', $student_id)->exists();
        if (!$postExists) {
            return response()->json([
                'message' => 'Post not found.',
                'data' => null
            ], 404);
        }
        $post = Post::where('id', $id)->where('student_id', $student_id)->first();
        $post->delete();
        return response()->json([
            'message' => 'Post deleted successfully.',
            'data' => $post
        ], 200);
    }
    public function checkStudentIsPostStudentAffairsAndDelete($id, $student_affairs_id)
    {
        $postExists = Post::where('id', $id)->where('student_affairs_id', $student_affairs_id)->exists();
        if (!$postExists) {
            return response()->json([
                'message' => 'Post not found.',
                'data' => null
            ], 404);
        }
        $post = Post::where('id', $id)->where('student_affairs_id', $student_affairs_id)->first();
        $post->delete();
        return response()->json([
            'message' => 'Post deleted successfully.',
            'data' => $post
        ], 200);
    }
    public function checkStudentIsPostLecturerAndDelete($id, $lecturer_id)
    {
        $postExists = Post::where('id', $id)->where('lecturer_id', $lecturer_id)->exists();
        if (!$postExists) {
            return response()->json([
                'message' => 'Post not found.',
                'data' => null
            ], 404);
        }
        $post = Post::where('id', $id)->where('lecturer_id', $lecturer_id)->first();
        $post->delete();
        return response()->json([
            'message' => 'Post deleted successfully.',
            'data' => $post
        ], 200);
    }
}

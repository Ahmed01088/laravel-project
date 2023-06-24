<?php

namespace App\Http\Controllers;

use App\Events\AddPost;
use App\Events\ReactPost;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\StudentAffair;
use App\Models\Student;
use App\Models\Lecturer;
use App\Models\Notification;
use App\Models\Reaction;

class PostController extends Controller
{

    public function store(Request $request)
    {
        $post = Post::create($request->all());
        $body = $request->input('content');
        $tokens = Student::whereNotNull('fcm_token')->pluck('fcm_token')->all();
        $students = Student::whereNotNull('fcm_token')->pluck('id')->all();
        if ($request->student_id != null) {
            $student = Student::find($request->student_id);
            $data = [
                'title' => "تم اضافة منشور جديد من " . $student->firstname . ' ' . $student->lastname,
                'body' => $body,
            ];
            event(new AddPost($post, $student));
        } else if ($request->student_affairs_id != null) {
            $student_affair = StudentAffair::find($request->student_affairs_id);
            $data = [
                'title' => "تم اضافة منشور جديد من " . $student_affair->firstname . ' ' . $student_affair->lastname,
                'body' => $body,
            ];
            event(new AddPost($post, $student_affair));
        } else if ($request->lecturer_id != null) {
            $lecturer = Lecturer::find($request->lecturer_id);
            $data = [
                'title' => "تم اضافة منشور جديد من " . $lecturer->firstname . ' ' . $lecturer->lastname,
                'body' => $body,
            ];
            event(new AddPost($post, $lecturer));
        }
        $payload = [
            'registration_ids' => $tokens,
            'notification' => $data,
            'data' => [
                'volume' => '3.21.15',
                'contents' => 'http://www.news-magazine.com/world-week/21659772',
            ],
        ];

        $headers = [
            'authorization: key=' . 'AAAAjfF8Wec:APA91bEWxNWtrsJ99bucIsqsA_QCpga1OFNOBoOMRwiFZpkGE1F0oLO84hZNEYxWj3KuMcjlaO6_icPysdIeIBFjpAkxNns70u8focMYTzcrnNxfPqaNdd2i3rZRJOr_eMY5hOGE_K0T',
            'Content-Type: application/json',
        ];
        foreach ($students as $student) {
            $notification = new Notification();
            $notification->title = $data['title'];
            $notification->body = $data['body'];
            $notification->student_id = $student;
            $notification->post_id = $post->id;
            $notification->type = 'post';
            $notification->date = date('Y-m-d H:i:s');
            $notification->save();
        }


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
    public function searchInPosts($search)
    {
        $posts = Post::where('content', 'LIKE', "%{$search}%")->get();
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
    public function addRectOnPost(Request $request)
    {
        $post = Post::find($request->id);
        //check if user(student , student affiars or lecturer ) already react on this post
        if ($request->student_id != null) {
            $reactionExists = Reaction::where('post_id', $request->id)->where('student_id', $request->student_id)->exists();
            if ($reactionExists) {
                $deleteReaction = Reaction::where('post_id', $request->id)->where('student_id', $request->student_id)->first();
                $deleteReaction->delete();
            } else {
                $reaction = new Reaction();
                $reaction->post_id = $request->id;
                $reaction->student_id = $request->student_id;
                $reaction->save();
            }
        } else if ($request->student_affairs_id != null) {
            $reactionExists = Reaction::where('post_id', $request->id)->where('student_affairs_id', $request->student_affairs_id)->exists();
            if ($reactionExists) {
                $deleteReaction = Reaction::where('post_id', $request->id)->where('student_affairs_id', $request->student_affairs_id)->first();
                $deleteReaction->delete();
            } else {
                $reaction = new Reaction();
                $reaction->post_id = $request->id;
                $reaction->student_affairs_id = $request->student_affairs_id;
                $reaction->save();
            }
        } else if ($request->lecturer_id != null) {
            $reactionExists = Reaction::where('post_id', $request->id)->where('lecturer_id', $request->lecturer_id)->exists();
            if ($reactionExists) {
                $deleteReaction = Reaction::where('post_id', $request->id)->where('lecturer_id', $request->lecturer_id)->first();
                $deleteReaction->delete();
            } else {
                $reaction = new Reaction();
                $reaction->post_id = $request->id;
                $reaction->lecturer_id = $request->lecturer_id;
                $reaction->save();
            }
        }
        $countLikes = Reaction::where('post_id', $request->id)->count();
        event(new ReactPost($countLikes, $post->id));
        //count all likes on this post
        $post->update(['likes' => $countLikes]);
        $post->save();
        return response()->json([
            'message' => 'Post reacted successfully.',
            'data' => $post
        ], 200);
    }
}

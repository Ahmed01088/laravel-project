<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Student;
use App\Models\Lecturer;
use App\Models\Classroom;

class MessageController extends Controller
{
    public function store(Request $request)
    {
        $message = Message::create($request->all());
        if (request()->hasFile('image')) {
            $file = request()->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('images/messages/', $filename);
            $message->image = $filename;
        }
        event(new MessageSent(
            $request->sender,
            $message
        ));

        $message->save();
        return response()->json([
            'message' => 'Message created successfully',
            'data' => $message
        ], 201);
    }

    public function delete(Request $request)
    {
        $message = Message::find($request->input('id'));
        $message->delete();
        return response()->json([
            'message' => 'Message deleted successfully',
            'data' => $message
        ], 201);
    }
    public function update(Request $request)
    {
        $message = Message::find($request->input('id'));
        $message->content = $request->input('content');
        $message->sentAt = now();
        $message->image = $request->file('image') ? $request->file('image')->store('images', 'public') : null;
        $message->voice_file = $request->file('voice_file') ? $request->file('voice_file')->store('voice_files', 'public') : null;
        $message->classroom_id = $request->input('classroom_id');
        $message->chat_id = $request->input('chat_id');
        $message->sender = $request->input('sender');
        $message->receiver = $request->input('receiver');
        $message->save();

        return response()->json([
            'message' => 'Message updated successfully',
            'data' => $message
        ], 201);
    }
    public function show(Request $request)
    {
        $message = Message::find($request->input('id'));

        return response()->json([
            'message' => 'Message found successfully',
            'data' => $message
        ], 201);
    }
    public function index()
    {
        $messages = Message::all();

        return response()->json([
            'message' => 'Messages found successfully',
            'data' => $messages
        ], 201);
    }
    public function getMessagesByChatId($id)
    {
        $messages = Message::where('chat_id', $id)->get();

        return response()->json([
            'message' => 'Messages found successfully',
            'data' => $messages
        ], 201);
    }
    public function getMessagesByClassroomId($classroomId)
    {
        $messages = Message::where('classroom_id', $classroomId)->get();
        $classroom = Classroom::find($classroomId);
        $lecturer_id = $classroom->lecturer_id;
        $lecturer = Lecturer::find($lecturer_id);
        foreach ($messages as $message) {
            if ($message->sender == $lecturer->id) {
                $lecturer = Lecturer::find($message->sender);
                $message->sender_name = $lecturer->firstname . ' ' . $lecturer->lastname;
                $message->sender_image = $lecturer->image;
            } else {
                $student = Student::find($message->sender);
                $message->sender_name = $student->firstname . ' ' . $student->lastname;
                $message->sender_image = $student->image;
            }
        }

        return response()->json([
            'message' => 'Messages found successfully',
            'data' => $messages,

        ], 201);
    }
    public function deleteMessageById($id)
    {
        $message = Message::find($id);
        $message->delete();
        return response()->json([
            'message' => 'Message deleted successfully',
            'data' => $message,
            'updated_at' => now()
        ], 201);
    }
    public function sentMessage(Request $request)
    {
        event(new MessageSent(
            $request->sender,
            $request->content
        ));
    }
}

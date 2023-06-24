<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\Message;
use App\Models\Student;
use App\Models\StudentAffair;
use App\Models\Lecturer;

class ChatController extends Controller
{
    public function index()
    {
        $chats = Chat::all();
        return response()->json([
            'message' => 'Chats retrieved successfully.',
            'data' => $chats
        ], 200);
    }
    public function store(Request $request)
    {
        if (!is_null($request->student_sender_id) && !is_null($request->student_reciver_id)) {
            $chatExists = Chat::where(function ($query) use ($request) {
                $query->where('student_sender_id', $request->student_sender_id)
                    ->where('student_reciver_id', $request->student_reciver_id);
            })
                ->orWhere(function ($query) use ($request) {
                    $query->where('student_sender_id', $request->student_reciver_id)
                        ->where('student_reciver_id', $request->student_sender_id);
                })
                ->first();
            if (!is_null($chatExists)) {
                return response()->json([
                    'message' => 'Chat already exists.',
                    'data' => $chatExists,
                    'statue' => 200
                ], 200);
            } else {
                $chat = Chat::create([
                    'student_sender_id' => $request->student_sender_id,
                    'student_reciver_id' => $request->student_reciver_id,
                    'student_affairs_sender_id' => $request->student_affairs_sender_id,
                    'student_affairs_reciver_id' => $request->student_affairs_reciver_id,
                    'lecturer_sender_id' => $request->lecturer_sender_id,
                    'lecturer_reciver_id' => $request->lecturer_reciver_id,

                ]);
                return response()->json([
                    'message' => 'Chat created successfully.',
                    'data' => $chat
                ], 201);
            }
        } else if (!is_null($request->student_affairs_sender_id) && !is_null($request->student_reciver_id)) {
            $chatExists = Chat::where(function ($query) use ($request) {
                $query->where('student_affairs_sender_id', $request->student_affairs_sender_id)
                    ->where('student_reciver_id', $request->student_reciver_id);
            })
                ->orWhere(function ($query) use ($request) {
                    $query->where('student_affairs_sender_id', $request->student_reciver_id)
                        ->where('student_reciver_id', $request->student_affairs_sender_id);
                })
                ->first();

            if (!is_null($chatExists)) {
                return response()->json([
                    'message' => 'Chat already exists.',
                    'data' => $chatExists,
                    'statue' => 200
                ], 200);
            } else {
                $chat = Chat::create([
                    'student_affairs_sender_id' => $request->student_affairs_sender_id,
                    'student_reciver_id' => $request->student_reciver_id,
                    'student_sender_id' => $request->student_sender_id,
                    'student_affairs_reciver_id' => $request->student_affairs_reciver_id,
                    'lecturer_sender_id' => $request->lecturer_sender_id,
                    'lecturer_reciver_id' => $request->lecturer_reciver_id,

                ]);
                return response()->json([
                    'message' => 'Chat created successfully.',
                    'data' => $chat
                ], 201);
            }
        } else if (!is_null($request->lecturer_sender_id) && !is_null($request->student_reciver_id)) {
            $chatExists = Chat::where(function ($query) use ($request) {
                $query->where('lecturer_sender_id', $request->lecturer_sender_id)
                    ->where('student_reciver_id', $request->student_reciver_id);
            })
                ->orWhere(function ($query) use ($request) {
                    $query->where('lecturer_sender_id', $request->student_reciver_id)
                        ->where('student_reciver_id', $request->lecturer_sender_id);
                })
                ->first();
            if (!is_null($chatExists)) {
                return response()->json([
                    'message' => 'Chat already exists.',
                    'data' => $chatExists,
                    'statue' => 200
                ], 200);
            } else {
                $chat = Chat::create([
                    'lecturer_sender_id' => $request->lecturer_sender_id,
                    'student_reciver_id' => $request->student_reciver_id,
                    'student_sender_id' => $request->student_sender_id,
                    'student_affairs_reciver_id' => $request->student_affairs_reciver_id,
                    'student_affairs_sender_id' => $request->student_affairs_sender_id,
                    'lecturer_reciver_id' => $request->lecturer_reciver_id,

                ]);
                return response()->json([
                    'message' => 'Chat created successfully.',
                    'data' => $chat
                ], 201);
            }
        } else if (!is_null($request->student_sender_id) && !is_null($request->student_affairs_reciver_id)) {
            $chatExists = Chat::where(function ($query) use ($request) {
                $query->where('student_sender_id', $request->student_sender_id)
                    ->where('student_affairs_reciver_id', $request->student_affairs_reciver_id);
            })
                ->orWhere(function ($query) use ($request) {
                    $query->where('student_sender_id', $request->student_affairs_reciver_id)
                        ->where('student_affairs_reciver_id', $request->student_sender_id);
                })
                ->first();
            if (!is_null($chatExists)) {
                return response()->json([
                    'message' => 'Chat already exists.',
                    'data' => $chatExists,
                    'statue' => 200
                ], 200);
            } else {
                $chat = Chat::create([
                    'student_sender_id' => $request->student_sender_id,
                    'student_affairs_reciver_id' => $request->student_affairs_reciver_id,
                    'student_affairs_sender_id' => $request->student_affairs_sender_id,
                    'student_reciver_id' => $request->student_reciver_id,
                    'lecturer_sender_id' => $request->lecturer_sender_id,
                    'lecturer_reciver_id' => $request->lecturer_reciver_id,

                ]);
                return response()->json([
                    'message' => 'Chat created successfully.',
                    'data' => $chat
                ], 201);
            }
        } else if (!is_null($request->student_sender_id) && !is_null($request->lecturer_reciver_id)) {
            $chatExists = Chat::where(function ($query) use ($request) {
                $query->where('student_sender_id', $request->student_sender_id)
                    ->where('lecturer_reciver_id', $request->lecturer_reciver_id);
            })
                ->orWhere(function ($query) use ($request) {
                    $query->where('student_sender_id', $request->lecturer_reciver_id)
                        ->where('lecturer_reciver_id', $request->student_sender_id);
                })
                ->first();
            if (!is_null($chatExists)) {
                return response()->json([
                    'message' => 'Chat already exists.',
                    'data' => $chatExists,
                    'statue' => 200
                ], 200);
            } else {
                $chat = Chat::create([
                    'student_sender_id' => $request->student_sender_id,
                    'lecturer_reciver_id' => $request->lecturer_reciver_id,
                    'student_affairs_sender_id' => $request->student_affairs_sender_id,
                    'student_reciver_id' => $request->student_reciver_id,
                    'student_affairs_reciver_id' => $request->student_affairs_reciver_id,
                    'lecturer_sender_id' => $request->lecturer_sender_id,

                ]);
                return response()->json([
                    'message' => 'Chat created successfully.',
                    'data' => $chat
                ], 201);
            }
        } else if (!is_null($request->student_affairs_sender_id) && !is_null($request->student_affairs_reciver_id)) {
            $chatExists = Chat::where(function ($query) use ($request) {
                $query->where('student_affairs_sender_id', $request->student_affairs_sender_id)
                    ->where('student_affairs_reciver_id', $request->student_affairs_reciver_id);
            })
                ->orWhere(function ($query) use ($request) {
                    $query->where('student_affairs_sender_id', $request->student_affairs_reciver_id)
                        ->where('student_affairs_reciver_id', $request->student_affairs_sender_id);
                })
                ->first();
            if (!is_null($chatExists)) {
                return response()->json([
                    'message' => 'Chat already exists.',
                    'data' => $chatExists,
                    'statue' => 200
                ], 200);
            } else {
                $chat = Chat::create([
                    'student_affairs_sender_id' => $request->student_affairs_sender_id,
                    'student_affairs_reciver_id' => $request->student_affairs_reciver_id,
                    'student_sender_id' => $request->student_sender_id,
                    'student_reciver_id' => $request->student_reciver_id,
                    'lecturer_sender_id' => $request->lecturer_sender_id,
                    'lecturer_reciver_id' => $request->lecturer_reciver_id,

                ]);
                return response()->json([
                    'message' => 'Chat created successfully.',
                    'data' => $chat
                ], 201);
            }
        } else if (!is_null($request->student_affairs_sender_id) && !is_null($request->lecturer_reciver_id)) {
            $chatExists = Chat::where(function ($query) use ($request) {
                $query->where('student_affairs_sender_id', $request->student_affairs_sender_id)
                    ->where('lecturer_reciver_id', $request->lecturer_reciver_id);
            })
                ->orWhere(function ($query) use ($request) {
                    $query->where('student_affairs_sender_id', $request->lecturer_reciver_id)
                        ->where('lecturer_reciver_id', $request->student_affairs_sender_id);
                })
                ->first();
            if (!is_null($chatExists)) {
                return response()->json([
                    'message' => 'Chat already exists.',
                    'data' => $chatExists,
                    'statue' => 200
                ], 200);
            } else {
                $chat = Chat::create([
                    'student_affairs_sender_id' => $request->student_affairs_sender_id,
                    'lecturer_reciver_id' => $request->lecturer_reciver_id,
                    'student_sender_id' => $request->student_sender_id,
                    'student_reciver_id' => $request->student_reciver_id,
                    'student_affairs_reciver_id' => $request->student_affairs_reciver_id,
                    'lecturer_sender_id' => $request->lecturer_sender_id,


                ]);
                return response()->json([
                    'message' => 'Chat created successfully.',
                    'data' => $chat
                ], 201);
            }
        } else if (!is_null($request->lecturer_sender_id) && !is_null($request->student_affairs_reciver_id)) {
            $chatExists = Chat::where(function ($query) use ($request) {
                $query->where('lecturer_sender_id', $request->lecturer_sender_id)
                    ->where('student_affairs_reciver_id', $request->student_affairs_reciver_id);
            })
                ->orWhere(function ($query) use ($request) {
                    $query->where('lecturer_sender_id', $request->student_affairs_reciver_id)
                        ->where('student_affairs_reciver_id', $request->lecturer_sender_id);
                })
                ->first();
            if (!is_null($chatExists)) {
                return response()->json([
                    'message' => 'Chat already exists.',
                    'data' => $chatExists,
                    'statue' => 200
                ], 200);
            } else {
                $chat = Chat::create([
                    'lecturer_sender_id' => $request->lecturer_sender_id,
                    'student_affairs_reciver_id' => $request->student_affairs_reciver_id,
                    'student_sender_id' => $request->student_sender_id,
                    'student_reciver_id' => $request->student_reciver_id,
                    'student_affairs_sender_id' => $request->student_affairs_sender_id,
                    'lecturer_reciver_id' => $request->lecturer_reciver_id,

                ]);
                return response()->json([
                    'message' => 'Chat created successfully.',
                    'data' => $chat
                ], 201);
            }
        } else if (!is_null($request->lecturer_sender_id) && !is_null($request->lecturer_reciver_id)) {
            $chatExists = Chat::where(function ($query) use ($request) {
                $query->where('lecturer_sender_id', $request->lecturer_sender_id)
                    ->where('lecturer_reciver_id', $request->lecturer_reciver_id);
            })
                ->orWhere(function ($query) use ($request) {
                    $query->where('lecturer_sender_id', $request->lecturer_reciver_id)
                        ->where('lecturer_reciver_id', $request->lecturer_sender_id);
                })
                ->first();
            if (!is_null($chatExists)) {
                return response()->json([
                    'message' => 'Chat already exists.',
                    'data' => $chatExists,
                    'statue' => 200
                ], 200);
            } else {
                $chat = Chat::create([
                    'lecturer_sender_id' => $request->lecturer_sender_id,
                    'lecturer_reciver_id' => $request->lecturer_reciver_id,
                    'student_sender_id' => $request->student_sender_id,
                    'student_reciver_id' => $request->student_reciver_id,
                    'student_affairs_sender_id' => $request->student_affairs_sender_id,
                    'student_affairs_reciver_id' => $request->student_affairs_reciver_id,

                ]);
                return response()->json([
                    'message' => 'Chat created successfully.',
                    'data' => $chat
                ], 201);
            }
        }
        if (!is_null($request->lecturer_sender_id)) {
            $chat = Chat::create([
                'lecturer_sender_id' => $request->lecturer_sender_id,
                'lecturer_reciver_id' => $request->lecturer_reciver_id,
                'student_sender_id' => $request->student_sender_id,
                'student_reciver_id' => $request->student_reciver_id,
                'student_affairs_sender_id' => $request->student_affairs_sender_id,
                'student_affairs_reciver_id' => $request->student_affairs_reciver_id,

            ]);
            return response()->json([
                'message' => 'Chat created successfully.',
                'data' => $chat
            ], 201);
        }
    }
    public function show(Chat $chat)
    {
        return response()->json(['data' => $chat], 200);
    }
    public function update(Request $request, Chat $chat)
    {
        $chat->update($request->all());

        return response()->json([
            'message' => 'Chat updated successfully.',
            'data' => $chat,
            'statue' => 200
        ], 200);
    }
    public function destroy($id)
    {
        $chat = Chat::findOrFail($id);
        $chat->delete();

        return response()->json([
            'message' => 'Chat deleted successfully.',
            'data' => $chat,
            'statue' => 200
        ], 200);
    }

    public function getMessagesByChatId($chat_id)
    {
        $messages = Message::join('chats', 'chats.id', '=', 'messages.chat_id')
            ->where('messages.chat_id', $chat_id)
            ->get(['messages.*']);
        return response()->json([
            'message' => 'Messages retrieved successfully.',
            'data' => $messages,
            'statue' => 200
        ], 200);
    }
    public function getChatsByStudentId($student_id)
    {
        $chats = Chat::where('student_sender_id', $student_id)
            ->orWhere('student_reciver_id', $student_id)
            ->get();
        foreach ($chats as $chat) {
            if ($chat->student_reciver_id != null && $chat->student_sender_id != null) {
                if ($chat->student_reciver_id == $student_id) {
                    $sender_id = $chat->student_sender_id;
                    $sender = Student::where('id', $sender_id)->first();
                    $chat->reciver_name = $sender->firstname . ' ' . $sender->lastname;
                    $chat->reciver_image = $sender->image;
                } else {
                    $reciver_id = $chat->student_reciver_id;
                    $reciver = Student::where('id', $reciver_id)->first();
                    $chat->reciver_name = $reciver->firstname . ' ' . $reciver->lastname;
                    $chat->reciver_image = $reciver->image;
                }
            } else if ($chat->student_reciver_id != null && $chat->lecturer_sender_id != null) {
                if ($chat->student_reciver_id == $student_id) {
                    $sender_id = $chat->lecturer_sender_id;
                    $sender = Lecturer::where('id', $sender_id)->first();
                    $chat->reciver_name = $sender->firstname . ' ' . $sender->lastname;
                    $chat->reciver_image = $sender->image;
                } else {
                    $reciver_id = $chat->student_reciver_id;
                    $reciver = Student::where('id', $reciver_id)->first();
                    $chat->reciver_name = $reciver->firstname . ' ' . $reciver->lastname;
                    $chat->reciver_image = $reciver->image;
                }
            } else if ($chat->student_reciver_id != null && $chat->student_affairs_sender_id != null) {
                if ($chat->student_reciver_id == $student_id) {
                    $sender_id = $chat->student_affairs_sender_id;
                    $sender = StudentAffair::where('id', $sender_id)->first();
                    $chat->reciver_name = $sender->firstname . ' ' . $sender->lastname;
                    $chat->reciver_image = $sender->image;
                } else {
                    $reciver_id = $chat->student_reciver_id;
                    $reciver = Student::where('id', $reciver_id)->first();
                    $chat->reciver_name = $reciver->firstname . ' ' . $reciver->lastname;
                    $chat->reciver_image = $reciver->image;
                }
            } else if ($chat->student_sender_id != null && $chat->student_affairs_reciver_id != null) {
                if ($chat->student_sender_id == $student_id) {
                    $reciver_id = $chat->student_affairs_reciver_id;
                    $reciver = StudentAffair::where('id', $reciver_id)->first();
                    $chat->reciver_name = $reciver->firstname . ' ' . $reciver->lastname;
                    $chat->reciver_image = $reciver->image;
                } else {
                    $sender_id = $chat->student_sender_id;
                    $sender = Student::where('id', $sender_id)->first();
                    $chat->reciver_name = $sender->firstname . ' ' . $sender->lastname;
                    $chat->reciver_image = $sender->image;
                }
            } else if ($chat->student_sender_id != null && $chat->lecturer_reciver_id != null) {
                if ($chat->student_sender_id == $student_id) {
                    $reciver_id = $chat->lecturer_reciver_id;
                    $reciver = Lecturer::where('id', $reciver_id)->first();
                    $chat->reciver_name = $reciver->firstname . ' ' . $reciver->lastname;
                    $chat->reciver_image = $reciver->image;
                } else {
                    $sender_id = $chat->student_sender_id;
                    $sender = Student::where('id', $sender_id)->first();
                    $chat->reciver_name = $sender->firstname . ' ' . $sender->lastname;
                    $chat->reciver_image = $sender->image;
                }
            } else if ($chat->student_sender_id != null && $chat->student_reciver_id != null) {
                if ($chat->student_sender_id == $student_id) {
                    $reciver_id = $chat->student_reciver_id;
                    $reciver = Student::where('id', $reciver_id)->first();
                    $chat->reciver_name = $reciver->firstname . ' ' . $reciver->lastname;
                    $chat->reciver_image = $reciver->image;
                } else {
                    $sender_id = $chat->student_sender_id;
                    $sender = Student::where('id', $sender_id)->first();
                    $chat->reciver_name = $sender->firstname . ' ' . $sender->lastname;
                    $chat->reciver_image = $sender->image;
                }
            }
        }

        return response()->json([
            'message' => 'Chats retrieved successfully.',
            'data' => $chats,
            'statue' => 200
        ], 200);
    }
    public function getChatsByStudentAffairsId($student_affairs_id)
    {
        $chats = Chat::where('student_affairs_sender_id', $student_affairs_id)
            ->orWhere('student_affairs_reciver_id', $student_affairs_id)
            ->get();
        foreach ($chats as $chat) {
            if ($chat->student_affairs_reciver_id != null && $chat->student_affairs_sender_id != null) {
                if ($chat->student_affairs_reciver_id == $student_affairs_id) {
                    $sender_id = $chat->student_affairs_sender_id;
                    $sender = StudentAffair::where('id', $sender_id)->first();
                    $chat->reciver_name = $sender->firstname . ' ' . $sender->lastname;
                    $chat->reciver_image = $sender->image;
                } else {
                    $reciver_id = $chat->student_affairs_reciver_id;
                    $reciver = StudentAffair::where('id', $reciver_id)->first();
                    $chat->reciver_name = $reciver->firstname . ' ' . $reciver->lastname;
                    $chat->reciver_image = $reciver->image;
                }
            } else if ($chat->student_affairs_reciver_id != null && $chat->student_sender_id != null) {
                if ($chat->student_affairs_reciver_id == $student_affairs_id) {
                    $sender_id = $chat->student_sender_id;
                    $sender = Student::where('id', $sender_id)->first();
                    $chat->reciver_name = $sender->firstname . ' ' . $sender->lastname;
                    $chat->reciver_image = $sender->image;
                } else {
                    $reciver_id = $chat->student_affairs_reciver_id;
                    $reciver = StudentAffair::where('id', $reciver_id)->first();
                    $chat->reciver_name = $reciver->firstname . ' ' . $reciver->lastname;
                    $chat->reciver_image = $reciver->image;
                }
            } else if ($chat->student_affairs_reciver_id != null && $chat->lecturer_sender_id != null) {
                if ($chat->student_affairs_reciver_id == $student_affairs_id) {
                    $sender_id = $chat->lecturer_sender_id;
                    $sender = Lecturer::where('id', $sender_id)->first();
                    $chat->reciver_name = $sender->firstname . ' ' . $sender->lastname;
                    $chat->reciver_image = $sender->image;
                } else {
                    $reciver_id = $chat->student_affairs_reciver_id;
                    $reciver = StudentAffair::where('id', $reciver_id)->first();
                    $chat->reciver_name = $reciver->firstname . ' ' . $reciver->lastname;
                    $chat->reciver_image = $reciver->image;
                }
            }
        }
        return response()->json([
            'message' => 'Chats retrieved successfully.',
            'data' => $chats,
            'statue' => 200
        ], 200);
    }
    public function getChatsByLecturerId($lecturer_id)
    {
        $chats = Chat::where('lecturer_sender_id', $lecturer_id)
            ->orWhere('lecturer_reciver_id', $lecturer_id)
            ->get();
        foreach ($chats as $chat) {
            if ($chat->lecturer_reciver_id != null && $chat->lecturer_sender_id != null) {
                if ($chat->lecturer_reciver_id == $lecturer_id) {
                    $sender_id = $chat->lecturer_sender_id;
                    $sender = Lecturer::where('id', $sender_id)->first();
                    $chat->reciver_name = $sender->firstname . ' ' . $sender->lastname;
                    $chat->reciver_image = $sender->image;
                } else {
                    $reciver_id = $chat->lecturer_reciver_id;
                    $reciver = Lecturer::where('id', $reciver_id)->first();
                    $chat->reciver_name = $reciver->firstname . ' ' . $reciver->lastname;
                    $chat->reciver_image = $reciver->image;
                }
            } else if ($chat->lecturer_reciver_id != null && $chat->student_sender_id != null) {
                $sender_id = $chat->student_sender_id;
                $sender = Student::where('id', $sender_id)->first();
                $chat->reciver_name = $sender->firstname . ' ' . $sender->lastname;
                $chat->reciver_image = $sender->image;
            } else if ($chat->lecturer_reciver_id != null && $chat->student_affairs_sender_id != null) {
                $sender_id = $chat->student_affairs_sender_id;
                $sender = StudentAffair::where('id', $sender_id)->first();
                $chat->reciver_name = $sender->firstname . ' ' . $sender->lastname;
                $chat->reciver_image = $sender->image;
            } else if ($chat->lecturer_sender_id != null && $chat->student_reciver_id != null) {
                $reciver_id = $chat->student_reciver_id;
                $reciver = Student::where('id', $reciver_id)->first();
                $chat->reciver_name = $reciver->firstname . ' ' . $reciver->lastname;
                $chat->reciver_image = $reciver->image;
            } else if ($chat->lecturer_sender_id != null && $chat->student_affairs_reciver_id != null) {
                $reciver_id = $chat->student_affairs_reciver_id;
                $reciver = StudentAffair::where('id', $reciver_id)->first();
                $chat->reciver_name = $reciver->firstname . ' ' . $reciver->lastname;
                $chat->reciver_image = $reciver->image;
            }
            if (
                is_null($chat->lecturer_reciver_id)
                && !is_null($chat->lecturer_sender_id)
                && is_null($chat->student_reciver_id)
                && is_null($chat->student_sender_id)
                && is_null($chat->student_affairs_reciver_id)
                && is_null($chat->student_affairs_sender_id)
            ) {

                //remove this chat
                $chat->delete();
            }
        }

        return response()->json([
            'message' => 'Chats retrieved successfully.',
            'data' => $chats,
            'statue' => 200
        ], 200);
    }
    public function deleteChatById($chat_id)
    {
        $chat = Chat::findOrFail($chat_id);
        $chat->delete();

        return response()->json([
            'message' => 'Chat deleted successfully.',
            'data' => $chat,
            'statue' => 200
        ], 200);
    }
}

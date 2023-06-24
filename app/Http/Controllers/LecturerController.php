<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Lecturer;
use App\Models\Department;

class LecturerController extends Controller
{
    public function index()
    {
        $lecturers = Lecturer::with('department')->get();
        return response()->json([
            'message' => 'Lecturers retrieved successfully',
            'data' => $lecturers
        ], 200);
    }

    public function show($id)
    {
        $lecturer = Lecturer::with('department')->find($id);

        if (!$lecturer) {
            return response()->json([
                'message' => 'Lecturer not found',
                'data' => null
            ], 404);
        }
        return response()->json([
            'message' => 'Lecturer retrieved successfully',
            'data' => $lecturer
        ], 200);
    }
    public function updateFcmTokenByLecturerId(Request $request, $lecturer_id)
    {
        $validatedData = $request->validate([
            'fcm_token' => 'required'
        ]);
        $lecturer = Lecturer::find($lecturer_id);
        $lecturer->fcm_token = $validatedData['fcm_token'];
        $lecturer->save();
        return response()->json([
            'message' => 'FCM token updated successfully!',
            'data' => $lecturer
        ], 200);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'national_id' => 'required|string|unique:lecturers,national_id',
            'email' => 'required|email|unique:lecturers,email',
            'image' => 'nullable|image',
            'course_id' => 'required|integer|exists:courses,id',
            'phone_no' => 'required|string|unique:lecturers,phone_no',
            'password' => 'required|string|min:6',
            'fcm_token' => 'nullable|string',
            'department_id' => 'required|integer|exists:departments,id'

        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'data' => $validator->errors()
            ], 422);
        }

        $image = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('public/images');
        }

        $lecturer = Lecturer::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'national_id' => $request->national_id,
            'email' => $request->email,
            'image' => $image,
            'course_id' => $request->course_id,
            'phone_no' => $request->phone_no,
            'password' => bcrypt($request->password),
            'department_id' => $request->department_id
        ]);
        $lecturer->save();

        return response()->json([
            'message' => 'Lecturer created successfully',
            'data' => $lecturer
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $lecturer = Lecturer::findOrFail($id);

        $lecturer->update([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'national_id' => $request->national_id,
            'email' => $request->email,
            'image' => $request->image,
            'course_id' => $request->course_id,
            'phone_no' => $request->phone_no,
            'password' => bcrypt($request->password),
            'department_id' => $request->department_id,
        ]);


        return response()->json([
            'message' => 'Lecturer updated successfully',
            'data' => $lecturer
        ], 200);
    }

    public function destroy($id)
    {
        $lecturer = Lecturer::findOrFail($id);
        if (!$lecturer) {
            return response()->json([
                'message' => 'المحاضر غير موجود',
                'data' => null
            ], 404);
        }

        $lecturer->delete();

        return response()->json([
            'message' => 'تم حذف المحاضر بنجاح',
            'data' => $lecturer
        ], 201);
    }
    public function login($national_id, $password)
    {
        $lecturer = Lecturer::where('national_id', $national_id)->first();
        if ($lecturer) {
            if (password_verify($password, $lecturer->password)) {
                $department = Department::find($lecturer->department_id);
                $lecturer->department_name = $department->name;
                $lecturer->department_level = $department->level;
                return response()->json([
                    'message' => 'Lecturer logged in successfully',
                    'data' => $lecturer
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Password is incorrect',
                    'data' => null
                ], 404);
            }
        } else {
            return response()->json([
                'message' => 'Lecturer not found',
                'data' => null
            ], 404);
        }
    }
    public function getLecturerById($id)
    {
        $lecturer = Lecturer::with('department')->find($id);

        if (!$lecturer) {
            return response()->json([
                'message' => 'Lecturer not found',
                'data' => null
            ], 404);
        }
        return response()->json([
            'message' => 'Lecturer retrieved successfully',
            'data' => $lecturer
        ], 200);
    }
    public function getLecturerByCourseId($course_id)
    {
        $lecturer = Lecturer::where('course_id', $course_id)->first();
        if (!$lecturer) {
            return response()->json([
                'message' => 'Lecturer not found',
                'data' => null
            ], 404);
        }
        return response()->json([
            'message' => 'Lecturer retrieved successfully',
            'data' => $lecturer
        ], 200);
    }
    public function getClassroomByLecturerId($lecturer_id)
    {
        $classroom = Classroom::where('lecturer_id', $lecturer_id)->first();
        return response()->json([
            'message' => 'Classroom retrieved successfully',
            'data' => $classroom,
            'statue' => 200

        ]);
    }
    public function sendNotificationrToLecturer(Request $request)
    {
        $lecturer = Lecturer::find($request->student_id);
        $fcm_token = $lecturer->fcm_token;
        $title = $request->title;
        $body = $request->body;
        $data = [
            'title' => $title,
            'body' => $body
        ];
    }
    public function getAllLecturers()
    {
        $lecturers = Lecturer::all();
        foreach ($lecturers as $lecturer) {
            $department = Department::find($lecturer->department_id);
            $lecturer->department_name = $department->name;
        }
        return response()->json([
            'message' => 'Lecturers retrieved successfully',
            'data' => $lecturers
        ], 200);
    }
}

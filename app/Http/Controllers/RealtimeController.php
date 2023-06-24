<?php

namespace App\Http\Controllers;

use App\Events\LiveAdded;
use App\Events\QuizAdded;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\Lecturer;
use App\Models\Notification;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Realtimes;
use App\Models\Student;

class RealtimeController extends Controller
{
    public function stratLive($id)
    {
        $classroom = Classroom::find($id);
        $lecturer = Lecturer::find($classroom->lecturer_id);
        $students = Student::where('department_id', $lecturer->department_id)->get();
        event(new LiveAdded($students, $lecturer, $classroom));
        $data = [
            'title' => "تم بدا محاضرة مباشر من الدكتور " . $lecturer->firstname . ' ' . $lecturer->lastname,
            'body' => "يمكنك الان الدخول للمحاضرة من خلال الدخول الي الفصل الدراسي",
        ];
        $tokens = [];
        foreach ($students as $student) {
            if ($student->fcm_token != null) {
                array_push($tokens, $student->fcm_token);
                $notification = new Notification();
                $notification->title = $data['title'];
                $notification->body = $data['body'];
                $notification->student_id = $student->id;
                $notification->lecturer_id = $lecturer->id;
                $notification->type = 'live';
                $notification->date = date('Y-m-d');
                $notification->save();
            }
        }
        // $tokens = ['fcm_token'];
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


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_close($ch);
        $result = curl_exec($ch);
        foreach ($students as $student) {
            $realtime = Realtimes::where('student_id', $student->id)->first();
            if ($realtime == null) {
                $realtime = new Realtimes();
                $realtime->student_id = $student->id;
                $realtime->lecturer_id = $lecturer->id;
                $realtime->classroom_id = $classroom->id;
                $realtime->is_online = false;
                $realtime->is_quiz_started = false;
                $realtime->is_quiz_finished = false;
                $realtime->is_live = true;
                $realtime->save();
            } else {
                $realtime->update(['is_live' => true]);
                $realtime->save();
            }
        }

        return response()->json([
            'message' => 'live started successfully',
            'data' => $classroom,
            'statue' => 200,
            'noti' => json_decode($result),

        ]);
    }
    public function closeLive($id)
    {
        $classroom = Classroom::find($id);
        $lecturer = Lecturer::find($classroom->lecturer_id);
        $students = Student::where('department_id', $lecturer->department_id)->get();
        foreach ($students as $student) {
            $realtime = Realtimes::where('student_id', $student->id)->first();
            if ($realtime == null) {
                $realtime = new Realtimes();
                $realtime->student_id = $student->id;
                $realtime->lecturer_id = $lecturer->id;
                $realtime->classroom_id = $classroom->id;
                $realtime->is_online = false;
                $realtime->is_quiz_started = false;
                $realtime->is_quiz_finished = false;
                $realtime->is_live = false;
                $realtime->save();
            } else {
                $realtime->update(['is_live' => false]);
                $realtime->save();
            }
        }
        event(new LiveAdded($students, $lecturer, $classroom));
        return response()->json([
            'message' => 'live closed successfully',
            'data' => $classroom,
            'statue' => 200,
        ]);
    }
    public function addQuiz($quiz_id)
    {
        $quiz = Quiz::find($quiz_id);
        $course = Course::find($quiz->course_id);
        $classroom = Classroom::find($quiz->classroom_id);
        $students = Student::where('department_id', $course->department_id)->get();
        $lecturer = Lecturer::find($quiz->lecturer_id);

        $tokens = [];
        foreach ($students as $student) {
            if ($student->fcm_token != null) {
                array_push($tokens, $student->fcm_token);
                $notification = new Notification();
                $notification->title = $quiz->title;
                $notification->body = 'تم اضافة اختبار جديد لمادة ' . $course->name .  ' من قبل ' . $lecturer->firstname . ' ' . $lecturer->lastname . ' للفصل الدراسي '
                    . $classroom->name . '  الرجاء الضغط علي رساله لدخول الاختبارات ' . ' مده الاختبار هي ' . $quiz->limit_time . ' دقيقه'  . ' ' . 'من الان';
                $notification->student_id = $student->id;
                $notification->lecturer_id = $lecturer->id;
                $notification->type = 'quiz';
                $notification->date = date('Y-m-d');
                $notification->save();
            }
            $realtime = Realtimes::where('student_id', $student->id)->first();
            if ($realtime == null) {
                $realtime = new Realtimes();
                $realtime->student_id = $student->id;
                $realtime->lecturer_id = $quiz->lecturer_id;
                $realtime->classroom_id = $quiz->classroom_id;
                $realtime->is_quiz_started = true;
                $realtime->quiz_id = $quiz->id;
                $realtime->save();
            } else {
                $realtime->update(['is_quiz_started' => true]);
                $realtime->update(['quiz_id' => $quiz->id]);

                $realtime->save();
            }
        }

        if (!$quiz) {
            return response()->json([
                'message' => 'Quiz not found'
            ], 404);
        }
        $data = [
            'title' => $quiz->title,
            'body' => 'تم اضافة اختبار جديد لمادة ' . $course->name .  ' من قبل ' . $lecturer->firstname . ' ' . $lecturer->lastname . ' للفصل الدراسي '
                . $classroom->name . '  الرجاء الضغط علي رساله لدخول الاختبارات ' . ' مده الاختبار هي ' . $quiz->limit_time . ' دقيقه'  . ' ' . 'من الان',
            'sound' => 'default',
            'color' => '#203E78',
        ];
        $customData = [
            'notification_type' => 'quizAdded',
            'quiz_id' => $quiz->id,
            'quiz_time' => $quiz->limit_time,
            'quiz_title' => $quiz->title,
            'course_name' => $course->name,
            'classroom_name' => $classroom->name,
            'lecturer_name' => $lecturer->firstname . ' ' . $lecturer->lastname,
        ];
        $payload = [
            'data' => [
                'notification_type' => 'quizAdded',
                'quiz_id' => $quiz->id,

            ],
            'registration_ids' => $tokens,
            'notification' => $data,
            'priority' => 'high',
            'messge_type' => 'quizAdded'
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
        $questions = Question::where('quiz_id', $quiz->id)->get();
        event(new QuizAdded($quiz, $questions, $lecturer, $classroom));
        return response()->json([
            'message' => 'push notification sent successfully for students',
            'data' => $questions,
            'result' => json_decode($result, true)
        ], 201);
    }
    public function endQuiz($quiz_id)
    {
        $quiz = Quiz::find($quiz_id);
        $course = Course::find($quiz->course_id);
        $students = Student::where('department_id', $course->department_id)->get();
        foreach ($students as $student) {
            $realtime = Realtimes::where('student_id', $student->id)->first();
            if ($realtime == null) {
                $realtime = new Realtimes();
                $realtime->student_id = $student->id;
                $realtime->lecturer_id = $quiz->lecturer_id;
                $realtime->classroom_id = $quiz->classroom_id;
                $realtime->is_quiz_started = false;
                $realtime->save();
            } else {
                $realtime->update(['is_quiz_started' => false]);
                $realtime->save();
            }
        }
        return response()->json([
            'message' => 'quiz ended successfully',
            'data' => $quiz,
            'statue' => 200,
        ]);
    }
    public function endQuizForStudentId($student_id)
    {
        $realtime = Realtimes::where('student_id', $student_id)->first();
        $realtime->is_quiz_started = false;
        $realtime->update(['is_quiz_started' => false]);
        $realtime->save();
        return response()->json([
            'message' => 'quiz ended successfully',
            'data' => $realtime,
            'statue' => 200,
        ]);
    }
    public function updateStatus($student_id, $is_online)
    {
        $realtime = Realtimes::where('student_id', $student_id)->first();
        $realtime->is_online = $is_online;
        $realtime->update(['is_online' => $is_online]);
        $realtime->save();
        return response()->json([
            'message' => 'student status updated successfully',
            'data' => $realtime,
            'statue' => 200,
        ]);
    }
    public function finishLive($student_id, $is_live)
    {
        $realtime = Realtimes::where('student_id', $student_id)->first();
        $realtime->is_live = $is_live;
        //update is_live to false in database
        $realtime->update(['is_live' => $is_live]);
        $realtime->save();
        return response()->json([
            'message' => 'live finished successfully',
            'data' => $realtime,
            'statue' => 200,
        ]);
    }
    public function startQuiz($student_id, $is_quiz_started)
    {
        $realtime = Realtimes::where('student_id', $student_id)->first();
        $realtime->is_quiz_started = $is_quiz_started;
        $realtime->update(['is_quiz_started' => $is_quiz_started]);

        $realtime->save();
        return response()->json([
            'message' => 'quiz started successfully',
            'data' => $realtime,
            'statue' => 200,
        ]);
    }
    public function getIsLive($student_id)
    {
        $realtime = Realtimes::where('student_id', $student_id)->first();


        return response()->json([
            'message' => 'get is live successfully',
            'data' => $realtime,
            'statue' => 200,
        ]);
    }
    public function getIsQuizStarted($student_id)
    {
        $realtime = Realtimes::where('student_id', $student_id)->first();
        $quiz = Quiz::where('id', $realtime->quiz_id)->first();
        $realtime->quiz = $quiz;
        return response()->json([
            'message' => 'get is quiz started successfully',
            'data' => $realtime,
            'statue' => 200,
        ]);
    }
    public function getIsQuizFinished($student_id)
    {
        $realtime = Realtimes::where('student_id', $student_id)->first();
        return response()->json([
            'message' => 'get is quiz finished successfully',
            'data' => $realtime,
            'statue' => 200,
        ]);
    }
    public function getIsOnline($student_id)
    {
        $realtime = Realtimes::where('student_id', $student_id)->first();
        return response()->json([
            'message' => 'get is online successfully',
            'data' => $realtime,
            'statue' => 200,
        ]);
    }
}

<?php

use App\Http\Controllers\StudentAffairController;
use App\Http\Controllers\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LecturerController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RealtimeController;
use App\Models\Realtime;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
//? 1==>========================Student Affairs===================
Route::post('/student-affairs/add', StudentAffairController::class . '@store');
Route::post('/student-affairs/update', StudentAffairController::class . '@update');
Route::get('student-affairs/login/{national_id}/{password}', StudentAffairController::class . '@login');
Route::get('student-affairs/all', StudentAffairController::class . '@getAllStudentAffairs');
Route::delete('student-affairs/delete/{id}', StudentAffairController::class . '@delete');

//? 2==>==============================Student============================

Route::post('/student/add', StudentController::class . '@store');
Route::post('/student/update', StudentController::class . '@update');
Route::post('/student/delete', StudentController::class . '@delete');
Route::get('/student/get', StudentController::class . '@index');
Route::get('student/login/{national_id}/{password}', StudentController::class . '@login');
Route::get('student/getAllStudentByDepartmentId/{departmentId}', StudentController::class . '@getAllStudentByDepartmentId');
Route::post('/student/{student_id}/update-fcm-token', [StudentController::class, 'updateFcmTokenByStudentId']);
Route::get('/student/all', StudentController::class . '@getAllStudents');
Route::delete('/student/delete/{id}', StudentController::class . '@destroy');

//? 3==>==================Lecturer============================
Route::post('/lecturer/add', LecturerController::class . '@store');
Route::post('/lecturer/update', LecturerController::class . '@update');
Route::post('/lecturer/delete', LecturerController::class . '@delete');
Route::get('/lecturer/get', LecturerController::class . '@index');
Route::get('/lecturer/login/{national_id}/{password}', LecturerController::class . '@login');
Route::get('/lecturer/getLecturersById/{id}', LecturerController::class . '@getLecturerById');
Route::get('/lecturer/getClassroomByLecturerId/{id}', LecturerController::class . '@getClassroomByLecturerId');
Route::get('/lecturer/all', LecturerController::class . '@getAllLecturers');
Route::delete('/lecturer/delete/{id}', LecturerController::class . '@destroy');
Route::post('/lecturer/{lecturer_id}/update-fcm-token', [StudentController::class, 'updateFcmTokenByLecturerId']);


//? 4==>================= department ====================

Route::get('/department/get', DepartmentController::class . '@index');
Route::post('/department/add', DepartmentController::class . '@store');
Route::post('/department/update', DepartmentController::class . '@update');
Route::post('/department/delete', DepartmentController::class . '@delete');
Route::get('/department/all', DepartmentController::class . '@allDepartments');
Route::get('/department/allCourses/{department_id}', DepartmentController::class . '@getAllCoursesByDepartmentId');
Route::get('/department/getClassroomIdByDepartmentId/{id}', DepartmentController::class . '@getClassroomIdByDepartmentId');
Route::get('/department/{id}', DepartmentController::class . '@getDepartmentById');

//? 5==>================= chat ====================
Route::post('/chat/update', ChatController::class . '@update');
Route::delete('/chat/delete/{id}', ChatController::class . '@deleteChatById');
Route::get('/chat/get', ChatController::class . '@index');
Route::post('/chat/add', ChatController::class . '@store');
Route::get('/chat/getMessages/{chat_id}', ChatController::class . '@getMessagesByChatId');
Route::get('/chat/getChatsByStudentId/{student_id}', ChatController::class . '@getChatsByStudentId');
Route::get('/chat/getChatsByLecturerId/{lecturer_id}', ChatController::class . '@getChatsByLecturerId');
Route::get('/chat/getChatsByStudentAffairId/{student_affair_id}', ChatController::class . '@getChatsByStudentAffairId');


//? 6==>================= course ====================
Route::post('/course/add', CourseController::class . '@store');
Route::post('/course/update', CourseController::class . '@update');
Route::get('/course/get', CourseController::class . '@index');
Route::get('/course/all', CourseController::class . '@getAllCourses');
Route::get('/course/getCoursesByDepartmentId/{id}', CourseController::class . '@getCoursesByDepartmentId');
Route::delete('/course/delete/{id}', CourseController::class . '@destroy');


//? 7==>================= Message ====================
Route::post('/message/add', MessageController::class . '@store');
Route::post('/message/update', MessageController::class . '@update');
Route::post('/message/delete', MessageController::class . '@delete');
Route::get('/message/get', MessageController::class . '@index');
Route::get('/message/getMessagesByChatId/{chat_id}', MessageController::class . '@getMessagesByChatId');
Route::delete('/message/deleteMessageById/{id}', MessageController::class . '@deleteMessageById');
Route::get('/message/getMessagesByClassroomId/{classroom_id}', MessageController::class . '@getMessagesByClassroomId');
//? 8==>================= Admin ====================

Route::post('/admin/add', AdminController::class . '@store');
Route::post('/admin/update', AdminController::class . '@update');
Route::post('/admin/delete', AdminController::class . '@delete');
Route::get('/admin/get', AdminController::class . '@index');
Route::get('/admin/login/{national_id}/{password}', AdminController::class . '@login');

//? 9==>================= classroom ====================
Route::post('/classroom/add', ClassroomController::class . '@store');
Route::post('/classroom/update', ClassroomController::class . '@update');
Route::post('/classroom/delete', ClassroomController::class . '@delete');
Route::get('/classroom/get', ClassroomController::class . '@index');
Route::get('/classroom/getClassroomsByDepartmentId/{id}', ClassroomController::class . '@getClassroomsByDepartmentId');
Route::get('/classroom/getCourseNameByClassroomId/{classroom_id}', ClassroomController::class . '@getCourseNameByClassroomId');
Route::get('/classroom/getClassroomByLecturerId/{lecturer_id}', ClassroomController::class . '@getClassroomByLecturerId');
//? 10==>================= post ====================
Route::post('/post/add', PostController::class . '@store');
Route::post('/post/update', PostController::class . '@update');
Route::get('/posts/getAll', PostController::class . '@getAllPosts');
Route::get('/posts/student/{student_id}', PostController::class . '@getPostsByStudentId');
Route::get('/posts/lecturer/{lecturer_id}', PostController::class . '@getPostsByLecturerId');
Route::get('posts/student-affairs/{student_affairs_id}', PostController::class . '@getPostsByStudentAffairsId');
Route::get('/posts/{student_id}/{student_affairs_id}/{lecturer_id}', PostController::class . '@getPostsAndNameByStudentIdOrStudentAffairsIdOrLecturerId');
Route::get('/posts/{student_affairs_id}', PostController::class . '@getPostsByStudentAffairsId');
Route::delete('/posts/delete/{id}/{student_id}', PostController::class . '@deletePostByIdAndStudentId');
Route::delete('/posts/deletebystudentid/{id}/{student_id}', PostController::class . '@checkStudentIsPostStudentAndDelete');
Route::delete('/posts/deletebylecturerid/{id}/{lecturer_id}', PostController::class . '@checkLecturerIsPostLecturerAndDelete');
Route::delete('/posts/deletebystudentaffairsid/{id}/{student_affairs_id}', PostController::class . '@checkStudentAffairsIsPostStudentAffairsAndDelete');
Route::get('/posts/searchInPosts/{search}', PostController::class . '@searchInPosts');
//addRectOnPost
Route::post('/posts/{id}/react', PostController::class . '@addRectOnPost');
//? 11==>================= comment ====================
Route::post('/comment/add', CommentController::class . '@store');
Route::post('/comment/update', CommentController::class . '@update');
Route::post('/comment/delete', CommentController::class . '@delete');
Route::get('/comment/get', CommentController::class . '@index');
Route::get('/comment/getCommentsByPostId/{post_id}', CommentController::class . '@getCommentsByPostId');

//? 12==>================= reply comment ====================

Route::post('/reply-comment/add', ReplyCommentController::class . '@store');
Route::post('/reply-comment/update', ReplyCommentController::class . '@update');
Route::post('/reply-comment/delete', ReplyCommentController::class . '@delete');
Route::get('/reply-comment/get', ReplyCommentController::class . '@index');

//? 13==>================= quiz ====================
Route::post('/quiz/add', QuizController::class . '@store');
Route::post('/quiz/update', QuizController::class . '@update');
Route::post('/quiz/delete', QuizController::class . '@delete');
Route::get('/quiz/get', QuizController::class . '@index');

//? 14==>================= question ====================
Route::post('/question/add', QuestionController::class . '@store');
Route::post('/question/update', QuestionController::class . '@update');
Route::post('/question/delete', QuestionController::class . '@delete');
Route::get('/question/get', QuestionController::class . '@index');
Route::get('/question/getQuestionsByQuizId', QuestionController::class . '@getQuestionsByQuizId');
Route::get('/question/getQuestionsByQuizIdAndLecturerId/{quiz_id}/{lecturer_id}', QuestionController::class . '@getQuestionsByQuizIdAndLecturerId');


//? 15==>================= rating ======================
Route::post('/rating/add', RatingController::class . '@store');



//? 16==>================= notificatins ======================

Route::post('/notification/sendNotification', NotificationController::class . '@sendNotification');
Route::post('/notification/sendNotificationsForAllStudents', NotificationController::class . '@sendNotificationForAllUsersByFCMToken');
Route::get('/notification/getNotificationByStudentId/{student_id}', NotificationController::class . '@getNotificationByStudentId');
Route::get('/notification/getNotificationByLcturerId/{lecturer_id}', NotificationController::class . '@getNotificationByLecturerId');

//? 17==>================= realtime ======================
Route::get('/realtime/livestrated/{classroom_id}', RealtimeController::class . '@stratLive');
Route::get('/realtime/finishLive/{classroom_id}', RealtimeController::class . '@closeLive');
Route::post('/realtime/updateStatus/{student_id}/{is_online}', RealtimeController::class . '@updateStatus');
Route::post('/realtime/isQuizStarted/{student_id}/{is_quiz_started}', RealtimeController::class . '@startQuiz');
Route::post('/realtime/finishLive/{student_id}/{is_live}', RealtimeController::class . '@finishLive');
Route::get('/realtime/isLive/{student_id}', RealtimeController::class . '@getIsLive');
Route::get('/realtime/isQuizStarted/{student_id}', RealtimeController::class . '@getIsQuizStarted');
Route::get('/realtime/isOnline/{student_id}', RealtimeController::class . '@getIsOnline');
Route::get('/realtime/push-quiz/{quiz_id}', RealtimeController::class . '@addQuiz');
Route::get('/realtime/end-quiz/{quiz_id}', RealtimeController::class . '@endQuiz');
//endQuisForStudentId
Route::get('/realtime/end-quiz-for-student/{student_id}', RealtimeController::class . '@endQuizForStudentId');

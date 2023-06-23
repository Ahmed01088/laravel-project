<?php

namespace App\Http\Controllers;

use App\Events\QuizAdded;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\Lecturer;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Student;

class QuestionController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([

            'question_text' => 'required|string|max:255',
            'answer_a' => 'required|string|max:255',
            'answer_b' => 'required|string|max:255',
            'answer_c' => 'required|string|max:255',
            'answer_d' => 'required|string|max:255',
            'correct_answer' => 'required|integer|max:11',
            'quiz_id' => 'required|exists:quizzes,id',


        ]);
        $question = Question::create($validatedData);
        $question->save();
        return response()->json([
            'message' => 'success created',
            'data' => $question
        ], 201);
    }

    public function update(Request $request, Question $question)
    {
        $validatedData = $request->validate([
            'question_text' => 'required|string|max:255',
            'answer_a' => 'required|string|max:255',
            'answer_b' => 'required|string|max:255',
            'answer_c' => 'required|string|max:255',
            'answer_d' => 'required|string|max:255',
            'correct_answer' => 'required|integer|max:11',
            'quiz_id' => 'required|exists:quizzes,id',
        ]);

        $question->update($validatedData);
        return response()->json([
            'message' => 'success updated',
            'data' => $question
        ], 200);
    }

    public function destroy(Question $question)
    {
        $question->delete();
        return response()->json([
            'message' => 'success deleted',
            'data' => $question
        ], 201);
    }
    public function show(Question $question)
    {
        return response()->json([
            'message' => 'success retrieved',
            'data' => $question
        ], 200);
    }
    public function index()
    {
        $questions = Question::all();
        return response()->json([
            'message' => 'success retrieved',
            'data' => $questions
        ], 200);
    }
    public function getQuestionsByQuizId($quiz_id)
    {
        $questions = Question::where('quiz_id', $quiz_id)->get();
        return response()->json([
            'message' => 'success retrieved',
            'data' => $questions
        ], 200);
    }
    // Assuming you have a "Question" model representing the "questions" table
    // and a "Quiz" model representing the "quizzes" table

    public function getQuestionsByQuizIdAndLecturerId($quizId, $lecturerId)
    {
        // Retrieve questions by quiz_id and lecturer_id
        $questions = Question::whereHas('quiz', function ($query) use ($quizId, $lecturerId) {
            $query->where('id', $quizId)
                ->where('lecturer_id', $lecturerId);
        })
            ->get();
        $quiz = Quiz::where('id', $quizId)->first();
        if (!$quiz) {
            return [];
        }
        $limit_time = $quiz->limit_time;

        // Return questions
        return response(
            [
                'message' => 'success retrieved',
                'data' => $questions,
                'quiz_time' => $limit_time
            ],
            200
        );
    }
}

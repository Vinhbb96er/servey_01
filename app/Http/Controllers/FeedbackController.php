<?php

namespace App\Http\Controllers;

use App\Repositories\Feedback\FeedbackInterface;
use Illuminate\Http\Request;
use App\Http\Requests\FeedbackRequest;
use App\Models\Feedback;
use DB;
use Exception;
use Auth;
use Session;

class FeedbackController extends Controller
{
    protected $feedbackRepository;

    public function __construct(FeedbackInterface $feedbackRepository)
    {
        $this->feedbackRepository = $feedbackRepository;
        $this->middleware('profile', ['except' => ['store']]);
    }

    public function index()
    {
        if (Auth::user()->cannot('viewAll', Feedback::class)) {
            return view('clients.layout.403');
        }

        $feedbacks = $this->feedbackRepository->getFeedbacks();
        $user = Auth::user();
        Session::put('page_profile_active', config('settings.page_profile_active.list_feedback'));

        return view('clients.feedback.index', compact('feedbacks', 'user'));
    }

    public function store(FeedbackRequest $request)
    {
        if (!$request->ajax()) {
            return response()->json([
                'success' => false,
            ]);
        }

        DB::beginTransaction();

        try {
            $feedbackData =  $request->only('name', 'email', 'content');
            $this->feedbackRepository->create($feedbackData);
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => trans('lang.send_feedback_success', ['name' => $feedbackData['name']]),
            ]);
        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => trans('lang.send_feedback_error'),
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $feedback = $this->feedbackRepository->findOrFail($id);

            if (Auth::user()->cannot('delete', $feedback)) {
                return view('clients.layout.403');
            }

            $feedback->delete();
            Session::flash('success', trans('lang.delete_feedback_success'));
        } catch (Exception $e) {
            Session::flash('error', trans('lang.delete_feedback_error'));
        }

        return redirect()->back();
    }

    public function getListFeedback(Request $request)
    {
        if (!$request->ajax()) {
            return response()->json([
                'success' => false,
            ]);
        }

        $data = $request->only('name', 'condition_search');
        $feedbacks = $this->feedbackRepository->getFeedbacks($data);
        $html = view('clients.feedback.list_feedback', compact('feedbacks'))->render();

        return response()->json([
            'success' => true,
            'html' => $html,
        ]);
    }
}

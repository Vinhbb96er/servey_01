<?php

namespace App\Repositories\Feedback;

use App\Repositories\BaseRepository;
use App\Models\Feedback;

class FeedbackRepository extends BaseRepository implements FeedbackInterface
{
    public function getModel()
    {
        return Feedback::class;
    }

    public function getFeedbacks($data = [])
    {
        $feedbacks = $this->model;

        if (!empty($data['name'])) {
            switch ($data['condition_search']) {
                case config('settings.feedbacks.condition_search.all'):
                    $feedbacks = $feedbacks->where('name', 'like', '%' . $data['name'] . '%')
                        ->orWhere('email', 'like', '%' . $data['name'] . '%');
                    break;
                case config('settings.feedbacks.condition_search.by_name'):
                    $feedbacks = $feedbacks->where('name', 'like', '%' . $data['name'] . '%');
                    break;
                case config('settings.feedbacks.condition_search.by_email'):
                    $feedbacks = $feedbacks->where('email', 'like', '%' . $data['name'] . '%');
                    break;
            }
        }

        return $feedbacks->orderBy('created_at', 'desc')->paginate();
    }
}

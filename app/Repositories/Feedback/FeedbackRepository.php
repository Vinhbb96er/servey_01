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

    public function create($input)
    {
        parent::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'content' => $input['content'],
        ]);

        return true;
    }
}

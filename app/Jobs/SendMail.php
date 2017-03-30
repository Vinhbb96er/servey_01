<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Collection;

class SendMail implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $inputs;
    protected $type;

    public function __construct(Collection $inputs, $type)
    {
        $this->inputs = $inputs;
        $this->type = $type;
    }

    public function handle()
    {
        $data = $this->inputs->only([
            'name',
            'title',
            'description',
            'link',
        ]);

        if ($this->type == 'mailManage') {
            $view = 'emails.email_manage';
            $data->put('linkManage', $this->inputs['linkManage']);
        } elseif ($type == 'reAnswer') {
            $view = 'emails.email_resend_answer';
        } else {
            $view = 'emails.email_invite';
            $data->put('emailSender', $this->inputs['emailSender']);
        }

        $email = $this->inputs['email'];
        Mail::send($view, $data->toArray(), function ($message) use ($email) {
           $message->from(config('mail.from.address') , trans('survey.title_web'));
           $message->to($email)->subject(trans('survey.subject_web'));
       });
    }
}

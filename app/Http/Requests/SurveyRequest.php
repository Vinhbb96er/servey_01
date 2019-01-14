<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SurveyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $settingAnswerRequired = implode(',', config('settings.survey_setting.answer_required'));
        $settingReminderEmailType = implode(',', config('settings.survey_setting.reminder_email'));
        $settingPrivacy = implode(',', config('settings.survey_setting.privacy'));
        $settingSendMailToWsm = implode(',', config('settings.survey.send_mail_to_wsm'));
        $questionTypes =  implode(',', array_diff(config('settings.question_type'), [config('settings.question_type.no_type')]));
        $answerTypes = implode(',', config('settings.answer_type'));

        $rules = [
            'title' => 'required|max:255',
            'start_time' => 'date',
            'end_time' => 'date|after:start_time',
            'setting.answer_required' => "required|integer|in:{$settingAnswerRequired}",
            'setting.answer_limited' => 'required|integer|min:0',
            'setting.reminder_email.type' => "required|integer|in:{$settingReminderEmailType}",
            'setting.reminder_email.next_time' => 'date|after:start_time',
            'setting.privacy' => "required|integer|in:{$settingPrivacy}",
            'invited_email.subject' => 'required',
            'invited_email.send_mail_to_wsm' => "required|integer|in:{$settingSendMailToWsm}",
            'invited_email.emails.*' => 'email|distinct',
            'members.*.email' => 'email|distinct',
            'members.*.role' => 'integer|in:1',
            'sections.*.title' => 'required|distinct',
            'sections.*.questions.*.media' => 'url',
            'sections.*.questions.*.type' => "required|integer|in:{$questionTypes}",
            'sections.*.questions.*.require' => 'required|boolean',
            'sections.*.questions.*.answers.*.type' => "required|integer|in:{$answerTypes}",
            'sections.*.questions.*.answers.*.media' => 'url',
        ];

        // validation rules distinct answer in question
        $sections = $this->json()->get('sections');
        foreach ($sections as $sectionIndex => $sectionVal) {
            foreach ($sections[$sectionIndex]['questions'] as $questionIndex => $questionVal) {
                if (!in_array($questionVal['type'], [
                    config('settings.question_type.image'),
                    config('settings.question_type.video'),
                ])) {
                    $rules['sections.' . $sectionIndex . '.questions.' . $questionIndex . '.title'] = 'required|distinct';
                }

                $rules['sections.' . $sectionIndex . '.questions.' . $questionIndex . '.answers.*.content'] = 'required|distinct'; 
            }
        }

        return $rules;
    }

    /**
     * Get data to be validated from the request.
     *
     * @return array
     */
    protected function validationData()
    {
        return $this->json()->all();
    }
}

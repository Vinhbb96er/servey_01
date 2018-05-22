<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Survey;
use App\Models\Invite;
use App\Models\Section;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Result;
use Carbon\Carbon;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        User::truncate();
        factory(User::class)->create([
            'email' => 'admin@gmail.com',
            'name' => 'Admin',
        ]);

        factory(User::class)->create([
            'email' => 'supperadmin@gmail.com',
            'name' => 'Supper Admin',
        ]);

        factory(User::class, 10)->create()->each(function ($user) use ($faker) {
            $survey = factory(Survey::class)->create();

            // create settings
            $survey->settings()->createMany($this->getKeySetting());

            // create owner
            $survey->members()->attach($user->id, [
                'role' => config('settings.survey.members.owner'),
                'status' => config('settings.survey.members.status.approve'),
            ]);

            //create invites mail
            $survey->invite()->create([
                'invite_mails' => $this->getEmailOfUser($user),
                'answer_mails' => '',
                'status' => config('settings.survey.invite_status.not_finish'),
                'subject' => $survey->title,
                'number_invite' => User::where('id', '!=', $user->id)->count(),
            ]);

            // create sections
            factory(Section::class, 3)->create([
                'survey_id' => $survey->id,
            ])->each(function ($section) use ($faker, $user) {
                factory(Question::class, 3)->create([
                    'section_id' => $section->id,
                ])->each(function ($question) use ($faker, $user) {
                    $settings = $question->settings()->create([
                        'key' => $faker->numberBetween(1, 9),
                        'value' => config('settings.setting_type.question_type.key'),
                    ]);

                    if (in_array($settings->key, [
                        config('settings.question_type.multiple_choice'),
                        config('settings.question_type.checkboxes'),
                    ])) {

                        factory(Answer::class, 3)->create([
                            'question_id' => $question->id,
                        ])->each(function ($answer) use ($faker){
                            $answer->settings()->create([
                                'key' => $faker->numberBetween(1, 2),
                                'value' => config('settings.setting_type.answer_type.key'),
                            ]);
                        });
                    }

                    if ($settings->key == config('settings.question_type.image')) {
                        $question->media()->create([
                            'user_id' => $user->id,
                            'type' => config('settings.media_type.image'),
                            'url' => $faker->imageUrl(124, 124, 'fashion', true, 'Faker', false),
                        ]);
                    }

                    if ($settings->key == config('settings.question_type.video')) {
                        $question->media()->create([
                            'user_id' => $user->id,
                            'type' => config('settings.media_type.video'),
                            'url' => 'https://www.youtube.com/watch?v=s-p4Rmh9s9w',
                        ]);
                    }
                });
            });
        });

        //create result
        $users = User::all();

        foreach ($users as $user) {
            $questions = Question::whereHas('settings', function ($query) {
                $query->whereNotIn('key', [
                    config('settings.question_type.image'),
                    config('settings.question_type.video'),
                    config('settings.question_type.title'),
                ]);
            })->get();

            $results = [];

            foreach ($questions as $question) {
                $answer_id = 0;
                $question_id = $question->id;
                $key = $question->settings()->pluck('key')->first();

                if (in_array($key, [
                    config('settings.question_type.multiple_choice'),
                    config('settings.question_type.checkboxes'),
                ])) {
                    $answer_id = $question->answers()->get()->random()->id;
                } else {
                    $answer_id = 0;
                }

                if ($answer_id && Answer::find($answer_id)->settings()->pluck('key')->first() == 2) {
                    $content = str_replace(' ', '', $faker->unique()->paragraph);
                } else {
                    $content = $this->getContent($key);
                }

                $results[] = [
                    'answer_id' => $answer_id,
                    'question_id' => $question_id,
                    'content' => $content,
                    'client_ip' => 0,
                ];
            }

            $user->results()->createMany($results);
        }

    }

    public function getEmailOfUser($user)
    {
        $emails = User::where('id', '!=', $user->id)->pluck('email')->all();
        $listMails = '';

        foreach ($emails as $email) {
            $listMails .= $email . '/';
        }

        return $listMails;
    }

    public function getKeySetting()
    {
        $keySettings = [
            config('settings.setting_type.answer_required.key'),
            config('settings.setting_type.answer_limited.key'),
            config('settings.setting_type.reminder_email.key'),
            config('settings.setting_type.privacy.key'),
        ];

        $settings = [];

        foreach ($keySettings as $value) {
            $settings[] = [
                'key' => $value,
                'value' => $this->getValue($value),
            ];
        }

        return $settings;
    }

    public function getValue($value)
    {
        $faker = Faker\Factory::create();

        switch ($value) {
            case config('settings.setting_type.answer_required.key'):
                return $faker->numberBetween(0, 2);
            case config('settings.setting_type.answer_limited.key'):
                return $faker->numberBetween(1, 10);
            case config('settings.setting_type.privacy.key'):
                return $faker->numberBetween(1, 2);
            case config('settings.setting_type.reminder_email.key'):
                return $faker->numberBetween(0, 3);

            default:
                break;
        }
    }

    public function getContent($value)
    {
        $faker = Faker\Factory::create();

        switch ($value) {
            case config('settings.question_type.date'):
                return Carbon::createFromFormat('Y-m-d', $faker->date($format = 'Y-m-d', $min = 'now'));
            case config('settings.question_type.time'):
                return Carbon::createFromFormat('h:i', $faker->date($format = 'h:i', $min = 'now'));
            case config('settings.question_type.short_answer'):
                return $faker->paragraph(10);
            case config('settings.question_type.long_answer'):
                return $faker->paragraph();

            default:
                return '';
        }
    }
}

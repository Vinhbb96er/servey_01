<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Policies\ProfilePolicy;
use App\Policies\SurveyPolicy;
use App\Policies\FeedbackPolicy;
use App\Models\User;
use App\Models\Survey;
use App\Models\Feedback;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class => ProfilePolicy::class,
        Survey::class => SurveyPolicy::class,
        Feedback::class => FeedbackPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}

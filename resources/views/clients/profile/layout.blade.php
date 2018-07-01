@extends ('clients.layout.master')

@push('styles')
    {!! Html::style(elixir(config('settings.public_template') . 'css/theme-styles.css')) !!}
    {!! Html::style(elixir(config('settings.public_template') . 'css/blocks.css')) !!}
    {!! Html::style(elixir(config('settings.public_template') . 'css/form-builder-custom.css')) !!}
    {!! Html::style(asset(config('settings.plugins') . 'datatables/css/jquery.dataTables.css')) !!}
    {!! Html::style(elixir(config('settings.public_template') . 'css/datatables-custom.css')) !!}
@endpush

@section ('content')
    <div class="font-profile">
        @include('clients.profile.notice')
        <div class="container padding-profile background-container">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="ui-block">
                        <div class="top-header">
                            <div class="top-header-thumb image-background-profile">
                                <img src="{{ asset($user->background) }}" alt="nature">
                            </div>
                            <div class="profile-section">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5 list-profile-menu">
                                        @if (Auth::user() == $user)
                                            <ul class="profile-menu">
                                                <li>
                                                    <a href="{{ route('survey.profile.index') }}"
                                                        class="{{ Session::get('page_profile_active') ==
                                                            config('settings.page_profile_active.information')
                                                            ? 'active' : '' }}">
                                                        @lang('profile.information')
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('survey.survey.show-surveys') }}" class="{{ Session::get('page_profile_active') ==
                                                            config('settings.page_profile_active.list_survey')
                                                            ? 'active' : '' }}">
                                                        @lang('profile.list_survey')
                                                    </a>
                                                </li>
                                            </ul>
                                        @endif
                                    </div>
                                    <div class="col-lg-5 offset-lg-2 col-md-5 offset-md-2 list-profile-menu">
                                        @if (Auth::user() == $user)
                                            <ul class="profile-menu">
                                                @if (Auth::user()->isAdmin())
                                                    <li>
                                                        <a href="{{ route('feedbacks.index') }}" 
                                                            class="{{ Session::get('page_profile_active') ==
                                                            config('settings.page_profile_active.list_feedback')
                                                            ? 'active' : '' }}">@lang('lang.list_feedback')
                                                        </a>
                                                    </li>
                                                @endif
                                                <li>
                                                    <a href="{{ route('surveys.create') }}">@lang('lang.create_survey')</a>
                                                </li>
                                            </ul>
                                        @endif
                                    </div>
                                </div>
                                @if (Auth::user() == $user)
                                    <div class="control-block-button">
                                        <div class="btn btn-control btn-color-red more">
                                            <span class="icon-setting-profile"><i class="fa fa-sliders"></i></span>
                                            <ul class="more-dropdown more-with-triangle triangle-bottom-right">
                                                <li>
                                                    <a href="#" data-toggle="modal" data-target="#update-profile-photo">@lang('profile.update_profile_photo')</a>
                                                </li>
                                                <li>
                                                    <a href="#" data-toggle="modal" data-target="#update-background-photo">@lang('profile.update_cover_photo')</a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('survey.profile.edit', $user->id) }}">@lang('profile.account_settings')</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="top-header-author">
                                <a href="{{ route('survey.profile.show', $user->id) }}" class="author-thumb">
                                    {!! Html::image(asset($user->image_path), '', ['width' => '120', 'height' => '120']) !!}
                                </a>
                                <div class="author-content">
                                    <a href="{{ route('survey.profile.show', $user->id) }}" class="h4 author-name">{{ ucwords($user->name) }}</a>
                                    <div class="country">{{ $user->email }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @yield ('content-profile')

        <div class="modal fade" id="update-profile-photo">
            <div class="modal-dialog ui-block window-popup update-header-photo">
                <a href="#" class="close icon-close" data-dismiss="modal">
                    <span><i class="fa fa-times"></i></span>
                </a>

                <div class="ui-block-title">
                    <h6 class="title">@lang('profile.update_profile_photo')</h6>
                </div>

                {!! Form::open(['route' => ['survey.profile.changeavatar'], 'method' => 'post', 'files' => 'true']) !!}
                    <a href="#" id="change-avatar" class="upload-photo-item">
                        <span><i class="fa fa-desktop fa-3x"></i></span>
                        <h6>@lang('profile.upload_photo')</h6>
                        <span class="browse-your-computer">@lang('profile.browse_your_computer')</span>
                        <span class="name-picture-profile"></span>
                    </a>
                    <a href="{{ route('survey.profile.deleteavatar') }}" class="upload-photo-item" onclick="return confirm('@lang('profile.are_you_sure_want_to_delete')')">
                        <span><i class="fa fa-trash fa-3x"></i></span>
                        <h6>@lang('profile.delete_avatar')</h6>
                        <span>@lang('profile.use_the_default_avatar')</span>
                    </a>
                    {!! Form::file('image', ['id' => 'upload-avatar', 'class' => 'a']) !!}
                    {!! Form::submit(trans('profile.change'), ['class' => 'submit-image-profile']) !!}
                {!! Form::close() !!}
            </div>
        </div>

        <div class="modal fade" id="update-background-photo">
            <div class="modal-dialog ui-block window-popup choose-from-my-photo">
                <a href="#" class="close icon-close" data-dismiss="modal">
                    <span><i class="fa fa-times"></i></span>
                </a>

                <div class="ui-block-title">
                    <h6 class="title">@lang('profile.update_cover_photo')</h6>
                </div>
                <div class="ui-block-content">
                    <div class="tab-content">
                        {!! Form::open(['route' => 'survey.survey.update-background', 'method' => 'post']) !!}
                            <div class="tab-pane active" id="home" role="tabpanel" aria-expanded="true">
                                <div class="choose-photo-item choose-photo-cover" data-mh="choose-item">
                                    <div class="radio-image">
                                        <label class="custom-radio">
                                            {!! Html::image(asset(config('settings.choose-cover-profile.default')), '') !!}
                                            {!! Form::radio('background_cover', config('settings.cover-profile.default')) !!}
                                            <span class="circle"></span>
                                            <span class="check"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="choose-photo-item choose-photo-cover" data-mh="choose-item">
                                    <div class="radio-image">
                                        <label class="custom-radio">
                                            {!! Html::image(asset(config('settings.choose-cover-profile.1')), '') !!}
                                            {!! Form::radio('background_cover', config('settings.cover-profile.1')) !!}
                                            <span class="circle"></span>
                                            <span class="check"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="choose-photo-item choose-photo-cover" data-mh="choose-item">
                                    <div class="radio-image">
                                        <label class="custom-radio">
                                            {!! Html::image(asset(config('settings.choose-cover-profile.2')), '') !!}
                                            {!! Form::radio('background_cover', config('settings.cover-profile.2')) !!}
                                            <span class="circle"></span>
                                            <span class="check"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="choose-photo-item choose-photo-cover" data-mh="choose-item">
                                    <div class="radio-image">
                                        <label class="custom-radio">
                                            {!! Html::image(asset(config('settings.choose-cover-profile.3')), '') !!}
                                            {!! Form::radio('background_cover', config('settings.cover-profile.3')) !!}
                                            <span class="circle"></span>
                                            <span class="check"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="choose-photo-item choose-photo-cover" data-mh="choose-item">
                                    <div class="radio-image">
                                        <label class="custom-radio">
                                            {!! Html::image(asset(config('settings.choose-cover-profile.4')), '') !!}
                                            {!! Form::radio('background_cover', config('settings.cover-profile.4')) !!}
                                            <span class="circle"></span>
                                            <span class="check"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="choose-photo-item choose-photo-cover" data-mh="choose-item">
                                    <div class="radio-image">
                                        <label class="custom-radio">
                                            {!! Html::image(asset(config('settings.choose-cover-profile.5')), '') !!}
                                            {!! Form::radio('background_cover', config('settings.cover-profile.5')) !!}
                                            <span class="circle"></span>
                                            <span class="check"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="choose-photo-item choose-photo-cover" data-mh="choose-item">
                                    <div class="radio-image">
                                        <label class="custom-radio">
                                            {!! Html::image(asset(config('settings.choose-cover-profile.6')), '') !!}
                                            {!! Form::radio('background_cover', config('settings.cover-profile.6')) !!}
                                            <span class="circle"></span>
                                            <span class="check"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="choose-photo-item choose-photo-cover" data-mh="choose-item">
                                    <div class="radio-image">
                                        <label class="custom-radio">
                                            {!! Html::image(asset(config('settings.choose-cover-profile.7')), '') !!}
                                            {!! Form::radio('background_cover', config('settings.cover-profile.7')) !!}
                                            <span class="circle"></span>
                                            <span class="check"></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="choose-photo-item choose-photo-cover" data-mh="choose-item">
                                    <div class="radio-image">
                                        <label class="custom-radio">
                                            {!! Html::image(asset(config('settings.choose-cover-profile.8')), '') !!}
                                            {!! Form::radio('background_cover', config('settings.cover-profile.8')) !!}
                                            <span class="circle"></span>
                                            <span class="check"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="btn-update-cover-profile">
                                {!! Form::button(trans('profile.cancel'), ['class' => 'btn btn-cancel btn-lg-profile btn-half-width-cover',
                                    'data-dismiss' => 'modal']) !!}
                                {!! Form::submit(trans('profile.change'), ['class' => 'btn btn-confirm btn-lg-profile btn-half-width-cover', 'disabled']) !!}
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    {!! Html::script(asset(config('settings.plugins') . 'popper/popper.min.js')) !!}
    {!! Html::script(asset(config('settings.plugins') . 'bootstrap/dist/js/bootstrap.min.js')) !!}
    {!! Html::script(asset(config('settings.plugins') . 'datatables/js/jquery.dataTables.js')) !!}
    {!! Html::script(elixir(config('settings.public_template') . 'js/datatables-script.js')) !!}
    {!! Html::script(elixir(config('settings.public_template') . 'js/manage-invite.js')) !!}
@endpush

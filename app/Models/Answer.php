<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Answer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'content',
        'question_id',
        'update',
    ];

    protected $dates = ['deleted_at'];

    protected $appends = [
        'trim_content',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class)->withTrashed();
    }

    public function results()
    {
        return $this->hasMany(Result::class)->withTrashed();
    }

    public function settings()
    {
        return $this->morphMany(Setting::class, 'settingable')->withTrashed();
    }

    public function withTrashedSettings()
    {
        return $this->morphMany(Setting::class, 'settingable')->withTrashed();
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'mediable')->withTrashed();
    }

    public function withTrashedMedia()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function getTrimContentAttribute()
    {
        return (mb_strlen($this->attributes['content']) > 50) ? mb_substr($this->attributes['content'], 0, 50) . '...' : $this->attributes['content'];
    }

    public function getTypeAttribute()
    {
        return $this->settings->first()->key;
    }

    public function getUrlMediaAttribute()
    {
        return $this->media()->withTrashed()->first()->url;
    }

    public function redirectSections()
    {
        return $this->hasMany(Section::class, 'redirect_id', 'id')->withTrashed()->orderBy('order');
    }
}

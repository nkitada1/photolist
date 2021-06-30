<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Comment;

class Photo extends Model
{
    protected $keyType = 'string';

    protected $appends = [
        'url', 'likes_count', 'liked_by_user'
    ];

    protected $hidden = [
        'user_id', 'filename',
        self::CREATED_AT, self::UPDATED_AT,
    ];

    protected $visible = [
        'id', 'owner', 'url', 'comments',
        'likes_count', 'liked_by_user'
    ];

    protected $perPage = 6;

    const ID_LENGTH = 12;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (! Arr::get($this->attributes, 'id')) {
            $this->setId();
        }
    }

    /**
     * ランダムなID値をid属性に代入する
     * @throws \Exception
     */
    private function setId()
    {
        $this->attributes['id'] = $this->getRandomId();
    }

    /**
     * ランダムなID値を生成する
     * @throws \Exception
     */
    private function getRandomId()
    {
        $characters = array_merge(
            range(0, 9), range('a', 'z'),
            range('A', 'Z'), ['-', '_']
        );

        $length = count($characters);

        $id = "";

        for ($i = 0; $i < self::ID_LENGTH; $i++) {
            $id .= $characters[random_int(0, $length - 1)];
        }

        return $id;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id', 'id', 'users');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->orderBy('id', 'desc');
    }

    public function getUrlAttribute()
    {
        return Storage::cloud()->url($this->attributes['filename']);
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'likes')->withTimestamps();
    }

    public function getLikesCountAttribute()
    {
        return $this->likes->count();
    }

    public function getLikedByUserAttribute()
    {
        if (Auth::guest()) {
            return false;
        }

        return $this->likes->contains(fn($user) => $user->id === Auth::user()->id);
    }
}

<?php

namespace App\Src\Domain\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function createPasswordRecoveryToken()
    {
        // Create a new token for the user.
        $key = config('app.key');

        if (Str::startsWith($key, 'base64:')) {
            $key = base64_decode(substr($key, 7));
        }

        $token = hash_hmac('sha256', Str::random(40), $key);
        //

        $created = DB::table('password_resets')->updateOrInsert(
            ['email' => $this->email],
            [
                'email' => $this->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]
        );
        return $created ? $token : false;
    }

    public function newPasswordByResetToken($token, $password)
    {
        $query = DB::table('password_resets')->where(compact('token'));
        $record = $query->first();

        if (!$record) {
            return false;
        }

        //check if expired, default expiry, 1 hour
        if (Carbon::parse($record->created_at)->addSeconds(config('auth.passwords.users.expire') * 60)->isPast()) {
            return false;
        }

        $user = self::where('email', $record->email)->first();

        $query->delete();

        $user->setPassword($password);

        return $user;
    }

    public function setPassword($password)
    {
        $this->password = Hash::make($password);
        return $this->save();
    }

    public function getMe($id, $role)
    {
        return self::find($id);
    }

    public function full_name()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}

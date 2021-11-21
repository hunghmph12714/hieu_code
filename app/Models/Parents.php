<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Parents extends Model
{
    use HasApiTokens, Notifiable;

    protected $guarded = ['id'];    
    public $table = 'parents';
    protected $fillable = ['id','fullname','relationship_id','phone','email','note','alt_fullname','alt_phone','alt_email','sent_otp_at'];
    public function students(){
        return $this->hasMany('App\Models\Student', 'parent_id');
    }
    protected $hidden = [
        'password', 'remember_token',
    ];    
    public function getAuthPassword(){
        return $this->password;
    }
    public function AauthAcessToken(){
        return $this->hasMany('\App\Models\OauthAccessToken');
    }
}

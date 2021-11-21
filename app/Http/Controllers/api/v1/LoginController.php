<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Parents;
use Twilio\Rest\Client;
use Auth;
use Hash;
use Illuminate\Support\Facades\Http;

class LoginController extends Controller
{
    //
    protected function checkAuth()
    {
        if (Auth::check()) {
            return response()->json(Auth::user());
        } else {
            return false;
        }
    }
    protected function login(Request $request)
    {
        $rules = [
            'phone' => 'required|string',
            'password' => 'required|string',
        ];
        $this->validate($request, $rules);

        if (!Auth::attempt($request->toArray())) {
            return response(['message' => 'Invalid login credential']);
        }

        $accessToken = Auth::user()->createToken('authToken')->accessToken;

        return response(['user' => Auth::user(), 'access_token' => $accessToken]);
    }
    protected function checkCooldown(Request $request)
    {
        $rules = ['phone' => 'required', 'sent_at' => 'required'];
        $this->validate($request, $rules);

        $parent = Parents::where('phone', $request->phone)->first();
        if ($parent) {
            if ($parent->sent_otp_at) {
                $cooldown = strtotime($request->sent_at) - strtotime($parent->sent_otp_at);
                if ($cooldown < 150) {
                    return response()->json($cooldown);
                }
            }
        }
        return response()->json(false);
    }
    protected function verifyPhone(Request $request)
    {
        $rules = [
            'phone' => 'required',
            'captcha' => 'required',
            'sent_at' => 'required'
        ];
        $this->validate($request, $rules);

        //Verify Recapcha
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            // 'secret' => '6LcszOAZAAAAALDaxBlRH5azh1xJRRBn_NkkL4zq',
            'secret' => '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe',
            'response' => $request->captcha,
        ]);
        if (!$response['success']) return response()->json('Mã Captcha không hợp lệ', 403);
        //Check phone number
        $parent = Parents::where('phone', $request->phone)->first();
        if ($parent) {
            //Send OTP + lưu lại thời gian gửi otp
            $sent_at = strtotime($request->sent_at);
            //Check thời gian cooldown
            if ($parent->sent_otp_at) {
                if ($sent_at - strtotime($parent->sent_otp_at) < 150) {
                    return response()->json('Mã OTP chưa hết hiệu lực', 413);
                }
            }
            //Gửi otp            
            $parent->sent_otp_at = date('Y-m-d h:i:s', $sent_at);
            $parent->save();
        } else {
            return response()->json('Số điện thoại không có trong hệ thống', 403);
        }
    }
    public function verifyOtp(Request $request)
    {
        $rules = ['otp' => 'required', 'phone' => 'required'];
        $this->validate($request, $rules);

        //OTP verified
        $user = Parents::where('phone', $request->phone)->first();
        if (!$user->password) {
            $user->password = Hash::make($request->otp);
            $user->save();
        }
        if (!Auth::attempt(['phone' => $request->phone, 'password' => $request->otp])) {
            return response(['message' => 'Invalid login credential']);
        }
        $accessToken = Auth::user()->createToken('authToken')->accessToken;
        return response(['user' => Auth::user(), 'access_token' => $accessToken]);
    }
    public function logoutApi()
    {
        if (Auth::check()) {
            Auth::user()->AauthAcessToken()->delete();
        }
    }
    public function testverifyPhone(Request $request)
    {
        /* Get credentials from .env */
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_sid = getenv("TWILIO_SID");
        $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
        $twilio = new Client($twilio_sid, $token);
        $twilio->verify->v2->services($twilio_verify_sid)
            ->verifications
            ->create('+84918186988', "sms", ["locale" => "vi"]);
    }
}

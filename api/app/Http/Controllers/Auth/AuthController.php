<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Mail\Auth\EmailVerification;
use App\Mail\Auth\PasswordResetLinkRequested;
use App\Models\User;
use App\Repositories\User\UserRepository;
use App\Services\Jobs\MailService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected UserRepository $userRepo;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }
    
    public function verifyEmail(Request $r):? JsonResponse
    {
        sleep(1);
        $userId = decodeId($r->token);
        
        $user = User::findOrFail($userId);
        if ($user->email_verified_at) {
            abort(422);
        }
        $user->email_verified_at = now();
        $user->save();
        
        return response()->json('success');
    }
    

    public function logout(): JsonResponse
    {
        auth()->user()->token()->delete();

        return response()->json('Success');
    }

    public function register(UserRequest $r): JsonResponse
    {
        $userData = $r->getUserDataForRegistration();

        $user = $this->userRepo->createNewUser($userData);
    
        $mailService = new MailService();
        $mailService->sendEmail(
            $user->email,
            new EmailVerification(
                encodeId($user->id),
                $user->login
            )
        );

        return $this->authUser($user, $userData['password']);
    }

    public function passwordForgot(Request $r): JsonResponse
    {
        $email = $r->validate(
            ['email' => 'email|required|exists:users,email|max:255'],
            ['email.exists' => trans('passwords.user')]
        )['email'];

        $resetPasswordData = [
            'email' => $email,
            'token' => sha1(time()),
            'created_at' => now()
        ];

        \DB::table('password_resets')->where('email', $email)->delete();

        \DB::table('password_resets')->insert($resetPasswordData);

        \Mail::to($email)->send(new PasswordResetLinkRequested($resetPasswordData));

        return response()->json(['message' => trans('passwords.sent')]);
    }

    public function passwordReset(Request $r): JsonResponse
    {
        $input = $r->validate([
            'token' => 'string',
            'password' => 'required|min:6|max:255',
            'password_confirmation' => 'same:password'
        ]);

        try {
            $resetData = \DB::table('password_resets')->where('token', $input['token'])->first();
            $user = User::whereEmail($resetData->email)->first();

            $user->update([
                'password' => bcrypt($input['password'])
            ]);

            \DB::table('password_resets')->where('token', $input['token'])->delete();

            return $this->authUser($user, $input['password']);
        } catch (\Exception $e) {
            return $this->sendErrorMessage(trans('common.something_went_wrong'));
        }
    }
}

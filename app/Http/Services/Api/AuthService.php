<?php

namespace app\Http\Services\Api;

use Carbon\Carbon;
use App\Models\User;
use App\Enums\HttpStatusCode;
use App\Enums\UserRoles;
use App\Mail\PasswordResetLink;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\TokenResource;
use app\Http\Services\Api\BaseApiService;

class AuthService extends BaseApiService
{
    /**
     * Register a new user
     *
     * @param array $data
     * @return array
     */
    public function register(array $data): array
    {
        DB::transaction(function () use($data) {
            $this->data['user']   = User::create($data);
        });

        $this->data['user']->refresh();

        return sendSuccessInternalResponse('User registered successfully', [
            'user'  => new UserResource($this->data['user']),
            'token' => $this->createToken($this->data['user']),
        ]);
    }

    /**
     * Login user
     *
     * @param array $data
     * @return array
     */
    public function loginToAdminPanel(array $data): array
    {
        $field = filter_var($data['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        if (Auth::attemptWhen([$field => $data['login'], 'password' => $data['password']], function(User $user) {
            return $user->isAdmin() || $user->isWriter();
        })) {
            $user  = Auth::user();
            $token = $this->createToken($user);

            return sendSuccessInternalResponse('User logged in successfully', [
                'user'  => new UserResource($user),
                'token' => $token,
            ]);
        }

        return sendFailInternalResponse('Login failed, please check your credentials');
    }

    /**
     * Login user
     *
     * @param array $data
     * @return array
     */
    public function login(array $data): array
    {
        $field = filter_var($data['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        if (Auth::attempt([$field => $data['login'], 'password' => $data['password']] )) {
            $user  = Auth::user();
            $token = $this->createToken($user);

            return sendSuccessInternalResponse('User logged in successfully', [
                'user'  => new UserResource($user),
                'token' => $token,
            ]);
        }

        return sendFailInternalResponse('Login failed, please check your credentials');
    }

    public function sendResetPasswordLink(array $data): array
    {
        $user = User::where('email', $data['email'])->first();

        if(! $user) {
            return sendFailInternalResponse('User not found');
        }

        $token = $this->createVerificationToken();

        DB::table('password_reset_tokens')->updateOrInsert(
            [
                'email' => $user->email,
            ],
            [
                'token'         => Hash::make($token),
                'created_at'    => now(),
            ]
        );

        Mail::to($user->email)->send(new PasswordResetLink($token, $user->email));

        return sendSuccessInternalResponse('Password reset link sent successfully');
    }

    public function resetPassword(array $data): array
    {
        $record = DB::table('password_reset_tokens')->where('email', $data['email'])->first();

        if(! $record || ! Hash::check($data['token'], $record->token)) {
            return sendFailInternalResponse('Password reset link invalid');
        }

        if(Carbon::parse($record->created_at)->addMinutes(30)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $data['email'])->delete();
            return sendFailInternalResponse('password_reset_link_expired', code: HttpStatusCode::EXPIRED->value);
        }

        User::where('email', $data['email'])->update(['password' => bcrypt($data['password'])]);

        DB::table('password_reset_tokens')->where('email', $data['email'])->delete();

        return sendSuccessInternalResponse('Password reset successfully');
    }

    /**
     * Logout user
     *
     * @param User $user
     * @return array
     */
    public function logout(User $user): array
    {
        $user->tokens()->delete();

        return sendSuccessInternalResponse('Logged out successfully');
    }

    /**
     * Create Api token
     *
     * @param User $user
     * @return TokenResource
     */
    private function createToken(User $user): TokenResource
    {
        $exiprationTime = Carbon::now()->addMinutes((int) config('sanctum.expiration'));
        $token          = $user->createToken($user->email, ['*'], $exiprationTime);

        return new TokenResource($token);
    }

    /**
     * Create verification token
     *
     * @return string
     */
    private function createVerificationToken(): string
    {
        return bin2hex(random_bytes(16));
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends BaseController
{
    const DEFAULT_MESSAGE_ERROR = 'User Error';
    const DEFAULT_MESSAGE_SUCCESS = 'User Success';

    protected $model = User::class;

    public function __construct()
    {
        $this->errorMessages = config('errors.user');
        $this->successMessages = config('successes.user');
    }

    public function login(UserRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = User::getUser($data[User::FIELD_EMAIL]);

        if (!$user) {
            return $this->error($this->getErrorMessage('email_not_found'), 404);
        }

        $isHashCorrect = $user->checkPassword($data[User::FIELD_PASSWORD]);

        if (!$isHashCorrect) {
            return $this->error($this->getErrorMessage('wrong_credentials'), 401);
        }

        try {
            $user->deleteToken();
            $token = $user->getNewToken();

            return $this->success([
                'message' => $this->getSuccessMessage('authorized'),
                'token' => $token,
            ]);
        } catch(\Exception $e) {
            Log::channel('auth')->error($e->getMessage());

            return $this->error($this->getErrorMessage('another_auth_trouble'), 500);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success($this->getSuccessMessage('logouted'));
    }
}

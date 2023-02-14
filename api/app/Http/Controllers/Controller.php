<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\Content\PostImageService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Routing\Controller as BaseController;
use Laravel\Passport\Client;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function sendErrorMessage(string $message): JsonResponse
    {
        return response()->json([
            'errors' => [
                'error_message' => $message
            ]
        ], 422);
    }

    protected function sendPaginationResponse(LengthAwarePaginator $paginator): JsonResponse
    {
        return response()->json([
            'data' => $paginator->items(),
            'meta' => [
                'is_last' => !$paginator->hasMorePages()
            ],
        ]);
    }

    protected function response($data, int $code = 200, string $status = 'success'): JsonResponse
    {
        return response()->json([
            'data' => $data,
            'code' => $code,
            'status' => $status
        ]);
    }

    protected function authUser(User $user, string $password): JsonResponse
    {
        $client = Client::find(2);

        $response = \Http::asForm()->post(env('APP_URL') . '/api/token', [
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'username' => $user->email,
            'password' => $password,
            'scope' => null,
        ]);

        $response = $response->object();

        return response()->json([
            'token' => $response,
            'user' => [
                'id' => $user->id,
                'login' => $user->login,
                'email' => $user->email
            ]
        ]);
    }

    public function test(): void
    {
        dd(config('cors'));
    }
}

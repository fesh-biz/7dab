<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
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
           'access_token' => $response->access_token,
           'user' => [
               'id' => $user->id,
               'name' => $user->name,
               'email' => $user->email
           ]
       ]);
   }
}

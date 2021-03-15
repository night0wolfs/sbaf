<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\SocialAccountService;
use http\Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialServiceController extends Controller
{
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @param $provider
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToProvider($provider): \Symfony\Component\HttpFoundation\RedirectResponse
    {

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @param SocialAccountService $accountService
     * @param $provider
     * @return Application|RedirectResponse|Redirector
     */
    public function handleProviderCallback(SocialAccountService $accountService, $provider): Redirector|Application|RedirectResponse
    {

        try {
            $result = Socialite::with($provider)->user();
        } catch (Exception $e) {
            return redirect('/login');
        }
        switch ($provider) {
            case 'eveOnline':
                if (Auth::check()) {
                    $auth_user_id = Auth::user()->id;
                    $user = User::find($auth_user_id);
                    $characterData = [
                        'character_id' => $result->user['CharacterID'],
                        'character_name' => $result->user['CharacterName'],
                        'access_token' => $result->accessTokenResponseBody['access_token'],
                        'refresh_token' => $result->accessTokenResponseBody['refresh_token']
                    ];
//                    dd($user->characters()->where('character_id', $result->user['CharacterID'])->exists());
                    if ($user->characters()->where('character_id', $result->user['CharacterID'])->exists()) {
                        $user->characters()->where('character_id', $result->user['CharacterID'])->update($characterData);
                    } else {
                        $user->characters()->create($characterData);
                    }
                }
                break;
            case 'discord':
                if (Auth::check()) {
                    $auth_user_id = Auth::user()->id;
                    $user = User::find($auth_user_id);
                    $discordData = [
                        'discord_id' => $result->id,
                        'discord_user_name' => $result->user['username'],
                        'access_token' => $result->accessTokenResponseBody['access_token'],
                        'refresh_token' => $result->accessTokenResponseBody['refresh_token'],
                        'discriminator' => $result->user['discriminator'],
                        'avatar' => $result->avatar,
                        'verified' => $result->user['verified']
                    ];
                    if (!$user->discord()->where('discord_id', $discordData['discord_id'])->exists()()) {
                        $user->discord()->create($discordData);
                    }
                }
                break;
            //TODO: Add redirect to profile user
        }

        dd('dddddddddddddddddd');
        $authUser = $accountService->findOrCreate(
            $result,
            $provider
        );

        auth()->login($authUser, true);

        return redirect()->to('/home');


    }
}

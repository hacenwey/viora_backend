<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('message', function () {
    return true;
});

Broadcast::channel('tenant.{tenant}.chat.{chat}', function ($user, $tenant, App\Models\Tenant\Chat $chat) {
    if ($tenant !== tenant('id')) return false;
    if ($chat->participants->contains($user)) {
        return $user;
    }
});

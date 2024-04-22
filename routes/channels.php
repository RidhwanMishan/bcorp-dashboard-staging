<?php

use Illuminate\Support\Facades\Broadcast;

/*
//bepa 2020-08-20
$proxy_url = getenv('PROXY_URL');
$proxy_schema = getenv('PROXY_SCHEMA');
if (!empty($proxy_url)) {
    URL::forceRootUrl($proxy_url);
}
if (!empty($proxy_schema)) {
    URL::forceScheme($proxy_schema);
}
*/

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

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

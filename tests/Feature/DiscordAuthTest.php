<?php

use App\Models\User;
use Illuminate\Support\Collection;
use Laravel\Socialite\Contracts\Factory as Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use MartinBean\Laravel\Socialite\DiscordProvider;
use function Pest\Laravel\get;

it('creates a new user and logs them in', function () {

    $object = file_get_contents(base_path('tests/ApiResponses/UserIdFromDiscordSnowflake.json'));
    Http::fake(['v1.api.tycoon.community/main/snowflake2user/*' => Http::response($object)]);

    get('/auth/callback')
        ->assertRedirect();

    expect(User::firstWhere('discord_snowflake', 324060102770556933))->toBeInstanceOf(User::class)
        ->and(Auth::check())->toBeTrue()
        ->and(Auth::user()->tt_id)->toBe(645753)
        ->and(Auth::user()->full_trailer_alerts)->toBeInstanceOf(Collection::class)
        ->and(Auth::user()->full_trailer_alerts->count())->toBe(8)
        ->and(Auth::user()->hidden_exportable_items)->toBeInstanceOf(Collection::class)
        ->and(Auth::user()->custom_combined_storage)->toBeInstanceOf(Collection::class);

});

it('logs in an existing user', function () {

    Http::preventStrayRequests();

    User::create([
        'discord_snowflake' => 324060102770556933,
        'name' => 'name'
    ]);

    get('/auth/callback')
        ->assertRedirect();

    expect(User::count())->toBe(1)
        ->and(Auth::check())->toBeTrue();

});


beforeEach(function () {

    $socialiteUser                 = mock(SocialiteUser::class)->expect(
        getId: fn () => 324060102770556933,
        getName: fn () => 'xxdalexx',
    );
    $socialiteUser->id             = 324060102770556933;
    $socialiteUser->nickname       = null;
    $socialiteUser->email          = 'xthedalex@gmail.com';
    $socialiteUser->avatar         = 'https://cdn.discordapp.com/avatars/324060102770556933/a_d3d4e253a2d4340cb17a0d138b9d8abd.png';
    $socialiteUser->token          = "r7LKBZBo39rIbLIjKYHxJBHscZ7M3j";
    $socialiteUser->refreshToken   = "IKLBRim1RYZ2PihB7uP0JkedN8a63I";
    $socialiteUser->expiresIn      = 604800;
    $socialiteUser->approvedScopes = [
        0 => "email",
        1 => "identify",
        2 => "guilds"
    ];
    $socialiteUser->user           = [
        "id"                => "324060102770556933",
        "username"          => "xxdalexx",
        "avatar"            => "a_d3d4e253a2d4340cb17a0d138b9d8abd",
        "avatar_decoration" => null,
        "discriminator"     => "9783",
        "public_flags"      => 0,
        "flags"             => 0,
        "banner"            => null,
        "banner_color"      => null,
        "accent_color"      => null,
        "locale"            => "en-US",
        "mfa_enabled"       => true,
        "premium_type"      => 2,
        "email"             => "xthedalex@gmail.com",
        "verified"          => true,
    ];

    $provider = $this->createMock(DiscordProvider::class);
    $provider->expects($this->any())
        ->method('user')
        ->willReturn($socialiteUser);

    $stub = $this->createMock(Socialite::class);
    $stub->expects($this->any())
        ->method('driver')
        ->willReturn($provider);

    // Replace Socialite Instance with our mock
    $this->app->instance(Socialite::class, $stub);

});

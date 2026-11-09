<?php

use App\Models\Nurses;
use App\Notifications\NursePasswordResetNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;

function createNurseForPasswordReset(): Nurses
{
    return Nurses::create([
        'full_name' => 'Taylor Nurse',
        'email' => 'taylor.nurse@example.test',
        'password' => Hash::make('temporary-password'),
        'status' => 'Active',
    ]);
}

test('nurses can request a password setup email without exposing a password', function () {
    Notification::fake();
    $nurse = createNurseForPasswordReset();

    $this->get(route('nurse.password.request'))->assertOk();
    $response = $this->post(route('nurse.password.email'), ['email' => $nurse->email]);

    $response->assertRedirect()->assertSessionHas('success');
    Notification::assertSentTo($nurse, NursePasswordResetNotification::class, function ($notification) use ($nurse) {
        $mail = $notification->toMail($nurse);

        return in_array('Login email: '.$nurse->email, $mail->introLines, true)
            && str_contains(implode(' ', $mail->introLines), 'secure link');
    });
});

test('nurses can set a password with a valid one-time link', function () {
    $nurse = createNurseForPasswordReset();
    $token = Password::broker('nurses')->createToken($nurse);

    $this->get(route('nurse.password.reset', ['token' => $token, 'email' => $nurse->email]))->assertOk();
    $this->post(route('nurse.password.reset.submit'), [
        'token' => $token,
        'email' => $nurse->email,
        'password' => 'new-secure-password',
        'password_confirmation' => 'new-secure-password',
    ])->assertRedirect(route('nurse.login'));

    expect(Hash::check('new-secure-password', $nurse->fresh()->password))->toBeTrue();
});

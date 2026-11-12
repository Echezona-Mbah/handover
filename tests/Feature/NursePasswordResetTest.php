<?php

use App\Models\Nurses;
use App\Models\Hospital;
use App\Models\User;
use App\Notifications\AdminPasswordResetNotification;
use App\Notifications\NursePasswordResetNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;

function createNurseForPasswordReset(): Nurses
{
    $hospital = Hospital::create(['name' => 'Test Hospital']);

    return Nurses::create([
        'full_name' => 'Taylor Nurse',
        'email' => 'taylor.nurse@example.test',
        'password' => Hash::make('temporary-password'),
        'status' => 'Active',
        'hospital_id' => $hospital->id,
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
            && str_contains(implode(' ', $mail->introLines), 'expires in 2 days')
            && config('auth.passwords.nurses.expire') === 2880;
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

test('hospital admins can resend a nurse password setup link without changing the password', function () {
    Notification::fake();
    $nurse = createNurseForPasswordReset();
    $originalPassword = $nurse->password;
    $admin = User::factory()->create(['hospital_id' => $nurse->hospital_id]);

    $this->actingAs($admin)
        ->post(route('nurses.password-setup-link', $nurse->id))
        ->assertRedirect()
        ->assertSessionHas('success');

    Notification::assertSentTo($nurse, NursePasswordResetNotification::class);
    expect($nurse->fresh()->password)->toBe($originalPassword);
});

test('admins cannot resend password links for nurses in another hospital', function () {
    Notification::fake();
    $nurse = createNurseForPasswordReset();
    $otherHospital = Hospital::create(['name' => 'Other Hospital']);
    $nurse->update(['hospital_id' => $otherHospital->id]);
    $admin = User::factory()->create(['hospital_id' => Hospital::create(['name' => 'Admin Hospital'])->id]);

    $this->actingAs($admin)
        ->post(route('nurses.password-setup-link', $nurse->id))
        ->assertNotFound();

    Notification::assertNothingSent();
});

test('hospital admins can resend an administrator password setup link', function () {
    Notification::fake();
    $hospital = Hospital::create(['name' => 'Admin Hospital']);
    $admin = User::factory()->create(['hospital_id' => $hospital->id]);
    $manager = User::factory()->create([
        'hospital_id' => $hospital->id,
        'doctor_department_id' => null,
        'nursing_hierarchy_role' => 'unit_head',
    ]);
    $originalPassword = $manager->password;

    $this->actingAs($admin)
        ->post(route('admin_user.password-setup-link', $manager))
        ->assertRedirect()
        ->assertSessionHas('success');

    Notification::assertSentTo($manager, AdminPasswordResetNotification::class, function ($notification) use ($manager) {
        $mail = $notification->toMail($manager);

        return in_array('Login email: '.$manager->email, $mail->introLines, true)
            && str_contains(implode(' ', $mail->introLines), 'expires in 2 days')
            && config('auth.passwords.users.expire') === 2880;
    });
    expect($manager->fresh()->password)->toBe($originalPassword);
});

test('admins cannot resend password links for administrators in another hospital', function () {
    Notification::fake();
    $adminHospital = Hospital::create(['name' => 'Admin Hospital']);
    $otherHospital = Hospital::create(['name' => 'Other Hospital']);
    $admin = User::factory()->create(['hospital_id' => $adminHospital->id]);
    $manager = User::factory()->create(['hospital_id' => $otherHospital->id]);

    $this->actingAs($admin)
        ->post(route('admin_user.password-setup-link', $manager))
        ->assertNotFound();

    Notification::assertNothingSent();
});

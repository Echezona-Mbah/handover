<?php

use App\Models\Nurses;
use App\Notifications\AdminNurseMessage;
use App\Notifications\HospitalActivityNotification;

it('delivers an admin message by email and stores its inbox details', function () {
    $nurse = new Nurses([
        'full_name' => 'Jordan Nurse',
        'email' => 'jordan@example.test',
    ]);
    $notification = new AdminNurseMessage(
        'Shift update',
        'Please review the new roster.',
        'Hospital Admin',
        4,
        true,
    );

    expect($notification->via($nurse))->toBe(['database', 'mail'])
        ->and($notification->toDatabase($nurse))->toBe([
            'title' => 'Shift update',
            'message' => 'Please review the new roster.',
            'sender_name' => 'Hospital Admin',
            'sender_id' => 4,
            'requires_acknowledgement' => true,
        ])
        ->and($notification->toMail($nurse)->subject)->toBe('Admin notification: Shift update');
});

it('includes patient urgency and a profile link in ward activity emails', function () {
    $nurse = new Nurses([
        'full_name' => 'Jordan Nurse',
        'email' => 'jordan@example.test',
    ]);
    $notification = new HospitalActivityNotification(
        'URGENT: New patient added: Jordan Nurse',
        'A new patient, Jordan Nurse, has been added to your ward. Patient urgency: Emergency.',
        'Hospital Admin',
        'https://hospital.example.test/nurse/patients/17',
        true,
        'emergency',
    );

    $mail = $notification->toMail($nurse);

    expect($notification->toDatabase($nurse))->toBe([
        'title' => 'URGENT: New patient added: Jordan Nurse',
        'message' => 'A new patient, Jordan Nurse, has been added to your ward. Patient urgency: Emergency.',
        'sender_name' => 'Hospital Admin',
        'url' => 'https://hospital.example.test/nurse/patients/17',
        'urgent' => true,
        'urgency' => 'emergency',
    ])
        ->and($mail->subject)->toBe('URGENT: New patient added: Jordan Nurse')
        ->and($mail->introLines)->toContain('URGENT: A new patient, Jordan Nurse, has been added to your ward. Patient urgency: Emergency.')
        ->and($mail->actionUrl)->toBe('https://hospital.example.test/nurse/patients/17');
});

it('marks nurse shift reminders with their roster assignment and link', function () {
    $nurse = new Nurses([
        'full_name' => 'Jordan Nurse',
        'email' => 'jordan@example.test',
    ]);
    $notification = new \App\Notifications\NurseShiftReminder(
        17,
        'October roster',
        'Morning',
        'Oct 19, 2026',
        '07:00',
        '15:00',
        'https://hospital.example.test/nurse/rosters/4',
    );

    expect($notification->via($nurse))->toBe(['database', 'mail'])
        ->and($notification->toDatabase($nurse))->toMatchArray([
            'assignment_id' => 17,
            'url' => 'https://hospital.example.test/nurse/rosters/4',
        ])
        ->and($notification->toMail($nurse)->subject)->toBe('Shift reminder: October roster');
});

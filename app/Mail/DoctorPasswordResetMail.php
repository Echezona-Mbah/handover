<?php

namespace App\Mail;

use App\Models\Doctor;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DoctorPasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public Doctor $doctor;
    public string $token;

    public function __construct(Doctor $doctor, string $token)
    {
        $this->doctor = $doctor;
        $this->token  = $token;
    }

    public function build(): self
    {
        $resetUrl = route('doctor.password.reset', $this->token);

        return $this->subject('Reset Your Password')
            ->html("
                <div style='font-family:Arial,sans-serif;max-width:600px;margin:auto;padding:30px;'>
                    <h2 style='color:#333;'>Password Reset Request</h2>
                    <p>Hello Dr. {$this->doctor->full_name},</p>
                    <p>We received a request to reset your password. Click the button below to proceed.</p>
                    <p style='margin:30px 0;'>
                        <a href='{$resetUrl}'
                           style='background:#13C5DD;color:white;padding:14px 28px;border-radius:6px;text-decoration:none;font-weight:bold;'>
                            Reset Password
                        </a>
                    </p>
                    <p style='color:#888;font-size:13px;'>This link expires in <strong>60 minutes</strong>.</p>
                    <p style='color:#888;font-size:13px;'>If you did not request a password reset, please ignore this email.</p>
                    <hr style='border:none;border-top:1px solid #eee;margin:30px 0;'>
                    <p style='color:#aaa;font-size:12px;'>
                        Or copy this link: <a href='{$resetUrl}' style='color:#13C5DD;'>{$resetUrl}</a>
                    </p>
                </div>
            ");
    }
}
<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminManagerCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $manager;
    public string $plainPassword;
    public string $departmentName;

    public function __construct(User $manager, string $plainPassword, string $departmentName)
    {
        $this->manager = $manager;
        $this->plainPassword = $plainPassword;
        $this->departmentName = $departmentName;
    }

    public function build(): self
    {
        $loginUrl = route('login');

        return $this->subject('Your Department Manager Account')
            ->html("
                <div style='font-family:Arial,sans-serif;max-width:600px;margin:auto;padding:30px;'>
                    <h2 style='color:#333;'>Department Manager Account Created</h2>
                    <p>Hello {$this->manager->name},</p>
                    <p>An administrator account has been created for you to manage the
                    <strong>{$this->departmentName}</strong> department.</p>

                    <table style='width:100%;border-collapse:collapse;margin:24px 0;'>
                        <tr>
                            <td style='padding:10px 0;border-bottom:1px solid #eee;color:#888;font-size:13px;'>Login email</td>
                            <td style='padding:10px 0;border-bottom:1px solid #eee;font-weight:bold;'>{$this->manager->email}</td>
                        </tr>
                        <tr>
                            <td style='padding:10px 0;color:#888;font-size:13px;'>Temporary password</td>
                            <td style='padding:10px 0;font-weight:bold;'>{$this->plainPassword}</td>
                        </tr>
                    </table>

                    <p style='margin:30px 0;'>
                        <a href='{$loginUrl}'
                           style='background:#13C5DD;color:white;padding:14px 28px;border-radius:6px;text-decoration:none;font-weight:bold;'>
                            Log In
                        </a>
                    </p>

                    <p style='color:#888;font-size:13px;'>For security, we recommend changing your password after your first login.</p>
                    <hr style='border:none;border-top:1px solid #eee;margin:30px 0;'>
                    <p style='color:#aaa;font-size:12px;'>
                        Or copy this link: <a href='{$loginUrl}' style='color:#13C5DD;'>{$loginUrl}</a>
                    </p>
                </div>
            ");
    }
}
<?php

namespace Tests\Unit;

use App\Support\MailConfigured;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class MailConfiguredTest extends TestCase
{
    public function test_brevo_ready_when_api_key_present(): void
    {
        Config::set('mail.default', 'brevo');
        Config::set('services.brevo.key', 'xkeysib-test');

        $this->assertTrue(MailConfigured::isReady());
    }

    public function test_brevo_not_ready_without_api_key(): void
    {
        Config::set('mail.default', 'brevo');
        Config::set('services.brevo.key', null);

        $this->assertFalse(MailConfigured::isReady());
    }

    public function test_smtp_ready_when_credentials_present(): void
    {
        Config::set('mail.default', 'smtp');
        Config::set('mail.mailers.smtp.username', 'user@gmail.com');
        Config::set('mail.mailers.smtp.password', 'secret');

        $this->assertTrue(MailConfigured::isReady());
    }
}

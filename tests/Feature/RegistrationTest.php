<?php

namespace Tests\Feature;

use App\Mail\PleaseConfirmYourEmail;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

use Illuminate\Foundation\Testing\DatabaseMigrations;

class RegistrationTest extends TestCase
{
    use DatabaseMigrations;
    
    /** @test */
    public function a_confirmation_email_is_sent_upon_registration()
    {
    	Mail::fake();

        event(new Registered(create('App\User')));

        Mail::assertSent(PleaseConfirmYourEmail::class);
    }

	/** @test */
	public function user_can_fully_confirm_their_email_addresses()
	{
		$this->post('/register', [
			'name' => 'JohnDoe',
			'email' => 'johndoe@example.com',
			'password' => 'foobar',
			'password_confirmation' => 'foobar'
		]);

		$user = User::whereName('JohnDoe')->first();

		$this->assertFalse($user->confirmed);
	}
}

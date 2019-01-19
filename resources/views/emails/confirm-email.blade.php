@component('mail::message')
# One Last Step

We just need you to confirm your email address to prove that you're a human. You get it, right? Cool.

@component('mail::button', ['url' => url('/register/confirm?token=' . $user->confirmation_token)])
Confirm Email
@endcomponent

@component('mail::table')
| Laravel       | Table         | Example  |
|:------------- |:-------------:| --------:|
| Col 2 is      | Centered      | $10      |
| Col 3 is      | Right-Aligned | $20      |
| Col 3 is      | Right-Aligned | $20      |
| Col 3 is      | Right-Aligned | $20      |
| Col 3 is      | Right-Aligned | $20      |
@endcomponent

@component('mail::panel')
This is the panel content.
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent

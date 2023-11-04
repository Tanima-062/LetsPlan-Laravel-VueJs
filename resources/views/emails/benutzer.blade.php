@component('mail::message')
    {{-- Greeting --}}
# @lang('Herzlich Willkommen bei Lets Plan')</br>
@lang("Sie wurden von :company eingeladen Lets Plan als :role zu nutzen.",[
        'company' => \App\Models\User::adminNamebyCompany($benutzer->company_id),
        'role' => \App\Models\User::getUserByRole($benutzer->role_id),
    ])
@lang(' Bitte melden Sie sich mit Ihrer E-Mail Adresse und folgendem temporären Passwort an: ')<strong>{{ $password }}</strong><br><br>
@lang('Bitte ändern Sie dieses Passwort aus Sicherheitsgründen in Ihrem Profil, sobald Sie sich das erste Mal angemeldet haben.')<br>

@component('mail::button', ['url' => url('/'), 'color' => 'primary'])
        @lang('Anmelden')
@endcomponent
    {{-- Salutation --}}
@lang('Freundliche Grüsse'),<br>
@lang('Ihr Lets Plan Team')
    
@endcomponent

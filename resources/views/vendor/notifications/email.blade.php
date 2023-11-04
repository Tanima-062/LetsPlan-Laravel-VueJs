@component('mail::message')
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# @lang('Whoops!')
@else
# @lang('Guten Tag!')
@endif
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach

{{-- Action Button --}}
@isset($actionText)
<?php
    switch ($level) {
        case 'success':
        case 'error':
            $color = $level;
            break;
        default:
            $color = 'primary';
    }
?>
@component('mail::button', ['url' => $actionUrl, 'color' => $color])
{{ $actionText }}
@endcomponent
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach

{{-- Salutation --}}
@lang('Freundliche Grüsse'),<br>
@lang('Ihr Lets Plan Team')

{{-- Subcopy --}}
@isset($actionText)
@slot('subcopy')
@lang(
    "Sollte der Zugriff mittels dem \"Passwort zurücksetzen\" Button nicht funktionieren, können Sie folgende URL komplett kopieren, in Ihrem Web Browser einfügen und mit der Enter Taste öffen: ")
 <span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
@endslot
@endisset
@endcomponent

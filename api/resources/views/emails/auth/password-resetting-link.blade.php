@component('mail::message')
@lang('common.hi'), {{ $user->name }}.

@lang('common.forgot_your_password')?

@lang('common.we_received_request_to_reset_password_for_your_account').

@lang('common.to_reset_your_password_click_on_button_bellow'):

@component('mail::button', ['url' => $actionUrl])
Button Text
@endcomponent

@lang('common.or_copy_and_paste_url_into_your_browser'):
<a href="{{ $actionUrl }}">{{ $actionUrl }}</a>
@endcomponent

@extends('layouts.app')
@section('content')
    <div class="description">
        <h3> {{ __('user.profile') }}</h3>
    </div>

    <div class="description actions comment mb4">
        <div class="float-left ml4 mt-2 shadow-outer-1 circle">@gravatar($user->email, 90)</div>
        <h3 class="ml4 float-left"> {{ $user->name }}</h3>
    </div>

    <div class="clear-both"></div>

    <div class="description mt4 new-comment">
        {{ Form::open( ["url" => route('profile.update'), 'method' => 'PUT'] ) }}
        <table class="w-50">
            <tr><td> {{ __('user.name') }}:     </td><td class="w-60">{{ Form::text('name', $user->name, ["class" => "w-100"]) }}</td></tr>
            <tr><td> {{ __('user.email') }}:    </td><td class="w-60">{{ Form::email('email', $user->email, ["class" => "w-100"]) }}</td></tr>
            <tr><td></td></tr>
            <tr><td>{{ __('user.dailyTasksNotification') }}:   </td><td> <input type="checkbox" name="daily_tasks_notification" @if($user->settings->daily_tasks_notification) checked @endif></td></tr>
            <tr><td>{{ __('user.ticketsSignature') }}:         </td><td><textarea name="tickets_signature"> {{ $user->settings->tickets_signature }} </textarea> </td></tr>
            <tr><td><button class="ph4 uppercase">@busy {{ __('ticket.update') }}</button></td></tr>
        </table>
        {{ Form::close() }}
    </div>

    <div class="clear-both mb4"></div>

    <a class="ml5 pointer" onClick="$('#password').toggle('fast')"> @icon(key) {{ __('user.changePassword') }}</a>
    <div id="password" class="comment actions hidden mt3">
        {{ Form::open( ["url" => route('profile.password')] ) }}
        <table class="w-50 ">
            <tr><td>{{ __('user.oldPassword') }}: </td><td class="w-60">{{ Form::password('old', ["class" => "w-100"]) }}</td></tr>
            <tr><td>{{ __('user.newPassword') }}: </td><td class="w-60">{{ Form::password('password', ["class" => "w-100"]) }}</td></tr>
            <tr><td>{{ __('user.confirmPassword') }}: </td><td class="w-60">{{ Form::password('password_confirmation', ["class" => "w-100"]) }}</td></tr>
            <tr><td><button class="ph4 uppercase">  {{ __('user.changePassword') }}</button></td></tr>
        </table>
        {{ Form::close() }}
    </div>
@endsection

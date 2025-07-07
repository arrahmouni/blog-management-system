@extends('emails.master')

@section('content')
    <div style="text-align: left; margin: 0 20px; padding: 40px; background-color:#ffffff; border-radius: 6px">
        <p style="margin: 0; padding: 0">
            Hello 
        </p>
        <p style="margin: 0; padding: 0">
            You have requested to reset your password for {{ config('app.name') }}.
        </p>
        <p style="margin: 0; padding: 0">
            Please click the button below to reset your password:
        </p>
        <a href="{{ $resetUrl }}" class="button">Reset Password</a>
        <p style="margin: 0; padding: 0">
            If you did not request a password reset, please ignore this email.
        </p>
    </div>
@endsection

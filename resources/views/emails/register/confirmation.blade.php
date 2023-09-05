<x-mail::message>
# Introduction
    Dear {{$user->name}} ', silahkan klik link dibawah ini untuk konfirmasi pendaftaran.

<x-mail::button :url="$url">
Konfirmasi
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

@if(file_exists(public_path('logo.png')))
    <img src="{{ asset('logo.png') }}" alt="QBASH Logo" {{ $attributes }}>
@elseif(file_exists(public_path('logo.jpg')))
    <img src="{{ asset('logo.jpg') }}" alt="QBASH Logo" {{ $attributes }}>
@elseif(file_exists(public_path('logo.svg')))
    <img src="{{ asset('logo.svg') }}" alt="QBASH Logo" {{ $attributes }}>
@else
    <span {{ $attributes }}>{{ config('app.name', 'QBASH') }}</span>
@endif

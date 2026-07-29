{{-- resources/views/layouts/frontend.blade.php --}}
{{-- Frontend layout with full-width content, no header/footer wrapper limitations --}}
@extends('layouts.app', ['hideHeader' => $hideHeader ?? false, 'hideFooter' => $hideFooter ?? false])

@section('content')
    {{ $slot }}
@endsection
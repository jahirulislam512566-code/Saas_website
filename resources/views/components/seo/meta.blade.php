{{-- resources/views/components/seo/meta.blade.php --}}
@props([
    'title' => 'AgencyPro - Digital Agency',
    'description' => 'We build digital experiences that drive growth for forward-thinking brands.',
    'keywords' => 'digital agency, web design, development, strategy',
    'robots' => 'index, follow',
    'canonical' => null,
    'language' => 'en',
])

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">
<meta name="keywords" content="{{ $keywords }}">
<meta name="robots" content="{{ $robots }}">
<meta name="language" content="{{ $language }}">

@if($canonical)
    <link rel="canonical" href="{{ $canonical }}">
@endif

<meta name="theme-color" content="#6c5ce7">
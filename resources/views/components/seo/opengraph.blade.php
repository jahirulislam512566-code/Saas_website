{{-- resources/views/components/seo/opengraph.blade.php --}}
@props([
    'title' => 'AgencyPro - Digital Agency',
    'description' => 'We build digital experiences that drive growth for forward-thinking brands.',
    'image' => null,
    'url' => null,
    'type' => 'website',
    'siteName' => 'AgencyPro',
])

@php
    $image = $image ?? asset('images/og-image.jpg');
    $url = $url ?? url('/');
@endphp

<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:image" content="{{ $image }}">
<meta property="og:url" content="{{ $url }}">
<meta property="og:type" content="{{ $type }}">
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:locale" content="{{ app()->getLocale() }}">

@if($type === 'article')
    <meta property="article:published_time" content="{{ $publishedTime ?? now()->toIso8601String() }}">
    <meta property="article:modified_time" content="{{ $modifiedTime ?? now()->toIso8601String() }}">
    @if(isset($author))
        <meta property="article:author" content="{{ $author }}">
    @endif
    @if(isset($tags) && is_array($tags))
        @foreach($tags as $tag)
            <meta property="article:tag" content="{{ $tag }}">
        @endforeach
    @endif
@endif
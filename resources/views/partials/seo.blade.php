@php
    $siteName = 'Dandy Al-Farisi Natanegara';
    $title = $seoTitle ?? $siteName;
    $description = $seoDescription ?? 'Portofolio Dandy Al-Farisi Natanegara, Network Engineer dan IT Enthusiast.';
    $image = $seoImage ?? asset('images/fotoprofil.png');
    $type = $seoType ?? 'website';
    $canonical = $seoCanonical ?? url()->current();
@endphp
<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">
<link rel="canonical" href="{{ $canonical }}">

<meta property="og:locale" content="id_ID">
<meta property="og:type" content="{{ $type }}">
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:image" content="{{ $image }}">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $image }}">

@if (!empty($seoJsonLd))
<script type="application/ld+json">{!! json_encode($seoJsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) !!}</script>
@endif

@extends('layouts.app')

@section('title', 'DhakaFin — Chartered Accountants, Tax, Audit & Advisory')

@section('content')
@php
    $indexHtml = file_exists(base_path('index.html')) ? file_get_contents(base_path('index.html')) : '';
    $mainContent = $indexHtml;
    if (preg_match('/<main\b[^>]*>(.*?)<\/main>/is', $indexHtml, $mm)) {
        $mainContent = $mm[1];
    }
@endphp
{!! $mainContent !!}
@endsection

<?php

namespace App\Support;

final class FeatureManifest
{
    /**
     * Exposes only boolean availability of approved frontend pages, never
     * credentials, user data, legal documents or administrative settings.
     */
    public static function enabledPages(): array
    {
        $pages = [];
        foreach (config('dhakafin.feature_pages', []) as $slug => $flag) {
            if (is_string($slug) && is_string($flag) &&
                config('dhakafin.features.' . $flag, false) === true) {
                $pages[$slug . '.html'] = true;
            }
        }
        return $pages;
    }

    public static function tag(): string
    {
        $payload = json_encode(
            self::enabledPages(),
            JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_THROW_ON_ERROR
        );
        return '<script>window.DhakaFinFeatures=' . $payload . ';</script>';
    }
}

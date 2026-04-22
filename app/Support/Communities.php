<?php

namespace App\Support;

class Communities
{
    /**
     * All communities as [slug => ['label','mono','color','color_dark','topic']].
     */
    public static function all(): array
    {
        return config('communities.list', []);
    }

    /**
     * Slug → label map (for Filament dropdowns and filters).
     *
     * @return array<string,string>
     */
    public static function labels(): array
    {
        return collect(self::all())->map(fn ($c) => $c['label'])->toArray();
    }

    /**
     * Look up a community by slug; fall back to a default so unknown slugs still render.
     *
     * @return array{label:string,mono:string,color:string,color_dark:string,topic:?string}
     */
    public static function get(?string $slug): array
    {
        $all = self::all();
        if ($slug && isset($all[$slug])) {
            return $all[$slug];
        }

        $default = config('communities.default');
        $name = $slug ?: 'Community';
        $mono = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $name) ?: 'MU', 0, 2));

        return array_merge($default, [
            'label' => $name,
            'mono' => $mono,
        ]);
    }
}

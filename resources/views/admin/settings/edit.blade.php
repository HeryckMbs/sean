@extends('layouts.admin')

@section('title', 'Configurações')

@section('content')
    @php
        $assetUrl = function (?string $path): ?string {
            if (! $path) return null;
            if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, '/')) return $path;
            if (str_starts_with($path, 'media/')) return asset('storage/'.$path);
            return asset($path);
        };
    @endphp

    <div class="page-heading">
        <div>
            <span>Site</span>
            <h1>Configurações gerais</h1>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @foreach ($settings as $group => $groupSettings)
            <section class="admin-panel">
                <div class="panel-header">
                    <h2>{{ ucfirst($group) }}</h2>
                </div>

                <div class="form-grid">
                    @foreach ($groupSettings as $setting)
                        @php
                            $type = $definitions[$setting->key]['type'] ?? $setting->type;
                            $value = old($setting->key, $setting->value);
                        @endphp

                        <div class="admin-field {{ in_array($type, ['textarea', 'json', 'image']) ? 'span-2' : '' }}">
                            @if ($type === 'textarea' || $type === 'json')
                                <label for="{{ $setting->key }}">{{ $setting->label }}</label>
                                <textarea id="{{ $setting->key }}" name="{{ $setting->key }}" class="materialize-textarea">{{ $value }}</textarea>
                            @elseif ($type === 'image')
                                <label>{{ $setting->label }}</label>
                                @if ($assetUrl($setting->value))
                                    <img class="image-preview" src="{{ $assetUrl($setting->value) }}" alt="{{ $setting->label }}">
                                @endif
                                <div class="file-field input-field">
                                    <div class="btn btn-secondary">
                                        <span>Selecionar</span>
                                        <input type="file" name="{{ $setting->key }}" accept="image/*">
                                    </div>
                                    <div class="file-path-wrapper">
                                        <input class="file-path validate" type="text">
                                    </div>
                                </div>
                            @else
                                <div class="input-field">
                                    <input id="{{ $setting->key }}" name="{{ $setting->key }}" type="{{ $type === 'email' ? 'email' : 'text' }}" value="{{ $value }}">
                                    <label for="{{ $setting->key }}">{{ $setting->label }}</label>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </section>
        @endforeach

        <div class="form-actions">
            <button class="btn btn-primary" type="submit">Salvar configurações</button>
        </div>
    </form>
@endsection

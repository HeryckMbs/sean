@extends('layouts.admin')

@section('title', ($item->exists ? 'Editar ' : 'Novo ').$config['singular'])

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
            <span>{{ $config['plural'] }}</span>
            <h1>{{ $item->exists ? 'Editar' : 'Novo' }} {{ $config['singular'] }}</h1>
        </div>
        <a class="btn btn-secondary" href="{{ route('admin.content.index', $resource) }}">Voltar</a>
    </div>

    <form method="POST" action="{{ $item->exists ? route('admin.content.update', [$resource, $item->id]) : route('admin.content.store', $resource) }}" enctype="multipart/form-data">
        @csrf
        @if ($item->exists)
            @method('PUT')
        @endif

        <section class="admin-panel">
            <div class="form-grid">
                @foreach ($config['fields'] as $field => $definition)
                    @php
                        $type = $definition['type'];
                        $rawValue = old($field, $item->{$field});
                        $value = is_array($rawValue) ? implode("\n", $rawValue) : $rawValue;
                        $isWide = in_array($type, ['textarea', 'tags', 'image']);
                    @endphp

                    <div class="admin-field {{ $isWide ? 'span-2' : '' }}">
                        @if ($type === 'textarea' || $type === 'tags')
                            <label for="{{ $field }}">{{ $definition['label'] }}</label>
                            <textarea id="{{ $field }}" name="{{ $field }}" class="materialize-textarea">{{ $value }}</textarea>
                        @elseif ($type === 'checkbox')
                            @php
                                $checkedDefault = $field === 'is_active';
                                $checked = old($field, $item->exists ? (bool) $item->{$field} : $checkedDefault);
                            @endphp
                            <label class="check-row">
                                <input type="checkbox" name="{{ $field }}" value="1" @checked($checked)>
                                <span>{{ $definition['label'] }}</span>
                            </label>
                        @elseif ($type === 'image')
                            <label>{{ $definition['label'] }}</label>
                            @if ($assetUrl($item->{$field}))
                                <img class="image-preview" src="{{ $assetUrl($item->{$field}) }}" alt="{{ $definition['label'] }}">
                            @endif
                            <div class="file-field input-field">
                                <div class="btn btn-secondary">
                                    <span>Selecionar</span>
                                    <input type="file" name="{{ $field }}" accept="image/*">
                                </div>
                                <div class="file-path-wrapper">
                                    <input class="file-path validate" type="text">
                                </div>
                            </div>
                        @else
                            <div class="input-field">
                                <input id="{{ $field }}" name="{{ $field }}" type="{{ $type === 'number' ? 'number' : 'text' }}" value="{{ $value }}">
                                <label for="{{ $field }}">{{ $definition['label'] }}</label>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>

        <div class="form-actions">
            <button class="btn btn-primary" type="submit">Salvar</button>
            <a class="btn-flat" href="{{ route('admin.content.index', $resource) }}">Cancelar</a>
        </div>
    </form>
@endsection

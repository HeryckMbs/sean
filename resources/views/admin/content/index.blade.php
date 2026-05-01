@extends('layouts.admin')

@section('title', $config['plural'])

@section('content')
    <div class="page-heading">
        <div>
            <span>Conteúdo</span>
            <h1>{{ $config['plural'] }}</h1>
        </div>
        <a class="btn btn-primary" href="{{ route('admin.content.create', $resource) }}">Novo</a>
    </div>

    <section class="admin-panel">
        <table class="striped responsive-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Ordem</th>
                    <th>Status</th>
                    <th class="right-align">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($items as $item)
                    <tr>
                        <td>{{ $item->{$config['primary']} }}</td>
                        <td>{{ $item->sort_order ?? 0 }}</td>
                        <td>
                            @if (($item->is_active ?? true) === true)
                                <span class="status-pill active">Ativo</span>
                            @else
                                <span class="status-pill inactive">Inativo</span>
                            @endif
                        </td>
                        <td class="right-align table-actions">
                            <a class="btn-flat" href="{{ route('admin.content.edit', [$resource, $item->id]) }}">Editar</a>
                            <form method="POST" action="{{ route('admin.content.destroy', [$resource, $item->id]) }}" onsubmit="return confirm('Remover este item?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn-flat red-text" type="submit">Remover</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4">Nenhum item cadastrado.</td></tr>
                @endforelse
            </tbody>
        </table>

        {{ $items->links() }}
    </section>
@endsection

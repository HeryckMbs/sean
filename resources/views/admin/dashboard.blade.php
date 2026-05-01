@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="page-heading">
        <div>
            <span>Painel</span>
            <h1>Visão geral</h1>
        </div>
        <a class="btn btn-primary" href="{{ route('home') }}" target="_blank" rel="noopener">Ver site</a>
    </div>

    <div class="stats-grid">
        @foreach ($counts as $label => $count)
            <article class="stat-card">
                <span>{{ ucfirst($label) }}</span>
                <strong>{{ $count }}</strong>
            </article>
        @endforeach
    </div>

    <section class="admin-panel">
        <div class="panel-header">
            <h2>Leads recentes</h2>
            <a href="{{ route('admin.leads.index') }}">Ver todos</a>
        </div>
        <table class="striped responsive-table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Empresa</th>
                    <th>E-mail</th>
                    <th>Recebido</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($latestLeads as $lead)
                    <tr>
                        <td><a href="{{ route('admin.leads.show', $lead) }}">{{ $lead->name }}</a></td>
                        <td>{{ $lead->company }}</td>
                        <td>{{ $lead->email }}</td>
                        <td>{{ $lead->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4">Nenhum lead recebido ainda.</td></tr>
                @endforelse
            </tbody>
        </table>
    </section>
@endsection

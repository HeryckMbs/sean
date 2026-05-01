@extends('layouts.admin')

@section('title', 'Leads')

@section('content')
    <div class="page-heading">
        <div>
            <span>Captação</span>
            <h1>Leads recebidos</h1>
        </div>
    </div>

    <section class="admin-panel">
        <table class="striped responsive-table">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Empresa</th>
                    <th>Contato</th>
                    <th>Status</th>
                    <th>Recebido</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($leads as $lead)
                    <tr>
                        <td><a href="{{ route('admin.leads.show', $lead) }}">{{ $lead->name }}</a></td>
                        <td>{{ $lead->company }}</td>
                        <td>{{ $lead->email }}<br>{{ $lead->whatsapp }}</td>
                        <td><span class="status-pill active">{{ $lead->integration_status }}</span></td>
                        <td>{{ $lead->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5">Nenhum lead recebido ainda.</td></tr>
                @endforelse
            </tbody>
        </table>

        {{ $leads->links() }}
    </section>
@endsection

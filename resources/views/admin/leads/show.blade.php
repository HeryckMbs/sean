@extends('layouts.admin')

@section('title', 'Lead')

@section('content')
    <div class="page-heading">
        <div>
            <span>Lead</span>
            <h1>{{ $lead->name }}</h1>
        </div>
        <a class="btn btn-secondary" href="{{ route('admin.leads.index') }}">Voltar</a>
    </div>

    <section class="admin-panel lead-detail">
        <dl>
            <dt>Empresa</dt>
            <dd>{{ $lead->company }}</dd>
            <dt>E-mail</dt>
            <dd><a href="mailto:{{ $lead->email }}">{{ $lead->email }}</a></dd>
            <dt>Telefone</dt>
            <dd>{{ $lead->phone }}</dd>
            <dt>WhatsApp</dt>
            <dd>{{ $lead->whatsapp }}</dd>
            <dt>Mensagem</dt>
            <dd>{{ $lead->message ?: 'Não informada' }}</dd>
            <dt>Serviços de interesse</dt>
            <dd>{{ collect($lead->service_interests)->filter()->implode(', ') ?: 'Não informado' }}</dd>
            <dt>Endpoint preparado</dt>
            <dd>{{ $lead->webhook_endpoint ?: 'Nenhum endpoint configurado' }}</dd>
            <dt>Recebido em</dt>
            <dd>{{ $lead->created_at->format('d/m/Y H:i') }}</dd>
        </dl>

        <form method="POST" action="{{ route('admin.leads.status', $lead) }}" class="status-form">
            @csrf
            @method('PATCH')
            <div class="input-field">
                <input id="integration_status" name="integration_status" type="text" value="{{ old('integration_status', $lead->integration_status) }}">
                <label for="integration_status">Status interno</label>
            </div>
            <button class="btn btn-primary" type="submit">Atualizar status</button>
        </form>

        <form method="POST" action="{{ route('admin.leads.destroy', $lead) }}" onsubmit="return confirm('Remover este lead?')">
            @csrf
            @method('DELETE')
            <button class="btn-flat red-text" type="submit">Remover lead</button>
        </form>
    </section>
@endsection

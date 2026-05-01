<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LeadController extends Controller
{
    public function store(Request $request): RedirectResponse|Response
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'company' => ['required', 'string', 'max:160'],
            'email' => ['required', 'email', 'max:160'],
            'phone' => ['required', 'string', 'max:40'],
            'whatsapp' => ['required', 'string', 'max:40'],
            'message' => ['nullable', 'string', 'max:2000'],
        ]);

        $settings = SiteSetting::values();

        Lead::create($validated + [
            'source' => 'landing',
            'utm_source' => $request->query('utm_source'),
            'utm_medium' => $request->query('utm_medium'),
            'utm_campaign' => $request->query('utm_campaign'),
            'webhook_endpoint' => $settings['lead_webhook_url'] ?? null,
            'integration_status' => empty($settings['lead_webhook_url']) ? 'pending' : 'ready_for_webhook',
            'metadata' => [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'referer' => $request->headers->get('referer'),
            ],
        ]);

        if ($request->expectsJson()) {
            return response(['message' => 'Solicitação recebida. Entraremos em contato em breve.'], 201);
        }

        return redirect('/#contato')->with('lead_success', 'Solicitação recebida. Entraremos em contato em breve.');
    }
}

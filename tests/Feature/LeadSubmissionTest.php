<?php

namespace Tests\Feature;

use App\Models\Lead;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeadSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_form_stores_lead(): void
    {
        $this->seed();

        $response = $this->post('/leads', [
            'name' => 'Maria Silva',
            'company' => 'Silva Consultoria',
            'email' => 'maria@example.com',
            'phone' => '(91) 3333-3333',
            'whatsapp' => '(91) 99999-9999',
            'message' => 'Quero avaliar campanhas.',
        ]);

        $response->assertRedirect('/#contato');

        $this->assertDatabaseHas('leads', [
            'name' => 'Maria Silva',
            'company' => 'Silva Consultoria',
            'email' => 'maria@example.com',
            'integration_status' => 'pending',
        ]);

        $this->assertSame(1, Lead::count());
        $this->assertNull(Lead::first()->service_interests);
    }

    public function test_landing_form_stores_optional_service_interests(): void
    {
        $this->seed();

        $response = $this->post('/leads', [
            'name' => 'João Santos',
            'company' => 'Santos Serviços',
            'email' => 'joao@example.com',
            'phone' => '(91) 3222-2222',
            'whatsapp' => '(91) 98888-8888',
            'message' => 'Quero organizar a captação.',
            'service_interests' => ['Performance', 'SEO', 'Sites e Landing Pages'],
        ]);

        $response->assertRedirect('/#contato');

        $lead = Lead::first();

        $this->assertSame(['Performance', 'SEO', 'Sites e Landing Pages'], $lead->service_interests);
    }
}

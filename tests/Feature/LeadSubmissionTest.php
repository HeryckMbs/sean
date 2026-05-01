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
    }
}

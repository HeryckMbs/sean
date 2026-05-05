<?php

namespace Tests\Feature;

use App\Models\PageSection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandingPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_landing_renders_seeded_content(): void
    {
        $this->seed();

        $this->get('/')
            ->assertOk()
            ->assertSee('Transforme sua presença digital em oportunidades reais de venda')
            ->assertSee('Uma solução 360° para o seu crescimento')
            ->assertSee('steps-row steps-row--summary', false)
            ->assertSee('Quero agendar uma reunião');
    }

    public function test_public_landing_shows_only_summary_records_and_links_to_topic_pages(): void
    {
        $this->seed();

        $this->get('/')
            ->assertOk()
            ->assertSee('Performance')
            ->assertDontSee('/solucoes/automacao-e-crm', false)
            ->assertSee('/solucoes', false)
            ->assertSee('/beneficios', false)
            ->assertSee('/nichos', false)
            ->assertSee('/perguntas-frequentes', false);
    }

    public function test_public_landing_shows_optional_service_interest_choices(): void
    {
        $this->seed();

        $this->get('/')
            ->assertOk()
            ->assertSee('name="service_interests[]"', false)
            ->assertSee('Performance')
            ->assertSee('Inbound Marketing')
            ->assertSee('Tráfego Pago')
            ->assertSee('Consultoria Estratégica')
            ->assertSee('Automação e CRM')
            ->assertSee('SEO')
            ->assertSee('Sites e Landing Pages');
    }

    public function test_public_topic_pages_show_full_registered_content(): void
    {
        $this->seed();

        $this->get('/solucoes')
            ->assertOk()
            ->assertSee('Automação e CRM')
            ->assertSee('Consultoria Estratégica')
            ->assertSee('/solucoes/automacao-e-crm', false);

        $this->get('/perguntas-frequentes')
            ->assertOk()
            ->assertSee('Preciso ter um orçamento alto para começar?');
    }

    public function test_public_service_detail_page_shows_deep_content(): void
    {
        $this->seed();

        $this->get('/solucoes/trafego-pago')
            ->assertOk()
            ->assertSee('Tráfego Pago')
            ->assertDontSee('O que você encontra')
            ->assertDontSee('service-summary-panel', false)
            ->assertDontSee('entregáveis cadastrados')
            ->assertSee('Meta Ads')
            ->assertSee('Aquisição mais rápida');
    }

    public function test_public_landing_does_not_render_hero_media_or_micro_proofs(): void
    {
        $this->seed();

        PageSection::query()
            ->where('key', 'hero')
            ->update(['media_path' => 'media/hero-custom.jpg']);

        PageSection::query()
            ->where('key', 'sobre')
            ->update(['media_path' => 'media/about-custom.jpg']);

        $this->get('/')
            ->assertOk()
            ->assertDontSee('storage/media/hero-custom.jpg')
            ->assertDontSee('storage/media/about-custom.jpg')
            ->assertDontSee('hero-media', false)
            ->assertDontSee('hero-proofs', false)
            ->assertDontSee('metric-strip', false)
            ->assertDontSee('images/hero-performance-meeting.png', false)
            ->assertDontSee('Foco em performance')
            ->assertDontSee('Processos claros')
            ->assertDontSee('Estratégias sob medida')
            ->assertDontSee('Acompanhamento próximo')
            ->assertDontSee('Campanhas, CRM, conversão e acompanhamento em um plano claro.');
    }
}

<?php

namespace Database\Seeders;

use App\Models\AgencyCase;
use App\Models\Benefit;
use App\Models\Faq;
use App\Models\PageSection;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\SocialLink;
use App\Models\TargetAudience;
use App\Models\Testimonial;
use App\Models\User;
use App\Models\WorkStep;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@perfildigitalads.com.br')],
            [
                'name' => env('ADMIN_NAME', 'Administrador'),
                'password' => env('ADMIN_PASSWORD', 'password'),
            ],
        );

        $this->seedSettings();
        $this->seedSections();
        $this->seedServices();
        $this->seedBenefits();
        $this->seedAudiences();
        $this->seedWorkSteps();
        $this->seedCase();
        $this->seedTestimonials();
        $this->seedFaqs();
        $this->seedSocialLinks();
    }

    private function seedSettings(): void
    {
        $settings = [
            ['brand_name', 'geral', 'Nome da marca', 'Perfil Digital Ads', 'text', 1],
            ['logo_path', 'geral', 'Logo', 'images/logo-perfil-digital-ads-nav-transparent.png', 'image', 2],
            ['hero_image', 'geral', 'Imagem principal', 'images/hero-performance-meeting.png', 'image', 3],
            ['whatsapp_number', 'contato', 'Número do WhatsApp', '5591999999999', 'text', 1],
            ['whatsapp_message', 'contato', 'Mensagem do WhatsApp', 'Olá, vim pelo site da Perfil Digital Ads e quero agendar uma reunião estratégica.', 'textarea', 2],
            ['lead_webhook_url', 'contato', 'Endpoint futuro do formulário', null, 'url', 3],
            ['contact_email', 'contato', 'E-mail de contato', 'contato@perfildigitalads.com.br', 'email', 4],
            ['primary_cta_label', 'conversao', 'Texto do CTA principal', 'Quero agendar uma reunião', 'text', 1],
            ['secondary_cta_label', 'conversao', 'Texto do CTA secundário', 'Falar no WhatsApp', 'text', 2],
            ['footer_slogan', 'geral', 'Slogan do rodapé', 'O perfil certo para o seu sucesso digital', 'text', 4],
            ['seo_title', 'seo', 'SEO title', 'Perfil Digital Ads | Agência de marketing e performance para gerar mais clientes', 'text', 1],
            ['seo_description', 'seo', 'SEO description', 'Agende uma reunião estratégica com a Perfil Digital Ads e descubra como gerar mais leads, melhorar sua performance e crescer com previsibilidade.', 'textarea', 2],
            ['menu_links', 'navegacao', 'Links do menu em JSON', [
                ['label' => 'Sobre a Perfil Digital', 'url' => '/#sobre'],
                ['label' => 'Soluções', 'url' => '/solucoes'],
                ['label' => 'Cases', 'url' => '/cases'],
                ['label' => 'Nichos', 'url' => '/nichos'],
                ['label' => 'Contato', 'url' => '/#contato'],
            ], 'json', 1],
        ];

        foreach ($settings as [$key, $group, $label, $value, $type, $order]) {
            SiteSetting::updateOrCreate(
                ['key' => $key],
                [
                    'group' => $group,
                    'label' => $label,
                    'value' => $type === 'json'
                        ? json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
                        : $value,
                    'type' => $type,
                    'sort_order' => $order,
                ],
            );
        }
    }

    private function seedSections(): void
    {
        $sections = [
            ['hero', 'Marketing e vendas com previsibilidade', 'Transforme sua presença digital em oportunidades reais de venda', 'A Perfil Digital Ads ajuda empresas a atrair mais clientes, gerar oportunidades qualificadas e crescer com estratégias digitais orientadas a resultado.', null, 'Quero agendar uma reunião', '#contato', 1],
            ['sobre', 'Sobre a Perfil Digital', 'Sua parceira de crescimento digital', 'Estratégia, execução e acompanhamento para sair do marketing solto e construir um processo comercial mais previsível.', 'Unimos performance, processos claros e decisões com base em dados para transformar investimento em oportunidade real de negócio.', null, null, 2],
            ['solucoes', 'Soluções 360°', 'Uma solução 360° para o seu crescimento', 'Do tráfego pago à automação, cada serviço é pensado para gerar resultado real e escalável.', null, 'Quero melhorar minha performance', '#contato', 3],
            ['beneficios', 'Ganhos concretos', 'O que sua empresa ganha', 'Resultados tangíveis que impactam diretamente o crescimento do seu negócio.', null, null, null, 4],
            ['nichos', 'Para quem é', 'Atendemos diferentes perfis de empresa', 'Se você precisa crescer com marketing digital e mais previsibilidade, a Perfil Digital Ads pode ajudar.', null, null, null, 5],
            ['processo', 'Processo', 'Como trabalhamos', 'Um processo claro, organizado e orientado a resultado em cada etapa.', null, null, null, 6],
            ['cases', 'Case em destaque', 'De zero a +130 contratos por mês com tráfego pago', 'Resultado apresentado de forma concreta, sem inventar números além do informado.', null, 'Quero resultados assim', '#contato', 7],
            ['prova-social', 'Prova social', 'Confiança antes da próxima decisão', 'Depoimentos curtos e logos editáveis para reforçar segurança antes do formulário.', null, null, null, 8],
            ['faq', 'FAQ', 'Perguntas frequentes', 'Respostas diretas para objeções comuns antes do contato.', null, null, null, 9],
            ['contato', 'Reunião estratégica', 'Pronto para transformar sua presença digital em crescimento real?', 'Agende uma reunião estratégica e entenda como a Perfil Digital Ads pode ajudar sua empresa a gerar mais oportunidades, melhorar a performance e crescer com previsibilidade.', null, null, null, 10],
        ];

        foreach ($sections as [$key, $eyebrow, $title, $subtitle, $body, $ctaLabel, $ctaUrl, $order]) {
            PageSection::updateOrCreate(
                ['key' => $key],
                compact('eyebrow', 'title', 'subtitle', 'body') + [
                    'cta_label' => $ctaLabel,
                    'cta_url' => $ctaUrl,
                    'sort_order' => $order,
                    'is_active' => true,
                ],
            );
        }
    }

    private function seedServices(): void
    {
        $services = [
            ['Performance', 'Campanhas orientadas a resultado mensurável. Cada centavo investido é acompanhado, otimizado e ajustado para gerar mais retorno.', ['CRM e gestão de pipeline', 'CRO e melhoria de conversão', 'Mídia paga multiplataforma', 'SEO técnico e estratégico'], ['Mais leads qualificados', 'Melhor custo por aquisição', 'Relatórios claros de ROI'], 'show_chart'],
            ['Inbound Marketing', 'Conteúdo e relacionamento para manter sua marca presente, preparar a venda e gerar demanda com mais consistência.', ['Criação de conteúdo', 'Gestão de mídia', 'Calendário editorial', 'Nutrição de base'], ['Marca mais presente', 'Demanda recorrente', 'Relacionamento com a base'], 'hub'],
            ['Tráfego Pago', 'Estratégias de mídia para atrair o público certo, gerar demanda e acelerar a aquisição de clientes.', ['Meta Ads', 'Google Ads', 'LinkedIn Ads', 'Remarketing inteligente', 'Otimização contínua'], ['Aquisição mais rápida', 'Público mais qualificado', 'Aprendizado constante'], 'ads_click'],
            ['Sites e Landing Pages', 'Páginas pensadas para conversão, com estrutura clara, boa leitura e foco em geração de oportunidades.', ['Landing pages para campanhas', 'Páginas institucionais', 'Ajustes de conversão', 'Estrutura para captação'], ['Mais conversão', 'Leitura clara', 'Campanhas com destino certo'], 'web'],
            ['Automação e CRM', 'Processos mais organizados, menos lead perdido e acompanhamento comercial mais eficiente.', ['Implementação de CRM', 'Automação de marketing', 'Integração de ferramentas', 'Chatbots e fluxos automáticos'], ['Menos perda de leads', 'Pipeline organizado', 'Follow-up consistente'], 'settings_suggest'],
            ['SEO', 'Presença digital mais forte para captar demanda recorrente e ganhar espaço de forma consistente no orgânico.', ['Pesquisa de palavras-chave', 'Conteúdo otimizado', 'SEO local', 'SEO técnico'], ['Demanda orgânica', 'Autoridade digital', 'Crescimento sustentável'], 'travel_explore'],
            ['Consultoria Estratégica', 'Diagnóstico, plano de ação e direcionamento para empresas que precisam entender onde estão perdendo dinheiro e como corrigir.', ['Diagnóstico de marketing', 'Plano de ação', 'Priorização de canais', 'Acompanhamento estratégico'], ['Clareza de decisão', 'Prioridades definidas', 'Menos desperdício'], 'psychology'],
        ];

        foreach ($services as $index => [$name, $description, $deliverables, $benefits, $icon]) {
            Service::updateOrCreate(
                ['slug' => str($name)->slug()->toString()],
                [
                    'name' => $name,
                    'icon' => $icon,
                    'summary' => $description,
                    'description' => $description,
                    'deliverables' => $deliverables,
                    'benefits' => $benefits,
                    'cta_label' => 'Quero melhorar minha performance',
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ],
            );
        }
    }

    private function seedBenefits(): void
    {
        $benefits = [
            ['Mais previsibilidade comercial', 'Tenha clareza sobre canais, investimento e próximos passos.', 'timeline'],
            ['Leads qualificados', 'Atraia pessoas com maior chance de avançar no processo comercial.', 'groups'],
            ['Campanhas com foco em retorno', 'Acompanhe mídia, conversão e eficiência com rotina de otimização.', 'paid'],
            ['Processos organizados', 'Reduza perda de oportunidades entre marketing e vendas.', 'account_tree'],
            ['Crescimento com inteligência', 'Decisões orientadas por dados, não por achismo.', 'insights'],
            ['Melhor ROI em marketing', 'Priorize ações que fazem sentido para o momento da empresa.', 'trending_up'],
            ['Presença digital forte', 'Construa uma marca mais consistente nos canais relevantes.', 'public'],
            ['Dados que guiam decisões', 'Relatórios claros para entender o que manter, pausar ou escalar.', 'query_stats'],
        ];

        foreach ($benefits as $index => [$title, $description, $icon]) {
            Benefit::updateOrCreate(
                ['title' => $title],
                ['description' => $description, 'icon' => $icon, 'sort_order' => $index + 1, 'is_active' => true],
            );
        }
    }

    private function seedAudiences(): void
    {
        $audiences = [
            ['Negócios locais', 'Precisam aumentar procura regional e transformar tráfego em atendimento.', 'storefront'],
            ['E-commerces', 'Buscam escala com controle de aquisição, conversão e recompra.', 'shopping_cart'],
            ['Escritórios de advocacia', 'Precisam gerar demanda respeitando posicionamento e diretrizes do setor.', 'balance'],
            ['Prestadores de serviço', 'Querem agenda mais previsível e oportunidades mais qualificadas.', 'handshake'],
            ['Empresas em crescimento', 'Precisam organizar aquisição antes de escalar investimento.', 'rocket_launch'],
            ['Empresas com marketing travado', 'Têm ações soltas, pouca clareza de resultado e baixa previsibilidade.', 'report_problem'],
        ];

        foreach ($audiences as $index => [$title, $description, $icon]) {
            TargetAudience::updateOrCreate(
                ['title' => $title],
                ['description' => $description, 'icon' => $icon, 'sort_order' => $index + 1, 'is_active' => true],
            );
        }
    }

    private function seedWorkSteps(): void
    {
        $steps = [
            ['01', 'Diagnóstico', 'Análise do momento atual do marketing, das métricas e das oportunidades.'],
            ['02', 'Planejamento', 'Definição do plano de ação, metas e prioridades.'],
            ['03', 'Implementação', 'Execução das ações com agilidade e foco em resultado.'],
            ['04', 'Otimização', 'Acompanhamento dos dados e ajustes constantes.'],
            ['05', 'Evolução', 'Reuniões, relatórios e expansão das frentes de atuação.'],
        ];

        foreach ($steps as $index => [$stepLabel, $title, $description]) {
            WorkStep::updateOrCreate(
                ['step_label' => $stepLabel],
                ['title' => $title, 'description' => $description, 'sort_order' => $index + 1, 'is_active' => true],
            );
        }
    }

    private function seedCase(): void
    {
        AgencyCase::updateOrCreate(
            ['title' => 'De zero a +130 contratos por mês com tráfego pago'],
            [
                'segment' => 'Advocacia',
                'initial_scenario' => 'Escritório com dificuldade para atrair novos clientes e com presença digital pouco estruturada.',
                'challenge' => 'Criar um canal de aquisição previsível e escalável, respeitando as diretrizes éticas da OAB.',
                'strategy' => 'Campanhas segmentadas, landing pages de conversão, organização do fluxo comercial e otimização contínua.',
                'result' => 'O escritório passou a fechar mais de 130 contratos por mês, com crescimento relevante e um processo comercial mais eficiente.',
                'metrics' => ['+130 contratos por mês', 'Crescimento relevante', 'Segmento jurídico'],
                'cta_label' => 'Quero resultados assim',
                'sort_order' => 1,
                'is_featured' => true,
                'is_active' => true,
            ],
        );
    }

    private function seedTestimonials(): void
    {
        $testimonials = [
            ['Cliente de serviços', 'Operação comercial', 'A Perfil Digital Ads transformou nossa captação de clientes. Saímos de um fluxo instável para um processo comercial muito mais previsível.'],
            ['Diretoria comercial', 'Empresa em crescimento', 'Finalmente encontramos uma agência que acompanha de perto e trabalha com foco real em resultado.'],
            ['Gestor de marketing', 'Negócio local', 'O atendimento é próximo, os relatórios são claros e as decisões fazem sentido para o nosso momento.'],
        ];

        foreach ($testimonials as $index => [$author, $company, $content]) {
            Testimonial::updateOrCreate(
                ['author_name' => $author],
                ['company' => $company, 'role' => null, 'content' => $content, 'sort_order' => $index + 1, 'is_active' => true],
            );
        }
    }

    private function seedFaqs(): void
    {
        $faqs = [
            ['Tenho medo de investir em marketing e não ter retorno. Como vocês lidam com isso?', 'Nosso trabalho começa com diagnóstico, definição de metas e acompanhamento dos números. A ideia é reduzir achismo e orientar cada decisão com base em dados.'],
            ['Vou ter acompanhamento de perto ou vou ser só mais um cliente?', 'A proposta da Perfil Digital Ads é trabalhar com acompanhamento próximo, reuniões estratégicas e ajustes constantes.'],
            ['O serviço funciona para o meu tipo de negócio?', 'Atendemos empresas de diferentes segmentos, sempre adaptando a estratégia à realidade comercial de cada operação.'],
            ['Como funciona o processo de trabalho de vocês?', 'Diagnóstico, planejamento, implementação, otimização e evolução. O cliente sabe o que está sendo feito e por qual motivo.'],
            ['Em quanto tempo vejo resultados?', 'Isso depende do tipo de negócio, da oferta, da verba e da estrutura comercial. O importante é construir um processo que gere resultado com consistência.'],
            ['Preciso ter um orçamento alto para começar?', 'Não. O investimento precisa fazer sentido para o momento da empresa. O ideal é definir um orçamento viável e trabalhar com estratégia desde o início.'],
        ];

        foreach ($faqs as $index => [$question, $answer]) {
            Faq::updateOrCreate(
                ['question' => $question],
                ['answer' => $answer, 'sort_order' => $index + 1, 'is_active' => true],
            );
        }
    }

    private function seedSocialLinks(): void
    {
        $links = [
            ['Instagram', 'Instagram', 'https://instagram.com/perfildigitalads', 'photo_camera'],
            ['LinkedIn', 'LinkedIn', 'https://linkedin.com/company/perfil-digital-ads', 'business_center'],
        ];

        foreach ($links as $index => [$network, $label, $url, $icon]) {
            SocialLink::updateOrCreate(
                ['network' => $network],
                ['label' => $label, 'url' => $url, 'icon' => $icon, 'sort_order' => $index + 1, 'is_active' => true],
            );
        }
    }
}

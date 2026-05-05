<?php

namespace App\Http\Controllers;

use App\Models\AgencyCase;
use App\Models\Benefit;
use App\Models\Faq;
use App\Models\PageSection;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\SocialLink;
use App\Models\TargetAudience;
use App\Models\Testimonial;
use App\Models\WorkStep;
use Illuminate\View\View;

class PublicController extends Controller
{
    public function index(): View
    {
        return view('landing.index', $this->publicViewData() + [
            'services' => Service::active()->ordered()->take(4)->get(),
            'formServices' => Service::active()->ordered()->get(),
            'benefits' => Benefit::active()->ordered()->take(4)->get(),
            'audiences' => TargetAudience::active()->ordered()->take(3)->get(),
            'workSteps' => WorkStep::active()->ordered()->take(3)->get(),
            'featuredCase' => AgencyCase::active()
                ->orderByDesc('is_featured')
                ->ordered()
                ->first(),
            'testimonials' => Testimonial::active()->ordered()->take(3)->get(),
            'faqs' => Faq::active()->ordered()->take(4)->get(),
        ]);
    }

    public function services(): View
    {
        $data = $this->publicViewData();

        return view('landing.topic', $data + [
            'page' => $this->topicPage($data['sections']->get('solucoes'), 'Soluções', 'services'),
            'items' => Service::active()->ordered()->get(),
        ]);
    }

    public function service(Service $service): View
    {
        abort_unless($service->is_active, 404);

        return view('landing.service-show', $this->publicViewData() + [
            'service' => $service,
            'relatedServices' => Service::active()
                ->whereKeyNot($service->id)
                ->ordered()
                ->take(3)
                ->get(),
        ]);
    }

    public function benefits(): View
    {
        $data = $this->publicViewData();

        return view('landing.topic', $data + [
            'page' => $this->topicPage($data['sections']->get('beneficios'), 'Benefícios', 'benefits'),
            'items' => Benefit::active()->ordered()->get(),
        ]);
    }

    public function audiences(): View
    {
        $data = $this->publicViewData();

        return view('landing.topic', $data + [
            'page' => $this->topicPage($data['sections']->get('nichos'), 'Nichos atendidos', 'audiences'),
            'items' => TargetAudience::active()->ordered()->get(),
        ]);
    }

    public function process(): View
    {
        $data = $this->publicViewData();

        return view('landing.topic', $data + [
            'page' => $this->topicPage($data['sections']->get('processo'), 'Processo de trabalho', 'process'),
            'items' => WorkStep::active()->ordered()->get(),
        ]);
    }

    public function cases(): View
    {
        $data = $this->publicViewData();

        return view('landing.topic', $data + [
            'page' => $this->topicPage($data['sections']->get('cases'), 'Cases', 'cases'),
            'items' => AgencyCase::active()
                ->orderByDesc('is_featured')
                ->ordered()
                ->get(),
        ]);
    }

    public function case(AgencyCase $agencyCase): View
    {
        abort_unless($agencyCase->is_active, 404);

        return view('landing.case-show', $this->publicViewData() + [
            'agencyCase' => $agencyCase,
            'relatedCases' => AgencyCase::active()
                ->whereKeyNot($agencyCase->id)
                ->ordered()
                ->take(3)
                ->get(),
        ]);
    }

    public function testimonials(): View
    {
        $data = $this->publicViewData();

        return view('landing.topic', $data + [
            'page' => $this->topicPage($data['sections']->get('prova-social'), 'Depoimentos', 'testimonials'),
            'items' => Testimonial::active()->ordered()->get(),
        ]);
    }

    public function faqs(): View
    {
        $data = $this->publicViewData();

        return view('landing.topic', $data + [
            'page' => $this->topicPage($data['sections']->get('faq'), 'Perguntas frequentes', 'faqs'),
            'items' => Faq::active()->ordered()->get(),
        ]);
    }

    private function publicViewData(): array
    {
        $settings = SiteSetting::values();

        return [
            'settings' => $settings,
            'sections' => PageSection::active()->ordered()->get()->keyBy('key'),
            'menuLinks' => $this->navigationLinks($settings),
            'socialLinks' => SocialLink::active()->ordered()->get(),
            'contactUrl' => route('home').'#contato',
        ];
    }

    private function topicPage(?PageSection $section, string $fallbackTitle, string $type): array
    {
        return [
            'type' => $type,
            'eyebrow' => $section?->eyebrow ?: 'Conteúdo',
            'title' => $section?->title ?: $fallbackTitle,
            'subtitle' => $section?->subtitle ?: 'Veja todos os registros cadastrados para este tópico.',
            'media_path' => $section?->media_path,
        ];
    }

    private function navigationLinks(array $settings): array
    {
        $links = $settings['menu_links'] ?? [];

        if (! is_array($links) || $links === []) {
            $links = [
                ['label' => 'Sobre a Perfil Digital', 'url' => '#sobre'],
                ['label' => 'Soluções', 'url' => '#solucoes'],
                ['label' => 'Cases', 'url' => '#cases'],
                ['label' => 'Nichos', 'url' => '#nichos'],
                ['label' => 'Contato', 'url' => '#contato'],
            ];
        }

        return collect($links)
            ->map(fn (array $link): array => [
                'label' => $link['label'] ?? 'Link',
                'url' => $this->publicUrl($link['url'] ?? '#'),
            ])
            ->values()
            ->all();
    }

    private function publicUrl(string $url): string
    {
        return match ($url) {
            '#sobre' => route('home').'#sobre',
            '#solucoes' => route('services.index'),
            '#beneficios' => route('benefits.index'),
            '#nichos' => route('audiences.index'),
            '#processo' => route('process.index'),
            '#cases' => route('cases.index'),
            '#prova-social' => route('testimonials.index'),
            '#faq' => route('faqs.index'),
            '#contato' => route('home').'#contato',
            default => str_starts_with($url, '#') ? route('home').$url : $url,
        };
    }
}

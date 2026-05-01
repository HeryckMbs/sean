<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgencyCase;
use App\Models\Benefit;
use App\Models\Faq;
use App\Models\MediaFile;
use App\Models\PageSection;
use App\Models\Service;
use App\Models\SocialLink;
use App\Models\TargetAudience;
use App\Models\Testimonial;
use App\Models\WorkStep;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ContentController extends Controller
{
    public function index(string $resource): View
    {
        $config = $this->resourceConfig($resource);
        $items = $config['model']::query()->orderBy('sort_order')->orderBy('id')->paginate(30);

        return view('admin.content.index', compact('resource', 'config', 'items'));
    }

    public function create(string $resource): View
    {
        $config = $this->resourceConfig($resource);
        $item = new $config['model']();

        return view('admin.content.form', compact('resource', 'config', 'item'));
    }

    public function store(Request $request, string $resource): RedirectResponse
    {
        $config = $this->resourceConfig($resource);
        $data = $this->validatedData($request, $config);

        if ($config['model'] === Service::class) {
            $data['slug'] = $this->uniqueSlug($data['name']);
        }

        $config['model']::create($data);

        return redirect()
            ->route('admin.content.index', $resource)
            ->with('status', $config['singular'].' criado.');
    }

    public function edit(string $resource, int $item): View
    {
        $config = $this->resourceConfig($resource);
        $item = $config['model']::query()->findOrFail($item);

        return view('admin.content.form', compact('resource', 'config', 'item'));
    }

    public function update(Request $request, string $resource, int $item): RedirectResponse
    {
        $config = $this->resourceConfig($resource);
        $item = $config['model']::query()->findOrFail($item);
        $data = $this->validatedData($request, $config, $item);

        if ($config['model'] === Service::class && isset($data['name'])) {
            $data['slug'] = $this->uniqueSlug($data['name'], $item->id);
        }

        $item->update($data);

        return redirect()
            ->route('admin.content.index', $resource)
            ->with('status', $config['singular'].' atualizado.');
    }

    public function destroy(string $resource, int $item): RedirectResponse
    {
        $config = $this->resourceConfig($resource);
        $config['model']::query()->findOrFail($item)->delete();

        return redirect()
            ->route('admin.content.index', $resource)
            ->with('status', $config['singular'].' removido.');
    }

    public static function resources(): array
    {
        return [
            'secoes',
            'servicos',
            'beneficios',
            'nichos',
            'processo',
            'cases',
            'depoimentos',
            'faq',
            'redes-sociais',
        ];
    }

    public function resourceConfig(string $resource): array
    {
        $configs = [
            'secoes' => [
                'plural' => 'Seções da landing',
                'singular' => 'Seção',
                'model' => PageSection::class,
                'primary' => 'title',
                'fields' => [
                    'key' => ['label' => 'Chave interna', 'type' => 'text', 'required' => true],
                    'eyebrow' => ['label' => 'Texto curto acima do título', 'type' => 'text'],
                    'title' => ['label' => 'Título', 'type' => 'text', 'required' => true],
                    'subtitle' => ['label' => 'Subtítulo', 'type' => 'textarea'],
                    'body' => ['label' => 'Texto principal', 'type' => 'textarea'],
                    'cta_label' => ['label' => 'Texto do CTA', 'type' => 'text'],
                    'cta_url' => ['label' => 'Destino do CTA', 'type' => 'text'],
                    'media_path' => ['label' => 'Imagem', 'type' => 'image'],
                    'sort_order' => ['label' => 'Ordem', 'type' => 'number'],
                    'is_active' => ['label' => 'Ativo', 'type' => 'checkbox'],
                ],
            ],
            'servicos' => [
                'plural' => 'Serviços',
                'singular' => 'Serviço',
                'model' => Service::class,
                'primary' => 'name',
                'fields' => [
                    'name' => ['label' => 'Nome', 'type' => 'text', 'required' => true],
                    'icon' => ['label' => 'Ícone Material Icons', 'type' => 'text'],
                    'summary' => ['label' => 'Resumo', 'type' => 'textarea'],
                    'description' => ['label' => 'Descrição', 'type' => 'textarea'],
                    'deliverables' => ['label' => 'Entregáveis, um por linha', 'type' => 'tags'],
                    'benefits' => ['label' => 'Benefícios, um por linha', 'type' => 'tags'],
                    'cta_label' => ['label' => 'Texto do CTA', 'type' => 'text'],
                    'sort_order' => ['label' => 'Ordem', 'type' => 'number'],
                    'is_active' => ['label' => 'Ativo', 'type' => 'checkbox'],
                ],
            ],
            'beneficios' => [
                'plural' => 'Benefícios',
                'singular' => 'Benefício',
                'model' => Benefit::class,
                'primary' => 'title',
                'fields' => [
                    'title' => ['label' => 'Título', 'type' => 'text', 'required' => true],
                    'description' => ['label' => 'Descrição', 'type' => 'textarea'],
                    'icon' => ['label' => 'Ícone Material Icons', 'type' => 'text'],
                    'sort_order' => ['label' => 'Ordem', 'type' => 'number'],
                    'is_active' => ['label' => 'Ativo', 'type' => 'checkbox'],
                ],
            ],
            'nichos' => [
                'plural' => 'Nichos atendidos',
                'singular' => 'Nicho',
                'model' => TargetAudience::class,
                'primary' => 'title',
                'fields' => [
                    'title' => ['label' => 'Título', 'type' => 'text', 'required' => true],
                    'description' => ['label' => 'Dor principal', 'type' => 'textarea'],
                    'icon' => ['label' => 'Ícone Material Icons', 'type' => 'text'],
                    'sort_order' => ['label' => 'Ordem', 'type' => 'number'],
                    'is_active' => ['label' => 'Ativo', 'type' => 'checkbox'],
                ],
            ],
            'processo' => [
                'plural' => 'Etapas do processo',
                'singular' => 'Etapa',
                'model' => WorkStep::class,
                'primary' => 'title',
                'fields' => [
                    'step_label' => ['label' => 'Rótulo da etapa', 'type' => 'text', 'required' => true],
                    'title' => ['label' => 'Título', 'type' => 'text', 'required' => true],
                    'description' => ['label' => 'Descrição', 'type' => 'textarea'],
                    'sort_order' => ['label' => 'Ordem', 'type' => 'number'],
                    'is_active' => ['label' => 'Ativo', 'type' => 'checkbox'],
                ],
            ],
            'cases' => [
                'plural' => 'Cases',
                'singular' => 'Case',
                'model' => AgencyCase::class,
                'primary' => 'title',
                'fields' => [
                    'title' => ['label' => 'Título', 'type' => 'text', 'required' => true],
                    'segment' => ['label' => 'Segmento', 'type' => 'text'],
                    'initial_scenario' => ['label' => 'Cenário inicial', 'type' => 'textarea'],
                    'challenge' => ['label' => 'Desafio', 'type' => 'textarea'],
                    'strategy' => ['label' => 'Estratégia aplicada', 'type' => 'textarea'],
                    'result' => ['label' => 'Resultado', 'type' => 'textarea'],
                    'metrics' => ['label' => 'Indicadores visuais, um por linha', 'type' => 'tags'],
                    'cta_label' => ['label' => 'Texto do CTA', 'type' => 'text'],
                    'image_path' => ['label' => 'Imagem', 'type' => 'image'],
                    'sort_order' => ['label' => 'Ordem', 'type' => 'number'],
                    'is_featured' => ['label' => 'Case em destaque', 'type' => 'checkbox'],
                    'is_active' => ['label' => 'Ativo', 'type' => 'checkbox'],
                ],
            ],
            'depoimentos' => [
                'plural' => 'Depoimentos',
                'singular' => 'Depoimento',
                'model' => Testimonial::class,
                'primary' => 'author_name',
                'fields' => [
                    'author_name' => ['label' => 'Nome', 'type' => 'text', 'required' => true],
                    'company' => ['label' => 'Empresa', 'type' => 'text'],
                    'role' => ['label' => 'Cargo', 'type' => 'text'],
                    'content' => ['label' => 'Depoimento', 'type' => 'textarea', 'required' => true],
                    'avatar_path' => ['label' => 'Foto', 'type' => 'image'],
                    'sort_order' => ['label' => 'Ordem', 'type' => 'number'],
                    'is_active' => ['label' => 'Ativo', 'type' => 'checkbox'],
                ],
            ],
            'faq' => [
                'plural' => 'FAQ',
                'singular' => 'Pergunta',
                'model' => Faq::class,
                'primary' => 'question',
                'fields' => [
                    'question' => ['label' => 'Pergunta', 'type' => 'text', 'required' => true],
                    'answer' => ['label' => 'Resposta', 'type' => 'textarea', 'required' => true],
                    'sort_order' => ['label' => 'Ordem', 'type' => 'number'],
                    'is_active' => ['label' => 'Ativo', 'type' => 'checkbox'],
                ],
            ],
            'redes-sociais' => [
                'plural' => 'Redes sociais',
                'singular' => 'Rede social',
                'model' => SocialLink::class,
                'primary' => 'network',
                'fields' => [
                    'network' => ['label' => 'Rede', 'type' => 'text', 'required' => true],
                    'label' => ['label' => 'Rótulo', 'type' => 'text'],
                    'url' => ['label' => 'URL', 'type' => 'url', 'required' => true],
                    'icon' => ['label' => 'Ícone Material Icons', 'type' => 'text'],
                    'sort_order' => ['label' => 'Ordem', 'type' => 'number'],
                    'is_active' => ['label' => 'Ativo', 'type' => 'checkbox'],
                ],
            ],
        ];

        abort_unless(isset($configs[$resource]), 404);

        return $configs[$resource];
    }

    private function validatedData(Request $request, array $config, ?Model $item = null): array
    {
        $rules = [];

        foreach ($config['fields'] as $field => $definition) {
            $required = $definition['required'] ?? false;

            $rules[$field] = match ($definition['type']) {
                'image' => ['nullable', 'image', 'max:4096'],
                'number' => [$required ? 'required' : 'nullable', 'integer', 'min:0'],
                'url' => [$required ? 'required' : 'nullable', 'url', 'max:255'],
                'checkbox' => ['nullable', 'boolean'],
                default => [$required ? 'required' : 'nullable', 'string'],
            };
        }

        $request->validate($rules);
        $data = [];

        foreach ($config['fields'] as $field => $definition) {
            $type = $definition['type'];

            if ($type === 'image') {
                if ($request->hasFile($field)) {
                    $data[$field] = $this->storeMedia($request, $field, $config['singular']);
                }

                continue;
            }

            if ($type === 'checkbox') {
                $data[$field] = $request->boolean($field);
                continue;
            }

            if ($type === 'tags') {
                $data[$field] = $this->splitLines($request->input($field, ''));
                continue;
            }

            $data[$field] = $request->input($field);
        }

        return $data;
    }

    private function splitLines(?string $value): array
    {
        return array_values(array_filter(array_map(
            fn (string $line) => trim($line),
            preg_split('/\r\n|\r|\n|\|/', $value ?: '') ?: []
        )));
    }

    private function storeMedia(Request $request, string $field, string $collection): string
    {
        $file = $request->file($field);
        $path = $file->store('media', 'public');

        MediaFile::create([
            'original_name' => $file->getClientOriginalName(),
            'path' => $path,
            'disk' => 'public',
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize() ?: 0,
            'collection' => $collection,
        ]);

        Storage::disk('public')->setVisibility($path, 'public');

        return $path;
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'servico';
        $slug = $base;
        $suffix = 2;

        while (Service::query()
            ->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
            ->where('slug', $slug)
            ->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaFile;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function edit(): View
    {
        return view('admin.settings.edit', [
            'settings' => SiteSetting::query()
                ->where('key', '!=', 'hero_image')
                ->orderBy('group')
                ->orderBy('sort_order')
                ->get()
                ->groupBy('group'),
            'definitions' => $this->definitions(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        foreach ($this->definitions() as $key => $definition) {
            if ($definition['type'] === 'image') {
                if ($request->hasFile($key)) {
                    $request->validate([$key => ['image', 'max:4096']]);
                    SiteSetting::setValue($key, $this->storeMedia($request, $key, 'settings'));
                }

                continue;
            }

            $value = $request->input($key, '');

            if ($definition['type'] === 'json') {
                $decoded = json_decode($value ?: '[]', true);

                if (! is_array($decoded)) {
                    return back()
                        ->withErrors([$key => 'Informe um JSON válido.'])
                        ->withInput();
                }

                SiteSetting::setValue($key, $decoded);
                continue;
            }

            SiteSetting::setValue($key, $value);
        }

        return redirect()->route('admin.settings.edit')->with('status', 'Configurações atualizadas.');
    }

    public function definitions(): array
    {
        return [
            'brand_name' => ['label' => 'Nome da marca', 'type' => 'text'],
            'logo_path' => ['label' => 'Logo', 'type' => 'image'],
            'whatsapp_number' => ['label' => 'Número do WhatsApp', 'type' => 'text'],
            'whatsapp_message' => ['label' => 'Mensagem do WhatsApp', 'type' => 'textarea'],
            'lead_webhook_url' => ['label' => 'Endpoint futuro do formulário', 'type' => 'url'],
            'primary_cta_label' => ['label' => 'Texto do CTA principal', 'type' => 'text'],
            'secondary_cta_label' => ['label' => 'Texto do CTA secundário', 'type' => 'text'],
            'footer_slogan' => ['label' => 'Slogan do rodapé', 'type' => 'text'],
            'seo_title' => ['label' => 'SEO title', 'type' => 'text'],
            'seo_description' => ['label' => 'SEO description', 'type' => 'textarea'],
            'contact_email' => ['label' => 'E-mail de contato', 'type' => 'email'],
            'menu_links' => ['label' => 'Links do menu em JSON', 'type' => 'json'],
        ];
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
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeadAdminController extends Controller
{
    public function index(): View
    {
        return view('admin.leads.index', [
            'leads' => Lead::latest()->paginate(30),
        ]);
    }

    public function show(Lead $lead): View
    {
        return view('admin.leads.show', compact('lead'));
    }

    public function updateStatus(Request $request, Lead $lead): RedirectResponse
    {
        $validated = $request->validate([
            'integration_status' => ['required', 'string', 'max:80'],
        ]);

        $lead->update($validated);

        return back()->with('status', 'Status do lead atualizado.');
    }

    public function destroy(Lead $lead): RedirectResponse
    {
        $lead->delete();

        return redirect()->route('admin.leads.index')->with('status', 'Lead removido.');
    }
}

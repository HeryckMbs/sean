<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgencyCase;
use App\Models\Benefit;
use App\Models\Faq;
use App\Models\Lead;
use App\Models\Service;
use App\Models\Testimonial;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'counts' => [
                'leads' => Lead::count(),
                'services' => Service::count(),
                'benefits' => Benefit::count(),
                'cases' => AgencyCase::count(),
                'testimonials' => Testimonial::count(),
                'faqs' => Faq::count(),
            ],
            'latestLeads' => Lead::latest()->take(8)->get(),
        ]);
    }
}

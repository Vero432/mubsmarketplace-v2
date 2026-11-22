<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // the allowed reasons, used for both the form and the validation
    private array $reasons = [
        'Scam or fake item',
        'Prohibited item',
        'Wrong category or misleading details',
        'Harassment or inappropriate behaviour',
        'Other',
    ];

    public function create(Listing $listing)
    {
        // you cannot report your own listing
        abort_if(auth()->id() === $listing->user_id, 403);

        return view('reports.create', [
            'listing' => $listing,
            'reasons' => $this->reasons,
        ]);
    }

    public function store(Request $request, Listing $listing)
    {
        abort_if(auth()->id() === $listing->user_id, 403);

        $data = $request->validate([
            'reason'  => 'required|in:' . implode(',', $this->reasons),
            'details' => 'nullable|string|max:1000',
        ]);

        Report::create([
            'reporter_id'      => auth()->id(),
            'listing_id'       => $listing->id,
            'reported_user_id' => $listing->user_id,
            'reason'           => $data['reason'],
            'details'          => $data['details'] ?? null,
            // status defaults to "open"
        ]);

        return redirect()->route('listings.show', $listing)
            ->with('success', 'Report sent. An administrator will review it.');
    }

    // reports I have sent, with their current status
    public function mine()
    {
        $reports = Report::with('listing')
            ->where('reporter_id', auth()->id())
            ->latest()
            ->get();

        return view('reports.mine', compact('reports'));
    }
}
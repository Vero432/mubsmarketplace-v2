<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Listing;
use App\Models\Report;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    // Dashboard: a few counts
    public function dashboard()
    {
        return view('admin.dashboard', [
            'userCount'    => User::count(),
            'pendingUsers' => User::where('status', 'pending')->count(),
            'listingCount' => Listing::count(),
            'openReports'  => Report::where('status', 'open')->count(),
        ]);
    }

    // Users
    public function users(Request $request)
{
    $query = User::withCount('listings')->latest();

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }
    if ($request->filled('search')) {
        $query->where(function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->search . '%');
        });
    }

    return view('admin.users', [
        'users'  => $query->get(),
        'counts' => [
            'pending'   => User::where('status', 'pending')->count(),
            'active'    => User::where('status', 'active')->count(),
            'rejected'  => User::where('status', 'rejected')->count(),
            'suspended' => User::where('status', 'suspended')->count(),
        ],
    ]);
}

public function updateUser(Request $request, User $user)
{
    $data = $request->validate([
        'status' => 'required|in:active,suspended,rejected',
        'note'   => 'nullable|string|max:300',
    ]);

    // an admin cannot change their own account
    abort_if($user->id === auth()->id(), 403);

    // An account must verify its email before admin approval
if ($data['status'] === 'active' && !$user->hasVerifiedEmail()) {
    return back()->with('error', 'This user must verify their email before approval.');
}

    $user->status = $data['status'];
    $user->status_note = $data['status'] === 'active' ? null : ($data['note'] ?? null);
    $user->save();

    // try to email the user; if email is not working, carry on
    try {
        $user->notify(new \App\Notifications\AccountStatusChanged($user->status, $user->status_note));
    } catch (\Throwable $e) {
        report($e);
    }

    return back()->with('success', 'User updated.');
}

    // Listings
    public function listings()
    {
        $listings = Listing::with(['user', 'category'])->latest()->get();

        return view('admin.listings', compact('listings'));
    }

    public function destroyListing(Listing $listing)
    {
        if ($listing->image) {
            File::delete(public_path('uploads/' . $listing->image));
        }

        $listing->delete();

        return back()->with('success', 'Listing removed.');
    }

    // Reports
    public function reports()
    {
        $reports = Report::with(['reporter', 'reportedUser', 'listing'])->latest()->get();

        return view('admin.reports', compact('reports'));
    }

    public function updateReport(Request $request, Report $report)
    {
        $data = $request->validate([
            'status'     => 'required|in:open,reviewing,resolved,dismissed',
            'admin_note' => 'nullable|string|max:500',
        ]);

        $report->update($data);

        return back()->with('success', 'Report updated.');
    }

    // Categories
    public function categories()
    {
        $categories = Category::withCount('listings')->get();

        return view('admin.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:60|unique:categories,name',
        ]);

        Category::create($data);

        return back()->with('success', 'Category added.');
    }

    public function destroyCategory(Category $category)
    {
        // do not delete a category that still has listings
        if ($category->listings()->exists()) {
            return back()->with('error', 'This category still has listings, so it cannot be deleted.');
        }

        $category->delete();

        return back()->with('success', 'Category deleted.');
    }
}
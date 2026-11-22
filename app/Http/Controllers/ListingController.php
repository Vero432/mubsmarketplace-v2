<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use App\Models\Message;
use App\Models\Rating;
use Illuminate\Support\Facades\File;


class ListingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    // Start a query: newest listings first.
    // with() loads the seller and category in advance, which is faster
    $query = Listing::with(['user', 'category'])->latest();

    // If the user typed something in the search box, keep titles that contain it
    if ($request->filled('search')) {
        $query->where('title', 'like', '%' . $request->search . '%');
    }

    // If the user chose a category, keep only that category
    if ($request->filled('category')) {
        $query->where('category_id', $request->category);
    }

    // 12 listings per page; withQueryString keeps the search when changing pages
    $listings = $query->paginate(12)->withQueryString();

    // All categories, for the dropdown
    $categories = Category::all();

    // Send the data to the Blade page
    return view('listings.index', compact('listings', 'categories'));
}

    /**
     * Show the form for creating a new resource.
     */
    // Shows the empty form
public function create()
{
    // the dropdown needs the list of categories
    $categories = Category::all();
    $servicesId = Category::where('name', 'Services')->value('id');

    return view('listings.create', compact('categories', 'servicesId'));
}

// Receives the form and saves the listing
public function store(Request $request)
{
    // 1. VALIDATE: if any rule fails, Laravel sends the user back to the
    //    form with error messages and keeps what they typed
    $validated = $request->validate([
        'title'       => 'required|string|max:100',
        'category_id' => 'required|exists:categories,id',   // must be a real category
        'description' => 'required|string|max:1000',
        'price'       => 'required|integer|min:0',
        'condition' => 'nullable|in:new,like new,used',
        'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:4096', // optional, max 4 MB
    ]);
    $isService = Category::where('id', $validated['category_id'])->where('name', 'Services')->exists();

if ($isService) {
    $validated['condition'] = 'not applicable';      // services have no condition
} elseif (empty($validated['condition'])) {
    return back()->withErrors(['condition' => 'Please choose the condition of the item.'])->withInput();
}

    // 2. PHOTO: if one was uploaded, save it in storage/app/public/listings
    //    with a random file name, and keep the path in the database
    if ($request->hasFile('image')) {
        $file = $request->file('image');
$filename = uniqid() . '.' . $file->extension();
$file->move(public_path('uploads/listings'), $filename);
$validated['image'] = 'listings/' . $filename;
    }

    // 3. OWNER: take the user from the login, never from the form
    $validated['user_id'] = auth()->id();

    // 4. SAVE (status uses the default "available" from the migration)
    $listing = Listing::create($validated);

    // 5. GO to the new listing's page with a success message
    return redirect()
        ->route('listings.show', $listing)
        ->with('success', 'Your listing has been posted.');
}

    /**
     * Display the specified resource.
     */
    // Laravel finds the listing from the URL (/listings/5), because the
// parameter is typed as "Listing". If it doesn't exist, the user sees a 404 page.
public function show(Listing $listing)
    {
        $listing->load(['user', 'category']);

        // can the logged-in user leave a rating for this listing?
        $canRate = false;

       if (auth()->id() !== $listing->user_id
    && auth()->user()->account_type === 'buyer'
    && $listing->status === 'sold') {
            $hasContacted = Message::where('listing_id', $listing->id)
                ->where('sender_id', auth()->id())->exists();

            $alreadyRated = Rating::where('listing_id', $listing->id)
                ->where('rater_id', auth()->id())->exists();

            $canRate = $hasContacted && ! $alreadyRated;
        }

        return view('listings.show', compact('listing', 'canRate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Listing $listing)
    {
        $this->authorizeOwner($listing);
        if ($response = $this->blockIfUnderReview($listing)) {
    return $response;
}
        $categories = Category::all();
        $servicesId = Category::where('name', 'Services')->value('id');

        return view('listings.edit', compact('listing', 'categories', 'servicesId'));
    }

    /**
     * Update the specified resource in storage.
     */
    // Save the changes
    public function update(Request $request, Listing $listing)
    {
        $this->authorizeOwner($listing);
        if ($response = $this->blockIfUnderReview($listing)) {
    return $response;
}

        $validated = $request->validate([
            'title'       => 'required|string|max:100',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|max:1000',
            'price'       => 'required|integer|min:0',
            'condition' => 'nullable|in:new,like new,used',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
        ]);

        $isService = Category::where('id', $validated['category_id'])->where('name', 'Services')->exists();

if ($isService) {
    $validated['condition'] = 'not applicable';      // services have no condition
} elseif (empty($validated['condition'])) {
    return back()->withErrors(['condition' => 'Please choose the condition of the item.'])->withInput();
}

        if ($request->hasFile('image')) {
            // delete the old photo, then store the new one
            if ($listing->image) {
               File::delete(public_path('uploads/' . $listing->image));
            }
           $file = $request->file('image');
$filename = uniqid() . '.' . $file->extension();
$file->move(public_path('uploads/listings'), $filename);
$validated['image'] = 'listings/' . $filename;
        } else {
            unset($validated['image']);   // keep the existing photo
        }

        $listing->update($validated);

        return redirect()->route('listings.show', $listing)
            ->with('success', 'Listing updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    // Delete a listing
    public function destroy(Listing $listing)
    {
        $this->authorizeOwner($listing);
        if ($response = $this->blockIfUnderReview($listing)) {
    return $response;
}

        if ($listing->image) {
            File::delete(public_path('uploads/' . $listing->image));
        }

        $listing->delete();

        return redirect()->route('listings.mine')
            ->with('success', 'Listing deleted.');
    }

    //Stops anyone except the owner (or an admin) from changing a listing
    private function authorizeOwner(Listing $listing): void 
    {
        abort_unless(
            auth()->id() === $listing->user_id || auth()->user()->role === 'admin',
            403
        );
    }

    // stops sellers from changing a listing while it is under review
private function blockIfUnderReview(Listing $listing)
{
    if (auth()->user()->role !== 'admin' && $listing->isUnderReview()) {
        return back()->with('error',
            'This listing has been reported and is under review by an administrator. '
            . 'It cannot be edited or deleted until the review is finished.');
    }

    return null;
}

    //my listings page
    public function mine()
    {
        $listings = Listing::where('user_id', auth()->id())->latest()->get();
        return view('listings.mine', compact('listings'));
    }

// Change the status only (available, reserved, sold)
    public function updateStatus(Request $request, Listing $listing)
    {
        $this->authorizeOwner($listing);
        if ($response = $this->blockIfUnderReview($listing)) {
    return $response;
}

        $data = $request->validate([
            'status' => 'required|in:available,reserved,sold',
        ]);

        $listing->update($data);

        return back()->with('success', 'Status updated.');
    }

}

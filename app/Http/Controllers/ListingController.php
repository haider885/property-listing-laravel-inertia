<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ListingController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Listing::class, 'listing');
    }

    public function index(Request $request)
    {
        $filters = $request->only([
            'priceFrom', 'priceTo', 'beds', 'baths', 'areaFrom', 'areaTo'
        ]);

        // $query = Listing::orderByDesc('created_at');

        // $query->when(
        //     $filters['priceFrom'] ?? false,
        //     fn ($query, $value) => $query->where('price', '>=', $value)
        // );

        // if ($filters['priceFrom'] ?? false) {
        //     $query->where('price', '>=', $filters['priceFrom']);
        // }

        // if ($filters['priceTo'] ?? false) {
        //     $query->where('price', '<=', $filters['priceTo']);
        // }

        // if ($filters['beds'] ?? false) {
        //     $query->where('beds', $filters['beds']);
        // }

        // if ($filters['baths'] ?? false) {
        //     $query->where('baths', $filters['baths']);
        // }

        // if ($filters['areaFrom'] ?? false) {
        //     $query->where('area', '>=', $filters['areaFrom']);
        // }

        // if ($filters['areaTo'] ?? false) {
        //     $query->where('area', '<=', $filters['areaTo']);
        // }

        return inertia(
            'Listing/Index',
            [
                'filters' => $filters,
                'listings'=> Listing::mostRecent()
                    ->filter($filters)
                    ->paginate(10)
                    ->withQueryString()
            ]
        );
    }

    public function create()
    {
        // $this->authorize('create', Listing::class);
        return inertia('Listing/Create');
    }

    public function store(Request $request)
    {
        // dd($request->user());
        // $listing = new Listing();
        // $listing->beds = $request->beds;
        // $listing->save();
        // $request->user()->listing()->create();
        $request->user()->listings()->create(
            $request->validate([
                'beds' => 'required|integer|min:0|max:20',
                'baths' => 'required|integer|min:0|max:20',
                'area' => 'required|integer|min:15|max:1500',
                'city' => 'required',
                'code' => 'required',
                'street' => 'required',
                'street_nr' => 'required|min:1|max:1000',
                'price' => 'required|integer|min:1|max:20000000',
            ])
        );

        return redirect()->route('listing.index')
            ->with('success', 'Listing was created!');
    }

    public function show(Listing $listing)
    {
        // if(Auth::user()->cannot('view', $listing)) {
        //     abort(403);
        // }
        // $this->authorize('view', $listing);

        return inertia(
            'Listing/Show',
            [
                'listing'=> $listing
            ]
        );
    }

    public function edit(Listing $listing)
    {
        return inertia(
            'Listing/Edit',
            [
                'listing'=> $listing
            ]
        );
    }

    public function update(Request $request, Listing $listing)
    {
        $listing->update(
            $request->validate([
                'beds' => 'required|integer|min:0|max:20',
                'baths' => 'required|integer|min:0|max:20',
                'area' => 'required|integer|min:15|max:1500',
                'city' => 'required',
                'code' => 'required',
                'street' => 'required',
                'street_nr' => 'required|min:1|max:1000',
                'price' => 'required|integer|min:1|max:20000000',
            ])
        );

        return redirect()->route('listing.index')
            ->with('success', 'Listing was changed!');
    }

    public function destroy(Listing $listing)
    {
        $listing->delete();

        return redirect()->back()
            ->with('success', 'Listing was deleted!');
    }
}

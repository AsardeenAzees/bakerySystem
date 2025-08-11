<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = Address::where('user_id', auth()->id())->orderByDesc('is_default')->get();
        return view('addresses.index', compact('addresses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'line1' => 'required|max:255',
            'line2' => 'nullable|max:255',
            'city' => 'required|max:100',
            'state' => 'nullable|max:100',
            'postal_code' => 'nullable|max:20',
            'country' => 'nullable|max:100',
        ]);

        $data['user_id'] = auth()->id();
        $data['country'] = $data['country'] ?? 'Sri Lanka';
        Address::create($data);

        return back()->with('success', 'Address added.');
    }

    public function update(Request $request, Address $address)
    {
        abort_unless($address->user_id === auth()->id(), 403);
        $data = $request->validate([
            'line1' => 'required|max:255',
            'line2' => 'nullable|max:255',
            'city' => 'required|max:100',
            'state' => 'nullable|max:100',
            'postal_code' => 'nullable|max:20',
            'country' => 'nullable|max:100',
        ]);
        $address->update($data);
        return back()->with('success', 'Address updated.');
    }

    public function destroy(Address $address)
    {
        abort_unless($address->user_id === auth()->id(), 403);
        $address->delete();
        return back()->with('success', 'Address removed.');
    }

    public function setDefault(Address $address)
    {
        abort_unless($address->user_id === auth()->id(), 403);
        Address::where('user_id', auth()->id())->update(['is_default' => false]);
        $address->update(['is_default' => true]);
        return back()->with('success', 'Default address set.');
    }
}

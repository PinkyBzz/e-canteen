<?php

namespace App\Http\Controllers\Warung;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function edit()
    {
        $warung = auth()->user()->warung;
        return view('warung.settings', compact('warung'));
    }

    public function update(Request $request)
    {
        $warung = auth()->user()->warung;

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'phone'       => 'nullable|string|max:20',
            'address'     => 'nullable|string|max:500',
            'logo'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            if ($warung->logo) Storage::disk('public')->delete($warung->logo);
            $validated['logo'] = $request->file('logo')->store('warungs', 'public');
        }

        $warung->update($validated);

        return back()->with('success', 'Profil warung berhasil diperbarui!');
    }
}

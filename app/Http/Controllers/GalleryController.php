<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\Button;
use App\Models\Type;
use App\Models\Material;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    // Store a new color
    public function storeColor(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',  // Validation for name field
        ]);

        // Store the color
        Color::create([
            'name' => $request->name,
        ]);

        return redirect()->route('colors.index')->with('success', 'Color added successfully');
    }

    // Store a new button
    public function storeButton(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',  // Validation for name field
        ]);

        // Store the button
        Button::create([
            'name' => $request->name,
        ]);

        return redirect()->route('buttons.index')->with('success', 'Button added successfully');
    }

    // Store a new type
    public function storeType(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',  // Validation for name field
        ]);

        // Store the type
        Type::create([
            'name' => $request->name,
        ]);

        return redirect()->route('types.index')->with('success', 'Type added successfully');
    }

    // Store a new material
    public function storeMaterial(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',  // Validation for name field
        ]);

        // Store the material
        Material::create([
            'name' => $request->name,
        ]);

        return redirect()->route('materials.index')->with('success', 'Material added successfully');
    }
}

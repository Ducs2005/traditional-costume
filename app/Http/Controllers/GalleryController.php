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

        return redirect()->route('admin.gallery')->with('success', 'Color added successfully');
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

        return redirect()->route('admin.gallery')->with('success', 'Button added successfully');
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

        return redirect()->route('admin.gallery')->with('success', 'Type added successfully');
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

        return redirect()->route('admin.gallery')->with('success', 'Material added successfully');
    }
    public function updateColor(Request $request, $id)
    {
        // Validate the incoming request
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Find the color by ID and update the name
        $color = Color::findOrFail($id);
        $color->name = $request->input('name');
        $color->save();

        // Redirect or respond back
        return redirect()->back()->with('success', 'Color updated successfully!');
    }

    // Update Button
    public function updateButton(Request $request, $id)
    {
        // Validate the incoming request
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Find the button by ID and update the name
        $button = Button::findOrFail($id);
        $button->name = $request->input('name');
        $button->save();

        // Redirect or respond back
        return redirect()->back()->with('success', 'Button updated successfully!');
    }

    // Update Material
    public function updateMaterial(Request $request, $id)
    {
        // Validate the incoming request
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Find the material by ID and update the name
        $material = Material::findOrFail($id);
        $material->name = $request->input('name');
        $material->save();

        // Redirect or respond back
        return redirect()->back()->with('success', 'Material updated successfully!');
    }

    // Update Type
    public function updateType(Request $request, $id)
    {
        // Validate the incoming request
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Find the type by ID and update the name
        $type = Type::findOrFail($id);
        $type->name = $request->input('name');
        $type->save();

        // Redirect or respond back
        return redirect()->back()->with('success', 'Type updated successfully!');
    }
    public function destroyColor($id)
    {
        $model = Color::findOrFail($id); // Replace `ModelName` with the corresponding model (e.g., Color, Button)
        $model->delete();

        return redirect()->back()->with('success', 'Item deleted successfully!');
    }
    public function destroyButton($id)
    {
        $model = Button::findOrFail($id); // Replace `ModelName` with the corresponding model (e.g., Color, Button)
        $model->delete();

        return redirect()->back()->with('success', 'Item deleted successfully!');
    } public function destroyMaterial($id)
    {
        $model = Material::findOrFail($id); // Replace `ModelName` with the corresponding model (e.g., Color, Button)
        $model->delete();

        return redirect()->back()->with('success', 'Item deleted successfully!');
    } public function destroyType($id)
    {
        $model = Type::findOrFail($id); // Replace `ModelName` with the corresponding model (e.g., Color, Button)
        $model->delete();

        return redirect()->back()->with('success', 'Item deleted successfully!');
    }

}

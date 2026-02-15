<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\ItemCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::with('category')->orderBy('name')->get();
        return view('admin.items.index', compact('items'));
    }

    public function create(Request $request)
    {
        $categories = ItemCategory::where('is_active', true)->get();
        $preSelectedCategory = $request->query('category_id');
        return view('admin.items.create', compact('categories', 'preSelectedCategory'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:item_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'preparation_time' => 'integer|min:1',
            'is_available' => 'boolean',
            'show_price' => 'boolean',
        ]);

        $validated['show_price'] = $request->has('show_price');
        $validated['is_available'] = $request->has('is_available');

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('items', 'public');
        }

        Item::create($validated);
        return redirect()->route('admin.items.index')->with('success', 'Item created successfully');
    }

    public function edit(Item $item)
    {
        $categories = ItemCategory::where('is_active', true)->get();
        return view('admin.items.edit', compact('item', 'categories'));
    }

    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:item_categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'preparation_time' => 'integer|min:1',
            'is_available' => 'boolean',
            'show_price' => 'boolean',
        ]);

        $validated['show_price'] = $request->has('show_price');
        $validated['is_available'] = $request->has('is_available');

        if ($request->hasFile('image')) {
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
            $validated['image'] = $request->file('image')->store('items', 'public');
        }

        $item->update($validated);
        return redirect()->route('admin.items.index')->with('success', 'Item updated successfully');
    }

    public function destroy(Item $item)
    {
        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }
        $item->delete();
        return redirect()->route('admin.items.index')->with('success', 'Item deleted successfully');
    }
}

<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Part;
use Illuminate\Http\Request;

class PartController extends Controller
{
    public function index()
    {
        $parts = Part::latest()->paginate(15);
        return view('admin.parts.index', compact('parts'));
    }

    public function create() { return view('admin.parts.create'); }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:100',
            'brand'         => 'nullable|string|max:50',
            'sku'           => 'nullable|string|unique:parts,sku|max:50',
            'price'         => 'nullable|numeric|min:0',
            'compatibility' => 'nullable|json',
            'description'   => 'nullable|string'
        ]);

        Part::create($request->only(['name','brand','sku','price','compatibility','description']));
        return redirect()->route('admin.parts.index')->with('success', 'Запчасть добавлена');
    }

    public function edit(Part $part) { return view('admin.parts.edit', compact('part')); }

    public function update(Request $request, Part $part)
    {
        $request->validate([
            'name'          => 'required|string|max:100',
            'brand'         => 'nullable|string|max:50',
            'sku'           => ['nullable','string','max:50', \Illuminate\Validation\Rule::unique('parts')->ignore($part)],
            'price'         => 'nullable|numeric|min:0',
            'compatibility' => 'nullable|json',
            'description'   => 'nullable|string'
        ]);

        $part->update($request->only(['name','brand','sku','price','compatibility','description']));
        return redirect()->route('admin.parts.index')->with('success', 'Запчасть обновлена');
    }

    public function destroy(Part $part)
    {
        $part->delete();
        return back()->with('success', 'Запчасть удалена');
    }
}
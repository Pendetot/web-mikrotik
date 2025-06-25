<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\PackageCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminPackageController extends Controller
{
    public function index(Request $request)
    {
        $query = Package::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        switch ($request->get('sort', 'newest')) {
            case 'oldest':
                $query->oldest();
                break;
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->latest();
        }

        $packages = $query->withCount('subscriptions')->paginate(10);

        $stats = [
            'total_packages' => Package::count(),
            'active_packages' => Package::where('is_active', true)->count(),
        ];

        return view('admin.packages.index', compact('packages', 'stats'));
    }

    public function create()
    {
        return view('admin.packages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'code' => 'nullable|string|max:50|unique:packages,code',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'featured' => 'boolean',
        ]);

        $data = $request->only([
            'name', 'price', 'original_price', 'duration', 
            'code', 'description', 'is_active', 'featured'
        ]);

        if (empty($data['code'])) {
            $data['code'] = 'PKG-' . strtoupper(Str::random(6));
        }

        $data['is_active'] = $request->boolean('is_active', true);
        $data['featured'] = $request->boolean('featured', false);

        Package::create($data);

        return redirect()->route('admin.packages.index')
                        ->with('success', 'Paket berhasil ditambahkan!');
    }

    public function show(Package $package)
    {
        $package->load('subscriptions.user');
        return view('admin.packages.show', compact('package'));
    }

    public function edit(Package $package)
    {
        return view('admin.packages.edit', compact('package'));
    }

    public function update(Request $request, Package $package)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'original_price' => 'nullable|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'code' => 'nullable|string|max:50|unique:packages,code,' . $package->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'featured' => 'boolean',
        ]);

        $data = $request->only([
            'name', 'price', 'original_price', 'duration', 
            'code', 'description', 'is_active', 'featured'
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $data['featured'] = $request->boolean('featured', false);

        $package->update($data);

        return redirect()->route('admin.packages.index')
                        ->with('success', 'Paket berhasil diperbarui!');
    }

    public function destroy(Package $package)
    {
        if ($package->subscriptions()->where('status', 'active')->exists()) {
            return redirect()->back()
                            ->with('error', 'Tidak dapat menghapus paket yang memiliki langganan aktif!');
        }

        $package->delete();

        return redirect()->route('admin.packages.index')
                        ->with('success', 'Paket berhasil dihapus!');
    }

    public function toggleStatus(Package $package)
    {
        $package->update(['is_active' => !$package->is_active]);
        
        $status = $package->is_active ? 'diaktifkan' : 'dinonaktifkan';
        
        return redirect()->back()
                        ->with('success', "Paket berhasil {$status}!");
    }

    public function toggleFeatured(Package $package)
    {
        $package->update(['featured' => !$package->featured]);
        
        $status = $package->featured ? 'dijadikan unggulan' : 'dihapus dari unggulan';
        
        return redirect()->back()
                        ->with('success', "Paket berhasil {$status}!");
    }

    public function categories()
    {
        $categories = PackageCategory::withCount('packages')->paginate(10);
        return view('admin.packages.categories.index', compact('categories'));
    }

    public function createCategory()
    {
        return view('admin.packages.categories.create');
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        PackageCategory::create([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.packages.categories')
                        ->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function editCategory(PackageCategory $category)
    {
        return view('admin.packages.categories.edit', compact('category'));
    }

    public function updateCategory(Request $request, PackageCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.packages.categories')
                        ->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroyCategory(PackageCategory $category)
    {
        if ($category->packages()->exists()) {
            return redirect()->back()
                            ->with('error', 'Tidak dapat menghapus kategori yang memiliki paket!');
        }

        $category->delete();

        return redirect()->route('admin.packages.categories')
                        ->with('success', 'Kategori berhasil dihapus!');
    }
}
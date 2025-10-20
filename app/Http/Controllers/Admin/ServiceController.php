<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');

        $services = Service::query()
            ->when($q, function ($query) use ($q) {
                $query->where(function ($w) use ($q) {
                    $w->where('name', 'like', "%{$q}%")
                      ->orWhere('description', 'like', "%{$q}%");
                });
            })
            ->withCount('doctors')
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.services.index', compact('services', 'q'));
    }

    public function create()
    {
        $service = new Service();
        return view('admin.services.create', compact('service'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:120', 'unique:services,name'],
            'description' => ['nullable', 'string'],
            'status'      => ['required', Rule::in(['active','inactive'])],
        ]);

        Service::create($data);
        return redirect()->route('admin.services.index')->with('success', 'Service created.');
    }

    public function show(Service $service)
    {
        $service->load([
            'doctors:id,name,email,specialty'
        ]);

        return view('admin.services.show', compact('service'));
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:120', Rule::unique('services','name')->ignore($service->id)],
            'description' => ['nullable', 'string'],
            'status'      => ['required', Rule::in(['active','inactive'])],
        ]);

        $service->update($data);
        return redirect()->route('admin.services.index')->with('success', 'Service updated.');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('admin.services.index')->with('success', 'Service deleted.');
    }
}

@if ($errors->any())
  <div class="alert alert-danger">
    <ul class="mb-0">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

<div class="row g-3">
  {{-- Name --}}
  <div class="col-md-6">
    <label for="name" class="form-label fw-semibold">Service Name</label>
    <input type="text" id="name" name="name" class="form-control shadow-sm"
           value="{{ old('name', $service->name ?? '') }}" required>
    @error('name')
      <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
  </div>

  {{-- Status --}}
  <div class="col-md-6">
    <label for="status" class="form-label fw-semibold">Status</label>
    @php $status = old('status', $service->status ?? 'active'); @endphp
    <select id="status" name="status" class="form-select shadow-sm">
      <option value="active"   {{ $status==='active' ? 'selected' : '' }}>Active</option>
      <option value="inactive" {{ $status==='inactive' ? 'selected' : '' }}>Inactive</option>
    </select>
    @error('status')
      <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
  </div>

  {{-- Description --}}
  <div class="col-12">
    <label for="description" class="form-label fw-semibold">Description</label>
    <textarea id="description" name="description" rows="4"
              class="form-control shadow-sm"
              placeholder="Describe this service briefly...">{{ old('description', $service->description ?? '') }}</textarea>
    @error('description')
      <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
  </div>
</div>

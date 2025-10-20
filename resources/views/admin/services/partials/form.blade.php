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
  <div class="col-md-6">
    <label class="form-label">Name</label>
    <input type="text" name="name" class="form-control"
           value="{{ old('name', $service->name ?? '') }}" required>
  </div>

  <div class="col-md-6">
    <label class="form-label">Status</label>
    @php $status = old('status', $service->status ?? 'active'); @endphp
    <select name="status" class="form-select">
      <option value="active"   {{ $status==='active' ? 'selected' : '' }}>Active</option>
      <option value="inactive" {{ $status==='inactive' ? 'selected' : '' }}>Inactive</option>
    </select>
  </div>

  <div class="col-12">
    <label class="form-label">Description</label>
    <textarea name="description" rows="4" class="form-control">{{ old('description', $service->description ?? '') }}</textarea>
  </div>
</div>

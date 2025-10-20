@php
  $selectedServiceIds = old('services',
      $doctor->relationLoaded('services')
        ? $doctor->services->pluck('id')->toArray()
        : $doctor->services()->pluck('services.id')->toArray()
  );
@endphp

<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">Name</label>
    <input name="name" class="form-control" value="{{ old('name', $doctor->name) }}" required>
  </div>
  <div class="col-md-6">
    <label class="form-label">Email</label>
    <input type="email" name="email" class="form-control" value="{{ old('email', $doctor->email) }}">
  </div>
  <div class="col-md-6">
    <label class="form-label">Phone</label>
    <input name="phone" class="form-control" value="{{ old('phone', $doctor->phone) }}">
  </div>
  <div class="col-md-6">
    <label class="form-label">Specialty</label>
    <input name="specialty" class="form-control" value="{{ old('specialty', $doctor->specialty) }}">
  </div>
  <div class="col-12">
    <label class="form-label">Bio</label>
    <textarea name="bio" rows="3" class="form-control">{{ old('bio', $doctor->bio) }}</textarea>
  </div>
  <div class="col-md-6">
    <label class="form-label">Photo Path</label>
    <input name="photo_path" class="form-control" value="{{ old('photo_path', $doctor->photo_path) }}">
  </div>
  <div class="col-md-6 d-flex align-items-center">
    <div class="form-check mt-4">
      <input type="checkbox" class="form-check-input" name="active" value="1"
             {{ old('active', $doctor->active) ? 'checked' : '' }}>
      <label class="form-check-label">Active</label>
    </div>
  </div>

  <div class="col-12">
    <label class="form-label">Services (multi-select)</label>
    <select name="services[]" multiple class="form-select" size="6">
      @foreach($services as $id => $name)
        <option value="{{ $id }}" {{ in_array($id, $selectedServiceIds, true) ? 'selected' : '' }}>
          {{ $name }}
        </option>
      @endforeach
    </select>
    <small class="text-muted">Hold Ctrl/Cmd to select multiple</small>
  </div>
</div>

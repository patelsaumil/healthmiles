@php
  // Keep your selected services logic intact
  $selectedServiceIds = old('services',
      ($doctor ?? null)?->relationLoaded('services')
        ? $doctor->services->pluck('id')->toArray()
        : (($doctor ?? null)?->exists
            ? $doctor->services()->pluck('services.id')->toArray()
            : [])
  );

  // Safe default for create
  $doctor = $doctor ?? new \App\Models\Doctor();
@endphp

@if ($errors->any())
  <div class="alert alert-danger">
    <ul class="mb-0">
      @foreach($errors->all() as $e)
        <li>{{ $e }}</li>
      @endforeach
    </ul>
  </div>
@endif

<div class="row g-3">
  {{-- Name --}}
  <div class="col-md-6">
    <label for="name" class="form-label fw-semibold">Full Name</label>
    <input id="name" name="name" class="form-control shadow-sm"
           value="{{ old('name', $doctor->name) }}" required>
    @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
  </div>

  {{-- Email --}}
  <div class="col-md-6">
    <label for="email" class="form-label fw-semibold">Email</label>
    <input id="email" type="email" name="email" class="form-control shadow-sm"
           value="{{ old('email', $doctor->email) }}">
    @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
  </div>

  {{-- Phone --}}
  <div class="col-md-6">
    <label for="phone" class="form-label fw-semibold">Phone</label>
    <input id="phone" name="phone" class="form-control shadow-sm"
           value="{{ old('phone', $doctor->phone) }}">
    @error('phone') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
  </div>

  {{-- Specialty --}}
  <div class="col-md-6">
    <label for="specialty" class="form-label fw-semibold">Specialty</label>
    <input id="specialty" name="specialty" class="form-control shadow-sm"
           value="{{ old('specialty', $doctor->specialty) }}">
    @error('specialty') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
  </div>

  {{-- Bio --}}
  <div class="col-12">
    <label for="bio" class="form-label fw-semibold">Bio</label>
    <textarea id="bio" name="bio" rows="3" class="form-control shadow-sm"
              placeholder="Short professional bio...">{{ old('bio', $doctor->bio) }}</textarea>
    @error('bio') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
  </div>

  {{-- Photo path + preview (text path as in your current design) --}}
  <div class="col-md-6">
    <label for="photo_path" class="form-label fw-semibold">Photo Path</label>
    <input id="photo_path" name="photo_path" class="form-control shadow-sm"
           value="{{ old('photo_path', $doctor->photo_path) }}">
    @error('photo_path') <div class="text-danger small mt-1">{{ $message }}</div> @enderror

    @php
      $photo = old('photo_path', $doctor->photo_path);
    @endphp
    @if($photo)
      <div class="mt-2">
        <img src="{{ Str::startsWith($photo, ['http://','https://']) ? $photo : asset('storage/'.$photo) }}"
             alt="Doctor photo preview" class="rounded border" style="max-width: 140px;">
      </div>
    @endif
  </div>

  {{-- Active --}}
  <div class="col-md-6 d-flex align-items-center">
    <div class="form-check mt-4">
      <input id="active" type="checkbox" class="form-check-input" name="active" value="1"
             {{ old('active', $doctor->active) ? 'checked' : '' }}>
      <label for="active" class="form-check-label fw-semibold">Active</label>
    </div>
  </div>

  {{-- Services multi-select --}}
  <div class="col-12">
    <label for="services" class="form-label fw-semibold">Services (multi-select)</label>
    <select id="services" name="services[]" multiple class="form-select shadow-sm" size="6">
      @foreach($services as $id => $name)
        <option value="{{ $id }}" {{ in_array($id, $selectedServiceIds, true) ? 'selected' : '' }}>
          {{ $name }}
        </option>
      @endforeach
    </select>
    <div class="form-text">Hold Ctrl/Cmd to select multiple services.</div>
    @error('services') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
  </div>
</div>

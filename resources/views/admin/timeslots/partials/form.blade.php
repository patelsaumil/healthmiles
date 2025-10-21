@if ($errors->any())
  <div class="alert alert-danger">
    <ul class="mb-0">
      @foreach($errors->all() as $e)
        <li>{{ $e }}</li>
      @endforeach
    </ul>
  </div>
@endif

@php
  $slot = $slot ?? new \App\Models\TimeSlot(['is_booked' => false]);
@endphp

<div class="row g-3">
  {{-- Slot Date --}}
  <div class="col-md-4">
    <label class="form-label fw-semibold">Date</label>
    <input type="date" name="slot_date" class="form-control shadow-sm"
           value="{{ old('slot_date', optional($slot->slot_date)->format('Y-m-d')) }}" required>
    @error('slot_date')
      <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
  </div>

  {{-- Start Time --}}
  <div class="col-md-4">
    <label class="form-label fw-semibold">Start Time</label>
    <input type="time" name="start_time" class="form-control shadow-sm"
           value="{{ old('start_time', optional($slot->start_time)->format('H:i')) }}" required>
    @error('start_time')
      <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
  </div>

  {{-- End Time --}}
  <div class="col-md-4">
    <label class="form-label fw-semibold">End Time</label>
    <input type="time" name="end_time" class="form-control shadow-sm"
           value="{{ old('end_time', optional($slot->end_time)->format('H:i')) }}" required>
    @error('end_time')
      <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror
  </div>

  {{-- Booked Checkbox --}}
  <div class="col-md-4 d-flex align-items-center">
    <div class="form-check mt-4">
      <input type="checkbox" name="is_booked" value="1" class="form-check-input"
             {{ old('is_booked', $slot->is_booked) ? 'checked' : '' }}>
      <label class="form-check-label fw-semibold">Mark as Booked</label>
    </div>
  </div>
</div>

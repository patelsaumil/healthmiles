@php
  $date = old('slot_date', optional($slot)->slot_date);
  $start = old('start_time', optional($slot)->start_time ? substr($slot->start_time,0,5) : '');
  $end = old('end_time', optional($slot)->end_time ? substr($slot->end_time,0,5) : '');
  $booked = old('is_booked', optional($slot)->is_booked);
@endphp

<div class="row g-3">
  <div class="col-md-4">
    <label class="form-label">Date</label>
    <input type="date" name="slot_date" class="form-control" value="{{ $date }}" required>
  </div>
  <div class="col-md-4">
    <label class="form-label">Start Time</label>
    <input type="time" name="start_time" class="form-control" value="{{ $start }}" required>
  </div>
  <div class="col-md-4">
    <label class="form-label">End Time</label>
    <input type="time" name="end_time" class="form-control" value="{{ $end }}" required>
  </div>
  <div class="col-12 form-check mt-2">
    <input class="form-check-input" type="checkbox" name="is_booked" value="1" id="is_booked" {{ $booked ? 'checked':'' }}>
    <label class="form-check-label" for="is_booked">Mark as booked</label>
  </div>
</div>

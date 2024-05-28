<div id="group-reservation-fields" class="bg-dark text-white shadow-lg border-0 rounded-lg p-4 mb-4">
    <form method="POST" action="{{ route('group_reservations.store') }}">
        @csrf
  
        <h3 class="text-white">Vytvoriť skupinovú rezerváciu</h3>
  
        <div class="form-group mb-3">
            <label for="start_time" class="text-white">Začiatok:</label>
            <input type="time" name="start_time" required class="form-control bg-secondary text-white border-0">
        </div>
  
        <div class="form-group mb-3">
            <label for="end_time" class="text-white">Koniec:</label>
            <input type="time" name="end_time" required class="form-control bg-secondary text-white border-0">
        </div>
  
        <div class="form-group mb-3">
            <label for="reservation_date" class="text-white">Dátum:</label>
            <input type="date" name="reservation_date" required class="form-control bg-secondary text-white border-0">
        </div>
  
        <div class="form-group mb-3">
            <label for="max_participants" class="text-white">Maximálny počet účastníkov:</label>
            <input type="number" name="max_participants" id="max_participants" required class="form-control bg-secondary text-white border-0">
        </div>
  
        <div class="form-group mb-3">
            <label for="room_id" class="text-white">Výber miestnosti:</label>
            <select name="room_id" id="room_id" class="form-control bg-secondary text-white border-0">
                @foreach ($rooms as $room)
                    <option value="{{ $room->id }}">{{ $room->name }}</option>
                @endforeach
            </select>
        </div>
  
        <button type="submit" class="btn btn-primary btn-block mt-3">Vytvoriť skupinovú rezerváciu</button>
    </form>
  </div>
  
  <style>
    .btn-primary {
        background-color: #007bff;
        border: none;
    }
  
    .btn-primary:hover {
        background-color: #0056b3;
    }
  
    .form-control {
        background-color: #495057;
        color: #ffffff;
        border: 1px solid #ced4da;
    }
  
    .form-control:focus {
        background-color: #495057;
        color: #ffffff;
        border-color: #80bdff;
    }
  
    .bg-dark {
        background-color: #343a40 !important;
    }
  
    .text-white {
        color: #ffffff !important;
    }
  
    .rounded-lg {
        border-radius: 15px !important;
    }
  
    .shadow-lg {
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
    }
  </style>
  
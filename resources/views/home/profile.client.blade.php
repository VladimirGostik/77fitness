<!-- profile.client.blade.php -->
<div>
    <h2>Client Profile</h2>
    <p>Name: {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
    <p>Email: {{ Auth::user()->email }}</p>

    <!-- Form for editing profile information -->
    <form method="post" action="{{ route('client.profile.update') }}">
        @csrf
        <!-- Include form fields for editing -->
        <!-- ... -->
        <button type="submit">Update Profile</button>
    </form>
</div>
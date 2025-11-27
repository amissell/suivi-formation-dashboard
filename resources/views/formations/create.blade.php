<form action="{{ route('formations.store') }}" method="POST">
    @csrf
    <input type="text" name="name" placeholder="Formation Name" required>
    <input type="text" name="trainer" placeholder="Trainer Name" required>
    <button type="submit">Add Formation</button>
</form>

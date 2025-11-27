<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Trainer</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($formations as $formation)
        <tr>
            <td>{{ $formation->name }}</td>
            <td>{{ $formation->trainer }}</td>
            <td>
                <a href="{{ route('formations.edit', $formation->id) }}">Edit</a>
                <form action="{{ route('formations.destroy', $formation->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

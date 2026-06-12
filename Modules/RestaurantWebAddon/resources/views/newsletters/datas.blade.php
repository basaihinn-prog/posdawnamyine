@foreach($newsletters as $newsletter)
    <tr>
        <td>{{ ($newsletters->currentPage() - 1) * $newsletters->perPage() + $loop->iteration }}</td>
        <td class="text-center">{{ $newsletter->user?->name }}</td>
        <td class="text-center">{{ $newsletter->email }}</td>
    </tr>
@endforeach

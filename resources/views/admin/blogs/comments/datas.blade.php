@foreach ($comments as $comment)
    <tr>
        <td class="w-60 checkbox table-single-content d-print-none">
            <label class="table-custom-checkbox">
                <input type="checkbox" name="ids[]" class="table-hidden-checkbox checkbox-item delete-checkbox-item multi-delete"
                    value="{{ $comment->id }}">
                <span class="table-custom-checkmark custom-checkmark"></span>
            </label>
        </td>
        <td>
            {{ $comments->firstItem() + $loop->index }}
        </td>
        <td>{{ $comment->name }}</td>
        <td>{{ $comment->email }}</td>
        <td>{{  Str::limit($comment->comment, 20, '...') }}</td>
        <td class="d-print-none">
            <div class="dropdown table-action">
                <button type="button" data-bs-toggle="dropdown">
                    <i class="far fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a href="{{ route('admin.comments.destroy',$comment->id) }}" class="confirm-action"
                            data-method="DELETE">
                            <i class="fal fa-trash-alt"></i>
                            {{ __('Delete') }}
                        </a>
                    </li>
                </ul>
            </div>
        </td>
    </tr>
@endforeach

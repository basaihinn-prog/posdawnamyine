@foreach ($faqs as $faq)
    <tr>
        <td class="w-60 checkbox table-single-content d-print-none">
        <label class="table-custom-checkbox">
            <input type="checkbox" name="ids[]" class="table-hidden-checkbox checkbox-item delete-checkbox-item multi-delete"
                value="{{ $faq->id }}">
            <span class="table-custom-checkmark custom-checkmark"></span>
        </label>
    </td>
        <td class="text-start">{{ $faqs->firstItem() + $loop->index }}</td>
        <td class="text-start">{{ $faq->question }}</td>
        <td class="text-start">{{Str::words($faq->answer, 10, '...') }}</td>
        <td class="text-center w-150">
            @can('features-update')
                <label class="switch">
                    <input type="checkbox" {{ $faq->status == 1 ? 'checked' : '' }} class="status"
                        data-url="{{ route('admin.faqs.status', $faq->id) }}">
                    <span class="slider round"></span>
                </label>
            @endcan
        </td>
        <td class="d-print-none">
            <div class="dropdown table-action">
                <button type="button" data-bs-toggle="dropdown">
                    <i class="far fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a href="{{ route('admin.faqs.edit', $faq->id) }}">
                            <i class="fal fa-pencil-alt"></i>
                            {{ __('Edit') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.faqs.destroy', $faq->id) }}" class="confirm-action"
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

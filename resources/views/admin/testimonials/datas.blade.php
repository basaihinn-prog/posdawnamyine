@foreach ($testimonials as $testimonial )
<tr>
    <td class="w-60 checkbox table-single-content d-print-none">
        <label class="table-custom-checkbox">
            <input type="checkbox" name="ids[]" class="table-hidden-checkbox checkbox-item delete-checkbox-item multi-delete"
                value="{{ $testimonial->id }}">
            <span class="table-custom-checkmark custom-checkmark"></span>
        </label>
    </td>
    <td class="text-start">{{ $testimonials->firstItem() + $loop->index }}</td>
    <td>
        @for ($i = 0; $i < 5; $i++)
            <i @class(['fas fa-star ', 'text-warning' => $testimonial->star > $i])></i>
        @endfor
    </td>
    <td>{{ $testimonial->client_name }}</td>
    <td>{{ $testimonial->work_at }}</td>
    <td>
        <img class="table-img" src="{{ asset($testimonial->client_image ?? 'assets/img/icon/no-image.svg') }}" alt="img">
    </td>
    <td class="d-print-none">
        <div class="dropdown table-action">
            <button type="button" data-bs-toggle="dropdown">
                <i class="far fa-ellipsis-v"></i>
            </button>
            <ul class="dropdown-menu">
                <li>
                    <a href="{{ route('admin.testimonials.edit',$testimonial->id) }}">
                        <i class="fal fa-pencil-alt"></i>
                        {{ __('Edit') }}
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.testimonials.destroy', $testimonial->id) }}" class="confirm-action" data-method="DELETE">
                        <i class="fal fa-trash-alt"></i>
                        {{ __('Delete') }}
                    </a>
                </li>
            </ul>
        </div>
    </td>
</tr>
@endforeach

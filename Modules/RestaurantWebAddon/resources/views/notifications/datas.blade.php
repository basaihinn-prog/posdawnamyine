@foreach ($notifications  as $notification)
    <tr>
        <td>{{ $loop->index+1 }}</td>
        <td class="text-start">{{ $notification->data['message'] ?? '' }}</td>
        <td class="text-start">{{ formatted_date($notification->created_at, 'd M Y - H:i A') }}</td>
        <td class="text-start">{{ formatted_date($notification->read_at, 'd M Y - H:i A') }}</td>
        <td class="d-print-none">
            <div class="d-flex align-items-center justify-content-end">
                <div class="icon-buttons">
                    @usercan('notification.view')
                    <a title="{{__('View')}}" data-bs-placement="top" href="{{ route('business.notifications.mtView', $notification->id) }}" class="action-btn view">
                        <svg width="25" height="24" viewBox="0 0 25 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <g clip-path="url(#clip0_4004_55660)">
                                <path d="M12.5 5C16.6818 5 20.264 9.01321 21.7572 10.9622C22.2314 11.5813 22.2314 12.4187 21.7572 13.0378C20.264 14.9868 16.6818 19 12.5 19C8.31818 19 4.73593 14.9868 3.24278 13.0378C2.76851 12.4187 2.76852 11.5813 3.24278 10.9622C4.73593 9.01321 8.31818 5 12.5 5Z" fill="#979797" />
                                <path d="M15.5 12C15.5 10.3431 14.1569 9 12.5 9C10.8431 9 9.5 10.3431 9.5 12C9.5 13.6569 10.8431 15 12.5 15C14.1569 15 15.5 13.6569 15.5 12Z" stroke="white" stroke-width="1.25" />
                            </g>
                            <defs>
                                <clipPath id="clip0_4004_55660">
                                    <rect x="0.5" width="24" height="24" rx="4" fill="white" />
                                </clipPath>
                            </defs>
                        </svg>
                    </a>
                    @endusercan
                </div>
            </div>
        </td>
    </tr>
@endforeach

@php
    $flashMessages = [
        ['key' => 'success', 'type' => 'success'],
        ['key' => 'status', 'type' => 'success'],
        ['key' => 'warning', 'type' => 'warning'],
        ['key' => 'error', 'type' => 'danger'],
        ['key' => 'fail', 'type' => 'danger'],
    ];

    $fallbackColors = [
        'success' => ['bg' => '#d1e7dd', 'border' => '#badbcc', 'text' => '#0f5132'],
        'warning' => ['bg' => '#fff3cd', 'border' => '#ffecb5', 'text' => '#664d03'],
        'danger' => ['bg' => '#f8d7da', 'border' => '#f5c2c7', 'text' => '#842029'],
        'info' => ['bg' => '#cff4fc', 'border' => '#b6effb', 'text' => '#055160'],
    ];
@endphp

@foreach ($flashMessages as $item)
    @if (session()->has($item['key']))
        @php
            $value = session()->get($item['key']);
            if ($item['key'] === 'status' && $value === 'verification-link-sent') {
                $value = 'A new verification link has been sent to your email address.';
            }
            $colors = $fallbackColors[$item['type']] ?? $fallbackColors['info'];
        @endphp
        <div class="alert alert-{{ $item['type'] }} alert-dismissible fade show" role="alert" style="padding:0.75rem 1rem; margin-bottom:1rem; border:1px solid {{ $colors['border'] }}; border-radius:6px; background:{{ $colors['bg'] }}; color:{{ $colors['text'] }};">
            <div class="alert-body">{{ $value }}</div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="background:transparent;border:0;float:right;font-size:1.25rem;line-height:1;opacity:.7;">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
@endforeach

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="padding:0.75rem 1rem; margin-bottom:1rem; border:1px solid {{ $fallbackColors['danger']['border'] }}; border-radius:6px; background:{{ $fallbackColors['danger']['bg'] }}; color:{{ $fallbackColors['danger']['text'] }};">
        <div class="alert-body">
            <ul class="mb-0" style="margin:0; padding-left:1.25rem;">
                @foreach ($errors->all() as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        </div>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close" style="background:transparent;border:0;float:right;font-size:1.25rem;line-height:1;opacity:.7;">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

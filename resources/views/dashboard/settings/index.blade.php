@extends('layout.app')

@section('content')
<div class="content-header row">
    <div class="content-header-left col-md-9 col-12 mb-2">
        <div class="row breadcrumbs-top">
            <div class="col-12">
                <h2 class="content-header-title float-left mb-0">Settings</h2>
                <div class="breadcrumb-wrapper">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Settings
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="content-body">
    <div class="row">
        <div class="col-12">
            @include('partials.alert')
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">General Settings</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('dashboard.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        @foreach($settings as $group => $groupSettings)
                            <div class="divider divider-left">
                                <div class="divider-text">{{ ucfirst($group) }}</div>
                            </div>
                            
                            @foreach($groupSettings as $setting)
                                <div class="form-group">
                                    <label for="{{ $setting->key }}">{{ ucwords(str_replace('_', ' ', $setting->key)) }}</label>
                                    
                                    @if($setting->type == 'textarea')
                                        <textarea class="form-control" id="{{ $setting->key }}" name="{{ $setting->key }}" rows="3">{{ $setting->value }}</textarea>
                                    @elseif($setting->type == 'number')
                                        <input type="number" class="form-control" id="{{ $setting->key }}" name="{{ $setting->key }}" value="{{ $setting->value }}">
                                    @elseif($setting->type == 'email')
                                        <input type="email" class="form-control" id="{{ $setting->key }}" name="{{ $setting->key }}" value="{{ $setting->value }}">
                                    @elseif($setting->type == 'boolean')
                                        <div class="custom-control custom-switch custom-switch-primary">
                                            <input type="hidden" name="{{ $setting->key }}" value="0">
                                            <input type="checkbox" class="custom-control-input" id="{{ $setting->key }}" name="{{ $setting->key }}" value="1" {{ $setting->value == '1' ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="{{ $setting->key }}">
                                                <span class="switch-icon-left"><i data-feather="check"></i></span>
                                                <span class="switch-icon-right"><i data-feather="x"></i></span>
                                            </label>
                                        </div>
                                    @elseif($setting->type == 'image')
                                        @if($setting->value)
                                            <div class="mb-1">
                                                <img src="{{ asset(str_replace('public/', 'storage/', $setting->value)) }}" alt="Site Logo" style="max-height: 100px;">
                                            </div>
                                        @endif
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="{{ $setting->key }}" name="{{ $setting->key }}">
                                            <label class="custom-file-label" for="{{ $setting->key }}">Choose file</label>
                                        </div>
                                    @else
                                        <input type="text" class="form-control" id="{{ $setting->key }}" name="{{ $setting->key }}" value="{{ $setting->value }}">
                                    @endif
                                </div>
                            @endforeach
                        @endforeach

                        <div class="col-12 mt-2">
                            <button type="submit" class="btn btn-primary mr-1">Save Changes</button>
                            <button type="reset" class="btn btn-outline-secondary">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@if (session()->has('error'))
<div class="alert alert-danger" role="alert">
    <div class="alert-body">
    {{session()->get('error')}}
    </div>
</div>
@endif
@if (session()->has('success'))
<div class="alert alert-success" role="alert">
    <div class="alert-body">
    {{session()->get('success')}}  
    </div>
</div>
@endif
@if (session()->has('warning'))
<div class="alert alert-warning" role="alert">
    <div class="alert-body">
    {{session()->get('warning')}}  
    </div>
</div>
@endif
@if (session()->has('fail'))
<div class="alert alert-danger" role="alert">
    <div class="alert-body">
    {{session()->get('fail')}}
    </div>
</div>
@endif
@if ($errors->count() > 0)
<div class="alert alert-danger" role="alert">
    <div class="alert-body">

   @foreach ($errors->all() as $message)
          {{$message}} <br>
   @endforeach

   </div>
</div>

@endif

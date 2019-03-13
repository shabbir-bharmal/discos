    
@if (Session::get('message', '') != '')

  <div class="alert alert-success">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      {{Session::get('message', '')}}
  </div>

@endif

{{ '';#dd(Session::get('error')) }}
        
@if (Session::get('error', '') != '')

@foreach (Session::get('error', '')->all('<span>:message</span>') as $message)
  <div class="alert alert-danger">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      {{$message}}
  </div>
@endforeach

@endif
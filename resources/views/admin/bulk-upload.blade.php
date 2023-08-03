@extends('layouts.app')

@section('content')
<style>
.invalid-feedback {
    color: red;
}
   #errorTextarea {
        width: 100%;
        height: 200px; /* Set an initial height (you can change it to your desired height) */
        overflow: auto; /* Add scrollbars when content exceeds the textarea's dimensions */
        resize: vertical; /* Allow vertical resizing (optional) */
    }
      .footer {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 250px;
  }
</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">


            <h3>{{ __('Bulk Upload Users') }}</h3>

            <div>
                <textarea id="errorTextarea" rows="10" readonly hidden></textarea>
            </div>

            <div>
                <form method="POST" action="{{ route('uploadUser') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group row">
                        <label class="control-label col-lg-4 col-sm-3">Sample File</label>
                        <div class="no_spacing_left col-lg-8 col-sm-9">
                            <a href="{{ url('public/uploads/user-file-sample.csv') }}" target="_blank" rel="noopener">Click here to download</a>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="user_list" class="col-md-4 col-form-label text-md-right">{{ __('Select File:') }}<p><small style="font-weight: 100">Note: Only .csv files are allowed.</small></p></label>
                                
                        <div class="col-md-6">
                            <input id="user_list" type="file" class="form-control @error('user_list') is-invalid @enderror"
                                name="user_list">
                            @error('user_list')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

           

                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" name="create" class="btn btn-primary">
                                {{ __('Upload') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection


@section('footer_script')
<script>
@if(Session::has('success'))
M.toast({html: '{{Session::get('success')}}', classes: 'rounded'});
@endif

@if(Session::has('error'))
    let errorMessage = {!! json_encode(Session::get('error')) !!};
    let errorTextarea = document.getElementById('errorTextarea');
    errorTextarea.value = errorMessage;
    errorTextarea.removeAttribute('hidden'); 
@endif
</script>
@endsection

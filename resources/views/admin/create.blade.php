@extends('layouts.app')

@section('content')
<style>
.invalid-feedback {
    color: red;
}
</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">


            <h3>{{ __('Create New User') }}</h3>

            <div>
                <form method="POST" action="{{ route('createUser') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email"
                            class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" required autocomplete="email">

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="dob" class="col-md-4 col-form-label text-md-right">{{ __('Date Of Birth') }}</label>

                        <div class="col-md-6">
                            <input id="dob" type="date" class="form-control @error('dob') is-invalid @enderror"
                                name="dob" value="{{ old('dob') }}" required autocomplete="dob" autofocus>

                            @error('dob')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="doj" class="col-md-4 col-form-label text-md-right">{{ __('Date Of Joining') }}</label>

                        <div class="col-md-6">
                            <input id="doj" type="date" class="form-control @error('doj') is-invalid @enderror"
                                name="doj" value="{{ old('doj') }}" required autocomplete="doj" autofocus>

                            @error('doj')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="picture" class="col-md-4 col-form-label text-md-right">{{ __('Picture') }}</label>

                        <div class="col-md-6">
                            <input id="picture" type="file" class="form-control @error('picture') is-invalid @enderror"
                                name="picture" value="{{ old('picture') }}" required autofocus onchange="previewImage(event)" accept=".jpeg, .png, .jpg">
                            <img id="preview" src="#" alt="Preview" style="max-width: 200px; display: none;">
 
                            @error('picture')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                     <div class="form-group row">
                        <div class="input-field col s12">
                            <select name="gender" id="gender">
                                <option value="" disabled selected>Choose gender</option>
                                <option value="male" {{ (old('gender') == 'male')? 'selected' : ''  }}>Male</option>
                                <option value="female" {{ (old('gender') == 'female')? 'selected' : ''  }}>Female</option>
                                <option value="others" {{ (old('gender') == 'others')? 'selected' : ''  }}>Others</option>
                            </select>
                            <label>Gender</label>
                            @error('gender')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                        <div class="col-md-6">
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password" required
                                autocomplete="new-password">

                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password-confirm"
                            class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                        <div class="col-md-6">
                            <input id="password-confirm" type="password" class="form-control"
                                name="password_confirmation" autocomplete="new-password">
                            @error('password_confirmation')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="input-field col s12">
                            <select name="user_type" id="user_type">
                                <option value="" disabled selected>Choose your option</option>
                                 @if(isAdmin())
                                <option value="1" {{ (old('user_type') == 1)? 'selected' : ''  }}>Admin</option>
                                @endif
                                <option value="2" {{ (old('user_type') == 2)? 'selected' : ''  }}>Manager</option>
                                <option value="3" {{ (old('user_type') == 3)? 'selected' : ''  }}>Employee</option>
                            </select>
                            <label>User Type</label>
                            @error('user_type')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                  <!--   @if(isAdmin())
                    <div class="form-group row permission">
                    <label class="col-md-4 col-form-label text-md-right"><h5>{{ __('Permission') }}</h5></label>
                            <hr/>
                        @foreach($permission_list as $key => $value)
                        <label class="col-md-4 col-form-label text-md-right"><p>{{ $key }}</label>
                             @foreach($value as $val)
                                <div class="col-md-6">
                                    <label>
                                        <input name="permission[]" value="{{$val['key']}}" type="checkbox" />
                                        <span>{{$val['name']}} ({{$val['description']}})</span>
                                    </label><br>
                                </div>
                             @endforeach 
                             <hr/>      
                        @endforeach
                    </div>
                    @else
                     <div class="form-group row permission">
                     <strong>Please contact admin to add Permission</strong>
                     </div>
                    @endif -->
                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" name="create" class="btn btn-primary">
                                {{ __('Create') }}
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
document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('select');
    var instances = M.FormSelect.init(elems, options = null);
});

$(document).ready(function(){
    $(".permission").hide();
    utype = $("#user_type").val();
    if(utype == 2)
    {
    $(".permission").show();
    }
    else
    {
    $(".permission").hide();
    }
    $("#user_type").change(function(){
        val = $(this).val();
        if(val == 2)
        {
            $(".permission").show();
        }
        else
        {
            $(".permission").hide();
        }
    })
})

function previewImage(event) {
        var input = event.target;
        var reader = new FileReader();

        reader.onload = function () {
            var preview = document.getElementById('preview');
            preview.src = reader.result;
            preview.style.display = 'block';
        };

        reader.readAsDataURL(input.files[0]);
}

@if(Session::has('success'))
M.toast({html: '{{Session::get('success')}}', classes: 'rounded'});
@endif
</script>
@endsection

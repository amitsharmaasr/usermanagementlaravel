@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div>
                <h2>Users</h2>
                <p> 
                <a href="{{url('/users/create')}}" class="btn btn-success">Create</a>
                @if(isAdmin())
		<!-- <a href="{{url('/permission')}}" class="btn btn-success">Permission</a> -->
		<span data-href="/user/users/export-csv" id="export" class="btn btn-success btn-sm" onclick ="exportTasks (event.target);">Export</span>
        <a href="{{url('/users/bulk-upload')}}" class="btn btn-success">Bulk Upload</a>
                @endif
                </p>
                <div>
                    <table class="table datatable" id="example">
                    <thead>
                    <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Gender</th>
                    <th>User Type</th>
                    <th>Picture</th>
                    <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                     @foreach($users as $user)
                        @if($user->user_type != 1)
                            <tr>
                            <td>{{$user->name}}</td>
                            <td>{{$user->email}}</td>
                            <td>{{$user->gender}}</td>
                            <td>{{(($user->user_type == 2) ? 'Manager' : 'Employee')}}</td>
                            <td><img src="{{url('public/avtars/'.$user->picture)}}" height="100" width="100" alt="no-profile-pic"></td>
                            <td>
                            @if(checkAdminPermission($user->id))
                            <a href="{{url('/users/getmail/'.$user->id)}}"><i class="small material-icons">outgoing_mail</i></a> 
                            <a href="{{url('/users/edit/'.$user->id)}}"><i class="small material-icons">border_color</i></a> 
                            @if(isAuthorized('can_delete_user'))
                            <a onclick="return confirm('Are you sure, you want to delete this user?')" href="{{url('/users/delete/'.$user->id)}}"><i class="small material-icons">delete_forever</i></a>
                            @endif
                            @endif
                             </td>
                            </tr>
                        @endif
                    @endforeach 
                    </tbody>
                  
                    </table>
                </div>
            </div>

    </div></div>
</div>
@endsection


@section('footer_script')
<script>

    @if(Session::has('success'))
     M.toast({html: '{{Session::get('success')}}', classes: 'rounded'});
    @endif

    function exportTasks(_this) {
      let _url = $(_this).data('href');
      window.location.href = _url;
    }

    $(document).ready(function() {
        let table = $('#example').DataTable({
            
    initComplete: function () {
      this.api().columns([0,1,2,3]).every(function () {
        let column = this;
        let title = $(column.header()).text();

        // Create input element
        let input = document.createElement('input');
        input.placeholder = 'Search ' + title;
        $(input).addClass('form-control form-control-sm');

        // Add the input element to the table header
        $(column.header()).append(input);

        // Event listener for user input
        $(input).on('keyup', function () {
          if (column.search() !== this.value) {
            column.search(this.value).draw();
          }
        });
      });
    }
  });
    });

  </script>
  @endsection

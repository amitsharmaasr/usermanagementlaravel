@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div>
                <h2>Inbox</h2>
                <div>
                    <table class="table datatable" id="example">
                    <thead>
                    <tr>
                        <th>Send Time</th>
                        <th>From</th>
                        <th>Subject</th>
                        <th>Body</th>
                   
                    </tr>
                    </thead>
                     <tbody>
                     @foreach($oMessage as $message)
                            <tr>                         
                            <td>{{date("Y-m-d H:i:s", strtotime($message->getDate()))}}</td>
                            <td>{{$message->getFrom()[0]->mail}}</td>
                            <td>{{$message->getSubject()}}</td>
                            <td>{{$message->getTextBody(true)}}</td>
                            </tr>
                     @endforeach
                    </tbody>
                   
                    </table>
                </div>
            </div>
            <?php   $paginator = $oMessage->paginate(); ?>
            {{ $paginator->links() }}
        </div>
    </div>
</div>
@endsection


@section('footer_script')
<script>
  @if(Session::has('success'))
     M.toast({html: '{{Session::get('success')}}', classes: 'rounded'});
  @endif

  $(document).ready(function () {
  let table = $('#example').DataTable({
    paging: false, // Enable pagination
    lengthMenu: [10, 25, 50, 100], // Customize "Show [Entries]" dropdown options
    pageLength: 10, // Default number of rows per page
    language: {
      lengthMenu: 'Show _MENU_ entries', // Customize "Show [Entries] entries" text
    }
  });
});
  </script>
  @endsection

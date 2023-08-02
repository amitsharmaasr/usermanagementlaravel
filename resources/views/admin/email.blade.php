@extends('layouts.app')
<style>
    .bordered-cell {
        border: 1px solid black;
    }
</style>
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
                        <th>Attachments</th>
                    </tr>
                    </thead>
                     <tbody>
                     @foreach($oMessage as $message)
                            <tr>                         
                            <td class="bordered-cell">{{date("Y-m-d H:i:s", strtotime($message->getDate()))}}</td>
                            <td class="bordered-cell">{{$message->getFrom()[0]->mail}}</td>
                            <td class="bordered-cell">{{$message->getSubject()}}</td>
                            <td class="bordered-cell">
                                {!! $message->getHTMLBody(true) !!}
                                  @foreach ($message->getAttachments() as $attachment)
                                @if ($attachment->getDisposition() === 'inline')
                                    <br>
                                    <img src="data:{{ $attachment->getType() }};base64,{{ base64_encode($attachment->getContent()) }}" alt="{{ $attachment->getName() }}">
                                @endif
                            @endforeach
                            </td>
                            <td class="bordered-cell">
                                    @if ($message->getAttachments()->count() > 0)
                                        <ul>
                                            @foreach ($message->getAttachments() as $attachment)
                                                <li><a href="{{ $attachment->getPath() }}" target="_blank">{{ $attachment->getName() }}</a></li>
                                            @endforeach
                                        </ul>
                                    @else
                                        No Attachments
                                    @endif
                            </td>
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

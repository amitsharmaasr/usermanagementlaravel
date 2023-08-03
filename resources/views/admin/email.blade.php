@extends('layouts.app')
<style>
    .bordered-cell {
        border: 1px solid black;
    }
    .table-wrapper {
      max-height: 100%; /* Set the desired height for the table */
      overflow-y: auto; /* Add vertical scroll if the content exceeds the max-height */
    }

    table {
      width: 1000px; /* Set the desired width for the table */
    }

    tbody {
      overflow-y: auto; /* Add vertical scroll if tbody content exceeds the max-height */
    }

</style>
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div>
                <h2>Inbox</h2>
                <div class="table-wrapper">
                    <table class="table datatable" id="example">
                    <thead>
                    <tr>
                        <th class="bordered-cell">Send Time</th>
                        <th class="bordered-cell">From</th>
                        <th class="bordered-cell">Subject</th>
                        <th class="bordered-cell">Body</th>
                        <th class="bordered-cell">Attachments</th>
                        <th class="bordered-cell">Reply</th>
                    </tr>
                    </thead>
                     <tbody>
                     @foreach($mails as $mail)
                            <tr>                         
                            <td class="bordered-cell">{{date("Y-m-d H:i:s", strtotime($mail['getDate']))}}</td>
                            <td class="bordered-cell">{{$mail['mail']}}</td>
                            <td class="bordered-cell">{{$mail['getSubject']}}</td>
                            <td class="bordered-cell">
                                {!! $mail['htmlBody']!!}
                                  @foreach ($mail['attachment'] as $attachment)
                                @if ($attachment->getDisposition() === 'inline')
                                    <br>
                                    <img src="data:{{ c }};base64,{{ base64_encode($attachment->getContent()) }}" alt="{{ $attachment->getName() }}">
                                @endif
                            @endforeach
                            </td>
                            <td class="bordered-cell">
                                    @if (count($mail['localAttachments']) > 0)
                                        <ul>
                                            @foreach ($mail['localAttachments'] as $attachment)
                                                <li><a href="{{url('public/mail_attachments/'.$attachment['name'])}}" target="_blank">{{ $attachment['name'] }}</a></li>
                                            @endforeach
                                        </ul>
                                    @else
                                        No Attachments
                                    @endif
                            </td>
                            <td class="bordered-cell"></td>
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

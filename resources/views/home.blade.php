@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="card">
            <div class="card-header">{{$title}}</div>

            <div class="card-body">
                <table class="tender-table">
                    @foreach($tenders as $tender)
                        <tr class="main" data-link="menu-{{$tender->id}}">
                            <td>
                                <a href='{{$tender->url}}' target="_blank">{{$tender->url}}</a>
                            </td>
                            <td>{{$tender->successTender->tender_name}}</td>
                            <td>до: {{$tender->end_date}}</td>
                        </tr>
                        @foreach($tender->successTender->lots as $lot)
                            <tr class="dropMenu menu-{{$tender->id}}">
                                <td colspan="3" class="lot">{{$lot->lot}}</td>
                            </tr >
                        @endforeach
                        <tr>
                            <td colspan="3" class="blank"></td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('tr.main').click(function() {
                let drops = $('.dropMenu.'+$(this).attr('data-link'));
                if (drops.first().css("display") == "none") {
                    drops.hide('normal');
                    drops.toggle('normal');
                } else drops.hide('normal');
            });
        });
    </script>
@endsection
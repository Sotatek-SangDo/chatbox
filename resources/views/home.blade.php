@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-primary">
                <div class="panel-heading">Chat Box</div>
                <div class="panel-body" style="height: 350px; overflow-y: auto" id="content">
                    <input type="hidden" id="auth" value="{{ auth()->user()->id }}">
                    @if(count($message))
                        @foreach($message as $val)
                            <div class="mess @if(auth()->user()->id == $val['user']['id']) own @endif">
                                <strong>@if($val['user']['id'] != auth()->user()->id) {{ $val['user']['name'] }} @else me @endif:</strong>
                                <p>{{ $val['content']}}</p>
                                <b class="position">{{ $val['date']}}</b>
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="panel-footer">
                    <div class="input-group">
                        <input type="text" class="form-control" id="input" placeholder="Enter your message...">
                        <input type="hidden" name="room_id" value="{{$id}}">
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button" id="send">Send</button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
    <style type="text/css">
        .mess {
            border: 1px solid #bfbfbf;
            padding: 3px 10px;
            background: #e0e0e0;
            color: #000;
            border-radius: 5px;
            box-shadow: 4px 4px 8px #999;
            position: relative;
            max-width: 22%;
            margin-left: 2%;
            margin-bottom: 15px;
        }
        .mess.own{
            margin-left: 75%;
            background: brown;
            color: #fff;
        }
        @media screen and (max-width: 768px) {
            .mess {
                max-width: 40%;
            }
            .mess strong {
                font-size: 10px;
            }
            .mess p {
                font-size: 11px;
            }
            .mess b {
                font-size: 10px
            }
            .position {
                bottom: 5% !important;
            }
            .mess.own {
                margin-left: 55%;
            }
        }
        .mess p{
            margin: 0;
            padding: 0;
        }
        .position {
            position: absolute;
            right: 1%;
            bottom: 30%;
            color: #928979;
        }
    </style>
    <script type="text/javascript">
        $('#send').on('click', function () {
            let message = $('#input').val();
            let room_id = $('input[name="room_id"]').val();
            $('#input').val('');
            let date = new Date().toLocaleString('vi-VI', { hour12: false })
            if (message != '') {
                $.ajax({
                    url: 'chatroom',
                    type: 'POST',
                    dataType: 'json',
                    data: {message: message, date: date, room_id: room_id},
                    success: function (response) {
                        console.log(response)
                    }
                });
            }
        });
        window.Echo.private('chatroom').listen('ChatEvent', (e) => {
            let room_id = $('input[name="room_id"]').val();
            if(e.message.chat_room_id == room_id) {
                let auth = $('#auth').val();
                let html = `<div class="mess">
                                <strong>` + ((auth != e.user.id) ? e.user.name : 'me') + `:</strong>
                                <p>${e.message.content}</p>
                                <b class="position">${e.message.date}</b>
                                <input type="hidden" id="user" value="${e.user.id}">
                            </div>
                            `;
                $('#content').append(html);
                addOwnClass(auth);
            }
        });
        function addOwnClass(auth) {
            let lastMess = $('.mess:last-child');
            let userId = $(lastMess).find('#user').val();
            if(auth == userId) {
                $(lastMess).addClass('own');
            }
        }
    </script>
@endsection

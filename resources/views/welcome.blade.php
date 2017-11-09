<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
            .modal-body {
                min-height: 170px;
            }
            button.btn.btn-primary {
                margin-top: 10px;
            }
            span.alert {
                color: #000;
                margin-top: 15px;
            }
            .col-md-6,
            label {
                margin-top: 10px;
            }
        </style>
    </head>
    <body>
    <div id="app">
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                        <a href="{{ route('register') }}">Register</a>
                    @endauth
                </div>
            @endif

            <div class="content">
                <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#create">Add room</button>
                <div class="title m-b-md">
                    Chat box
                </div>
                <div class="create">
                    <!-- Modal -->
                  <div class="modal fade" id="create" role="dialog">
                    <div class="modal-dialog">

                      <!-- Modal content-->
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                          <h4 class="modal-title">Add room</h4>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('add-room') }}" method="post">
                                {{ csrf_field() }}
                                <div class="form-group">
                                        <label for="name" class="col-md-4 control-label">Room Name</label>
                                        <div class="col-md-6">
                                            <input type="text" id="name" class="form-control" name="room" required>
                                        </div>
                                </div>
                                <div class="form-group">
                                    <label for="pass" class="col-md-4 control-label">Password</label>
                                    <div class="col-md-6">
                                        <input type="password" id="pass" class="form-control" name="password" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-8 col-md-offset-4">
                                        <button type="submit" class="btn btn-primary">
                                            Add
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                      </div>

                    </div>
                  </div>
                </div>
                <div class="links">
                    @foreach($chatRooms as $chatroom)
                        <a class="click" data-value="{{$chatroom['id']}}" data-toggle="modal">{{ $chatroom['room_name']}}</a>
                    @endforeach
                </div>
            <!-- Modal -->
            <div class="modal fade" id="myModal" role="dialog">
                <div class="modal-dialog">
                  <!-- Modal content-->
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Enter pass</h4>
                    </div>
                    <div class="modal-body">
                        <form action="check-room" class="join" method="post">
                            <div class="form-group">
                                <label for="password" class="col-md-4 control-label">Password</label>
                                <div class="col-md-6">
                                    <input type="password" id="password" class="form-control" name="password" required>
                                </div>
                            </div>
                            <input type="hidden" name="id" id="room-id">
                            <div class="form-group">
                                <div class="col-md-8 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        OK
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-8" style="display: none;">
                                <span class="alert alert-danger"></span>
                            </div>
                        </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>
    </body>
    <script src="{{ asset('js/app.js') }}"></script>
    <script type="text/javascript">
    $(function() {
        $('a.click').click(function(e) {
            e.preventDefault();
            let room_id = $(this).attr('data-value');
            $('#room-id').val(room_id);
            $('#myModal').modal();
        })
        $('form.join').submit(function(e) {
            e.preventDefault();
            let url = $(this).attr('action');
            let id =  $('#room-id').val();
            let password = $('#password').val();
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                data: {pass: password, room_id: id},
                success: function (response) {
                    if(!response.status) {
                        $('#password').val('')
                        $('span.alert').html(response.mess);
                        $('span.alert').parent().show().hide(3000);
                    } else {
                        console.log(response)
                        window.location.href = response.url;
                    }
                }
            });
        })
    })
    </script>

</html>

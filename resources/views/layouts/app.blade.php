<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        ul{
            margin: 0;
            padding: 0;
        }

        li{
            list-style: none;
        }
        .user-wrapper, .message-wrapper{
            border: 1px solid  #dddddd;
            overflow-y: auto;
        }

        .user-wrapper {
            height: 500px;
        }

        .user{
            cursor: pointer;
            padding: 5px 0;
            position: relative;
        }

        .user:hover{
            background: #eeeeeee;
        }

        .user: last-child{
            margin-bottom: 0;
        }

        .name{
            margin-top: 4px;
        }

        .pending{
            position: absolute;
            left: 365px;
            top: 25px;
            background: #25d366;
            margin: 0;
            border-radius: 50%;
            width: 25px;
            height: 25px;
            line-height: 10px;
            padding-top:6px;
            padding-left:7px;
            color: #ffffff;
            font-size: 12px;
            border: 2px solid #eeeeee;
        }

        .media-left{
            margin: 0 10px;
        }

        .media-left img {
            width: 64px;
            border-radius: 64px;
        }

        .media-body p{
            padding: 6px 0;
        }

        .message-wrapper{
            padding: 10px;
            height: 500px;
            background-image: url("../img/wa-background.png");
        }

        .messages .message{
            margin-bottom: 15px;
        }

        .messages .message:last-child{
            margin-bottom: 0;
        }

        .received .sent{
            width: 45px;
            padding: 3px 10px;
            border-radius: 10px;
        }

        .received{
            background: #ffffff;
            border-radius: 10px;
        }

        .sent{
            background:#dcf8c6;
            float: right;
            text-align: right;
            border-radius: 10px;
        }
        .message p{
            padding: 10px;
            max-width: 300px;
        }

        .date{
            color: #999999;
            font-size: 0.75em;
        }

        .active{
            background: #eeeeee;
        }

        input[type=text]{
            width: 100%;
            padding: 12px 20px;
            margin: 15px 0 0 0;
            display: inline-block;
            border-radius: 4px;
            box-sizing: border-box;
            outline: none;
            border: 1px solid #cccccc;
        }

        input[type=text]:focus{
            border: 1px solid #aaaaaa;
        }

    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <script src="https://js.pusher.com/5.1/pusher.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script>
    var receiver_id = '';
    var my_id = "{{ Auth::id() }}";
    $(document).ready(function () {
        // ajax setup form csrf token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;
        var pusher = new Pusher('9a3998ae1d58a1003125', {
            cluster: 'ap2',
            forceTLS: true
        });
        var channel = pusher.subscribe('my-channel');
        channel.bind('my-event', function (data) {
            // alert(JSON.stringify(data));
            if (my_id == data.from) {
                $('#' + data.to).click();
            } else if (my_id == data.to) {
                if (receiver_id == data.from) {
                    // if receiver is selected, reload the selected user ...
                    $('#' + data.from).click();
                } else {
                    // if receiver is not seleted, add notification for that user
                    var pending = parseInt($('#' + data.from).find('.pending').html());
                    if (pending) {
                        $('#' + data.from).find('.pending').html(pending + 1);
                    } else {
                        $('#' + data.from).append('<span class="pending">1</span>');
                    }
                }
            }
        });
        $('.user').click(function () {
            $('.user').removeClass('active');
            $(this).addClass('active');
            $(this).find('.pending').remove();
            receiver_id = $(this).attr('id');
            $.ajax({
                type: "get",
                url: "message/" + receiver_id, // need to create this route
                data: "",
                cache: false,
                success: function (data) {
                    $('#messages').html(data);
                    scrollToBottomFunc();
                }
            });
        });
        $(document).on('keyup', '.input-text input', function (e) {
            var message = $(this).val();
            // check if enter key is pressed and message is not null also receiver is selected
            if (e.keyCode == 13 && message != '' && receiver_id != '') {
                $(this).val(''); // while pressed enter text box will be empty
                var datastr = "receiver_id=" + receiver_id + "&message=" + message;
                $.ajax({
                    type: "post",
                    url: "message", // need to create this post route
                    data: datastr,
                    cache: false,
                    success: function (data) {
                    },
                    error: function (jqXHR, status, err) {
                    },
                    complete: function () {
                        scrollToBottomFunc();
                    }
                })
            }
        });
    });
    // make a function to scroll down auto
    function scrollToBottomFunc() {
        $('.message-wrapper').animate({
            scrollTop: $('.message-wrapper').get(0).scrollHeight
        }, 50);
    }
    </script>
</body>
</html>

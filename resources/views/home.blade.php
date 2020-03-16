@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row">

        <div class="col-md-4">
            <div class="user-wrapper">
                <ul class="users">
                    @foreach($users as $user)
                        <li class="user" id="{{ $user->id }}">
                        @if($user->unread)
                            <span class="pending">1</span>
                        @endif
                        <div class="media">
                            <div class="media-left">
                                <img src="https://via.placeholder.com/150" alt="" class="media-object">
                            </div>
                            <div class="media-body">
                                <p class="name">
                                    {{ $user->name }}
                                <br>
                                    <small>
                                        {{ $user->email }}
                                    </small>
                                </p>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="col-md-6" id="messages">
        
        </div>
  </div>
</div>
@endsection

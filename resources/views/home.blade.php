@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="col-md-4">
      <div class="user-wrapper">
          <ul class="users">
              <li class="user">
                  <span class="pending">1</span>
                  <div class="media">
                      <div class="media-left">
                          <img src="https://via.placeholder.com/150" alt="" class="media-object">
                      </div>
                      <div class="media-body">
                          <p class="name">
                            Satrio Utomo
                          </p>
                          <p class="email">
                            satrio@abc.com
                          </p>
                      </div>
                  </div>
              </li>
          </ul>
      </div>
  </div>
</div>
@endsection

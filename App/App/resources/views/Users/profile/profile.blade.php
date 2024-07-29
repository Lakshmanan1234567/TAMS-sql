@extends('layouts.layout')
@section('content')
  <!-- Container-fluid starts-->
  <div class="container-fluid">
            <div class="user-profile">
              <div class="row">
                <!-- user profile first-style start-->
                <div class="col-sm-12">
                  <div class="card hovercard text-center">
                    <div class="col-12" style="margin-top: 5%"></div>
                    <div class="cardheader"></div>
                    <div class="user-image">
                      <div class="avatar"><img class="img-fluid rounded-circle oneIsone" alt="User-Image" src="{{url('/')}}/{{$UInfo->ProfileImage}}"></div><a class="icon-wrapper" href="{{url('/')}}/users-and-permissions/UpdateProfile"><i class="icofont icofont-pencil-alt-5"></i></a>
                    </div>
                    <div class="info">
                      <div class="row">
                        <div class="col-sm-6 col-lg-4 order-sm-1 order-xl-0">
                          <div class="row">
                            <div class="col-md-6">
                              <div class="ttl-info text-center">
                                <h6>&nbsp;<i class="fa fa-envelope"></i> Email</h6><span>{{$UInfo->EMail}}</span>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="ttl-info text-center">
                                <h6><i class="fa fa-calendar"></i>BOD</h6><span>@if($UInfo->DOB!=null){{date("d M Y",strtotime($UInfo->DOB))}}@endif</span>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-sm-12 col-lg-4 order-sm-0 order-xl-1">
                          <div class="user-designation">
                            <div class="title"><a class="icon-wrapper" href="{{url('/')}}/users-and-permissions/UpdateProfile">{{$UInfo->Name}}</a></div>
                            <div class="desc mt-2">{{$UInfo->RoleName}}</div>
                          </div>
                        </div>
                        <div class="col-sm-6 col-lg-4 order-sm-2 order-xl-2">
                          <div class="row">
                            <div class="col-md-6">
                              <div class="ttl-info text-center">
                                <h6><i class="fa fa-phone"></i>   Contact Us</h6><span>+{{$UInfo->PhoneCode}} {{$UInfo->MobileNumber}}</span>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="ttl-info text-center">
                                <h6><i class="fa fa-location-arrow"></i> Location</h6><span>
                                {{$UInfo->StateName}}<br>
                                {{$UInfo->CityName}}
                            </span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <hr>
                      <div class="social-media">
                        <ul class="list-inline">
                          <li class="list-inline-item"><a href="https://www.facebook.com/login/"><i class="fa fa-facebook"></i></a></li>
                          <li class="list-inline-item"><a href="https://myaccount.google.com/"><i class="fa fa-google-plus"></i></a></li>
                          <li class="list-inline-item"><a href="https://twitter.com/i/flow/login"><i class="fa fa-twitter"></i></a></li>
                          <li class="list-inline-item"><a href="https://www.instagram.com/accounts/login/"><i class="fa fa-instagram"></i></a></li>
                          <li class="list-inline-item"><a href="https://rss.app/signin"><i class="fa fa-rss"></i></a></li>
                        </ul>
                      </div>
                      </div>
                      </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- user profile first-style end-->
@endsection
@extends('pos.layouts.master')

@section('content')
    <div style="margin-top: 20px;">
        <h4><b>HI, {{ auth()->user()->name }} <span>Welcome to POS Dashboard</span></b></h4>
        <hr>
    </div>
    <div class="row" style="margin-top: 20px;">
        <div class="col-md-12" style="margin-top: 15px;">
            <div class="card illustration flex-fill">
                <div class="card-body p-0 d-flex flex-fill">
                    <div class="row g-0 w-100">
                        <div class="col-6">
                            <div class="">
                                <span><img style="height: 50px; width: 50px;margin-left: 20px;margin-top: 20px;"
                                        src="{{ asset('images/' . auth()->user()->image) }}" alt=""
                                        class="thumb-lg rounded-circle"></span>
                                <h4 class="illustration-text" style="color: #062962;margin-left: 81px;margin-top: -33px;">
                                    <b>{{ auth()->user()->name }}
                                        ({{ auth()->user()->user_id }})</b>
                                </h4>
                                @php
                                    $roles = auth()->user()->getRoleNames()->toArray();
                                @endphp
                                {{-- <p class="mb-0">{{ implode(' ', $roles) }}</p> --}}
                            </div>
                        </div>
                        <div class="col-6 align-self-end text-end">
                            {{-- <a href="{{ route('frontend.index') }}"><button class="btn btn-warning"><span>Shop
                                        Now</span></button></a> --}}

                            <img src="{{ asset('images/admin/customer-support.png') }}" alt="Customer Support"
                                class="img-fluid illustration-img">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

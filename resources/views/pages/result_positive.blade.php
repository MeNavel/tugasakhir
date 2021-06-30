@extends('layouts.app', ['title' => __('User Profile')])

@section('content')
    @include('users.partials.header', [
        // 'title' => __('Halo') . ' '. auth()->user()->name,
        'description' => __('Berikut Hasil Deteksi Menggunakan Progam PYTHON'),
        'class' => 'col-lg-12'
    ])   

    <div class="container-fluid mt--7">
        <div class="row justify-content-md-center">
            <div class="col-xl-4 order-xl-0 mb-5 mb-xl-0">
                <div class="card card-profile shadow">
                    <div class="row justify-content-center">
                        <div class="col-lg-3 order-lg-2">
                            <div class="card-profile-image">
                                <a href="#">
                                    <img src="{{ asset('storage') }}/{{ $foto }}" class="center-cropped rounded-circle">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0 mt-8 pt-md-4">
                        <div class="row">
                            <div class="col">
                            </div>
                        </div>
                        <div class="text-center">
                            <div>
                                <i class="ni education_hat mr-2"></i>{{ $result }}
                            </div>
                            <div>
                                Terdeteksi Pada<i class="ni education_hat mr-2"></i>{{ $tgl }}
                            </div>
                            <hr class="my-4" />
                            <p>{{ $info }}</p>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="col-xl-8 order-xl-2">
                <div class="card bg-secondary shadow">
                    
                    <div class="card-body">
                        <h2 class="text-muted mb-4">{{ __('Data Diri') }}</h2>

                        
                        <div class="card mb-3">
                            <div class="card-body">
                              <div class="row">
                                <div class="col-sm-3">
                                  <h3 class="mb-0">Nama Lengkap</h3>
                                </div>
                                <div class="col-sm-5">
                                    {{ $id->nama }}
                                </div>
                              </div>
                              <hr>
                              <div class="row">
                                <div class="col-sm-3">
                                  <h3 class="mb-0">Email</h3>
                                </div>
                                <div class="col-sm-5">
                                    {{ $id->email }}
                                </div>
                              </div>
                              <hr>
                              <div class="row">
                                <div class="col-sm-3">
                                  <h3 class="mb-0">Nomor Telfon</h3>
                                </div>
                                <div class="col-sm-5">
                                    {{ $id->hp }}
                                </div>
                              </div>
                              <hr>
                              <div class="row">
                                <div class="col-sm-3">
                                  <h3 class="mb-0">Akun Instagram</h3>
                                </div>
                                <div class="col-sm-5">
                                    {{ $id->instagram }}
                                </div>
                              </div>
                            </div>
                          </div>
                    </div>
                </div>
            </div> --}}
        </div>
        
        @include('layouts.footers.auth')
    </div>
@endsection

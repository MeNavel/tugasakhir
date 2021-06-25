@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    
    <div class="container-fluid mt--7">
        <div class="row">
            {{-- Deteksi Masker Menggunakan Image Capture--}}
            {{-- <div class="col-xl-12 mb-5 mb-xl-0">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">Deteksi Masker Menggunakan Foto dari Webcam</h3>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card body">
                        <div class="container">
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
                            <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>

                            <form method="POST" action="{{ route('predict.webcam') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div id="my_camera"></div>
                                        <br/>
                                        <a href="#!" class="btn btn-sm btn-primary" onclick="take_snapshot()">Capture</a>
                                        <input type="hidden" name="image" class="image-tag">
                                    </div>
                                    <div class="col-md-6">
                                        <div id="results">Hasil gambar akan ditampilkan disini...</div>
                                    </div>
                                    
                                </div>
                                <div class="row">
                                    <div class="col text-center">
                                        <button class="btn btn-success">Deteksi Gambar</button>
                                    </div>
                                </div>
                                <div class="mt-3"></div>
                            </form>

                            <script language="JavaScript">
                                Webcam.set({
                                    width: 490,
                                    height: 390,
                                    image_format: 'JPG',
                                    jpeg_quality: 100
                                });
                            
                                Webcam.attach( '#my_camera' );
                            
                                function take_snapshot() {
                                    Webcam.snap( function(data_uri) {
                                        $(".image-tag").val(data_uri);
                                        document.getElementById('results').innerHTML = '<img src="'+data_uri+'"/>';
                                    } );
                                }
                            </script>
                        </div>
                        
                    </div>
                </div>
            </div> --}}

            {{-- Deteksi Masker Menggunakan File --}}
            <div class="col-xl-12 mb-5 mb-xl-0 mt-3">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">Deteksi Masker Menggunakan File Yang Di Upload</h3>
                            </div>
                        </div>
                    </div>

                    <div class="card body">
                        {{-- <div class="card body"></div> --}}
                        <br/>
                        <div class="container">
                            <form action="{{  route('predict.file')  }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <input type="file" class="form-control" name="file" id="file">
                                </div>
                                <div class="row">
                                    <div class="col text-center">
                                        <button type="submit" class="btn btn-success">Deteksi File</button>
                                    </div>
                                </div>
                                <div class="mt-3"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.footers.auth')
    </div>
@endsection

@push('js')
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/chart.js/dist/Chart.extension.js"></script>
@endpush
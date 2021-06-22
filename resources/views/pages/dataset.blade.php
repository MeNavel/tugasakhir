@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    
    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-12 mb-5 mb-xl-0">
                <div class="card shadow">
                    <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col">
                                <h3 class="mb-0">Dataset Deteksi Masker</h3>
                            </div>
                        </div>
                    </div>

                    <div class="card body">
                        @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                        @endif
                        
                        <div class="table-responsive">
                            <table class="table align-items-center table-flush">
                                <thead class="thead-light">
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>No Telfon</th>
                                        <th>Alamat</th>
                                        <th>Instagram</th>
                                        <th>Kode</th>
                                    </tr>
                                </thead>
                                @foreach ($datasets as $dataset)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <th scope="row">{{ $dataset->nama }}</th>
                                    <td>{{ $dataset->email }}</td>
                                    <td>{{ $dataset->hp }}</td>
                                    <td>{{ $dataset->alamat }}</td>
                                    <td>{{ $dataset->instagram }}</td>
                                    <td>{{ $dataset->kode }}</td>
                                </tr>
                                @endforeach
                            </table>
                            <br/>{!! $datasets->links() !!}<br/>
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
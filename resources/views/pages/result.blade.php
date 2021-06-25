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
                                <h3 class="mb-0">Data Hasil Prediksi</h3>
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
                                        <th width="20px" class="text-center">No</th>
                                        <th>Nama</th>
                                        <th>Status</th>
                                        <th>Waktu Deteksi</th>
                                        {{-- <th class="text-center">Action</th> --}}
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                @foreach ($results as $result)
                                <tr>
                                    <td class="text-center">{{ ++$i }}</td>
                                    <th scope="row">{{ $result->nama }}</th>
                                    <td>{{ $result->status }}</td>
                                    <td>{{ $result->created_at }}</td>
                                    <td width="20px" class="text-center">
                                        <a class="btn btn-primary btn-sm" href="{{ route('result.show', $result->id) }}">Lihat</a>
                                    </td>
                                    <td width="20px">
                                        <form action="{{ route('result.destroy', $result->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                            <br/>{!! $results->links() !!}<br/>
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
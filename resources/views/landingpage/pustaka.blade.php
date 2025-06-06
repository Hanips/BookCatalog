@extends('landingpage.index')
@section('content')
<br><br><br><br><br>
<div class="container-lg py-5">
    <div class="container">
        <div class="container">
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a class="text-body" href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item text-dark active" aria-current="page">Pustaka</li>
                </ol>
            </nav>
        </div>
        <br><br>
        <div class="row g-5 justify-content-center">
            <div class="col-lg-3 col-md-12 wow fadeInUp text-dark d-flex flex-column h-100 p-3 shadow" data-wow-delay="0.1s" style="border-radius: 10px;">
                <span><b>Filter</b></span>
                <br>
            </div>
            
            <div class="col-lg-9 col-md-12 wow fadeInUp" data-wow-delay="0.5s">
                <div class="col-lg-12 text-start text-lg-start wow slideInRight" data-wow-delay="0.1s">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <form action="{{ route('pustaka') }}" method="GET" class="d-flex align-items-center me-2">
                            <input type="text" class="form-control me-2" name="search" placeholder="Cari judul buku" value="{{ $search }}" style="border-radius: 10px;">
                            <button type="submit" class="btn btn-primary rounded-pill-custom" style="border-radius: 10px;">Cari</button>
                        </form>
                        <div class="nav nav-pills d-inline-flex align-items-center">
                            <span class="me-2"><b>Urut Berdasarkan:</b></span>
                            <!-- Select Option for Sorting -->
                            <form action="{{ route('pustaka') }}" method="GET">
                                <div class="input-group">
                                    <select id="sorting" class="form-select shadow" onchange="this.form.submit()" name="urutan" style="border-radius: 10px;">
                                        <option value="terlama" {{ $urutan == 'terlama' ? 'selected' : '' }}>Terlama</option>
                                        <option value="terbaru" {{ $urutan == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row g-4">
                    <!-- Book Listing -->
                    @foreach ($buku_terpilih as $buku)
                        <div class="col-xl-3 col-lg-2 col-md-4 col-sm-6 wow fadeInUp" data-wow-delay="0.1s">
                            {{-- <a href="{{ route('ebook.read', $buku->buku_id) }} target="_blank" class="btn btn-primary"> --}}
                            <a href="">
                                <div class="product-item shadow" style="border-radius: 5px;">
                                    <div class="position-relative bg-light overflow-hidden" style="border-radius: 5px;">
                                        @empty($buku->buku_foto)
                                            <div class="image-container">
                                                <img src="{{ url('landingpage/img/nophoto.jpg') }}" class="img-fluid" alt="Foto e-book" style="object-fit: cover; width: 100%; height: 285px;">
                                            </div>
                                        @else
                                            @php
                                                $fotoPath = 'landingpage/img/' . $buku->buku_foto;
                                                $fotoUrl = url($fotoPath);
                                            @endphp
                                            @if (file_exists(public_path($fotoPath)))
                                                <div class="image-container">
                                                    <img src="{{ $fotoUrl }}" class="img-fluid" alt="Foto e-book" style="object-fit: cover; width: 100%; height: 285px;">
                                                </div>
                                            @else
                                                <div class="image-container">
                                                    <img src="{{ url('landingpage/img/nophoto.jpg') }}" class="img-fluid" alt="Foto e-book" style="object-fit: cover; width: 100%; height: 285px;">
                                                </div>
                                            @endif
                                        @endempty
                                    </div>
                                    <div class="text-center">
                                        <a class="d-block h8 mb-2 text-truncate text-dark capitalize" href="" title="{{ $buku->buku_judul }}"><b>{{ $buku->buku_judul }}</b></a>
                                        <p>Baca!</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

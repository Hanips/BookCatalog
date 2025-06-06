@extends('landingpage.index')
@section('content')
<br><br><br><br>
<main>
  <div class="container-fluid px-4 mx-auto">
    <div class="container">
      <nav aria-label="breadcrumb animated slideInDown">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a class="text-body" href="{{ url('/') }}">Home</a></li>
          <li class="breadcrumb-item"><a class="text-body" href="{{ url('/ebook') }}">Pengarang</a></li>
          <li class="breadcrumb-item text-dark active" aria-current="page">{{ $pengarang->nama_pengarang }}</li>
        </ol>
      </nav>
    </div>

    <div class="container-lg py-5">
      <div class="container">
        <div class="row">
          <!-- Bagian Kiri: Foto Pengarang -->
          <div class="col-md-2 text-center">
            @if(!empty($pengarang->foto))
              <img src="{{ asset('landingpage/img/' . $pengarang->foto) }}" class="img-fluid rounded-circle" alt="Foto pengarang" style="object-fit: cover;">
            @else
              <img src="{{ asset('landingpage/img/no-photo-icon.jpg') }}" class="img-fluid rounded-circle" alt="Foto pengarang" style="object-fit: cover;">
            @endif
          </div>

          <!-- Bagian Kanan: Detail Pengarang -->
          <div class="col-md-10">
            <h4>
                <b>{{ $pengarang->nama_pengarang }}</b> 
                <span style="font-size: 0.7em; font-weight: 100;">
                  &nbsp;&bull;&nbsp; <!-- Pemisah titik besar di tengah -->
                  <i class="fa fa-book"></i> {{ $pengarang->pengarang_count }} Buku
                </span>
            </h4>
        
            <!-- Bagian Biografi -->
            @if ($pengarang->biografi)
                <p>{{ $pengarang->biografi }}</p>
            @else
                <p><em>Biografi untuk pengarang ini belum tersedia.</em></p>
            @endif
        </div>
        
        </div>

        <!-- Daftar Buku Pengarang -->
        <div class="row g-4 justify-content mt-5">
          @foreach ($buku_pengarang as $buku)
            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-4">
                <a href="{{ route('landingpage.buku_detail', $buku->slug) }}">
                    <div class="product-item shadow" style="border-radius: 10px;">
                        <div class="position-relative bg-light overflow-hidden" style="border-radius: 10px;">
                            @empty($buku->foto)
                                <div class="image-container">
                                    <img src="{{ url('landingpage/img/nophoto.jpg') }}" class="img-fluid" alt="Foto e-book" style="object-fit: cover; width: 100%; height: 285px;">
                                </div>
                            @else
                                @php
                                    $fotoPath = 'landingpage/img/' . $buku->foto;
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
                            @if ($buku->diskon > 0)
                                <div class="discount-label rounded-bottom">{{ number_format($buku->diskon, 0, ',', '.') }}%</div>
                            @endif
                        </div>
                        <div class="content">
                            <div class="author">{{ $buku->pengarang->nama_pengarang }}</div>
                            <div class="title">{{ $buku->judul }}</div>
                            @if ($buku->diskon > 0)
                                @php
                                    $hargaDiskon = $buku->harga - ($buku->harga * $buku->diskon / 100);
                                @endphp
                                <div class="price-container">
                                    <span class="price">Rp. {{ number_format($hargaDiskon, 0, ',', '.') }}</span><br>
                                    <span class="discount-price">Rp. {{ number_format($buku->harga, 0, ',', '.') }}</span><br>
                                </div>
                            @else
                                <div class="price-container">Rp. {{ number_format($buku->harga, 0, ',', '.') }}</div>
                            @endif
                        </div>
                    </div>
                </a>
            </div>
          @endforeach
        </div>

        <!-- Pagination untuk daftar buku -->
        <div class="pagination-wrapper">
          {{ $buku_pengarang->links() }}  <!-- Menambahkan pagination link -->
        </div>
      </div>
    </div>
  </div>
</main>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
@endsection

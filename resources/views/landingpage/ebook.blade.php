@extends('landingpage.index')
@section('content')
<br><br>
<div class="container-lg py-5">
    <div class="container">
        <div class="container">
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb mb-0">
                    @foreach ($breadcrumb as $crumb)
                        <li class="breadcrumb-item {{ $loop->last ? 'text-dark active' : '' }}" aria-current="page">
                            {{ $crumb }}
                        </li>
                    @endforeach
                </ol>
            </nav>            
        </div>
        <br>
        <div class="row g-5 justify-content-center">
            <div class="col-lg-3 col-md-12 wow fadeInUp text-dark d-flex flex-column h-100 p-3 shadow" data-wow-delay="0.1s" style="border-radius: 10px;">
                <span><b>Filter</b></span>
                <br>
                <form action="{{ route('landingpage.ebook') }}" method="GET">
                    <div class="form-group mb-3">
                        <label for="kategori">Kategori</label>
                        <select class="form-select" name="kategori" id="kategori" style="border-radius: 10px;">
                            <option value="" {{ $selectedKategori == '' ? 'selected' : '' }}>Semua Kategori</option>
                            @foreach ($semua_kategori as $kategori)
                                <option value="{{ $kategori->id }}" {{ $selectedKategori == $kategori->id ? 'selected' : '' }}>{{ $kategori->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="penerbit">Penerbit</label>
                        <select class="form-select" name="penerbit" id="penerbit" style="border-radius: 10px;">
                            <option value="" {{ $selectedPenerbit == '' ? 'selected' : '' }}>Semua Penerbit</option>
                            @foreach ($semua_penerbit as $penerbit)
                                <option value="{{ $penerbit->id }}" {{ $selectedPenerbit == $penerbit->id ? 'selected' : '' }}>{{ $penerbit->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="hargaMin">Range Harga</label>
                        <div class="input-group">
                            <span class="input-group-text" style="border-top-left-radius: 10px; border-bottom-left-radius: 10px;">Rp.</span>
                            <input type="text" class="form-control" name="harga_min" id="harga_min" value="{{ $hargaMin ?: '' }}" placeholder="Min. Harga" style="border-top-right-radius: 10px; border-bottom-right-radius: 10px;">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <div class="input-group">
                            <span class="input-group-text" style="border-top-left-radius: 10px; border-bottom-left-radius: 10px;">Rp.</span>
                            <input type="text" class="form-control" name="harga_max" id="harga_max" value="{{ $hargaMax ?: '' }}" placeholder="Max. Harga" style="border-top-right-radius: 10px; border-bottom-right-radius: 10px;">
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="promo" id="promo" {{ $promo ? 'checked' : '' }}>
                            <label class="form-check-label" for="promo">Promo</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary rounded-pill-custom w-100" style="border-radius: 10px;">Terapkan</button>
                </form>
            </div>
            
            <div class="col-lg-9 col-md-12 wow fadeInUp" data-wow-delay="0.5s">
                <div class="col-lg-12 text-start text-lg-start wow slideInRight" data-wow-delay="0.1s">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center me-2">
                            @if ($buku_terpilih->total() > 0)
                                @php
                                    $from = $buku_terpilih->firstItem();
                                    $to = $buku_terpilih->lastItem();
                                    $total = $buku_terpilih->total();
                                @endphp
                                <div class="d-flex justify-content-between align-items-center mt-4 mb-2">
                                    <span>Menampilkan <b>{{ $from }} - {{ $to }}</b> dari <b>{{ $total }}</b> hasil pencarian produk<b></b></span>
                                </div>
                            @endif
                        </div>
                        <div class="nav nav-pills d-inline-flex align-items-center">
                            <!-- Select Option for Sorting -->
                            <form action="{{ route('landingpage.ebook') }}" method="GET">
                                <div class="input-group">
                                    <select id="sorting" class="form-select shadow" onchange="this.form.submit()" name="urutan" style="border-radius: 10px;">
                                        <option value="terbaru" {{ $urutan == 'terbaru' ? 'selected' : '' }}>Terbaru</option>
                                        <option value="terlama" {{ $urutan == 'terlama' ? 'selected' : '' }}>Terlama</option>
                                        <option value="harga-tertinggi" {{ $urutan == 'harga-tertinggi' ? 'selected' : '' }}>Harga Tertinggi</option>
                                        <option value="harga-terendah" {{ $urutan == 'harga-terendah' ? 'selected' : '' }}>Harga Terendah</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row g-4">
                    <!-- Book Listing -->
                    @foreach ($buku_terpilih as $buku)
                        <div class="col-xl-3 col-lg-2 col-md-4 col-sm-4">
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
                                        <a class="author" href="{{ route('landingpage.pengarang_detail', $buku->pengarang->slug) }}">{{ $buku->pengarang->nama_pengarang }}</a>
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
                <div class="d-flex justify-content-center mt-4">
                    {{ $buku_terpilih->links('vendor.pagination.simple') }}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function formatNumber(value) {
            // Format angka dengan pemisah ribuan titik
            return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
        
        function unformatNumber(value) {
            // Hilangkan pemisah ribuan titik
            return value.replace(/\./g, '');
        }
        
        function updateInputValue(input) {
            let value = unformatNumber(input.value);
            let formattedValue = formatNumber(value);
            input.value = formattedValue;
        }
        
        const hargaMinInput = document.getElementById('harga_min');
        const hargaMaxInput = document.getElementById('harga_max');
        
        hargaMinInput.addEventListener('input', function() {
            updateInputValue(hargaMinInput);
        });
        
        hargaMaxInput.addEventListener('input', function() {
            updateInputValue(hargaMaxInput);
        });
    });

</script>


<style>
    .pagination {
        list-style: none;
        padding: 0;
    }

    .pagination .page-item {
        margin: 5px;
    }

    .pagination .page-item .page-link {
        padding: 0;
        background-color: transparent; /* Warna background */
        color: #333; /* Warna teks */
        border: none; /* Hilangkan border */
        border-radius: none; /* Bentuk bulat */
        box-shadow: none; /* Hilangkan shadow */
        text-align: center;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .pagination .page-item .page-link:hover {
        background-color: transparent; /* Warna saat hover */
        color: #0261ae;
    }

    .pagination .page-item.disabled .page-link {
        background-color: transparent; /* Warna untuk tombol disabled */
        color: #000;
    }

    .pagination .page-item .page-link:focus {
        outline: none; /* Hilangkan outline saat klik */
        box-shadow: none; /* Hilangkan shadow saat klik */
    }
</style>

@endsection

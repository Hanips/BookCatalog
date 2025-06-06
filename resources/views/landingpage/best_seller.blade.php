<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-0 gx-5 align-items-end">
            <div class="col-lg-6">
                <div class="section-header text-start mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 500px;">
                    <h5 class="display-5 mb-3">Best Seller</h5>
                    <p>Dapatkan e-book paling populer yang telah terjual lebih dari 10.000 pengguna.</p>
                </div>
            </div>
        </div>
        <div class="tab-content">
            <div id="tab-1" class="tab-pane fade show p-0 active">
                <div class="row g-4">
                    @foreach ($ar_buku as $key => $buku)
                        @if ($key < 12)
                            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6">
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
                                            <a class="author" href="{{ route('landingpage.pengarang_detail', $buku->pengarang_slug) }}">{{ $buku->pengarang }}</a>
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
                        @else
                            @break
                        @endif
                    @endforeach
                    <div class="col-12 text-center wow fadeInUp" data-wow-delay="0.1s">
                        <a class="btn btn-primary rounded-pill py-3 px-5" href="{{ url('/ebook') }}">Browse More Products</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
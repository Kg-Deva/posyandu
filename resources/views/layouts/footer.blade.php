<footer class="footer-area section-gap">
    <div class="container">
        <div class="row">
            
            <div class="col-lg-2 col-md-6 col-sm-6">
                <div class="single-footer-widget">
                    <h4>Informasi LPQ <br>Baiturrahmah</h4>
                    <ul>
                        <li><a href="/profil-lpq">Tentang LPQ</a></li>
                        {{-- <li><a href="/profil-pengajar">Profil Pengajar</a></li> --}}
                        <li><a href="/struktur-organisasi">Struktur Organisasi</a></li>
                        <li><a href="/gallery">Galeri LPQ</a></li>
                        
                    </ul>
                </div>
            </div>
           
            <div class="col-lg-2 col-md-6 col-sm-6">
                <div class="single-footer-widget">
                    <h4>Program<br>Pendidikan</h4>
                    <ul>
                        <li><a href="#">Daftar Program Pendidikan</a></li>
                    </ul>
                </div>
            </div>
          
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="single-footer-widget">
                    <h4>Kontak <br> Kami</h4>
                    <ul>

                         {{-- <li><i class="fa fa-map-marker"></i> {{ $kontaks->alamat }}</li>
                        <li><i class="fa fa-phone"></i> <a href="tel:{{ $kontaks->no_telp }}">{{ $kontaks->no_telp }}</a></li>
                        <li><i class="fa fa-envelope"></i> <a href="mailto:{{ $kontaks->email }}">{{ $kontaks->email }}</a></li> --}}
                        <li>
                            <i class="fa fa-map-marker"></i> 
                            {{ optional($kontaks)->alamat ?? 'Alamat belum tersedia' }}
                        </li>
                        
                        <li>
                            <i class="fa fa-phone"></i> 
                            @if(optional($kontaks)->no_telp)
                                <a href="tel:{{ $kontaks->no_telp }}">{{ $kontaks->no_telp }}</a>
                            @else
                                Nomor telepon belum tersedia
                            @endif
                        </li>
                        
                        <li>
                            <i class="fa fa-envelope"></i> 
                            @if(optional($kontaks)->email)
                                <a href="mailto:{{ $kontaks->email }}">{{ $kontaks->email }}</a>
                            @else
                                Email belum tersedia
                            @endif
                        </li>
                        
                        
                    </ul>
                </div>
            </div>
            
            {{-- <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="single-footer-widget">
                    <h4>Dukung Pendidikan Al-Qur'an</h4>
                    <p>Bantu LPQ Baiturrahmah dalam mencetak generasi Qur'ani dengan berdonasi.</p>
                    @if($kontaks)
                    <a href="https://wa.me/{{ str_replace('+', '', $kontaks->whatsapp) }}?text=Halo,%20saya%20ingin%20berdonasi%20untuk%20LPQ%20Baiturrahmah." 
                       class="btn btn-primary btn-sm" target="_blank">
                        <i class="fa fa-whatsapp"></i> Donasi Sekarang
                    </a>
                @endif
                </div>
            </div> --}}

            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="single-footer-widget">
                    <h4>Dukung Pendidikan Al-Qur'an</h4>
                    <p>Bantu LPQ Baiturrahmah dalam mencetak generasi Qur'ani dengan berdonasi.</p>
            
                    @if($kontaks && $kontaks->whatsapp)
                        {{-- <a href="https://wa.me/62{{ ltrim($kontaks->whatsapp, '0') }}?text=Halo,%20saya%20ingin%20berdonasi%20untuk%20LPQ%20Baiturrahmah." 
                            class="btn btn-primary btn-sm" target="_blank">
                             <i class="fa fa-whatsapp"></i> Donasi Sekarang
                         </a> --}}

                         <a href="https://wa.me/62{{ ltrim($kontaks->whatsapp, '0') }}?text=Halo,%20saya%20ingin%20berdonasi%20untuk%20LPQ%20Baiturrahmah." 
                            class="genric-btn primary" target="_blank" style="float: left;">
                            <i class="fa fa-whatsapp"></i> Donasi Sekarang
                        </a>
                         
                    @else
                        <p class="text-muted">Kontak WhatsApp belum tersedia.</p>
                    @endif
                </div>
            </div>
            
          
            
            
            
        </div>
        <div class="footer-bottom row align-items-center justify-content-between">

            <p class="footer-text m-0 col-lg-6 col-md-12">
                Copyright &copy; <script>document.write(new Date().getFullYear());</script> 
                LPQ Baiturrahmah. All Rights Reserved.
            </p>
            
        </div>
    </div>
</footer>

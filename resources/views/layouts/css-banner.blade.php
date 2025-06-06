<style>
    .custom-about-banner {
        position: relative;
        background: url("{{ asset(isset($berandas) && $berandas->gambar ? 'storage/' . $berandas->gambar : 'storage/default/default.jpg') }}") right;
        background-size: cover;
       
    }

    .custom-overlay-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(4, 9, 30, 0.8);
    }
</style>

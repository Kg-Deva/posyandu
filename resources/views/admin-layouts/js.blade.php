<script src="{{ asset('admin/assets/js/bootstrap.js') }}"></script>
<script src="{{ asset('admin/assets/js/app.js') }}"></script>

<!-- Need: Apexcharts -->
<script src="{{ asset('admin/assets/extensions/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/pages/dashboard.js') }}"></script>
<script>
    document.getElementById('saveButton').addEventListener('click', function() {
        const content = document.querySelector('.ql-editor').innerHTML;

        fetch('/save-content', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content')
                },
                body: JSON.stringify({
                    content: content
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log(data);
            });
    });
</script>

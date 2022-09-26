<div>
    @unless($alert == [])
    <script>
        window.addEventListener("load", function(){
            swal({
                title: '{{ $alert['title'] }}',
                text: '{{ $alert['message'] }}',
                icon: '{{ $alert['type'] }}',
                padding: '2em'
            });
        });
    </script>
    @endunless
</div>

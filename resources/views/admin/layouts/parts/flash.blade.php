<script type="application/javascript">
    $(document).ready(function (){
        let success = '{{ session('success') }}'
        let status = '{{ session('status') }}'
        let error = '{{ session('error') }}'
        if(status) {
            notify(status, 'success');
        }
        else if(success) {
            notify(success, 'success');
        }
        else if(error) {
            notify(error, 'error');
        }
    })
</script>

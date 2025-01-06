@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
@endpush

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js" type="module"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js" type="module"></script>

    @if(isset($table_id))
    <script type="module">
        const dataTable = new DataTable('#{{ $table_id }}', {
            ordering: false,
            searching: true,
            pageLength: 50,
            language: {
                emptyTable: "データがありません。",
                info: "_TOTAL_ 件中 _START_ 〜 _END_ 件まで表示",
                infoEmpty: "0 件中 0 〜 0 件まで表示",
                infoFiltered: "合計 _MAX_ 件中",
                zeroRecords: "記録が見つかりません",
                lengthMenu: "_MENU_",
                search: "",
                searchPlaceholder : "検索",
                paginate: {
                    next:       "<i class='bx bx-chevron-right'></i>",
                    previous:   "<i class='bx bx-chevron-left' ></i>"
                },
            },
        });
    </script>
    @endif
@endpush

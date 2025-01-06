@extends('admin.layouts.app')
@section('title', 'Import & Export Users')

@section('content')
    <div class="row">
        <div class="col-sm-12">
                <div class="card mb-6">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">CSV Manage</h5>
                    </div>
                    <div class="card-body ">
                        @if ($errors->any())
                            <div class="alert alert-danger" style="width: 100%; text-align: center;">
                                @foreach($errors->all() as $error)
                                    {{ $error }}<br>
                                @endforeach
                            </div>
                            <br>
                        @endif

                        <div class="row justify-content-center">
                            <div class="col-sm-4 text-center py-5" >
                                <p>
                                    First click on the download button, download the CSV file.
                                </p>
                                <div class="d-grid gap-2">
                                    <a href="{{ route('admin.user.csv_download') }}" class="btn btn-outline-primary">Download Users</a>
                                </div>


                                <p class="mt-10">
                                    After download, open it in excel software. <br>
                                    Modify or create rows according to headers. <br>
                                    Then choose the CSV file from the following. <br>
                                    Finally click on the upload button.
                                </p>
                                <form method="post" action="{{ route('admin.user.csv_upload') }}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-6">
                                        <input name="csv_file" class="form-control" type="file" id="formFile" required accept=".csv, text/csv">
                                    </div>
                                    <div class="d-grid gap-2 mt-6">
                                        <button class="btn btn-primary">Upload Users</button>
                                    </div>
                                </form>

                            </div>

                        </div>
                    </div>

                </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script type="application/javascript">
        $(document).ready(function (){

        })
    </script>
@endpush

@extends('admin.layouts.app')
@section('title', 'Server Profiles')

@section('content')
    <div class="row">
        <!-- Basic Layout -->
        <div class="col-md-6">
            <form method="post" action="">
                <div class="card mb-6">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Server Profiles</h5>
                    </div>
                    <div class="card-body">
                        @csrf
                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered table-fixed-header">
                                <thead>
                                <tr>
                                    <th>S/N</th>
                                    <th>Name</th>
                                    <th class="text-center">Has in Server?</th>
                                    <th class="text-center">Packages</th>
                                    <th class="text-center">Remove</th>
                                </tr>
                                </thead>
                                <tbody class="table-border-bottom-0 table-body">
                                @foreach($profiles as $key => $profile)
                                    @php $package_count = $profile->packages()->count(); @endphp
                                    <tr class="{{ $profile->is_active? '':'disabled' }}">
                                        <td>{{ $key + 1 }}</td>
                                        <td><input name="profiles[{{$key}}][name]" type="text" value="{{ $profile->name }}" class="form-control form-control-sm" placeholder="profile name"></td>
                                        <td class="text-center">{{ $profile->is_active }} <input name="profiles[{{$key}}][is_active]" type="hidden" value="{{ $profile->is_active }}"></td>
                                        <td class="text-center">{{ $package_count }}</td>
                                        <td class="text-center"><a package_count="{{$package_count}}" href="javascript:void(0)" class="btn-remove"><i class='bx bx-trash text-danger'></i></a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row ">
                            <div class="col-sm-12 text-center">
                                <a href="{{ route('admin.server.profile.download') }}" class="btn btn-outline-warning btn-download" title="Synchronize">Sync</a>
                                <button type="button" class="btn btn-outline-secondary btn-add-new ms-5">Add New</button>
                                <button type="submit" class="btn btn-primary btn-save  ms-5">Save</button>

                                @if(session('error_message'))
                                    <div class="form-text text-danger text-center mt-4"> {{ session('error_message') }} </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
@endsection

@push('scripts')
    <style>
        .disabled{background: #fff2ea9c;}
    </style>
    <script type="application/javascript">
        $(document).ready(function (){
            $(".btn-add-new").click(function () {
                let new_sn = $(".table-body tr").length + 1;
                let html = '<tr><td>'+new_sn+'</td><td><input name="profiles[][name]" type="text" value="" class="form-control" placeholder="profile name" required></td><td></td><td></td><td class="text-center"><a href="javascript:void(0)" class="btn-remove"><i class="bx bx-trash text-danger"></i></a></td></tr>';
                $(".table-body").append(html);
            })

            $(".table-body").on("click", ".btn-remove", function () {
                let pcount = $(this).attr('package_count');
                if(pcount > 0){
                    notify(''+pcount+' package exists.', 'error');
                    return;
                }
                let tr = $(this).parents('tr');
                tr.fadeOut(function () {
                    tr.remove();
                })
            })

            $(".btn-download").click(function () {
                $(this).text('Synchronizing.....');
                $(this).css('pointer-events', 'none');
                $(".btn-save").attr('disabled', 'disabled');
                $(".btn-add-new").attr('disabled', 'disabled');
            })
        })
    </script>
@endpush

@extends('admin.layouts.app')
@section('title', 'Packages List')

@section('content')
    <div class="row">
        <!-- Basic Layout -->
        <div class="col-sm-12">
            <div class="card mb-6">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">All Packages</h5>

                    <div>
                        <a href="{{ route('admin.package.create') }}" class="btn btn-sm btn-outline-primary">Add New</a>
                        <button id="btnSaveOrder" type="button" class="btn btn-sm btn-primary ms-5 d-none">Save</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive text-nowrap table-fixed-header">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Profile</th>
                                <th>Validity</th>
                                <th class="text-center">Price({{config('settings.system_general.currency_symbol', '$')}})</th>
                                <th class="text-center">Sort</th>
                                <th class="text-center">Users</th>
                                <th class="text-center">Display</th>
                                <th class="text-center">Action</th>
                            </tr>
                            </thead>
                            <tbody class="table-border-bottom-0 table-body">
                            @forelse($packages as $key => $p)
                                @php $server_profile = $p->serverProfile; @endphp
                                <tr>
                                    <td><a class="text-decoration-underline" href="{{ route('admin.package.update', $p->id) }}">{{ $p->name }}</a></td>
                                    <td class="{{ $server_profile && $server_profile->is_active ? '' : 'text-danger' }}">{{ $p->profile }}</td>
                                    <td>{{ $p->valid }}</td>
                                    <td class="text-end">{{ $p->price }}</td>
                                    <td class="text-center"><input pid="{{ $p->id }}" type="number" value="{{ $p->sort }}" class="form-control form-control-sm w-px-100 sortInput" style="margin: 0 auto;" placeholder="Sort no"></td>
                                    <td class="text-end">{{ $p->users()->count() }}</td>
                                    <td class="text-center">{{ $p->is_home_display ? 'Yes' : 'No' }}</td>
                                    <td class="text-center"><a route="{{ route('admin.package.delete', $p->id) }}" href="javascript:void(0)" class="btn-delete"><i class='bx bx-trash text-danger'></i></a></td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center">No records are found</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    {{ $packages->links('admin.layouts.parts.paginate') }}
                </div>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script type="application/javascript">
        $(document).ready(function (){
            $(".btn-delete").click(function (){
                const route = $(this).attr('route');
               confirmFormModal(route, '', 'Are you sure to delete this package?');
            });

            $(".sortInput").on("input", function() {
                $("#btnSaveOrder").removeClass('d-none');
            });

            $("#btnSaveOrder").click(function () {
                let sorts = [];
                $(".sortInput").each(function (i,e) {
                    sorts.push({'id': $(e).attr('pid'), 'sort' : $(e).val() })
                })
                loading();
                let url = BASE_URL + '/admin/packages/sort';
                const data = {
                    sorts : sorts,
                }
                axios.post(url , data)
                    .then((response) => {
                        notify(response.data.message, 'success');
                        location.reload();
                    })
                    .catch(error => {
                        notify(error.response.data.message, 'error');
                    })
                    .finally(() => {
                        loading(false);
                    });
            })
        })
    </script>
@endpush

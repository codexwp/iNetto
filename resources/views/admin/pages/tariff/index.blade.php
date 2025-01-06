@extends('admin.layouts.app')
@section('title', 'Tariff List')

@section('content')
    <div class="row">
        <!-- Basic Layout -->
        <div class="col-sm-12">
            <form method="post" action="{{ route('admin.tariff.update') }}">
                @csrf
                <div class="card mb-6">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Tariff & Costs</h5>
                    <div>
                        <button type="submit" class="btn btn-sm btn-primary me-3">Save</button>
                        <a href="{{ route('admin.tariff.create') }}" class="btn btn-sm btn-outline-primary">Add New</a>
                    </div>

                </div>
                <div class="card-body">
                    <div class="table-responsive text-nowrap table-fixed-header">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th></th>
                                <th>ID</th>
                                <th>Tariff</th>
                                <th>Seller</th>
                                @foreach($packages as $p)
                                    <th class="text-center">{{ $p->name }}<br><span class="text-primary" style="font-size: 0.7rem;text-transform:lowercase;">{{config('settings.system_general.currency_symbol', '$')}} {{ $p->price }} /{{ $p->valid }}</span></th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody class="table-border-bottom-0 table-body">
                            @forelse($tariffs as $key => $t)
                                @php $tariff_packages = $t->tariffPackages()->pluck('cost','package_id')->toArray();  @endphp
                                <tr>
                                    <td><a onclick="confirmFormModal('{{ route('admin.tariff.delete', $t->id) }}', '', 'Are you sure to delete this tariff?')" href="javascript:void(0)" class="btn-delete text-danger" title="Delete"><i class='bx bx-trash'></i></a></td>
                                    <td>{{ $t->id }}</td>
                                    <td><input name="tpc[{{$t->id}}][name]" type="text" class="form-control form-control-sm w-auto" value="{{ $t->name }}"></td>
                                    <td>{{ $t->sellers()->count() }}</td>
                                    @foreach($packages as $p)
                                        @php $tariff_package = $t->tariffPackage($p->id); @endphp
                                        <td>
                                            <div class="d-inline-flex justify-content-between">
                                                <div class="form-check" style="padding-top: 5px;">
                                                    <input name="tpc[{{$t->id}}][packages][{{$p->id}}][is_active]" class="form-check-input chk_active" type="checkbox" value="1" {{ $tariff_package?->is_active ? 'checked' : '' }}>
                                                </div>
                                                <input name="tpc[{{$t->id}}][packages][{{$p->id}}][cost]"
                                                       type="number"
                                                       class="form-control form-control-sm"
                                                       value="{{ $tariff_packages[$p->id] ?? $p->price }}"
                                                       {{ $tariff_package?->is_active ? '' : 'disabled' }}
                                                       style="width: 80px">
                                            </div>
                                        </td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr><td colspan="20" class="text-center">No records are found</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">

                </div>
            </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="application/javascript">
        $(document).ready(function (){
            $(".chk_active").click(function (){
                if ($(this).is(':checked')) {
                    console.log('test');
                    $(this).parents("td").find("input[type=number]").removeAttr('disabled');
                } else {
                    $(this).parents("td").find("input[type=number]").attr('disabled', 'disabled');
                }
            })
        })
    </script>
@endpush

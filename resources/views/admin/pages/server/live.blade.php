@extends('admin.layouts.app')
@section('title', 'Server Clients')

@section('content')
    <div class="row">
        <!-- Basic Layout -->
        <div class="col-sm-12">
            <div class="card mb-6">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Active Connections</h5>
                </div>
                <div class="card-body">
                    <table id="clientList"
                           data-toggle="table"
                           data-toolbar=".toolbar"
                           data-virtual-scroll="true"
                           data-show-columns="true"
                           data-show-fullscreen="true"
                           data-show-search-clear-button="true"
                           data-pagination="true"
                           data-page-list="[10, 25, 50, 100, all]"
                           data-show-footer="true"
                           data-minimum-count-columns="2"
                           data-filter-control="true">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th data-field="name" data-filter-control="input">Name</th>
                            <th class="text-center" data-field="local_status" data-filter-control="select">Software Status</th>
                            <th class="text-center" data-field="expire">MAC</th>
                            <th class="text-center" data-field="address">Address</th>
                            <th class="text-center" data-field="uptime">Uptime</th>
                        </tr>
                        </thead>
                        <tbody id="clientList">
                        @foreach($clients as $client)
                            @if(!empty($client['name']))
                                <tr>
                                    <td>{{ $client['id'] }}</td>
                                    <td>{{ $client['name'] }}</td>
                                    <td class="text-center {{ isset($users[$client['name']]) ? ($users[$client['name']]['is_active_client'] == '1' ? 'text-success' : 'text-danger') : '' }}">
                                        @if(isset($users[$client['name']]))
                                            {{ $users[$client['name']]['is_active_client'] == '1' ? 'Enabled' : 'Disabled' }}
                                        @else
                                            No_Account
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $client['mac'] }}</td>
                                    <td class="text-center">{{ $client['address'] }}</td>
                                    <td class="text-center">{{ $client['uptime'] }}</td>
                                </tr>
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@endsection

@include('assets.bs_table')

@push('scripts')
    <style>
        .disabled{background: #fff2ea9c;}
        .bootstrap-table.fullscreen{z-index: 9999999}
    </style>
    <script type="application/javascript">
        $(document).ready(function (){

            $('#clientList').bootstrapTable();

            var user = '';
            var status = '';
            var element = '';

            $("#clientList").on("click", "td.server-status", function (){
                element = $(this);
                let txt = element.text();
                user = element.attr('id');
                status = txt == 'Enabled' ? 0 : 1;
                let msg = txt == 'Enabled' ? 'Are you sure to disable the user "'+user+'"?' : 'Are you sure to enable the user "'+user+'"?';
                confirmModal(updateStatus, '', msg, '')
            });

            function updateStatus()
            {
                loading();
                let url = BASE_URL + '/admin/servers/clients/status';
                const data = {
                    user : user,
                    status : status
                }
                axios.post(url , data)
                    .then((response) => {
                        notify(response.data.message, 'success');
                        element.text(status?'Enabled':'Disabled');
                        element.removeClass("text-danger")
                        element.removeClass("text-success")
                        element.addClass(status ? "text-success" : "text-danger")
                        if(status) {
                            element.parents("tr").removeClass('disabled')
                        } else {
                            element.parents("tr").addClass('disabled')
                        }
                    })
                    .catch(error => {
                        notify(error.response.data.message, 'error');
                    })
                    .finally(() => {
                        loading(false);
                    });
            }

        })
    </script>
@endpush

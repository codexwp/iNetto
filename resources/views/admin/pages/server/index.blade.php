@extends('admin.layouts.app')
@section('title', 'Mikrotik Server')

@section('content')
    <div class="row">
        <!-- Basic Layout -->
        <div class="col-md-6">
            <form method="post">
                <div class="card mb-6">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">Mikrotik Server</h5>
                    </div>
                    <div class="card-body">

                            @csrf

                            <div class="row mb-6">
                                <label class="col-sm-3 col-form-label required" for="is_active">Status</label>
                                <div class="col-sm-9">
                                    <select name="is_active" id="is_active" class="form-select" required="">
                                        <option value="0" {{ old('is_active', $server?->is_active) == '0' ? 'selected' : '' }}>Disabled</option>
                                        <option value="1" {{ old('is_active', $server?->is_active) == '1' ? 'selected' : '' }}>Enabled</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-6">
                                <label class="col-sm-3 col-form-label required" for="name">Name</label>
                                <div class="col-sm-9">
                                    <input name="name" type="text" value="{{ old('name', $server?->name) }}" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Server name">
                                    @error('name')
                                    <div class="form-text text-danger"> {{ $message }} </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-6">
                                <label class="col-sm-3 col-form-label required" for="ip">IP & Port</label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <input id="ip" name="ip" type="text" value="{{ old('ip', $server?->ip) }}" placeholder="172.10.21.2"  class="form-control @error('ip') is-invalid @enderror" style="width: 50%;">
                                        <input name="port" type="text" value="{{ old('port', $server?->port) }}" placeholder="8087"  class="form-control @error('port') is-invalid @enderror">
                                    </div>
                                    @error('ip')
                                    <div class="form-text text-danger"> {{ $message }} </div>
                                    @enderror
                                    @error('port')
                                    <div class="form-text text-danger"> {{ $message }} </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label class="col-sm-3 col-form-label required" for="username">Username</label>
                                <div class="col-sm-9">
                                    <input name="username" type="text" value="{{ old('username', $server?->username) }}" class="form-control @error('username') is-invalid @enderror" id="username" placeholder="Username">
                                    @error('username')
                                    <div class="form-text text-danger"> {{ $message }} </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label class="col-sm-3 col-form-label required" for="password">Password</label>
                                <div class="col-sm-9 form-password-toggle">
                                    <div class="input-group input-group-merge">
                                        <input name="password" type="password" value="{{ old('password', $server?->password) }}" class="form-control toggle-password-input @error('password') is-invalid @enderror" id="password" placeholder="*********">
                                        <span class="input-group-text cursor-pointer toggle-password"><i class="bx bx-hide"></i></span>
                                    </div>
                                </div>
                            </div>
                        <div class="row mb-4">
                            <label class="col-sm-3 col-form-label " for="ssl">SSL</label>
                            <div class="col-sm-9">
                                <input name="ssl" type="checkbox" value="1" class="form-check-input mt-2" id="ssl" {{ old('ssl', $server?->ssl) ? 'checked' : '' }}>
                            </div>
                        </div>


                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-sm-12 text-center">
                                <button type="submit" class="btn btn-primary btn-save">Save</button>
                                @if($server)
                                    <a href="{{ route('admin.server.test') }}" class="btn btn-outline-warning ms-5 btn-test-connect" >Test Connection</a>
                                    @if(session('error_message'))
                                        <div class="form-text text-danger text-center mt-4"> {{ session('error_message') }} </div>
                                    @endif
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
    <script type="application/javascript">
        $(document).ready(function (){
            $(".btn-test-connect").click(function () {
                $(this).text('Connecting.....');
                $(this).css('pointer-events', 'none');
                $(".btn-save").attr('disabled', 'disabled');
            })
        })
    </script>
@endpush

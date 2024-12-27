@extends('layouts.master')
@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header">
          Add Customer
        </div>
        <form action="{{ route('admin.user.customer-store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-2">
                            <label for="name">Name*</label>
                            <input type="text" id="name" name="name"
                                class="form-control @error('name') is-invalid @enderror">
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <input type="hidden" name="password">
                        <div class="mb-2">
                            <label for="email">Email*</label>
                            <input type="email" id="email" name="email"
                                class="form-control @error('email') is-invalid @enderror">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label for="mobilenumber">Phone*</label>
                            <input type="number" id="mobilenumber" name="mobilenumber"
                                class="form-control @error('mobilenumber') is-invalid @enderror" required>
                            @error('mobilenumber')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label for="address">Address*</label>
                            <input type="text" id="address" name="address"
                                class="form-control @error('address') is-invalid @enderror" >
                            @error('address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label for="gender">Gender*</label>
                            <select id="gender" name="gender" class="form-control @error('gender') is-invalid @enderror"
                                >
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            </select>
                            @error('gender')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-2">
                            <label for="city">City*</label>
                            <input type="text" id="city" name="city"
                                class="form-control @error('city') is-invalid @enderror" >
                            @error('city')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label for="state">State*</label>
                            <input type="text" id="state" name="state"
                                class="form-control @error('state') is-invalid @enderror" >
                            @error('state')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label for="zip">Zip*</label>
                            <input type="number" id="zip" name="zip"
                                class="form-control @error('zip') is-invalid @enderror" >
                            @error('zip')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-2">
                            <label for="search_user">Sponsor User</label>
                            <input type="text" id="search_user" name="sponsor" class="form-control"
                                placeholder="Type Sponsor MobileNumber or user ID..." autocomplete="off">

                            <input type="hidden" id="hidden_sponsor_id" name="sponsor_id" class="form-control">
                            <div id="userList" class="dropdown-menu" style="display: none; width: 100%;"></div>
                        </div>

                        {{-- <div class="mb-2">
                        <label for="parent_level">Parent FOR LEVEL*</label>
                        <input type="text" id="parent_level" name="parent_level" class="form-control @error('parent_level') is-invalid @enderror">
                        @error('parent_level')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div> --}}
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-primary me-2" type="submit">Register</button>
                <a class="btn btn-secondary" href="{{ route('admin.users.index') }}">
                    Back to list
                </a>
            </div>
        </form>
    </div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#search_user').on('keyup', function() {
            let query = $(this).val();
            if (query.length > 0) {
                $.ajax({
                    url: "{{ route('admin.search.sponsor') }}",
                    type: "GET",
                    data: {
                        query: query
                    },
                    success: function(data) {
                        $('#userList').empty().show();
                        if (data.length > 0) {
                            $.each(data, function(index, user) {
                                $('#userList').append(
                                    '<a href="#" class="dropdown-item user-item" data-id="' +
                                    user.id + '">' +
                                    user.mobilenumber + ' (' + user.user_id + ')</a>'
                                );
                            });
                        } else {
                            $('#userList').append(
                                '<div class="dropdown-item">No users found</div>'
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: " + status + " - " +
                            error);
                    },
                });
            } else {
                $('#userList').hide();
            }
        });

        $(document).on('click', '.user-item', function() {
            let userName = $(this).text();
            let sponsorId = $(this).data('id');
            console.log(sponsorId);

            $('#search_user').val(userName);
            $('#hidden_sponsor_id').val(sponsorId);
            $('#userList').hide();
        });
    });
</script>

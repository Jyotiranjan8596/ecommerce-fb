@extends('layouts.master')
@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-header">
            <h5><b>Edit User</b></h5>
        </div>
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <!-- First Column -->
                <div class="col-md-6">
                    <div class="mb-2">
                        <label for="name">Name*</label>
                        <input type="text" id="name" name="name"
                            class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}">
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-2">
                        <label for="email">Email*</label>
                        <input type="email" id="email" name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email) }}">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-2">
                        <label for="gender">Gender*</label>
                        <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror">
                            <option value="">Select</option>
                            <option value="Male" {{ old('gender', $user->gender) == 'M' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender', $user->gender) == 'F' ? 'selected' : '' }}>Female
                            </option>
                            <option value="Other" {{ old('gender', $user->gender) == 'Other' ? 'selected' : '' }}>Other
                            </option>
                        </select>
                        @error('gender')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-2">
                        <label for="mobilenumber">Mobile Number*</label>
                        <input type="text" id="mobilenumber" name="mobilenumber"
                            class="form-control @error('mobilenumber') is-invalid @enderror"
                            value="{{ old('mobilenumber', $user->mobilenumber) }}">
                        @error('mobilenumber')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-2">
                        <label for="address">Address</label>
                        <input type="text" id="address" name="address"
                            class="form-control @error('address') is-invalid @enderror"
                            value="{{ old('address', $user->address) }}">
                        @error('address')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-2">
                        <label for="city">City</label>
                        <input type="text" id="city" name="city"
                            class="form-control @error('city') is-invalid @enderror"
                            value="{{ old('city', $user->city) }}">
                        @error('city')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-2">
                        <label for="state">State</label>
                        <input type="text" id="state" name="state"
                            class="form-control @error('state') is-invalid @enderror"
                            value="{{ old('state', $user->state) }}">
                        @error('state')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-2">
                        <label for="country">Country</label>
                        <input type="text" id="country" name="country"
                            class="form-control @error('country') is-invalid @enderror"
                            value="{{ old('country', $user->country) }}">
                        @error('country')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <!-- Second Column -->
                <div class="col-md-6">
                    <div class="mb-2">
                        <label for="pan_no">PAN Number</label>
                        <input type="text" id="pan_no" name="pan_no"
                            class="form-control @error('pan_no') is-invalid @enderror"
                            value="{{ old('pan_no', $user->pan_no) }}">
                        @error('pan_no')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-2">
                        <label for="ifsc">IFSC Code</label>
                        <input type="text" id="ifsc" name="ifsc"
                            class="form-control @error('ifsc') is-invalid @enderror"
                            value="{{ old('ifsc', $user->ifsc) }}">
                        @error('ifsc')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-2">
                        <label for="account_no">Account Number</label>
                        <input type="text" id="account_no" name="account_no"
                            class="form-control @error('account_no') is-invalid @enderror"
                            value="{{ old('account_no', $user->account_no) }}">
                        @error('account_no')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-2">
                        <label for="nominee_name">Nominee Name</label>
                        <input type="text" id="nominee_name" name="nominee_name"
                            class="form-control @error('nominee_name') is-invalid @enderror"
                            value="{{ old('nominee_name', $user->nominee_name) }}">
                        @error('nominee_name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-2">
                        <label for="bank">Bank</label>
                        <input type="text" id="bank" name="bank"
                            class="form-control @error('bank') is-invalid @enderror"
                            value="{{ old('bank', $user->bank) }}">
                        @error('bank')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-2">
                        <label for="relation_user">Relation to User</label>
                        <input type="text" id="relation_user" name="relation_user"
                            class="form-control @error('relation_user') is-invalid @enderror"
                            value="{{ old('relation_user', $user->relation_user) }}">
                        @error('relation_user')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-2">
                        <label for="zip">ZIP Code</label>
                        <input type="text" id="zip" name="zip"
                            class="form-control @error('zip') is-invalid @enderror"
                            value="{{ old('zip', $user->zip) }}">
                        @error('zip')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-2">
                        <label for="search_user">Sponsor User</label>
                        <input type="text" id="search_user" name="sponsor" class="form-control"
                            placeholder="Type Sponsor MobileNumber or user ID..." autocomplete="off"
                            value="{{ $sponsor ? $sponsor->mobilenumber . ' (' . $sponsor->user_id . ')' : '' }}">

                        <input type="hidden" id="hidden_sponsor_id" name="sponsor_id" class="form-control"
                            value="{{ $sponsor ? $sponsor->id : '' }}">

                        <div id="userList" class="dropdown-menu" style="display: none; width: 100%;"></div>
                    </div>


                    {{-- <div class="mb-2">
                        <label for="parent_level">Parent Level</label>
                        <input type="text" id="parent_level" name="parent_level" class="form-control @error('parent_level') is-invalid @enderror"
                               value="{{ old('parent_level', $user->parent_level) }}">
                        @error('parent_level')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div> --}}
                </div>
            </div>

            <div class="card-footer">
                <button class="btn btn-primary me-2" type="submit">Update</button>
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

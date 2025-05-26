@extends('frontend.dashboard.layouts.master')

@section('content')
    <style>
        #srch-form {
            width: 140%;
        }
    </style>
    <div class="card">
        <div class="card-header">
            <div class="d-flex flex-column flex-md-row heading justify-content-between">
                <h4 class=""><b>List of POS</b></h4>
                <!-- Search form -->
                <form id="srch-form" class="justify-content-end">
                    <div class="row g-2">
                        <div class="col-12 col-md-auto">
                            <input type="text" name="search" placeholder="Search by City or ZIP" class="form-control">
                        </div>
                        <div class="col-12 col-md-auto">
                            <button class="btn btn-warning w-100" type="submit">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card-body">
            <!-- Responsive table wrapper -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="posList-table">
                    <thead class="thead-dark">
                        <tr>
                            <th>Sl.No</th>
                            <th>POS Name</th>
                            <th>Address</th>
                            <th>City</th>
                            <th>ZIP</th>
                            <th>Phone</th>
                        </tr>
                    </thead>
                    <tbody id="pos-list-body">
                        <!-- Dynamic rows go here -->
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <div id="pagination-container" class="d-flex justify-content-end mt-3"></div>


    {{-- modal --}}
    {{-- @include('frontend.dashboard.includes.scanmodal'); --}}
    <script>
        $(document).ready(function() {
            getPosList(1);

            $('#srch-form').on('submit', function(e) {
                e.preventDefault();
                getPosList(1, this);
            })

            function getPosList(p = 1, formElement) {

                let formData = new FormData(formElement); // Capital 'F'
                formData.append('page', p);
                console.log(formData);
                $.ajax({
                    type: "POST",
                    url: "{{ route('user.pos.list') }}",
                    data: formData,
                    processData: false, // Important when using FormData
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        console.log("Success:", response);
                        if (response.code == 200) {
                            const pagination = response.data;
                            let paginationHtml = '';
                            const posList = response.data.data;
                            const table = $("#pos-list-body");
                            table.empty();
                            if (posList.length === 0) {
                                table.append('<tr><td colspan="6">No records found!</td></tr>');
                            } else {
                                posList.forEach((item, index) => {
                                    const imageUrl = item.image ?
                                        `/uploads/pos/${item.image}` :
                                        '/images/default-user.png'; // fallback image if null

                                    const row = `<tr>
                                                        <td>${index + 1}</td>
                                                        <td>${item.name}</td>
                                                        <td>${item.address}</td>
                                                        <td>${item.city}</td>
                                                        <td>${item.zip}</td>
                                                        <td>${item.mobilenumber}</td>
                                                    </tr>
                                                `;

                                    table.append(row);
                                });
                                // Previous Button
                                if (pagination.current_page > 1) {
                                    paginationHtml +=
                                        `<button class="btn btn-sm btn-secondary mx-1 pagination-btn" data-page="${pagination.current_page - 1}">Prev</button>`;
                                }

                                // Page Numbers
                                for (let i = 1; i <= pagination.last_page; i++) {
                                    paginationHtml +=
                                        `<button class="btn btn-sm mx-1 pagination-btn ${i === pagination.current_page ? 'btn-primary' : 'btn-outline-primary'}" data-page="${i}">${i}</button>`;
                                }

                                // Next Button
                                if (pagination.current_page < pagination.last_page) {
                                    paginationHtml +=
                                        `<button class="btn btn-sm btn-secondary mx-1 pagination-btn" data-page="${pagination.current_page + 1}">Next</button>`;
                                }

                                $('#pagination-container').html(paginationHtml);
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", error);
                    }
                });
            }

            $(document).on('click', '.pagination-btn', function() {
                const page = $(this).data('page');
                getPosList(page, $('#srch-form')[0]); // Reuse the same form
            });

        });
    </script>
@endsection

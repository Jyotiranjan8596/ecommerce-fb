@extends('frontend.dashboard.layouts.master')


@section('content')
    <style>
        /* Default styles (light mode) */
        .modal-body {
            background-color: #fff;
            color: black;
        }

        .modal-body label,
        .modal-body strong {
            color: black;
        }

        /* Dark mode */
        @media (prefers-color-scheme: dark) {
            .modal-body {
                background-color: #eaeaea;
                color: black;
            }

            .modal-body label,
            .modal-body strong {
                color: black;
            }

            /* Input and select backgrounds */
            .modal-body .form-control {
                background-color: #eaeaea;
                color: black;
                border-color: #666;
            }

            .modal-body .form-control::placeholder {
                color: #bbb;
            }

            .btn-success {
                background-color: #28a745;
                color: white;
            }
        }

        .modal-label {
            color: black;
            text-align: left;
            /* Ensure left alignment */
            display: block;
            /* Make sure it aligns left as a block element */
        }

        .table-responsive::-webkit-scrollbar {
            width: 8px;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background: #ccc;
            border-radius: 4px;
        }

        .table-responsive::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .scrollable-table {
            max-height: 300px;
            overflow-y: auto;
            overflow-x: auto;
        }

        /* Mobile styling adjustments */
        @media (max-width: 768px) {
            .card-box {
                padding: 20px;
            }

            h3,
            h4 {
                font-size: 1.2rem;
            }

            .btn {
                width: 100%;
                margin-top: 10px;
            }

            .scrollable-table table {
                width: 100%;
                font-size: 0.9rem;
            }

            .scrollable-table th,
            .scrollable-table td {
                padding: 8px;
            }
        }
    </style>

    {{-- banner start  --}}
    <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
        {{-- <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"
                aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"
                aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"
                aria-label="Slide 3"></button>
        </div> --}}
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('assets/images/freebazarbanner.jpg') }}" style="border-radius: 10px;" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('assets/images/b1.png') }}" style="border-radius: 10px;" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('assets/images/b2.png') }}" style="border-radius: 10px;" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('assets/images/b3.png') }}" style="border-radius: 10px;" class="d-block w-100" alt="...">
            </div>
        </div>
        {{-- <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying"
            data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="false"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying"
            data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="false"></span>
            <span class="visually-hidden">Next</span>
        </button> --}}
    </div>
    <br>
    {{-- Banner Ends  --}}
    <div class="row">
        {{-- <div class="col-12 mb-4">
            <div class="card bg-yellow-100 border-0 shadow">
                <div class="card-header d-sm-flex flex-row align-items-center flex-0">
                    <div class="d-block mb-3 mb-sm-0">
                        <div class="fs-5 fw-normal mb-2">Sales Value</div>
                        <h2 class="fs-3 fw-extrabold">₹ 10,567</h2>
                        <div class="small mt-2">
                            <span class="fw-normal me-2">Yesterday</span>
                            <span class="fas fa-angle-up text-success"></span>
                            <span class="text-success fw-bold">10.57%</span>
                        </div>
                    </div>
                    <div class="d-flex ms-auto">
                        <a href="#" class="btn btn-secondary text-dark btn-sm me-2">Month</a>
                        <a href="#" class="btn btn-dark btn-sm me-3">Week</a>
                    </div>
                </div>
                <div class="card-body p-2">
                    <div class="ct-chart-sales-value ct-double-octave ct-series-g"></div>
                </div>
            </div>
        </div> --}}
        <div class="col-12 col-sm-6 col-xl-4 mb-4">
            <div class="card border-0 shadow">
                <div class="card-body">
                    <div class="row d-block d-xl-flex align-items-center">
                        <div
                            class="col-12 col-xl-5 text-xl-center mb-3 mb-xl-0 d-flex align-items-center justify-content-xl-center">
                            <div class="icon-shape icon-shape-primary rounded me-4 me-sm-0">
                                <svg width="800px" height="800px" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M6.25 14.75C6.25 15.1642 6.58579 15.5 7 15.5C7.41421 15.5 7.75 15.1642 7.75 14.75V8.75C7.75 8.33579 7.41421 8 7 8C6.58579 8 6.25 8.33579 6.25 8.75V14.75Z"
                                        fill="#1C274C" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M21.1884 8.00377C21.1262 7.99995 21.0584 7.99998 20.9881 8L20.9706 8.00001H18.2149C15.9435 8.00001 14 9.73607 14 12C14 14.2639 15.9435 16 18.2149 16H20.9706L20.9881 16C21.0584 16 21.1262 16 21.1884 15.9962C22.111 15.9397 22.927 15.2386 22.9956 14.2594C23.0001 14.1952 23 14.126 23 14.0619L23 14.0444V9.95556L23 9.93815C23 9.874 23.0001 9.80479 22.9956 9.74058C22.927 8.76139 22.111 8.06034 21.1884 8.00377ZM17.9706 13.0667C18.5554 13.0667 19.0294 12.5891 19.0294 12C19.0294 11.4109 18.5554 10.9333 17.9706 10.9333C17.3858 10.9333 16.9118 11.4109 16.9118 12C16.9118 12.5891 17.3858 13.0667 17.9706 13.0667Z"
                                        fill="#1C274C" />
                                    <path opacity="0.5"
                                        d="M21.1394 8.00152C21.1394 6.82091 21.0965 5.55447 20.3418 4.64658C20.2689 4.55894 20.1914 4.47384 20.1088 4.39124C19.3604 3.64288 18.4114 3.31076 17.239 3.15314C16.0998 2.99997 14.6442 2.99999 12.8064 3H10.6936C8.85583 2.99999 7.40019 2.99997 6.26098 3.15314C5.08856 3.31076 4.13961 3.64288 3.39124 4.39124C2.64288 5.13961 2.31076 6.08856 2.15314 7.26098C1.99997 8.40019 1.99999 9.85581 2 11.6936V11.8064C1.99999 13.6442 1.99997 15.0998 2.15314 16.239C2.31076 17.4114 2.64288 18.3604 3.39124 19.1088C4.13961 19.8571 5.08856 20.1892 6.26098 20.3469C7.40018 20.5 8.8558 20.5 10.6935 20.5H12.8064C14.6442 20.5 16.0998 20.5 17.239 20.3469C18.4114 20.1892 19.3604 19.8571 20.1088 19.1088C20.3133 18.9042 20.487 18.6844 20.6346 18.4486C21.0851 17.7291 21.1394 16.8473 21.1394 15.9985C21.0912 16 21.0404 16 20.9882 16L18.2149 16C15.9435 16 14 14.2639 14 12C14 9.73607 15.9435 8.00001 18.2149 8.00001L20.9881 8.00001C21.0403 7.99999 21.0912 7.99997 21.1394 8.00152Z"
                                        fill="currentColor" />
                                </svg>
                            </div>
                            <div class="d-sm-none">
                                <h2 class="h5">Wallet Balance</h2>
                                <h3 class="fw-extrabold mb-1">₹{{ $walletBalance }}/-</h3>
                            </div>
                        </div>
                        <div class="col-12 col-xl-7 px-xl-0">
                            <div class="d-none d-sm-block">
                                <h2 class="h6 text-gray-400 mb-0">Wallet Balance</h2>
                                <h3 class="fw-extrabold mb-2">₹{{ $walletBalance }}/-</h3>
                            </div>
                            {{-- <small class="d-flex align-items-center text-gray-500">
                                Feb 1 - Apr 1,
                                <svg class="icon icon-xxs text-gray-500 ms-2 me-1" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM4.332 8.027a6.012 6.012 0 011.912-2.706C6.512 5.73 6.974 6 7.5 6A1.5 1.5 0 019 7.5V8a2 2 0 004 0 2 2 0 011.523-1.943A5.977 5.977 0 0116 10c0 .34-.028.675-.083 1H15a2 2 0 00-2 2v2.197A5.973 5.973 0 0110 16v-2a2 2 0 00-2-2 2 2 0 01-2-2 2 2 0 00-1.668-1.973z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                USA
                            </small>
                            <div class="small d-flex mt-1">
                                <div>
                                    Since last month
                                    <svg class="icon icon-xs text-success" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                            clip-rule="evenodd"></path>
                                    </svg><span class="text-success fw-bolder">22%</span>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-4 mb-4">
            <div class="card border-0 shadow">
                <div class="card-body">
                    <div class="row d-block d-xl-flex align-items-center">
                        <div
                            class="col-12 col-xl-5 text-xl-center mb-3 mb-xl-0 d-flex align-items-center justify-content-xl-center">
                            <div class="icon-shape icon-shape-primary rounded me-4 me-sm-0">
                                <svg width="800px" height="800px" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M6.25 14.75C6.25 15.1642 6.58579 15.5 7 15.5C7.41421 15.5 7.75 15.1642 7.75 14.75V8.75C7.75 8.33579 7.41421 8 7 8C6.58579 8 6.25 8.33579 6.25 8.75V14.75Z"
                                        fill="#1C274C" />
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M21.1884 8.00377C21.1262 7.99995 21.0584 7.99998 20.9881 8L20.9706 8.00001H18.2149C15.9435 8.00001 14 9.73607 14 12C14 14.2639 15.9435 16 18.2149 16H20.9706L20.9881 16C21.0584 16 21.1262 16 21.1884 15.9962C22.111 15.9397 22.927 15.2386 22.9956 14.2594C23.0001 14.1952 23 14.126 23 14.0619L23 14.0444V9.95556L23 9.93815C23 9.874 23.0001 9.80479 22.9956 9.74058C22.927 8.76139 22.111 8.06034 21.1884 8.00377ZM17.9706 13.0667C18.5554 13.0667 19.0294 12.5891 19.0294 12C19.0294 11.4109 18.5554 10.9333 17.9706 10.9333C17.3858 10.9333 16.9118 11.4109 16.9118 12C16.9118 12.5891 17.3858 13.0667 17.9706 13.0667Z"
                                        fill="#1C274C" />
                                    <path opacity="0.5"
                                        d="M21.1394 8.00152C21.1394 6.82091 21.0965 5.55447 20.3418 4.64658C20.2689 4.55894 20.1914 4.47384 20.1088 4.39124C19.3604 3.64288 18.4114 3.31076 17.239 3.15314C16.0998 2.99997 14.6442 2.99999 12.8064 3H10.6936C8.85583 2.99999 7.40019 2.99997 6.26098 3.15314C5.08856 3.31076 4.13961 3.64288 3.39124 4.39124C2.64288 5.13961 2.31076 6.08856 2.15314 7.26098C1.99997 8.40019 1.99999 9.85581 2 11.6936V11.8064C1.99999 13.6442 1.99997 15.0998 2.15314 16.239C2.31076 17.4114 2.64288 18.3604 3.39124 19.1088C4.13961 19.8571 5.08856 20.1892 6.26098 20.3469C7.40018 20.5 8.8558 20.5 10.6935 20.5H12.8064C14.6442 20.5 16.0998 20.5 17.239 20.3469C18.4114 20.1892 19.3604 19.8571 20.1088 19.1088C20.3133 18.9042 20.487 18.6844 20.6346 18.4486C21.0851 17.7291 21.1394 16.8473 21.1394 15.9985C21.0912 16 21.0404 16 20.9882 16L18.2149 16C15.9435 16 14 14.2639 14 12C14 9.73607 15.9435 8.00001 18.2149 8.00001L20.9881 8.00001C21.0403 7.99999 21.0912 7.99997 21.1394 8.00152Z"
                                        fill="currentColor" />
                                </svg>
                            </div>
                            <div class="d-sm-none">
                                <h2 class="h5">Wallet Balance</h2>
                                <h3 class="fw-extrabold mb-1">₹{{ $rewardBalance }}/-</h3>
                            </div>
                        </div>
                        <div class="col-12 col-xl-7 px-xl-0">
                            <div class="d-none d-sm-block">
                                <h2 class="h6 text-gray-400 mb-0">Wallet Balance</h2>
                                <h3 class="fw-extrabold mb-2">₹{{ $rewardBalance }}/-</h3>
                            </div>
                            {{-- <small class="d-flex align-items-center text-gray-500">
                                Feb 1 - Apr 1,
                                <svg class="icon icon-xxs text-gray-500 ms-2 me-1" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM4.332 8.027a6.012 6.012 0 011.912-2.706C6.512 5.73 6.974 6 7.5 6A1.5 1.5 0 019 7.5V8a2 2 0 004 0 2 2 0 011.523-1.943A5.977 5.977 0 0116 10c0 .34-.028.675-.083 1H15a2 2 0 00-2 2v2.197A5.973 5.973 0 0110 16v-2a2 2 0 00-2-2 2 2 0 01-2-2 2 2 0 00-1.668-1.973z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                USA
                            </small>
                            <div class="small d-flex mt-1">
                                <div>
                                    Since last month
                                    <svg class="icon icon-xs text-success" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                            clip-rule="evenodd"></path>
                                    </svg><span class="text-success fw-bolder">22%</span>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-4 mb-4">
            <div class="card border-0 shadow">
                <div class="card-body">
                    <div class="row d-block d-xl-flex align-items-center">
                        <div
                            class="col-12 col-xl-5 text-xl-center mb-3 mb-xl-0 d-flex align-items-center justify-content-xl-center">
                            <div class="icon-shape icon-shape-secondary rounded me-4 me-sm-0">
                                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path opacity="0.1"
                                            d="M3 12C3 4.5885 4.5885 3 12 3C19.4115 3 21 4.5885 21 12C21 19.4115 19.4115 21 12 21C4.5885 21 3 19.4115 3 12Z"
                                            fill="#323232"></path>
                                        <path
                                            d="M3 12C3 4.5885 4.5885 3 12 3C19.4115 3 21 4.5885 21 12C21 19.4115 19.4115 21 12 21C4.5885 21 3 19.4115 3 12Z"
                                            stroke="#323232" stroke-width="2"></path>
                                        <path
                                            d="M12 17L9.12186 14.1219V14.1219C9.07689 14.0769 9.11206 13.9992 9.17562 13.9971C13.9993 13.8351 13.9408 7 9 7H15"
                                            stroke="#323232" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                        <path d="M9 10.5H15" stroke="#323232" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                    </g>
                                </svg>
                            </div>
                            <div class="d-sm-none">
                                <h2 class="fw-extrabold h5">Cur. Month Expenses</h2>
                                <h3 class="mb-1">₹{{ $monthlyPurchase }}/-</h3>
                            </div>
                        </div>
                        <div class="col-12 col-xl-7 px-xl-0">
                            <div class="d-none d-sm-block">
                                <h2 class="h6 text-gray-400 mb-0">Cur. Month Expenses</h2>
                                <h3 class="fw-extrabold mb-2">₹{{ $monthlyPurchase }}/-</h3>
                            </div>
                            {{-- <small class="d-flex align-items-center text-gray-500">
                                Feb 1 - Apr 1,
                                <svg class="icon icon-xxs text-gray-500 ms-2 me-1" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM4.332 8.027a6.012 6.012 0 011.912-2.706C6.512 5.73 6.974 6 7.5 6A1.5 1.5 0 019 7.5V8a2 2 0 004 0 2 2 0 011.523-1.943A5.977 5.977 0 0116 10c0 .34-.028.675-.083 1H15a2 2 0 00-2 2v2.197A5.973 5.973 0 0110 16v-2a2 2 0 00-2-2 2 2 0 01-2-2 2 2 0 00-1.668-1.973z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                GER
                            </small>
                            <div class="small d-flex mt-1">
                                <div>
                                    Since last month
                                    <svg class="icon icon-xs text-danger" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd"></path>
                                    </svg><span class="text-danger fw-bolder">2%</span>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-xl-4 mb-4">
            <div class="card border-0 shadow">
                <div class="card-body">
                    <div class="row d-block d-xl-flex align-items-center">
                        <div
                            class="col-12 col-xl-5 text-xl-center mb-3 mb-xl-0 d-flex align-items-center justify-content-xl-center">
                            <div class="icon-shape icon-shape-tertiary rounded me-4 me-sm-0">
                                <svg viewBox="0 0 6.3500002 6.3500002" id="svg1976" version="1.1"
                                    xmlns="http://www.w3.org/2000/svg" xmlns:cc="http://creativecommons.org/ns#"
                                    xmlns:dc="http://purl.org/dc/elements/1.1/"
                                    xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
                                    xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
                                    xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
                                    xmlns:svg="http://www.w3.org/2000/svg" fill="#000000">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <defs id="defs1970"></defs>
                                        <g id="layer1" style="display:inline">
                                            <path
                                                d="m 0.26485,5.8204456 a 0.2645835,0.2645835 0 0 0 -0.26563,0.26563 0.2645835,0.2645835 0 0 0 0.26563,0.26367 h 5.82031 a 0.2645835,0.2645835 0 0 0 0.26562,-0.26367 0.2645835,0.2645835 0 0 0 -0.26562,-0.26563 z"
                                                id="path726"
                                                style="color:#272626;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:medium;line-height:normal;font-family:sans-serif;font-variant-ligatures:normal;font-variant-position:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-alternates:normal;font-variant-east-asian:normal;font-feature-settings:normal;font-variation-settings:normal;text-indent:0;text-align:start;text-decoration:none;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:#272626;letter-spacing:normal;word-spacing:normal;text-transform:none;writing-mode:lr-tb;direction:ltr;text-orientation:mixed;dominant-baseline:auto;baseline-shift:baseline;text-anchor:start;white-space:normal;shape-padding:0;shape-margin:0;inline-size:0;clip-rule:nonzero;display:inline;overflow:visible;visibility:visible;isolation:auto;mix-blend-mode:normal;color-interpolation:sRGB;color-interpolation-filters:linearRGB;solid-color:#272626;solid-opacity:1;vector-effect:none;fill:#272626;fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:0.529167;stroke-linecap:round;stroke-linejoin:miter;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;color-rendering:auto;image-rendering:auto;shape-rendering:auto;text-rendering:auto;enable-background:accumulate;stop-color:#272626">
                                            </path>
                                            <path
                                                d="m 1.16328,3.9688856 c -0.34722,0 -0.63476,0.28754 -0.63476,0.63477 v 1.48242 a 0.26460996,0.26460996 0 0 0 0.26562,0.26367 h 1.0586 a 0.26460996,0.26460996 0 0 0 0.26367,-0.26367 v -1.48242 c 0,-0.34723 -0.28755,-0.63477 -0.63477,-0.63477 z"
                                                id="path728"
                                                style="color:#272626;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:medium;line-height:normal;font-family:sans-serif;font-variant-ligatures:normal;font-variant-position:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-alternates:normal;font-variant-east-asian:normal;font-feature-settings:normal;font-variation-settings:normal;text-indent:0;text-align:start;text-decoration:none;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:#272626;letter-spacing:normal;word-spacing:normal;text-transform:none;writing-mode:lr-tb;direction:ltr;text-orientation:mixed;dominant-baseline:auto;baseline-shift:baseline;text-anchor:start;white-space:normal;shape-padding:0;shape-margin:0;inline-size:0;clip-rule:nonzero;display:inline;overflow:visible;visibility:visible;isolation:auto;mix-blend-mode:normal;color-interpolation:sRGB;color-interpolation-filters:linearRGB;solid-color:#272626;solid-opacity:1;vector-effect:none;fill:#272626;fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:0.529167;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;paint-order:stroke fill markers;color-rendering:auto;image-rendering:auto;shape-rendering:auto;text-rendering:auto;enable-background:accumulate;stop-color:#272626">
                                            </path>
                                            <path
                                                d="m 3.0168,3.0684956 c -0.34722,0 -0.63477,0.28753 -0.63477,0.63477 v 2.38281 a 0.26460996,0.26460996 0 0 0 0.26367,0.26367 h 1.0586 a 0.26460996,0.26460996 0 0 0 0.26367,-0.26367 v -2.38281 c 0,-0.34724 -0.28755,-0.63477 -0.63477,-0.63477 z"
                                                id="path730"
                                                style="color:#272626;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:medium;line-height:normal;font-family:sans-serif;font-variant-ligatures:normal;font-variant-position:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-alternates:normal;font-variant-east-asian:normal;font-feature-settings:normal;font-variation-settings:normal;text-indent:0;text-align:start;text-decoration:none;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:#272626;letter-spacing:normal;word-spacing:normal;text-transform:none;writing-mode:lr-tb;direction:ltr;text-orientation:mixed;dominant-baseline:auto;baseline-shift:baseline;text-anchor:start;white-space:normal;shape-padding:0;shape-margin:0;inline-size:0;clip-rule:nonzero;display:inline;overflow:visible;visibility:visible;isolation:auto;mix-blend-mode:normal;color-interpolation:sRGB;color-interpolation-filters:linearRGB;solid-color:#272626;solid-opacity:1;vector-effect:none;fill:#272626;fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:0.529167;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;paint-order:stroke fill markers;color-rendering:auto;image-rendering:auto;shape-rendering:auto;text-rendering:auto;enable-background:accumulate;stop-color:#272626">
                                            </path>
                                            <path
                                                d="m 4.86836,2.2755256 c -0.34722,0 -0.63477,0.28754 -0.63477,0.63477 v 3.17578 a 0.26460996,0.26460996 0 0 0 0.26368,0.26367 h 1.05859 a 0.26460996,0.26460996 0 0 0 0.26563,-0.26367 v -3.17578 c 0,-0.34723 -0.2895,-0.63477 -0.63672,-0.63477 z"
                                                id="path732"
                                                style="color:#272626;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:medium;line-height:normal;font-family:sans-serif;font-variant-ligatures:normal;font-variant-position:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-alternates:normal;font-variant-east-asian:normal;font-feature-settings:normal;font-variation-settings:normal;text-indent:0;text-align:start;text-decoration:none;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:#272626;letter-spacing:normal;word-spacing:normal;text-transform:none;writing-mode:lr-tb;direction:ltr;text-orientation:mixed;dominant-baseline:auto;baseline-shift:baseline;text-anchor:start;white-space:normal;shape-padding:0;shape-margin:0;inline-size:0;clip-rule:nonzero;display:inline;overflow:visible;visibility:visible;isolation:auto;mix-blend-mode:normal;color-interpolation:sRGB;color-interpolation-filters:linearRGB;solid-color:#272626;solid-opacity:1;vector-effect:none;fill:#272626;fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:0.529167;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;paint-order:stroke fill markers;color-rendering:auto;image-rendering:auto;shape-rendering:auto;text-rendering:auto;enable-background:accumulate;stop-color:#272626">
                                            </path>
                                            <path
                                                d="M 4.6205208,2.5237e-4 A 0.2645835,0.2645835 0 0 0 4.3564534,0.26380219 0.2645835,0.2645835 0 0 0 4.6205208,0.52941905 H 4.8938883 C 3.3974791,1.8159538 1.8306324,2.6151331 0.2161369,2.9142865 A 0.2645835,0.2645835 0 0 0 0.0052984,3.2227949 0.2645835,0.2645835 0 0 0 0.3117388,3.4357016 C 2.050091,3.1136013 3.722697,2.2498105 5.2923138,0.88753671 V 1.1991456 A 0.2645835,0.2645835 0 0 0 5.5558626,1.4647625 0.2645835,0.2645835 0 0 0 5.8214805,1.1991456 V 0.41986501 C 5.8215308,0.19150501 5.62816,2.0237e-4 5.3998008,2.5237e-4 Z"
                                                id="path734"
                                                style="color:#272626;font-style:normal;font-variant:normal;font-weight:normal;font-stretch:normal;font-size:medium;line-height:normal;font-family:sans-serif;font-variant-ligatures:normal;font-variant-position:normal;font-variant-caps:normal;font-variant-numeric:normal;font-variant-alternates:normal;font-variant-east-asian:normal;font-feature-settings:normal;font-variation-settings:normal;text-indent:0;text-align:start;text-decoration:none;text-decoration-line:none;text-decoration-style:solid;text-decoration-color:#272626;letter-spacing:normal;word-spacing:normal;text-transform:none;writing-mode:lr-tb;direction:ltr;text-orientation:mixed;dominant-baseline:auto;baseline-shift:baseline;text-anchor:start;white-space:normal;shape-padding:0;shape-margin:0;inline-size:0;clip-rule:nonzero;display:inline;overflow:visible;visibility:visible;isolation:auto;mix-blend-mode:normal;color-interpolation:sRGB;color-interpolation-filters:linearRGB;solid-color:#272626;solid-opacity:1;vector-effect:none;fill:#272626;fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:0.529167;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;paint-order:stroke fill markers;color-rendering:auto;image-rendering:auto;shape-rendering:auto;text-rendering:auto;enable-background:accumulate;stop-color:#272626">
                                            </path>
                                        </g>
                                    </g>
                                </svg>
                            </div>
                            <div class="d-sm-none">
                                <h2 class="fw-extrabold h5">Payback Achieved</h2>
                                <h3 class="mb-1">₹{{ $total_payback ?? 0 }}/-</h3>
                            </div>
                        </div>
                        <div class="col-12 col-xl-7 px-xl-0">
                            <div class="d-none d-sm-block">
                                <h2 class="h6 text-gray-400 mb-0">Payback Achieved</h2>
                                <h3 class="fw-extrabold mb-2">₹{{ $total_payback ?? 0 }}/-</h3>
                            </div>
                            {{-- <small class="text-gray-500"> Feb 1 - Apr 1 </small>
                            <div class="small d-flex mt-1">
                                <div>
                                    Since last month
                                    <svg class="icon icon-xs text-success" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                            clip-rule="evenodd"></path>
                                    </svg><span class="text-success fw-bolder">4%</span>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-4">
            <div class="card border-0 shadow">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h2 class="fs-5 fw-bold mb-0">Transactions</h2>
                        </div>
                        <div class="col text-end">
                            {{-- <a href="#" class="btn btn-sm btn-primary">See all</a> --}}
                        </div>
                    </div>
                </div>

                <!-- POS Transactions Table -->
                <div class="col-md-6 col-12 mb-4" style="width: 100%">
                    <div class="card flex-fill">
                        <div class="card-body py-4">
                            <h6><b>POS Transaction (Latest 50 Transactions)</b></h6>
                            <br>
                            <!-- Date Filter Form -->
                            <form action="{{ route('user.index') }}" method="GET" class="form-inline mb-3">
                                <div class="input-group mb-3"
                                    style="align-content: center;height: 60px; align-items: normal !important;margin-top: 1.5%">
                                    <div class="me-2">
                                        <label for="from_date" class="form-label">From</label>
                                        <input type="date" class="form-control" name="from_date" id="from_date"
                                            value="{{ request('from_date') }}" placeholder="From Date">
                                    </div>

                                    <div class="me-2">
                                        <label for="to_date" class="form-label">To</label>
                                        <input type="date" class="form-control" name="to_date" id="to_date"
                                            value="{{ request('to_date') }}" placeholder="To Date">
                                    </div>

                                    <button class="btn btn-info btn-sm" type="submit"
                                        style="height: 38px;margin:2.6%; border-radius: 10px;">Search Transactions</button>
                                </div>
                            </form>
                            <br>
                            <!-- Responsive Table -->
                            <div class="scrollable-table">
                                <table cid="tech-companies-1" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Sl.No</th>
                                            <th>POS ID</th>
                                            <th>Invoice</th>
                                            <th>Transaction Date</th>
                                            <th>Billing Amount</th>
                                            <th>Wallet Deduct</th>
                                            <th>Net Pay</th>
                                            <th>Remaining</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($walletList as $key => $data)
                                            <tr>
                                                <td>{{ ($walletList->currentPage() - 1) * $walletList->perPage() + $key + 1 }}
                                                </td>
                                                <td>{{ $data->getPos->user_id ?? 'N/A' }}</td>
                                                <td>{{ $data->invoice }}</td>
                                                <td>{{ date('d/m/Y', strtotime($data->transaction_date)) }}</td>
                                                <td>₹{{ $data->billing_amount ?? 0 }}/-</td>
                                                <td>₹{{ $data->amount_wallet ?? 0 }}/-</td>
                                                <td>₹{{ $data->billing_amount - $data->amount_wallet ?? 0 }}/-</td>
                                                <td>₹{{ $data->remaining_amount ?? 0 }}/-</td>
                                                <td>
                                                    @if ($data->status == 0)
                                                        <span class="btn btn-danger btn-sm">Unverified</span>
                                                    @else
                                                        <span class="btn btn-success btn-sm">Verified</span>
                                                    @endif
                                                </td>

                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">
                                                    <div class="alert alert-danger" role="alert">
                                                        No transaction record found.
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <br>
                            {{ $walletList->links() }}
                        </div>
                    </div>
                </div>

                {{-- <div class="row">
                    <div class="col-12 col-xl-8">
                        <div class="row">

                            <div class="col-12 col-xxl-6 mb-4">
                                <div class="card border-0 shadow">
                                    <div
                                        class="card-header border-bottom d-flex align-items-center justify-content-between">
                                        <h2 class="fs-5 fw-bold mb-0">Team members</h2>
                                        <a href="#" class="btn btn-sm btn-primary">See all</a>
                                    </div>
                                    <div class="card-body">
                                        <ul class="list-group list-group-flush list my--3">
                                            <li class="list-group-item px-0">
                                                <div class="row align-items-center">
                                                    <div class="col-auto">
                                                        <!-- Avatar -->
                                                        <a href="#" class="avatar">
                                                            <img class="rounded" alt="Image placeholder"
                                                                src="{{ asset('assets/img/team/profile-picture-1.jpg') }}" />
                                                        </a>
                                                    </div>
                                                    <div class="col-auto ms--2">
                                                        <h4 class="h6 mb-0">
                                                            <a href="#">Chris Wood</a>
                                                        </h4>
                                                        <div class="d-flex align-items-center">
                                                            <div class="bg-success dot rounded-circle me-1"></div>
                                                            <small>Online</small>
                                                        </div>
                                                    </div>
                                                    <div class="col text-end">
                                                        <a href="#"
                                                            class="btn btn-sm btn-secondary d-inline-flex align-items-center">
                                                            <svg class="icon icon-xxs me-2" fill="currentColor"
                                                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd"
                                                                    d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                                    clip-rule="evenodd"></path>
                                                            </svg>
                                                            Invite
                                                        </a>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item px-0">
                                                <div class="row align-items-center">
                                                    <div class="col-auto">
                                                        <!-- Avatar -->
                                                        <a href="#" class="avatar">
                                                            <img class="rounded" alt="Image placeholder"
                                                                src="{{ asset('assets/img/team/profile-picture-2.jpg') }}" />
                                                        </a>
                                                    </div>
                                                    <div class="col-auto ms--2">
                                                        <h4 class="h6 mb-0">
                                                            <a href="#">Jose Leos</a>
                                                        </h4>
                                                        <div class="d-flex align-items-center">
                                                            <div class="bg-warning dot rounded-circle me-1"></div>
                                                            <small>In a meeting</small>
                                                        </div>
                                                    </div>
                                                    <div class="col text-end">
                                                        <a href="#"
                                                            class="btn btn-sm btn-secondary d-inline-flex align-items-center">
                                                            <svg class="icon icon-xxs me-2" fill="currentColor"
                                                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd"
                                                                    d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zM7 8H5v2h2V8zm2 0h2v2H9V8zm6 0h-2v2h2V8z"
                                                                    clip-rule="evenodd"></path>
                                                            </svg>
                                                            Message
                                                        </a>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item px-0">
                                                <div class="row align-items-center">
                                                    <div class="col-auto">
                                                        <!-- Avatar -->
                                                        <a href="#" class="avatar">
                                                            <img class="rounded" alt="Image placeholder"
                                                                src="{{ asset('assets/img/team/profile-picture-3.jpg') }}" />
                                                        </a>
                                                    </div>
                                                    <div class="col-auto ms--2">
                                                        <h4 class="h6 mb-0">
                                                            <a href="#">Bonnie Green</a>
                                                        </h4>
                                                        <div class="d-flex align-items-center">
                                                            <div class="bg-danger dot rounded-circle me-1"></div>
                                                            <small>Offline</small>
                                                        </div>
                                                    </div>
                                                    <div class="col text-end">
                                                        <a href="#"
                                                            class="btn btn-sm btn-secondary d-inline-flex align-items-center">
                                                            <svg class="icon icon-xxs me-2" fill="currentColor"
                                                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd"
                                                                    d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zM7 8H5v2h2V8zm2 0h2v2H9V8zm6 0h-2v2h2V8z"
                                                                    clip-rule="evenodd"></path>
                                                            </svg>
                                                            Message
                                                        </a>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="list-group-item px-0">
                                                <div class="row align-items-center">
                                                    <div class="col-auto">
                                                        <!-- Avatar -->
                                                        <a href="#" class="avatar">
                                                            <img class="rounded" alt="Image placeholder"
                                                                src="{{ asset('assets/img/team/profile-picture-4.jpg') }}" />
                                                        </a>
                                                    </div>
                                                    <div class="col-auto ms--2">
                                                        <h4 class="h6 mb-0">
                                                            <a href="#">Neil Sims</a>
                                                        </h4>
                                                        <div class="d-flex align-items-center">
                                                            <div class="bg-danger dot rounded-circle me-1"></div>
                                                            <small>Offline</small>
                                                        </div>
                                                    </div>
                                                    <div class="col text-end">
                                                        <a href="#"
                                                            class="btn btn-sm btn-secondary d-inline-flex align-items-center">
                                                            <svg class="icon icon-xxs me-2" fill="currentColor"
                                                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd"
                                                                    d="M18 5v8a2 2 0 01-2 2h-5l-5 4v-4H4a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2zM7 8H5v2h2V8zm2 0h2v2H9V8zm6 0h-2v2h2V8z"
                                                                    clip-rule="evenodd"></path>
                                                            </svg>
                                                            Message
                                                        </a>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-xxl-6 mb-4">
                                <div class="card border-0 shadow">
                                    <div
                                        class="card-header border-bottom d-flex align-items-center justify-content-between">
                                        <h2 class="fs-5 fw-bold mb-0">Progress track</h2>
                                        <a href="#" class="btn btn-sm btn-primary">See tasks</a>
                                    </div>
                                    <div class="card-body">
                                        <!-- Project 1 -->
                                        <div class="row mb-4">
                                            <div class="col-auto">
                                                <svg class="icon icon-sm text-gray-500" fill="currentColor"
                                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                                    <path fill-rule="evenodd"
                                                        d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                            <div class="col">
                                                <div class="progress-wrapper">
                                                    <div class="progress-info">
                                                        <div class="h6 mb-0">Rocket - SaaS Template</div>
                                                        <div class="small fw-bold text-gray-500">
                                                            <span>75 %</span>
                                                        </div>
                                                    </div>
                                                    <div class="progress mb-0">
                                                        <div class="progress-bar bg-success" role="progressbar"
                                                            aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"
                                                            style="width: 75%"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Project 2 -->
                                        <div class="row align-items-center mb-4">
                                            <div class="col-auto">
                                                <svg class="icon icon-sm text-gray-500" fill="currentColor"
                                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                                    <path fill-rule="evenodd"
                                                        d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                            <div class="col">
                                                <div class="progress-wrapper">
                                                    <div class="progress-info">
                                                        <div class="h6 mb-0">Themesberg - Design System</div>
                                                        <div class="small fw-bold text-gray-500">
                                                            <span>60 %</span>
                                                        </div>
                                                    </div>
                                                    <div class="progress mb-0">
                                                        <div class="progress-bar bg-success" role="progressbar"
                                                            aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"
                                                            style="width: 60%"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Project 3 -->
                                        <div class="row align-items-center mb-4">
                                            <div class="col-auto">
                                                <svg class="icon icon-sm text-gray-500" fill="currentColor"
                                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                                    <path fill-rule="evenodd"
                                                        d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                            <div class="col">
                                                <div class="progress-wrapper">
                                                    <div class="progress-info">
                                                        <div class="h6 mb-0">Homepage Design in Figma</div>
                                                        <div class="small fw-bold text-gray-500">
                                                            <span>45 %</span>
                                                        </div>
                                                    </div>
                                                    <div class="progress mb-0">
                                                        <div class="progress-bar bg-warning" role="progressbar"
                                                            aria-valuenow="45" aria-valuemin="0" aria-valuemax="100"
                                                            style="width: 45%"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Project 4 -->
                                        <div class="row align-items-center mb-3">
                                            <div class="col-auto">
                                                <svg class="icon icon-sm text-gray-500" fill="currentColor"
                                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                                    <path fill-rule="evenodd"
                                                        d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                            <div class="col">
                                                <div class="progress-wrapper">
                                                    <div class="progress-info">
                                                        <div class="h6 mb-0">Backend for Themesberg v2</div>
                                                        <div class="small fw-bold text-gray-500">
                                                            <span>34 %</span>
                                                        </div>
                                                    </div>
                                                    <div class="progress mb-0">
                                                        <div class="progress-bar bg-danger" role="progressbar"
                                                            aria-valuenow="34" aria-valuemin="0" aria-valuemax="100"
                                                            style="width: 34%"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-4">

                        <div class="col-12 px-0 mb-4">
                            <div class="card border-0 shadow">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between border-bottom pb-3">
                                        <div>
                                            <div class="h6 mb-0 d-flex align-items-center">
                                                <svg class="icon icon-xs text-gray-500 me-2" fill="currentColor"
                                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM4.332 8.027a6.012 6.012 0 011.912-2.706C6.512 5.73 6.974 6 7.5 6A1.5 1.5 0 019 7.5V8a2 2 0 004 0 2 2 0 011.523-1.943A5.977 5.977 0 0116 10c0 .34-.028.675-.083 1H15a2 2 0 00-2 2v2.197A5.973 5.973 0 0110 16v-2a2 2 0 00-2-2 2 2 0 01-2-2 2 2 0 00-1.668-1.973z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                Global Rank
                                            </div>
                                        </div>
                                        <div>
                                            <a href="#" class="d-flex align-items-center fw-bold">
                                                #755
                                                <svg class="icon icon-xs text-gray-500 ms-1" fill="currentColor"
                                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                        d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between border-bottom py-3">
                                        <div>
                                            <div class="h6 mb-0 d-flex align-items-center">
                                                <svg class="icon icon-xs text-gray-500 me-2" fill="currentColor"
                                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                        d="M3 6a3 3 0 013-3h10a1 1 0 01.8 1.6L14.25 8l2.55 3.4A1 1 0 0116 13H6a1 1 0 00-1 1v3a1 1 0 11-2 0V6z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                Country Rank
                                            </div>
                                            <div class="small card-stats">
                                                United States
                                                <svg class="icon icon-xs text-success" fill="currentColor"
                                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                        d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div>
                                            <a href="#" class="d-flex align-items-center fw-bold">
                                                #32
                                                <svg class="icon icon-xs text-gray-500 ms-1" fill="currentColor"
                                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                        d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between pt-3">
                                        <div>
                                            <div class="h6 mb-0 d-flex align-items-center">
                                                <svg class="icon icon-xs text-gray-500 me-2" fill="currentColor"
                                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                        d="M2 6a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1H8a3 3 0 00-3 3v1.5a1.5 1.5 0 01-3 0V6z"
                                                        clip-rule="evenodd"></path>
                                                    <path
                                                        d="M6 12a2 2 0 012-2h8a2 2 0 012 2v2a2 2 0 01-2 2H2h2a2 2 0 002-2v-2z">
                                                    </path>
                                                </svg>
                                                Category Rank
                                            </div>
                                            <div class="small card-stats">
                                                Computers Electronics > Technology
                                                <svg class="icon icon-xs text-success" fill="currentColor"
                                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                        d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div>
                                            <a href="#" class="d-flex align-items-center fw-bold">
                                                #11
                                                <svg class="icon icon-xs text-gray-500 ms-1" fill="currentColor"
                                                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                        d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 0l-2 2a1 1 0 101.414 1.414L8 10.414l1.293 1.293a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 px-0">
                            <div class="card border-0 shadow">
                                <div class="card-body">
                                    <h2 class="fs-5 fw-bold mb-1">Acquisition</h2>
                                    <p>
                                        Tells you where your visitors originated from, such as search
                                        engines, social networks or website referrals.
                                    </p>
                                    <div class="d-block">
                                        <div class="d-flex align-items-center me-5">
                                            <div class="icon-shape icon-sm icon-shape-danger rounded me-3">
                                                <svg fill="currentColor" viewBox="0 0 20 20"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                        d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11 4a1 1 0 10-2 0v4a1 1 0 102 0V7zm-3 1a1 1 0 10-2 0v3a1 1 0 102 0V8zM8 9a1 1 0 00-2 0v2a1 1 0 102 0V9z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                            <div class="d-block">
                                                <label class="mb-0">Bounce Rate</label>
                                                <h4 class="mb-0">33.50%</h4>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center pt-3">
                                            <div class="icon-shape icon-sm icon-shape-purple rounded me-3">
                                                <svg fill="currentColor" viewBox="0 0 20 20"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <div class="d-block">
                                                <label class="mb-0">Sessions</label>
                                                <h4 class="mb-0">9,567</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}

                {{-- modal --}}
                @include('frontend.dashboard.includes.scanmodal')
            @endsection

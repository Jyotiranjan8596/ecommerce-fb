@extends('ui.layout.master')
{{-- @section('title', 'Dashboard') --}}
{{-- @include('ui.layout.header') --}}
<!-- Page Contain -->
@section('content')
    {{-- banner start  --}}
    <div class="p-2">
        <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"
                    aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"
                    aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"
                    aria-label="Slide 3"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="3"
                    aria-label="Slide 4"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="{{ asset('assets/images/freebazarbanner.jpg') }}" style="border-radius: 10px;"
                        class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('assets/images/b1.png') }}" style="border-radius: 10px;" class="d-block w-100"
                        alt="...">
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('assets/images/b2.png') }}" style="border-radius: 10px;" class="d-block w-100"
                        alt="...">
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('assets/images/b3.png') }}" style="border-radius: 10px;" class="d-block w-100"
                        alt="...">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="false"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="false"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
    {{-- Banner Ends  --}}

    {{-- Custom Links just like phonpe  --}}
    <section class="p-2 text-center">
        <div class=" " style="border-radius: 15px; background-color: #e6e6e6; padding: 10px;">
            <div class="row text-center" style="--bs-gutter-x: -0.5rem !important;">
                <div class="col">
                    <a href="javascript:void(0);" style="text-decoration: none;" onclick="comingSoon()">
                        <svg fill="#444cc5" width="50px" height="50px" viewBox="-5 -5 60.00 60.00"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" stroke="#444cc5">
                            <g id="SVGRepo_bgCarrier" stroke-width="0">
                                <rect x="-5" y="-5" width="60.00" height="60.00" rx="9" fill="#c1cacd"
                                    strokewidth="0">
                                </rect>
                            </g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path
                                    d="M11 11C4.898438 11 0 15.898438 0 22C0 24.300781 0.699219 26.5 2 28.300781L2 42C2 43.601563 3.398438 45 5 45L7.09375 45C7.574219 47.828125 10.042969 50 13 50C15.957031 50 18.425781 47.828125 18.90625 45L28.097656 45C29.699219 45 30.902344 43.699219 30.902344 42.199219L30.902344 17.902344C31 16.300781 29.699219 15 28.199219 15L19.5 15C17.5 12.601563 14.398438 11 11 11 Z M 11 13C16 13 20 17 20 22C20 27 16 31 11 31C6 31 2 27 2 22C2 17 6 13 11 13 Z M 10.984375 13.984375C10.433594 13.996094 9.992188 14.449219 10 15L10 20.75C9.621094 21.054688 9.402344 21.515625 9.398438 22C9.402344 22.058594 9.40625 22.117188 9.414063 22.171875L7.292969 24.292969C7.03125 24.542969 6.925781 24.917969 7.019531 25.265625C7.109375 25.617188 7.382813 25.890625 7.734375 25.980469C8.082031 26.074219 8.457031 25.96875 8.707031 25.707031L10.824219 23.589844C10.882813 23.597656 10.941406 23.597656 11 23.597656C11.882813 23.597656 12.597656 22.882813 12.597656 22C12.597656 21.515625 12.378906 21.054688 12 20.75L12 15C12.003906 14.730469 11.898438 14.46875 11.707031 14.277344C11.515625 14.085938 11.253906 13.980469 10.984375 13.984375 Z M 34 20C32.898438 20 32 20.898438 32 22L32 43C32 43.839844 32.527344 44.5625 33.265625 44.855469C33.6875 47.753906 36.191406 50 39.199219 50C42.15625 50 44.628906 47.828125 45.109375 45L47 45C48.699219 45 50 43.699219 50 42L50 32.402344C50 30.402344 48.601563 28.300781 48.402344 28.097656L46.097656 25L44.199219 22.5C43.199219 21.398438 41.699219 20 40 20 Z M 38 25L43.597656 25L46.800781 29.199219C47.101563 29.699219 48 31.199219 48 32.300781L48 33L38 33C37 33 36 32 36 31L36 27C36 25.898438 37 25 38 25 Z M 13 40C15.199219 40 17 41.800781 17 44C17 46.199219 15.199219 48 13 48C10.800781 48 9 46.199219 9 44C9 41.800781 10.800781 40 13 40 Z M 39.199219 40C41.398438 40 43.199219 41.800781 43.199219 44C43.199219 46.199219 41.398438 48 39.199219 48C37 48 35.199219 46.199219 35.199219 44C35.199219 41.800781 37 40 39.199219 40Z">
                                </path>
                            </g>
                        </svg>
                    </a>

                    <br>
                    <a href="javascript:void(0);" style="text-decoration: none;" onclick="comingSoon()">
                        <span style="font-size: 13px; font-weight: bold;">Service</span>
                    </a>
                </div>

                <div class="col">
                    <a href="javascript:void(0);" style="text-decoration: none;" onclick="comingSoon()">
                        <svg fill="#444cc5" height="50px" width="50px" version="1.1" id="Layer_1"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            viewBox="-51.2 -51.2 614.40 614.40" xml:space="preserve" stroke="#444cc5">
                            <g id="SVGRepo_bgCarrier" stroke-width="0">
                                <rect x="-51.2" y="-51.2" width="614.40" height="614.40" rx="92.16" fill="#c1cacd"
                                    strokewidth="0"></rect>
                            </g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <g>
                                    <g>
                                        <path
                                            d="M124.75,71.639h-22.524c-21.494,0-38.981,17.487-38.981,38.981s17.487,38.981,38.981,38.981h22.524 c4.191,0,7.588-3.398,7.588-7.588V79.228C132.338,75.037,128.94,71.639,124.75,71.639z M117.161,134.425h-14.936 c-13.126,0-23.804-10.678-23.804-23.804s10.678-23.804,23.804-23.804h14.936V134.425z">
                                        </path>
                                    </g>
                                </g>
                                <g>
                                    <g>
                                        <path
                                            d="M407.893,40.499h-37.68V8.637c0-4.191-3.398-7.588-7.588-7.588H194.908C193.781,0.386,192.473,0,191.072,0 s-2.709,0.386-3.835,1.048h-37.862c-4.191,0-7.588,3.398-7.588,7.588v31.862h-37.68c-38.973,0-70.68,31.707-70.68,70.68 c0,38.974,31.707,70.68,70.68,70.68h37.68v0.927c0,45.7,31.964,84.074,74.71,93.941v29.377c0,4.191,3.398,7.588,7.588,7.588 c3.677,0,6.669,2.992,6.669,6.669v86.165h-50.847c-4.191,0-7.588,3.398-7.588,7.588v25.798h-25.975 c-4.191,0-7.588,3.398-7.588,7.588v56.911c0,4.191,3.398,7.588,7.588,7.588H365.15c4.191,0,7.588-3.398,7.588-7.588v-56.911 c0-4.191-3.398-7.588-7.588-7.588h-25.167v-25.798c0-4.191-3.398-7.588-7.588-7.588h-51.098v-86.165 c0-3.677,2.992-6.669,6.669-6.669c4.191,0,7.588-3.398,7.588-7.588v-29.388c42.721-9.885,74.66-48.248,74.66-93.93v-0.927h37.68 c38.973,0,70.68-31.707,70.68-70.68S446.866,40.499,407.893,40.499z M141.786,81.591v75.221v9.87h-37.68 c-30.604,0-55.504-24.899-55.504-55.504s24.899-55.504,55.504-55.504h37.68V81.591z M198.661,89.18h154.396v60.044H198.661V89.18z M156.963,48.087V16.225h26.521v57.778h-26.521V48.087z M156.963,89.18h26.521v60.044h-26.521V89.18z M357.561,455.089v41.734 H153.932v-41.734h25.975h152.487H357.561z M324.806,421.703v18.209h-0.001H187.496v-18.209h50.847h35.365H324.806z M280.376,299.876c-8.317,3.09-14.257,11.107-14.257,20.487v86.165h-20.188v-86.165c0-9.379-5.941-17.397-14.257-20.487v-20.912 c2.154,0.145,4.325,0.225,6.515,0.225h35.621c2.208,0,4.395-0.082,6.566-0.229L280.376,299.876L280.376,299.876z M355.036,174.271 v8.515c0,44.789-36.437,81.226-81.226,81.226h-35.621c-44.789,0-81.226-36.437-81.226-81.226v-8.515v-9.87h26.521v15.89 c0,4.191,3.398,7.588,7.588,7.588c4.191,0,7.588-3.398,7.588-7.588v-15.89h156.376V174.271z M355.036,48.087v25.916H198.661 V16.225h156.376V48.087z M407.893,166.683h-37.68V55.676h37.68c30.604,0,55.504,24.899,55.504,55.504 S438.497,166.683,407.893,166.683z">
                                        </path>
                                    </g>
                                </g>
                                <g>
                                    <g>
                                        <path
                                            d="M409.773,72.756h-22.524c-4.191,0-7.588,3.398-7.588,7.588v62.785c0,4.191,3.398,7.588,7.588,7.588h22.524 c21.494,0,38.981-17.487,38.981-38.981C448.754,90.243,431.267,72.756,409.773,72.756z M409.773,135.542h-14.936V87.933h14.936 c13.126,0,23.804,10.678,23.804,23.804C433.577,124.863,422.899,135.542,409.773,135.542z">
                                        </path>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </a>

                    <br>
                    <a href="javascript:void(0);" style="text-decoration: none;" onclick="comingSoon()">
                        <span style="font-size: 13px; font-weight: bold;">Achievements</span>
                    </a>
                </div>

                <div class="col">
                    <a href="{{ route('frontend.offer') }}" style="text-decoration: none;">
                        <svg width="50px" height="50px" viewBox="-2.4 -2.4 28.80 28.80" fill="none"
                            xmlns="http://www.w3.org/2000/svg" stroke="#444cc5">
                            <g id="SVGRepo_bgCarrier" stroke-width="0">
                                <rect x="-2.4" y="-2.4" width="28.80" height="28.80" rx="4.32" fill="#c1cacd"
                                    strokewidth="0"></rect>
                            </g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <path
                                    d="M12 21C15.5 17.4 19 14.1764 19 10.2C19 6.22355 15.866 3 12 3C8.13401 3 5 6.22355 5 10.2C5 14.1764 8.5 17.4 12 21Z"
                                    stroke="#444cc5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                </path>
                                <path
                                    d="M12 12C13.1046 12 14 11.1046 14 10C14 8.89543 13.1046 8 12 8C10.8954 8 10 8.89543 10 10C10 11.1046 10.8954 12 12 12Z"
                                    stroke="#444cc5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                </path>
                            </g>
                        </svg>
                    </a>

                    <br>
                    <a href="{{ route('frontend.offer') }}" style="text-decoration: none;">
                        <span style="font-size: 13px; font-weight: bold;">Offer</span>
                    </a>
                </div>

                <div class="col">
                    <a href="javascript:void(0);" style="text-decoration: none;" onclick="comingSoon()">
                        <svg fill="#444cc5" width="50px" height="50px" viewBox="-51.2 -51.2 614.40 614.40"
                            enable-background="new 0 0 512 512" id="Achievement" version="1.1" xml:space="preserve"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <g id="SVGRepo_bgCarrier" stroke-width="0">
                                <rect x="-51.2" y="-51.2" width="614.40" height="614.40" rx="92.16" fill="#c1cacd"
                                    strokewidth="0"></rect>
                            </g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <g>
                                    <path
                                        d="M471.909,217.5H351.421c-3.711,0-5.921,4.132-5.921,7.865v266.604c0,3.713,2.21,6.531,5.921,6.531h120.488 c3.711,0,5.591-2.818,5.591-6.531V225.365C477.5,221.632,475.62,217.5,471.909,217.5z">
                                    </path>
                                    <path
                                        d="M318.5,366.648c0-3.729-2.641-7.148-6.368-7.148h-121.5c-3.728,0-7.132,3.42-7.132,7.148v126.5 c0,3.727,3.404,6.352,7.132,6.352h121.5c3.728,0,6.368-2.625,6.368-6.352V366.648z">
                                    </path>
                                    <path
                                        d="M158.5,435.648c0-3.729-2.64-7.148-6.368-7.148h-121.5c-3.728,0-7.132,3.42-7.132,7.148v56.5 c0,3.727,3.404,6.352,7.132,6.352h121.5c3.728,0,6.368-2.625,6.368-6.352V435.648z">
                                    </path>
                                    <path
                                        d="M59.5,395.459c3.416,0,7-2.771,7-6.188v-30.756c0-45.203,35.901-83.016,81.104-83.016h40.838 c3.418,0,6.058-1.734,6.058-5.15v-41.471c0-47.924,39.12-88.378,87.044-88.378h0.956v14.757c0,2.164,1.125,4.08,3.133,4.887 c0.641,0.258,1.222,0.383,1.882,0.383c1.406,0,2.735-0.566,3.753-1.633l18.589-19.477c1.949-2.041,1.939-5.238-0.012-7.277 l-18.613-19.479c-1.498-1.566-3.591-2.055-5.597-1.25c-2.01,0.805-3.135,2.723-3.135,4.887V129.5h-0.956 c-54.748,0-101.044,44.63-101.044,99.378V264.5h-32.896c-52.027,0-95.104,41.99-95.104,94.016v30.756 C52.5,392.688,56.084,395.459,59.5,395.459z">
                                    </path>
                                    <path
                                        d="M410.5,40.318c3.416,0,7-2.77,7-6.188V19.107c0-3.418-3.584-6.188-7-6.188s-7,2.77-7,6.188V34.13 C403.5,37.548,407.084,40.318,410.5,40.318z">
                                    </path>
                                    <path
                                        d="M439.237,46.416c0.902,0.465,1.867,0.684,2.816,0.684c2.248,0,4.416-1.23,5.512-3.367l6.957-13.578 c1.557-3.041,0.354-6.77-2.688-8.328c-3.041-1.563-6.77-0.355-8.328,2.686l-6.955,13.576 C434.993,41.13,436.196,44.859,439.237,46.416z">
                                    </path>
                                    <path
                                        d="M375.431,42.513c1.053,2.244,3.279,3.563,5.605,3.563c0.881,0,1.773-0.189,2.623-0.586 c3.094-1.453,4.426-5.137,2.975-8.232l-6.463-13.781c-1.449-3.092-5.133-4.42-8.229-2.975c-3.094,1.453-4.426,5.135-2.975,8.23 L375.431,42.513z">
                                    </path>
                                    <path
                                        d="M470.728,80.5h-12.385l0.875-14.082c0.234-3.814-2.797-6.918-6.619-6.918h-83.877c-3.822,0-6.854,3.104-6.617,6.918 l0.873,14.082h-10.352c-9.676,0-17.529,8.062-17.529,17.737c0,5.152,2.25,10.139,6.211,13.467l15.365,13.056l0.002,0.055 l9.945,8.416c-0.002-0.012-0.004-0.021-0.006-0.032l0.006,0.005l0.027,0.036c3.271,16.344,15.336,29.375,30.951,34.113 c3.102,0.943,5.238,3.791,5.465,7.025l2.299,23.269c0.092,1.301-0.939,1.853-2.244,1.853h-19.854c-1.244,0-1.765,2.121-1.765,3.361 v4.5c0,1.244,0.521,1.139,1.765,1.139h54.793c1.242,0,1.442,0.105,1.442-1.139v-4.5c0-1.24-0.2-3.361-1.442-3.361h-19.852 c-1.305,0-2.336-0.55-2.246-1.851l2.301-23.544c0.227-3.234,2.361-5.862,5.463-6.806c14.928-4.533,26.605-16.799,30.465-31.799 h0.025l0.006-0.089c-0.002,0.012-0.022-0.016-0.026-0.006l10.307-8.79v-0.014l17.525-14.799c3.938-3.328,6.196-8.418,6.196-13.571 C488.222,88.558,480.38,80.5,470.728,80.5z M347.112,104.947c-1.912-1.619-3.016-4.213-3.016-6.709 c0-4.705,3.826-8.737,8.529-8.737h1.861h0.027h9.023h-0.027h0.021l1.879,30.704l-9.502-7.802L347.112,104.947z M410.405,137.337 c-13.773,0-24.939-11.168-24.939-24.941c0-13.777,11.166-24.943,24.939-24.943c13.777,0,24.943,11.166,24.943,24.943 C435.349,126.169,424.183,137.337,410.405,137.337z M476.218,104.947l-10.912,9.225l-9.506,7.814l1.988-32.485h0.029h-0.027h9.023 h0.027h3.887c4.703,0,8.506,4.032,8.506,8.737C479.233,100.733,478.13,103.328,476.218,104.947z">
                                    </path>
                                    <circle cx="410.405" cy="112.396" r="12.375"></circle>
                                </g>
                            </g>
                        </svg>
                    </a>

                    <br>
                    <a href="javascript:void(0);" style="text-decoration: none;" onclick="comingSoon()"><span
                            style="font-size: 13px; font-weight: bold;">Payback</span></a>
                </div>
            </div>

        </div>
    </section>

    {{-- features  --}}


    <div class="p-3">
        <div class="row g-4 row-cols-1 row-cols-lg-3" style="padding: 10px !important;">
            <div class="col d-flex align-items-start"
                style="background-color: #edc78b; border-radius: 10px; padding: 16px;">
                <div
                    class="icon-square text-body-emphasis d-inline-flex align-items-center justify-content-center fs-4 flex-shrink-0 me-3">
                    <svg fill="#000000" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink" width="35px" height="35px"
                        viewBox="0 0 94.667 94.667" xml:space="preserve" stroke="#000000"
                        stroke-width="1.3253380000000001">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <g>
                                <g>
                                    <path
                                        d="M82.413,9.146h9.346V83.33h-9.346V9.146z M63.803,11.831l-1.294,0.402c-1.62,0.512-3.524-0.201-4.179-1.558 c-0.314-0.657-0.329-1.383-0.041-2.047c0.334-0.768,1.044-1.369,1.945-1.65l14.591-4.545l1.776,13.001 c0.1,0.662-0.086,1.338-0.525,1.898c-0.537,0.688-1.4,1.134-2.368,1.226c-0.116,0.012-0.246,0.018-0.371,0.018 c-1.651,0-3.053-1.052-3.261-2.444l-0.225-1.967C52.988,37.514,14.157,62.539,12.472,63.617c-0.572,0.366-1.256,0.561-1.98,0.561 c-0.976,0-1.894-0.36-2.517-0.991c-0.573-0.577-0.841-1.313-0.758-2.069c0.087-0.785,0.558-1.507,1.294-1.975 C8.906,58.889,47.367,34.026,63.803,11.831z M74.859,25.623v57.705h-9.344V25.623H74.859z M58.518,42.77v40.56h-9.347V42.77 H58.518z M41.617,60.583v22.744h-9.345V60.583H41.617z M23.75,69.494v13.834h-9.344V69.494H23.75z M94.666,92.234H0V85.3h94.667 L94.666,92.234L94.666,92.234z">
                                    </path>
                                </g>
                            </g>
                        </g>
                    </svg>
                </div>
                <div>
                    <h2 class=" text-body-emphasis" style="color: white !important; fornt-size: 10px !important;">IPO</h2>
                    <p style="color: black !important;">The IPO (Inflation Protection Option) is a movement against
                        inflation and global recession,
                        inviting individuals to a new era of free economy.
                    </p>
                </div>
            </div>
            <div class="col d-flex align-items-start"
                style="background-color: #c7ed67;border-radius: 10px; padding: 16px;">
                <div
                    class="icon-square text-body-emphasis d-inline-flex align-items-center justify-content-center fs-4 flex-shrink-0 me-3">
                    <svg width="30px" height="30px" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg" stroke="#000000" stroke-width="0.096">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path
                                d="M20 15C20.5523 15 21 14.5523 21 14C21 13.4477 20.5523 13 20 13C19.4477 13 19 13.4477 19 14C19 14.5523 19.4477 15 20 15Z"
                                fill="#0F0F0F"></path>
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M16.775 0.985398C18.4919 0.460783 20.2821 1.55148 20.6033 3.3178L20.9362 5.14896C22.1346 5.54225 23 6.67006 23 8V10.7639C23.6137 11.3132 24 12.1115 24 13V15C24 15.8885 23.6137 16.6868 23 17.2361V20C23 21.6569 21.6569 23 20 23H4C2.34315 23 1 21.6569 1 20V8C1 6.51309 2.08174 5.27884 3.50118 5.04128L16.775 0.985398ZM21 16C21.5523 16 22 15.5523 22 15V13C22 12.4477 21.5523 12 21 12H18C17.4477 12 17 12.4477 17 13V15C17 15.5523 17.4477 16 18 16H21ZM21 18V20C21 20.5523 20.5523 21 20 21H4C3.44772 21 3 20.5523 3 20V8C3 7.44772 3.44772 7 4 7H20C20.55 7 20.9962 7.44396 21 7.99303L21 10H18C16.3431 10 15 11.3431 15 13V15C15 16.6569 16.3431 18 18 18H21ZM18.6954 3.60705L18.9412 5H10L17.4232 2.82301C17.9965 2.65104 18.5914 3.01769 18.6954 3.60705Z"
                                fill="#0F0F0F"></path>
                        </g>
                    </svg>
                </div>
                <div>
                    <h3 class="fs-2 text-body-emphasis" style="color: #ffffff !important;">SCO</h3>
                    <p style="color: black !important;">The SCO (Smart Customer Option) is a social support system that
                        generates income from
                        expenditure, helping consumers save and earn from their spending.
                    </p>
                </div>
            </div>
            <div class="col d-flex align-items-start"
                style="background-color: #88e0f0; border-radius: 10px; padding: 16px;">
                <div
                    class="icon-square text-body-emphasis d-inline-flex align-items-center justify-content-center fs-4 flex-shrink-0 me-3">
                    <svg width="35px" height="35px" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M5 3C3.89543 3 3 3.89543 3 5V6.83772L1.49006 11.3675C1.10052 12.5362 1.8474 13.7393 3 13.963V20C3 21.1046 3.89543 22 5 22H9H10H14H15H19C20.1046 22 21 21.1046 21 20V13.963C22.1526 13.7393 22.8995 12.5362 22.5099 11.3675L21 6.83772V5C21 3.89543 20.1046 3 19 3H5ZM15 20H19V14H17.5H12H6.5H5V20H9V17C9 15.3431 10.3431 14 12 14C13.6569 14 15 15.3431 15 17V20ZM11 20H13V17C13 16.4477 12.5523 16 12 16C11.4477 16 11 16.4477 11 17V20ZM3.38743 12L4.72076 8H6.31954L5.65287 12H4H3.38743ZM7.68046 12L8.34713 8H11V12H7.68046ZM13 12V8H15.6529L16.3195 12H13ZM18.3471 12L17.6805 8H19.2792L20.6126 12H20H18.3471ZM19 5V6H16.5H12H7.5H5V5H19Z"
                                fill="#000000"></path>
                        </g>
                    </svg>
                </div>
                <div>
                    <h3 class="fs-2 text-body-emphasis" style="color: #ffffff !important;">POS</h3>
                    <p style="color: black !important;">The POS (Point of Sales) is an employment-generating mission
                        that empowers small and medium-sized
                        business houses or individuals to grow through our social network.</p>
                </div>
            </div>
        </div>
    </div>





    {{-- Discount Banner  --}}
    <section>
        <div class="container-lg">
            <div class="bg-secondary text-light py-5 my-5 position-relative"
                style="background: url('{{ asset('assets/images/grocery.jpeg') }}') no-repeat center center;
            background-size: cover;">

                <!-- Overlay -->
                <div
                    style="position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5); /* Adjust darkness */
                z-index: 1;">
                </div>

                <div class="container position-relative" style="z-index: 2;">
                    <div class="row justify-content-center">
                        <div class="col-md-5 p-3">
                            <div class="section-header">
                                <h2 class="section-title display-5 text-light">
                                    Get up to 100% Savings on your purchase
                                </h2>
                            </div>
                            <p>Just Sign Up & Register to be a smart customer.</p>
                            <a href="{{ route('register') }}">
                                <button type="submit" class="btn btn-light btn-md rounded-2">
                                    Register Now
                                </button>
                            </a>
                        </div>
                        {{-- <div class="col-md-5 p-3">
                <div class="d-grid gap-2">
                    <a href="{{ route('register') }}">
                        <button type="submit" class="btn btn-dark btn-md rounded-0">
                            Register Now
                        </button>
                    </a>
                </div>
            </div> --}}
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- Service Offered  --}}
    <section class="py-5">
        <div class="container-lg">
            <div class="row row-cols-1 row-cols-sm-3 row-cols-lg-5">
                <div class="col">
                    <div class="card mb-3 border border-dark-subtle p-3">
                        <div class="text-dark mb-3">
                            <svg width="32" height="32">
                                <use xlink:href="#offers"></use>
                            </svg>
                        </div>
                        <div class="card-body p-0">
                            <h5>Quality guarantee</h5>
                            <p class="card-text">
                                We offer assured product from genuine manufactures, suppliers & service providers for
                                optimum level satisfaction of our customers.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card mb-3 border border-dark-subtle p-3">
                        <div class="text-dark mb-3">
                            <svg width="32" height="32">
                                <use xlink:href="#savings"></use>
                            </svg>
                        </div>
                        <div class="card-body p-0">
                            <h5>Best Price</h5>
                            <p class="card-text">
                                Enjoy an extra 10% savings on every bill, on top of our regular discounts! This payback
                                benefit is our way of saying thank you for choosing us.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card mb-3 border border-dark-subtle p-3">
                        <div class="text-dark mb-3">
                            <svg width="32" height="32">
                                <use xlink:href="#quality"></use>
                            </svg>
                        </div>
                        <div class="card-body p-0">
                            <h5>Saving Opportunity</h5>
                            <p class="card-text">
                                Save up-to 100% on yours monthly spending and ride over the inflation with ease.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card mb-3 border border-dark-subtle p-3">
                        <div class="text-dark mb-3">
                            <svg width="32" height="32">
                                <use xlink:href="#secure"></use>
                            </svg>
                        </div>
                        <div class="card-body p-0">
                            <h5>Business Opportunity</h5>
                            <p class="card-text">
                                Start and grow your business with us on ZERO investment with huge income possibility.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card mb-3 border border-dark-subtle p-3">
                        <div class="text-dark mb-3">
                            <svg width="32" height="32">
                                <use xlink:href="#package"></use>
                            </svg>
                        </div>
                        <div class="card-body p-0">
                            <h5>Employement Opportunity</h5>
                            <p class="card-text">
                                We focus to empower unemployed youth and skilled professionals at their locality nationally
                                and fund them to start a new business.
                            </p>
                        </div>
                    </div>
                </div>
                {{-- End  --}}
            </div>
        </div>
    </section>

    {{-- Faq  --}}
    <div class="p-3">
        <div class="accordion" id="accordionExample">
            <div class="accordion-item" style="border: 1px solid #757575;" id="aboutus">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"
                        style="color: black; font-weight: bold;">
                        About Us
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                    <div class="accordion-body" style="color: black; background: rgb(226, 226, 226);">
                        FreeBazar is a pioneering initiative by M/S Satshree Marketing Pvt. Ltd., a company founded in 2002
                        by a team of visionary professionals. We strive to empower consumers worldwide by providing a shield
                        against inflation, promoting economic stability, and contributing to national progress. Our aim is
                        to create opportunities for inclusive growth, fostering a free economy that benefits all.
                    </div>
                </div>
            </div>
            <div class="accordion-item" style="border: 1px solid #757575;" id="mission">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseTwo" aria-expanded="false" style="color: black; font-weight: bold;"
                        aria-controls="collapseTwo">
                        Mission
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body"
                        style="color: black; background: rgb(226, 226, 226); text-align: justify;">
                        Our mission is to lead a movement against inflation, making a real difference in people's lives.
                        We're fostering a free economy that empowers consumers worldwide by providing quality products and
                        services at competitive prices, ensuring savings, security, and scope for huge earnings.
                    </div>
                </div>
            </div>
            <div class="accordion-item" style="border: 1px solid #757575;" id="vision">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseThree" aria-expanded="false" style="color: black; font-weight: bold;"
                        aria-controls="collapseThree">
                        Vision
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body"
                        style="color: black; background: rgb(226, 226, 226); text-align: justify;">
                        We believe everyone deserves a chance to succeed, regardless of socio-economic barriers. Our goal is
                        to help you beat inflation, build a stronger economy, and create opportunities for everyone to
                        thrive. We aim to make a positive impact on communities and beyond by bridging gaps and promoting
                        social interaction.

                    </div>
                </div>
            </div>
            <div class="accordion-item" style="border: 1px solid #757575;" id="concept">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseFour" style="color: black; font-weight: bold;" aria-expanded="false"
                        aria-controls="collapseFour">
                        Concept
                    </button>
                </h2>
                <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body"
                        style="color: black; background: rgb(226, 226, 226); text-align: justify;">
                        Free Bazar's Inflation Protection Option (IPO) is an investment and risk-free saving opportunity.
                        Our SIP module combines the best of traditional and online marketing systems, offering benefits to
                        customers and businesses through a well-designed profit-sharing program via POS terminals. We
                        emphasize quality products with up to 100% payback facilities through SIP. <br>
                        <strong>1. Spend:</strong> Register as a regular customer, spend ₹500/month, and save 10% on every spending. <br>
                        <strong>2. Invite:</strong> Become a smart customer, share your experience, and earn up to 100% (max ₹10,000) monthly
                        shopping free. <br>
                        <strong>3. Partnership:</strong> Join as a POS partner with zero investment and grow your business with huge
                        income possibilities.

                    </div>
                </div>
            </div>
            {{-- <div class="accordion-item" style="border: 1px solid #757575;" id="mission">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseTwo" aria-expanded="false" style="color: black; font-weight: bold;"
                        aria-controls="collapseTwo">
                        How it works
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body"
                        style="color: black; background: rgb(226, 226, 226); text-align: justify;">
                        
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
@endsection

@extends('layouts.master')

@section('content')
    <div class="payback-upload-page">
        <section class="payback-upload-heading">
            <div>
                <span>Payback management</span>
                <h1>Payback Upload</h1>
                <p>Import wallet and reward transactions from a CSV file.</p>
            </div>
            <a href="{{ route('admin.wallet') }}" class="btn payback-report-link">
                <i class="bi bi-arrow-left me-1" aria-hidden="true"></i> Payback report
            </a>
        </section>

        <section class="card payback-upload-card">
            <div class="card-body p-4 p-lg-5">
                <div class="payback-upload-icon" aria-hidden="true">
                    <i class="bi bi-file-earmark-arrow-up"></i>
                </div>
                <h2 class="h5 fw-bold mb-2">Upload transaction CSV</h2>
                <p class="text-muted mb-4">
                    Choose a <strong>.csv</strong> file with the column headings listed below.
                </p>

                <form action="{{ route('admin.wallet.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label for="payback-file" class="form-label fw-semibold">CSV file</label>
                    <div class="payback-file-input">
                        <input id="payback-file" type="file" class="form-control @error('file') is-invalid @enderror"
                            name="file" accept=".csv,text/csv"
                            required>
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-flex flex-wrap gap-2 mt-4">
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-upload me-1" aria-hidden="true"></i> Upload payback data
                        </button>
                        <a href="{{ route('admin.wallet.upload.template') }}" class="btn btn-outline-primary">
                            <i class="bi bi-download me-1" aria-hidden="true"></i> Download blank CSV template
                        </a>
                        <a href="{{ route('admin.wallet') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>

                <div class="payback-upload-columns mt-4">
                    <h3 class="h6 fw-bold mb-2">Required CSV columns</h3>
                    <p class="mb-0">
                        <code>MONTH</code>, <code>MOBILE NUMBER</code>, <code>USER ID</code>,
                        <code>SPONSOR ID</code>, <code>TOTAL BILLING AMOUNT</code>, <code>REWARD POINT</code>,
                        <code>WALLET AMOUNT</code>, <code>TRANSACTION DATE</code>, <code>PAYMENT MODE</code>
                    </p>
                    <p class="small text-muted mt-2 mb-0">
                        Keep the headings in the first row and use this order.
                    </p>
                    <p class="small text-muted mt-2 mb-0">
                        Enter transaction dates as <code>DD-MM-YY</code>, for example <code>01-06-26</code>.
                    </p>
                </div>
            </div>
        </section>
    </div>

    <style>
        .payback-upload-page {
            display: grid;
            gap: 1.25rem;
            padding-bottom: 1rem;
        }

        .payback-upload-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1.5rem 1.6rem;
            border: 1px solid rgba(148, 163, 184, 0.22);
            border-radius: 22px;
            background:
                radial-gradient(circle at top right, rgba(255, 255, 255, 0.16), transparent 28%),
                linear-gradient(135deg, #102b2b 0%, #142f43 55%, #1e40af 100%);
            color: #fff;
            box-shadow: 0 18px 42px rgba(15, 23, 42, 0.12);
        }

        .payback-upload-heading > div > span {
            display: inline-block;
            margin-bottom: 0.45rem;
            color: #a8e7d2;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        .payback-upload-heading h1 {
            margin: 0;
            color: #fff;
            font-size: clamp(1.5rem, 2vw, 2rem);
            font-weight: 800;
        }

        .payback-upload-heading p {
            margin: 0.35rem 0 0;
            color: rgba(255, 255, 255, 0.76);
        }

        .payback-report-link {
            border: 1px solid rgba(255, 255, 255, 0.72);
            background: #fff;
            color: #173c9b;
            font-weight: 700;
        }

        .payback-report-link:hover,
        .payback-report-link:focus {
            border-color: #fff;
            background: #dbeafe;
            color: #102b72;
        }

        .payback-upload-card {
            max-width: 850px;
            border: 1px solid rgba(148, 163, 184, 0.22) !important;
        }

        .payback-upload-icon {
            display: grid;
            width: 52px;
            height: 52px;
            margin-bottom: 1rem;
            place-items: center;
            border-radius: 16px;
            background: #eff6ff;
            color: #2563eb;
            font-size: 1.4rem;
        }

        .payback-upload-columns {
            padding: 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            background: #f8fafc;
        }

        .payback-upload-columns code {
            display: inline-block;
            margin: 0.15rem 0.1rem;
        }

        @media (max-width: 575.98px) {
            .payback-upload-heading {
                align-items: flex-start;
                flex-direction: column;
                padding: 1.25rem;
            }
        }
    </style>
@endsection

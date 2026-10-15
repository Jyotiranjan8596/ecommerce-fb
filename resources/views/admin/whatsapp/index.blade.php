@extends('layouts.master')

@section('content')
    <div class="whatsapp-management">
        <section class="whatsapp-management__heading">
            <div>
                <span class="whatsapp-management__eyebrow">Audience communication</span>
                <h1>WhatsApp Management</h1>
                <p>Send an approved WhatsApp template to customers and POS recipients.</p>
            </div>
            <span class="whatsapp-management__heading-icon" aria-hidden="true">
                <i class="fab fa-whatsapp"></i>
            </span>
        </section>

        @if (session('success'))
            <div class="alert alert-success" role="status">{{ session('success') }}</div>
        @endif
        @if (session('warning'))
            <div class="alert alert-warning" role="alert">{{ session('warning') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="card whatsapp-management__card">
            <div class="whatsapp-management__card-heading">
                <div>
                    <h2>Compose WhatsApp template</h2>
                    <p>Choose an audience, then enter the exact approved template and parameters.</p>
                </div>
                <span class="whatsapp-management__approved"><i class="fas fa-check-circle" aria-hidden="true"></i> Approved templates only</span>
            </div>

            <form action="{{ route('admin.whatsapp.store') }}" method="POST" class="whatsapp-management__form">
                @csrf

                <div class="whatsapp-management__section-label">
                    <span class="whatsapp-management__step">1</span>
                    <div>
                        <h3>Choose recipients</h3>
                        <p>Send by customer category or target one account.</p>
                    </div>
                </div>

                <div class="whatsapp-management__grid">
                    <div class="whatsapp-management__field">
                        <label for="category">User category <span>*</span></label>
                        <select class="form-control @error('category') is-invalid @enderror" id="category" name="category" required>
                            <option value="pos" {{ old('category', 'pos') === 'pos' ? 'selected' : '' }}>POS</option>
                            <option value="prime" {{ old('category') === 'prime' ? 'selected' : '' }}>Customers — Prime</option>
                            <option value="non_prime" {{ old('category') === 'non_prime' ? 'selected' : '' }}>Customers — Non-prime</option>
                            <option value="all" {{ old('category') === 'all' ? 'selected' : '' }}>All POS and customers</option>
                        </select>
                    </div>

                    <fieldset class="whatsapp-management__field whatsapp-management__delivery">
                        <legend>Send to <span>*</span></legend>
                        <label class="whatsapp-management__choice">
                            <input type="radio" name="delivery" value="all" {{ old('delivery', 'all') === 'all' ? 'checked' : '' }}>
                            <span>Everyone in this category</span>
                        </label>
                        <label class="whatsapp-management__choice">
                            <input type="radio" name="delivery" value="individual" {{ old('delivery') === 'individual' ? 'checked' : '' }}>
                            <span>One user by User ID</span>
                        </label>
                    </fieldset>

                    <div class="whatsapp-management__field whatsapp-management__user-id" @if (old('delivery') !== 'individual') hidden @endif>
                        <label for="user_id">User ID <span>*</span></label>
                        <input class="form-control @error('user_id') is-invalid @enderror" id="user_id" name="user_id"
                            type="text" value="{{ old('user_id') }}" maxlength="30" autocomplete="off"
                            placeholder="Enter the recipient's User ID"
                            @if (old('delivery') === 'individual') required @endif>
                        <small>Must belong to the selected category and have a phone number.</small>
                    </div>
                </div>

                <div class="whatsapp-management__section-label whatsapp-management__section-label--template">
                    <span class="whatsapp-management__step">2</span>
                    <div>
                        <h3>Configure the template</h3>
                        <p>Names and language codes must match your WhatsApp Business template exactly.</p>
                    </div>
                </div>

                <div class="whatsapp-management__grid">
                    <div class="whatsapp-management__field">
                        <label for="template_name">Approved template name <span>*</span></label>
                        <input class="form-control @error('template_name') is-invalid @enderror" id="template_name" name="template_name"
                            type="text" value="{{ old('template_name') }}" required maxlength="100"
                            pattern="[a-z0-9_]+" placeholder="e.g. order_update">
                        <small>Use lowercase letters, numbers, and underscores.</small>
                    </div>
                    <div class="whatsapp-management__field">
                        <label for="language">Template language code <span>*</span></label>
                        <input class="form-control @error('language') is-invalid @enderror" id="language" name="language"
                            type="text" value="{{ old('language', 'en_US') }}" required maxlength="20"
                            pattern="[a-z]{2,3}(_[A-Z]{2})?" placeholder="en_US">
                        <small>Example: <code>en_US</code> or <code>en</code>.</small>
                    </div>
                    <div class="whatsapp-management__field whatsapp-management__field--full">
                        <label for="body_parameters">Body parameters <span class="whatsapp-management__optional">Optional</span></label>
                        <textarea class="form-control @error('body_parameters') is-invalid @enderror" id="body_parameters"
                            name="body_parameters" rows="5" maxlength="2000"
                            placeholder="One parameter per line, in template order&#10;Hello &#123;&#123;name&#125;&#125;, your order is ready.">{{ old('body_parameters') }}</textarea>
                        <small>
                            Enter one value per line in placeholder order. For bulk sends, use
                            <code>&#123;&#123;name&#125;&#125;</code> or <code>&#123;&#123;user_id&#125;&#125;</code>
                            to personalize a value for each recipient.
                        </small>
                    </div>
                </div>

                <div class="whatsapp-management__notice">
                    <i class="fas fa-info-circle" aria-hidden="true"></i>
                    <p>Only users with a phone number are included. Bulk sends start immediately; delivery failures are counted in the result and recorded in the application log.</p>
                </div>

                <div class="whatsapp-management__actions">
                    <p><i class="fas fa-shield-alt" aria-hidden="true"></i> Review the audience and template before sending.</p>
                    <button class="btn btn-success" type="submit">
                        <i class="fab fa-whatsapp mr-2" aria-hidden="true"></i> Send WhatsApp template
                    </button>
                </div>
            </form>
        </section>
    </div>

    <style>
        .whatsapp-management {
            --whatsapp-ink: #172b3a;
            --whatsapp-muted: #64748b;
            --whatsapp-border: #e2eaf1;
            padding: .5rem 0;
            color: var(--whatsapp-ink);
        }

        .whatsapp-management__heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.25rem;
            padding: 1.5rem;
            border: 1px solid #dce8ef;
            border-radius: 1.1rem;
            background: linear-gradient(120deg, #effaf5, #eef7f2);
        }

        .whatsapp-management__eyebrow {
            color: #16834c;
            font-size: .7rem;
            font-weight: 800;
            letter-spacing: .1em;
            text-transform: uppercase;
        }

        .whatsapp-management__heading h1 {
            margin: .25rem 0;
            color: var(--whatsapp-ink);
            font-size: clamp(1.4rem, 3vw, 1.8rem);
            font-weight: 800;
        }

        .whatsapp-management__heading p,
        .whatsapp-management__card-heading p {
            margin: 0;
            color: var(--whatsapp-muted);
        }

        .whatsapp-management__heading-icon {
            display: grid;
            width: 3.25rem;
            height: 3.25rem;
            flex: 0 0 3.25rem;
            place-items: center;
            border-radius: 1rem;
            background: #d8f3e3;
            color: #16834c;
            font-size: 1.55rem;
        }

        .whatsapp-management__card {
            overflow: hidden;
            border: 1px solid var(--whatsapp-border);
            border-radius: 1rem;
            background: #fff;
            box-shadow: 0 .8rem 2rem rgba(24, 52, 74, .06);
        }

        .whatsapp-management__card-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--whatsapp-border);
        }

        .whatsapp-management__card-heading h2 {
            margin: 0 0 .25rem;
            font-size: 1.05rem;
            font-weight: 800;
        }

        .whatsapp-management__card-heading p {
            font-size: .86rem;
        }

        .whatsapp-management__approved {
            flex: 0 0 auto;
            padding: .45rem .7rem;
            border-radius: 2rem;
            background: #e8f7ee;
            color: #167344;
            font-size: .75rem;
            font-weight: 700;
        }

        .whatsapp-management__form {
            padding: 1.5rem;
        }

        .whatsapp-management__section-label {
            display: flex;
            align-items: center;
            gap: .75rem;
            margin-bottom: 1rem;
        }

        .whatsapp-management__section-label--template {
            margin-top: 1.65rem;
        }

        .whatsapp-management__step {
            display: grid;
            width: 2rem;
            height: 2rem;
            flex: 0 0 2rem;
            place-items: center;
            border-radius: 50%;
            background: #e8f7ee;
            color: #16834c;
            font-size: .85rem;
            font-weight: 800;
        }

        .whatsapp-management__section-label h3 {
            margin: 0 0 .15rem;
            font-size: .95rem;
            font-weight: 800;
        }

        .whatsapp-management__section-label p {
            margin: 0;
            color: var(--whatsapp-muted);
            font-size: .82rem;
        }

        .whatsapp-management__grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1.15rem 1.25rem;
        }

        .whatsapp-management__field {
            min-width: 0;
            margin: 0;
        }

        .whatsapp-management__field--full {
            grid-column: 1 / -1;
        }

        .whatsapp-management__field > label,
        .whatsapp-management__delivery legend {
            display: block;
            margin-bottom: .4rem;
            color: #2b3d50;
            font-size: .87rem;
            font-weight: 700;
        }

        .whatsapp-management__field > label span,
        .whatsapp-management__delivery legend span {
            color: #b42318;
        }

        .whatsapp-management__field > label .whatsapp-management__optional {
            margin-left: .35rem;
            color: var(--whatsapp-muted);
            font-size: .75rem;
            font-weight: 500;
        }

        .whatsapp-management__field .form-control {
            min-height: 2.9rem;
            border-color: #cbd7e2;
            border-radius: .65rem;
        }

        .whatsapp-management__field textarea.form-control {
            min-height: 8rem;
            resize: vertical;
        }

        .whatsapp-management__field small {
            display: block;
            margin-top: .35rem;
            color: var(--whatsapp-muted);
        }

        .whatsapp-management__delivery {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: .6rem 1rem;
            padding: 0;
            border: 0;
        }

        .whatsapp-management__delivery legend {
            width: 100%;
            margin-bottom: .2rem;
        }

        .whatsapp-management__choice {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            color: #42536a;
            font-size: .87rem;
        }

        .whatsapp-management__choice input {
            accent-color: #16834c;
        }

        .whatsapp-management__notice {
            display: flex;
            align-items: flex-start;
            gap: .7rem;
            margin-top: 1.4rem;
            padding: .9rem 1rem;
            border: 1px solid #d9eafa;
            border-radius: .75rem;
            background: #f3f8fd;
            color: #315b7e;
        }

        .whatsapp-management__notice i {
            margin-top: .15rem;
        }

        .whatsapp-management__notice p {
            margin: 0;
            font-size: .82rem;
        }

        .whatsapp-management__actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-top: 1.5rem;
            padding-top: 1.25rem;
            border-top: 1px solid var(--whatsapp-border);
        }

        .whatsapp-management__actions p {
            margin: 0;
            color: var(--whatsapp-muted);
            font-size: .82rem;
        }

        .whatsapp-management__actions .btn {
            min-height: 2.8rem;
            flex: 0 0 auto;
            padding: .65rem 1rem;
            border: 0;
            border-radius: .7rem;
            background: linear-gradient(135deg, #16834c, #0b6b42);
            font-weight: 750;
        }

        @media (max-width: 767.98px) {
            .whatsapp-management__heading {
                padding: 1.1rem;
            }

            .whatsapp-management__form,
            .whatsapp-management__card-heading {
                padding: 1.1rem;
            }

            .whatsapp-management__grid {
                grid-template-columns: 1fr;
            }

            .whatsapp-management__field--full {
                grid-column: auto;
            }

            .whatsapp-management__card-heading,
            .whatsapp-management__actions {
                align-items: flex-start;
                flex-direction: column;
            }

            .whatsapp-management__actions .btn {
                width: 100%;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deliveryOptions = document.querySelectorAll('input[name="delivery"]');
            const userIdField = document.querySelector('.whatsapp-management__user-id');
            const userIdInput = document.getElementById('user_id');

            function updateUserIdField() {
                const selectedDelivery = document.querySelector('input[name="delivery"]:checked');
                const individual = selectedDelivery && selectedDelivery.value === 'individual';
                userIdField.hidden = !individual;
                userIdInput.required = individual;
                if (!individual) userIdInput.value = '';
            }

            deliveryOptions.forEach(option => option.addEventListener('change', updateUserIdField));
            updateUserIdField();
        });
    </script>
@endsection

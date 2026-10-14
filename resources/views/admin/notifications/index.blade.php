@extends('layouts.master')

@section('content')
    <div class="notification-management">
        <section class="notification-management__heading">
            <div>
                <span class="notification-management__eyebrow">Audience communication</span>
                <h1>Notification Management</h1>
                <p>Send an in-app notification to a customer or POS audience.</p>
            </div>
            <span class="notification-management__heading-icon" aria-hidden="true">
                <i class="fas fa-bell"></i>
            </span>
        </section>

        @if (session('success'))
            <div class="alert alert-success" role="status">{{ session('success') }}</div>
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

        <section class="card notification-management__card">
            <div class="notification-management__card-heading">
                <h2>Compose notification</h2>
                <p>Notifications appear in the selected recipients’ dashboard bell.</p>
            </div>

            <form action="{{ route('admin.notifications.store') }}" method="POST" class="notification-management__form">
                @csrf

                <div class="notification-management__grid">
                    <div class="notification-management__field">
                        <label for="category">User category <span>*</span></label>
                        <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                            <option value="pos" {{ old('category') === 'pos' ? 'selected' : '' }}>POS</option>
                            <option value="prime" {{ old('category') === 'prime' ? 'selected' : '' }}>Customers — Prime</option>
                            <option value="non_prime" {{ old('category') === 'non_prime' ? 'selected' : '' }}>Customers — Non-prime</option>
                            <option value="all" {{ old('category', 'all') === 'all' ? 'selected' : '' }}>All POS and customers</option>
                        </select>
                    </div>

                    <fieldset class="notification-management__field notification-management__delivery">
                        <legend>Send to <span>*</span></legend>
                        <label class="notification-management__choice">
                            <input type="radio" name="delivery" value="all" {{ old('delivery', 'all') === 'all' ? 'checked' : '' }}>
                            <span>Everyone in this category</span>
                        </label>
                        <label class="notification-management__choice">
                            <input type="radio" name="delivery" value="individual" {{ old('delivery') === 'individual' ? 'checked' : '' }}>
                            <span>One user by User ID</span>
                        </label>
                    </fieldset>

                    <div class="notification-management__field notification-management__user-id" @if (old('delivery') !== 'individual') hidden @endif>
                        <label for="user_id">User ID <span>*</span></label>
                        <input class="form-control @error('user_id') is-invalid @enderror" id="user_id" name="user_id"
                            type="text" value="{{ old('user_id') }}" maxlength="30" autocomplete="off"
                            @if (old('delivery') === 'individual') required @endif>
                        <small>Must belong to the selected category.</small>
                    </div>

                    <div class="notification-management__field">
                        <label for="title">Notification title <span>*</span></label>
                        <input class="form-control @error('title') is-invalid @enderror" id="title" name="title"
                            type="text" value="{{ old('title') }}" maxlength="120" required>
                    </div>

                    <div class="notification-management__field">
                        <label for="type">Notification style <span>*</span></label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="info" {{ old('type', 'info') === 'info' ? 'selected' : '' }}>Information</option>
                            <option value="success" {{ old('type') === 'success' ? 'selected' : '' }}>Success</option>
                            <option value="warning" {{ old('type') === 'warning' ? 'selected' : '' }}>Warning</option>
                            <option value="danger" {{ old('type') === 'danger' ? 'selected' : '' }}>Important</option>
                        </select>
                    </div>

                    <div class="notification-management__field notification-management__field--full">
                        <label for="message">Message <span>*</span></label>
                        <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message"
                            rows="5" maxlength="2000" required>{{ old('message') }}</textarea>
                    </div>

                </div>

                <div class="notification-management__actions">
                    <p>Bulk messages are saved to each recipient’s in-app notification inbox.</p>
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-paper-plane me-2" aria-hidden="true"></i>Send notification
                    </button>
                </div>
            </form>
        </section>
    </div>

    <style>
        .notification-management {
            --notification-ink: #172b3a;
            --notification-muted: #64748b;
            --notification-border: #e2eaf1;
            padding: .5rem 0;
            color: var(--notification-ink);
        }

        .notification-management__heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.25rem;
            padding: 1.5rem;
            border: 1px solid #dce8ef;
            border-radius: 1.1rem;
            background: linear-gradient(120deg, #f0faf8, #eef5ff);
        }

        .notification-management__eyebrow {
            color: #0d7a8a;
            font-size: .7rem;
            font-weight: 800;
            letter-spacing: .1em;
            text-transform: uppercase;
        }

        .notification-management__heading h1 {
            margin: .25rem 0;
            color: var(--notification-ink);
            font-size: clamp(1.4rem, 3vw, 1.8rem);
            font-weight: 800;
        }

        .notification-management__heading p,
        .notification-management__card-heading p {
            margin: 0;
            color: var(--notification-muted);
        }

        .notification-management__heading-icon {
            display: grid;
            width: 3.25rem;
            height: 3.25rem;
            flex: 0 0 3.25rem;
            place-items: center;
            border-radius: 1rem;
            background: #d9f2ef;
            color: #0d7a8a;
            font-size: 1.3rem;
        }

        .notification-management__card {
            overflow: hidden;
            border: 1px solid var(--notification-border);
            border-radius: 1rem;
            background: #fff;
            box-shadow: 0 .8rem 2rem rgba(24, 52, 74, .06);
        }

        .notification-management__card-heading {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--notification-border);
        }

        .notification-management__card-heading h2 {
            margin: 0 0 .25rem;
            font-size: 1.05rem;
            font-weight: 800;
        }

        .notification-management__card-heading p {
            font-size: .86rem;
        }

        .notification-management__form {
            padding: 1.5rem;
        }

        .notification-management__grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1.15rem 1.25rem;
        }

        .notification-management__field {
            min-width: 0;
            margin: 0;
        }

        .notification-management__field--full {
            grid-column: 1 / -1;
        }

        .notification-management__field > label,
        .notification-management__delivery legend {
            display: block;
            margin-bottom: .4rem;
            color: #2b3d50;
            font-size: .87rem;
            font-weight: 700;
        }

        .notification-management__field > label span,
        .notification-management__delivery legend span {
            color: #b42318;
        }

        .notification-management__field .form-control,
        .notification-management__field .form-select {
            min-height: 2.9rem;
            border-color: #cbd7e2;
            border-radius: .65rem;
        }

        .notification-management__field textarea.form-control {
            min-height: 8rem;
        }

        .notification-management__field small {
            display: block;
            margin-top: .35rem;
            color: var(--notification-muted);
        }

        .notification-management__delivery {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: .6rem 1rem;
            padding: 0;
            border: 0;
        }

        .notification-management__delivery legend {
            width: 100%;
            margin-bottom: .2rem;
        }

        .notification-management__choice {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            color: #42536a;
            font-size: .87rem;
        }

        .notification-management__choice input {
            accent-color: #0d7a8a;
        }

        .notification-management__actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-top: 1.5rem;
            padding-top: 1.25rem;
            border-top: 1px solid var(--notification-border);
        }

        .notification-management__actions p {
            margin: 0;
            color: var(--notification-muted);
            font-size: .82rem;
        }

        .notification-management__actions .btn {
            min-height: 2.8rem;
            flex: 0 0 auto;
            border-radius: .7rem;
            background: linear-gradient(135deg, #0d7a8a, #145da0);
            font-weight: 750;
        }

        @media (max-width: 767.98px) {
            .notification-management__heading {
                padding: 1.1rem;
            }

            .notification-management__form,
            .notification-management__card-heading {
                padding: 1.1rem;
            }

            .notification-management__grid {
                grid-template-columns: 1fr;
            }

            .notification-management__field--full {
                grid-column: auto;
            }

            .notification-management__actions {
                align-items: stretch;
                flex-direction: column;
            }

            .notification-management__actions .btn {
                width: 100%;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deliveryOptions = document.querySelectorAll('input[name="delivery"]');
            const userIdField = document.querySelector('.notification-management__user-id');
            const userIdInput = document.getElementById('user_id');

            function updateUserIdField() {
                const individual = document.querySelector('input[name="delivery"]:checked')?.value === 'individual';
                userIdField.hidden = !individual;
                userIdInput.required = individual;
                if (!individual) userIdInput.value = '';
            }

            deliveryOptions.forEach(option => option.addEventListener('change', updateUserIdField));
            updateUserIdField();
        });
    </script>
@endsection

<div class="permission-editor">
    <section class="permission-editor__heading">
        <div>
            <span class="permission-editor__eyebrow">Access control</span>
            <h1>{{ $editing ? 'Edit permission' : 'Create permission' }}</h1>
            <p>{{ $editing ? 'Update this capability name wherever it is assigned.' : 'Create a capability that can be assigned to roles.' }}</p>
        </div>
        <span class="permission-editor__heading-icon" aria-hidden="true"><i class="fas fa-key"></i></span>
    </section>

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="card permission-editor__card">
        <div class="permission-editor__card-heading">
            <h2>Permission details</h2>
            <p>Names are unique within the web guard and are used by role authorization checks.</p>
        </div>
        <form action="{{ $editing ? route('admin.permissions.update', $permission) : route('admin.permissions.store') }}" method="POST">
            @csrf
            @if ($editing)
                @method('PUT')
            @endif
            <div class="permission-editor__body">
                <div class="permission-editor__field">
                    <label for="permission-name">Permission name <span>*</span></label>
                    <input type="text" id="permission-name" name="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name', $editing ? $permission->name : '') }}"
                        maxlength="125" autocomplete="off" placeholder="e.g. order_view" required autofocus>
                    @error('name')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                    <small>Use a clear, consistent name such as <code>user_access</code> or <code>order_edit</code>.</small>
                </div>
            </div>
            <div class="permission-editor__actions">
                <a class="btn permission-editor__back" href="{{ route('admin.permissions.index') }}">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i> Back to permissions
                </a>
                <button class="btn permission-editor__save" type="submit">
                    <i class="fas {{ $editing ? 'fa-save' : 'fa-plus' }}" aria-hidden="true"></i>
                    {{ $editing ? 'Save changes' : 'Create permission' }}
                </button>
            </div>
        </form>
    </section>
</div>

<style>
    .permission-editor {
        --permission-editor-ink: #172b3a;
        --permission-editor-muted: #64748b;
        --permission-editor-border: #e2eaf1;
        padding: .5rem 0;
        color: var(--permission-editor-ink);
    }
    .permission-editor__heading {
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
    .permission-editor__eyebrow {
        color: #0d7a8a;
        font-size: .7rem;
        font-weight: 800;
        letter-spacing: .1em;
        text-transform: uppercase;
    }
    .permission-editor__heading h1 {
        margin: .25rem 0;
        color: var(--permission-editor-ink);
        font-size: clamp(1.4rem, 3vw, 1.8rem);
        font-weight: 800;
    }
    .permission-editor__heading p,
    .permission-editor__card-heading p {
        margin: 0;
        color: var(--permission-editor-muted);
    }
    .permission-editor__heading-icon {
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
    .permission-editor__card {
        overflow: hidden;
        border: 1px solid var(--permission-editor-border);
        border-radius: 1rem;
        background: #fff;
        box-shadow: 0 .8rem 2rem rgba(24, 52, 74, .06);
    }
    .permission-editor__card-heading {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--permission-editor-border);
    }
    .permission-editor__card-heading h2 {
        margin: 0 0 .25rem;
        font-size: 1.05rem;
        font-weight: 800;
    }
    .permission-editor__card-heading p { font-size: .86rem; }
    .permission-editor__body { padding: 1.5rem; }
    .permission-editor__field { max-width: 42rem; }
    .permission-editor__field label {
        display: block;
        margin-bottom: .4rem;
        color: #2b3d50;
        font-size: .87rem;
        font-weight: 700;
    }
    .permission-editor__field label span { color: #b42318; }
    .permission-editor__field .form-control {
        min-height: 2.9rem;
        border-color: #cbd7e2;
        border-radius: .65rem;
    }
    .permission-editor__field small {
        display: block;
        margin-top: .4rem;
        color: var(--permission-editor-muted);
    }
    .permission-editor__actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 1.1rem 1.5rem;
        border-top: 1px solid var(--permission-editor-border);
        background: #fbfdff;
    }
    .permission-editor__back,
    .permission-editor__save {
        display: inline-flex;
        align-items: center;
        gap: .5rem;
        min-height: 2.7rem;
        padding: .6rem .9rem;
        border-radius: .65rem;
        font-weight: 700;
    }
    .permission-editor__back {
        border: 1px solid #d4dee8;
        color: #526579;
    }
    .permission-editor__save {
        border: 0;
        background: linear-gradient(135deg, #0d7a8a, #145da0);
        color: #fff;
    }
    .permission-editor__save:hover { color: #fff; filter: brightness(.96); }
    @media (max-width: 575.98px) {
        .permission-editor__heading,
        .permission-editor__card-heading,
        .permission-editor__body,
        .permission-editor__actions { padding: 1.1rem; }
        .permission-editor__actions { align-items: stretch; flex-direction: column-reverse; }
        .permission-editor__actions .btn { justify-content: center; }
    }
</style>

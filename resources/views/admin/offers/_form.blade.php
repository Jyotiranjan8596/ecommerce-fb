@php
    $selectedAudiences = old('audiences', $offer?->audiences ?? []);
@endphp

<form action="{{ $action }}" method="POST" class="offer-form">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="offer-form-grid">
        <div class="offer-field offer-field-wide">
            <label for="offer-title" class="form-label">Offer title <span class="text-danger">*</span></label>
            <input id="offer-title" name="title" type="text" class="form-control @error('title') is-invalid @enderror"
                value="{{ old('title', $offer?->title) }}" maxlength="160" required
                placeholder="e.g. Festive shopping bonus">
            @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="offer-field offer-field-wide">
            <label for="offer-description" class="form-label">Offer details <span class="text-danger">*</span></label>
            <textarea id="offer-description" name="description" rows="5"
                class="form-control @error('description') is-invalid @enderror" maxlength="10000" required
                placeholder="Describe any business offer, benefit, event, reward, service or promotion.">{{ old('description', $offer?->description) }}</textarea>
            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
            <div class="form-text">No product or discount format is required. Describe the offer in your own words.</div>
        </div>

        <fieldset class="offer-field offer-field-wide">
            <legend class="form-label">Who can see this offer? <span class="text-danger">*</span></legend>
            <p class="offer-field-hint">Choose any combination. Selecting both customer groups includes Prime and non-Prime customers.</p>
            <div class="offer-audience-options">
                @foreach (\App\Models\Offer::AUDIENCES as $key => $label)
                    <label class="offer-audience-option" for="audience-{{ $key }}">
                        <input id="audience-{{ $key }}" type="checkbox" name="audiences[]" value="{{ $key }}"
                            @checked(in_array($key, $selectedAudiences, true))>
                        <span><i class="fas {{ $key === 'pos' ? 'fa-store' : ($key === 'prime' ? 'fa-crown' : 'fa-users') }}" aria-hidden="true"></i>{{ $label }}</span>
                    </label>
                @endforeach
            </div>
            @error('audiences') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
            @error('audiences.*') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
        </fieldset>

        <div class="offer-field offer-field-wide">
            <label for="redemption-instructions" class="form-label">How to claim or use it <span class="text-muted">(optional)</span></label>
            <textarea id="redemption-instructions" name="redemption_instructions" rows="3"
                class="form-control @error('redemption_instructions') is-invalid @enderror" maxlength="5000"
                placeholder="Explain any steps, contact details or instructions.">{{ old('redemption_instructions', $offer?->redemption_instructions) }}</textarea>
            @error('redemption_instructions') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="offer-field offer-field-wide">
            <label for="offer-terms" class="form-label">Terms and conditions <span class="text-muted">(optional)</span></label>
            <textarea id="offer-terms" name="terms" rows="3"
                class="form-control @error('terms') is-invalid @enderror" maxlength="5000"
                placeholder="Add eligibility, exclusions or other conditions.">{{ old('terms', $offer?->terms) }}</textarea>
            @error('terms') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="offer-field">
            <label for="cta-label" class="form-label">Action button text <span class="text-muted">(optional)</span></label>
            <input id="cta-label" name="cta_label" type="text"
                class="form-control @error('cta_label') is-invalid @enderror"
                value="{{ old('cta_label', $offer?->cta_label) }}" maxlength="60" placeholder="e.g. Learn more">
            @error('cta_label') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="offer-field">
            <label for="cta-url" class="form-label">Action link <span class="text-muted">(optional)</span></label>
            <input id="cta-url" name="cta_url" type="url"
                class="form-control @error('cta_url') is-invalid @enderror"
                value="{{ old('cta_url', $offer?->cta_url) }}" maxlength="2048" placeholder="https://">
            @error('cta_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="offer-field">
            <label for="starts-at" class="form-label">Starts at <span class="text-muted">(optional)</span></label>
            <input id="starts-at" name="starts_at" type="datetime-local"
                class="form-control @error('starts_at') is-invalid @enderror"
                value="{{ old('starts_at', $offer?->starts_at?->format('Y-m-d\TH:i')) }}">
            @error('starts_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>
        <div class="offer-field">
            <label for="ends-at" class="form-label">Ends at <span class="text-muted">(optional)</span></label>
            <input id="ends-at" name="ends_at" type="datetime-local"
                class="form-control @error('ends_at') is-invalid @enderror"
                value="{{ old('ends_at', $offer?->ends_at?->format('Y-m-d\TH:i')) }}">
            @error('ends_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="offer-field offer-field-wide">
            <input type="hidden" name="is_active" value="0">
            <label class="offer-active-toggle">
                <input type="checkbox" name="is_active" value="1"
                    @checked(old('is_active', $offer?->is_active ?? true))>
                <span><strong>Publish this offer</strong><small>Unpublished offers are visible only in Offer Management.</small></span>
            </label>
        </div>
    </div>

    <div class="offer-form-actions">
        <a href="{{ route('admin.offers.index') }}" class="btn btn-outline-secondary">Cancel</a>
        <button type="submit" class="btn offer-primary-button">
            <i class="fas fa-check me-2" aria-hidden="true"></i>{{ $submitLabel }}
        </button>
    </div>
</form>

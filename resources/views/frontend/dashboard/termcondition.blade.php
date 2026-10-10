@extends('ui.layout.master')

@section('content')
    <style>
        .terms-page {
            background: linear-gradient(180deg, #eef4ff 0%, #f7f9fd 45%, #f5f7fb 100%);
            padding: 40px 0 60px;
        }

        .terms-shell {
            max-width: 1180px;
            margin: 0 auto;
        }

        .terms-hero {
            background: linear-gradient(135deg, #17324d 0%, #204d7a 45%, #0a8a84 100%);
            border-radius: 28px;
            padding: 2.2rem 1.5rem;
            text-align: center;
            color: #fff;
            box-shadow: 0 1.2rem 2.2rem rgba(23, 50, 77, 0.18);
            margin-bottom: 24px;
            overflow: hidden;
            position: relative;
        }

        .terms-hero::before,
        .terms-hero::after {
            content: "";
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.08);
        }

        .terms-hero::before {
            width: 220px;
            height: 220px;
            left: -56px;
            top: -80px;
        }

        .terms-hero::after {
            width: 260px;
            height: 260px;
            right: -70px;
            bottom: -110px;
        }

        .terms-badge {
            position: relative;
            z-index: 1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.2);
            padding: 0.5rem 1rem;
            border-radius: 999px;
            font-size: 0.74rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 1rem;
        }

        .terms-hero h1 {
            position: relative;
            z-index: 1;
            margin: 0;
            font-size: clamp(2rem, 3vw, 3.3rem);
            font-weight: 900;
            letter-spacing: -0.04em;
        }

        .terms-hero p {
            position: relative;
            z-index: 1;
            margin: 0.7rem 0 0;
            font-size: 1.03rem;
            color: rgba(255,255,255,0.85);
        }

        .terms-card {
            background: rgba(255,255,255,0.96);
            border: 1px solid rgba(23,50,77,0.08);
            border-radius: 22px;
            box-shadow: 0 0.9rem 1.7rem rgba(15, 34, 59, 0.06);
            padding: 1.6rem 1.5rem 1.8rem;
        }

        .terms-card .intro {
            background: linear-gradient(135deg, #f5f9ff 0%, #edf8f7 100%);
            border: 1px solid #e2edf7;
            border-left: 5px solid #0d8c83;
            border-radius: 16px;
            padding: 1rem 1.1rem;
            margin-bottom: 1.2rem;
        }

        .terms-card h5 {
            color: #17324d;
            font-weight: 800;
            margin-top: 1.4rem;
            margin-bottom: 0.65rem;
        }

        .terms-card ol {
            margin: 0;
            padding-left: 1.3rem;
            line-height: 1.8;
            color: #263449;
        }

        .terms-card li + li {
            margin-top: 0.35rem;
        }

        .terms-card p {
            color: #263449;
            line-height: 1.8;
            margin-bottom: 0.9rem;
        }

        .terms-card a {
            color: #1764a7;
            text-decoration: none;
        }

        .terms-card a:hover {
            text-decoration: underline;
        }

        .terms-declaration {
            margin-top: 1.3rem;
            border-top: 1px solid #e6edf5;
            padding-top: 1.2rem;
            font-weight: 600;
            color: #20334c;
        }

        @media (max-width: 767.98px) {
            .terms-page {
                padding-top: 22px;
            }

            .terms-card {
                padding: 1.1rem 1rem 1.3rem;
            }

            .terms-hero {
                padding: 1.7rem 1rem;
            }
        }
    </style>

    <div class="terms-page">
        <div class="terms-shell">
            <div class="terms-hero">
                <div class="terms-badge">FreeBazar Policy</div>
                <h1>TERMS &amp; CONDITIONS</h1>
                <p>Consumer Savings, Rewards, Referrals &amp; POS Partnership Network</p>
            </div>

            <div class="terms-card">
                <div class="intro">
                    <p><strong>FreeBazar — A Free Commerce Initiative by Satshree Marketing Pvt. Ltd.</strong></p>
                    <p><strong>Last Updated:</strong> October 2026</p>
                    <p>Welcome to FreeBazar. These Terms &amp; Conditions govern your participation in our consumer savings and rewards program, referral-based membership benefits, and POS partnership network.</p>
                    <p>By registering with or using FreeBazar, you agree to comply with these Terms &amp; Conditions and the applicable program policies.</p>
                </div>

                <h5>1. Eligibility &amp; Registration</h5>
                <ol>
                    <li>Individuals aged 18 years or above who meet the applicable registration requirements may register as FreeBazar customers.</li>
                    <li>A valid mobile number and any additional information required for identity verification must be provided during registration.</li>
                    <li>To qualify for the Regular Customer payback program, members must meet the applicable monthly spending and transaction eligibility requirements at authorized FreeBazar POS locations.</li>
                    <li>Registration, participation, and benefits are subject to verification and approval where applicable.</li>
                </ol>

                <h5>2. Reward Points (RP) &amp; Wallet Benefits</h5>
                <ol>
                    <li>Eligible Regular Customers may qualify for a <strong>10% payback benefit</strong> based on qualifying purchases, subject to the applicable program rules.</li>
                    <li>Eligible rewards will be credited monthly to the member's designated wallet or Reward Balance after transaction verification and processing.</li>
                    <li>Reward Points and wallet benefits may be used for eligible bill payments, purchases, or discounts at participating FreeBazar POS locations, according to the applicable redemption rules.</li>
                    <li>Reward Points are not automatically equivalent to cash and are not withdrawable or transferable unless expressly permitted under the applicable program terms.</li>
                    <li>Eligibility, calculation methods, redemption conditions, expiry, and applicable limits will be governed by the published reward policy.</li>
                    <li>FreeBazar reserves the right to investigate transactions suspected of being fraudulent, duplicated, reversed, or otherwise ineligible before crediting or redeeming rewards.</li>
                </ol>

                <h5>3. Prime Membership &amp; Referral-Based Benefits</h5>
                <ol>
                    <li>Customers may qualify for Prime Membership by referring at least <strong>10 qualifying members</strong>, subject to the applicable referral and activation requirements.</li>
                    <li>Eligible Prime Members may qualify for additional rewards under FreeBazar's referral-based community rewards and profit-sharing program.</li>
                    <li>The program offers an opportunity for up to <strong>100% shopping benefits</strong>, depending on eligible referral-network transactions, applicable calculations, program limits, and company policies.</li>
                    <li>The stated maximum is an opportunity, not a guarantee. <strong>No fixed income, guaranteed return, or assured recovery of spending is promised.</strong></li>
                    <li>Rewards depend on qualifying transactions, the applicable reward-sharing formula, and compliance with program requirements. Referral numbers alone do not guarantee any particular reward amount.</li>
                    <li>Members must not make misleading claims, promise guaranteed earnings, or misrepresent FreeBazar's benefits when inviting others to join.</li>
                </ol>

                <h5>4. Spending Requirements</h5>
                <ol>
                    <li>FreeBazar aims to encourage flexible spending without imposing unnecessary purchase targets.</li>
                    <li>However, members must meet the applicable minimum spending requirement to qualify for specific benefits. Where the Regular Customer program specifies a minimum of ₹500 per month, that requirement applies to the relevant monthly eligibility period.</li>
                    <li>Spending in one month does not automatically satisfy the eligibility requirements for another month unless expressly stated in the applicable program policy.</li>
                    <li>Members should review the current eligibility and reward rules before relying on any advertised benefit.</li>
                </ol>

                <h5>5. POS Partnership</h5>
                <ol>
                    <li>Shops and businesses may apply to join FreeBazar as POS Partners, subject to business verification, documentation, and company approval.</li>
                    <li>Approved POS Partners may qualify for transaction-linked service benefits or other commercial incentives under their applicable partnership agreements.</li>
                    <li>Where a daily transaction-based benefit is offered, its calculation, eligibility, settlement, and payment conditions will be governed by the relevant partnership policy.</li>
                    <li>Finance support, trade support, and other business assistance are subject to verification, eligibility, availability, and company approval. Such support is not guaranteed merely by registering as a POS Partner.</li>
                    <li>POS Partners must comply with applicable laws, transaction procedures, and FreeBazar's operational policies.</li>
                </ol>

                <h5>6. Legacy, Nomination &amp; Succession</h5>
                <ol>
                    <li>The term <em>Legacy</em> describes FreeBazar's vision of sustained community participation and the potential continuation of eligible referral-network activity.</li>
                    <li>Legacy does not automatically guarantee lifetime rewards, permanent referral benefits, or inheritance of a membership account.</li>
                    <li>Where legally permissible, eligible balances or benefits may be considered for transfer to a registered nominee or legal heir upon submission of valid documents and completion of the required verification procedures.</li>
                    <li>Account transfers and benefit settlements are subject to applicable law, the nature of the benefit, the relevant program rules, and company verification.</li>
                    <li>Certain membership privileges, referral rights, or promotional rewards may be personal and non-transferable.</li>
                </ol>

                <h5>7. IPO &amp; SIP Terminology</h5>
                <ol>
                    <li>Within FreeBazar, <strong>IPO</strong> refers to <em>Inflation Protection Option</em>, and <strong>SIP</strong> refers to <em>Savings &amp; Incentive Program</em>.</li>
                    <li>These terms describe FreeBazar's internal savings and rewards concepts. They are not intended to refer to stock-market Initial Public Offerings or mutual fund Systematic Investment Plans.</li>
                    <li>The use of these terms does not, by itself, determine the legal classification of any program. The actual structure and operation of the program remain subject to applicable law.</li>
                </ol>

                <h5>8. Fair Use, Fraud &amp; Account Restrictions</h5>
                <ol>
                    <li>Members must provide accurate registration information and use FreeBazar in accordance with these Terms &amp; Conditions.</li>
                    <li>Fraudulent transactions, false referrals, duplicate or manipulated transactions, misuse of accounts, and attempts to obtain unauthorized rewards may result in transaction review, suspension of benefits, or account restrictions.</li>
                    <li>Where appropriate, FreeBazar will investigate disputed transactions and provide a reasonable process for members to raise concerns or submit supporting evidence.</li>
                    <li>Any suspension, cancellation, or adjustment of rewards will be handled in accordance with applicable law and the relevant program policies.</li>
                </ol>

                <h5>9. Changes to the Program</h5>
                <ol>
                    <li>FreeBazar may revise its reward percentages, eligibility criteria, referral requirements, wallet usage rules, and partnership policies when reasonably necessary.</li>
                    <li>Material changes will be communicated through appropriate channels with reasonable prior notice, except where immediate action is required by law, security concerns, or the prevention of fraud.</li>
                    <li>Changes will not override any non-waivable rights available to members under applicable law.</li>
                    <li>Members should review the latest published Terms &amp; Conditions and program policies periodically.</li>
                </ol>

                <h5>10. Disputes &amp; Jurisdiction</h5>
                <ol>
                    <li>Members are encouraged to contact FreeBazar customer support first to resolve transaction disputes, reward discrepancies, or other concerns.</li>
                    <li>Any unresolved dispute will be handled in accordance with applicable Indian law.</li>
                    <li>Subject to applicable law and the jurisdiction of competent courts, disputes shall be subject to the jurisdiction of the courts in Bhubaneswar, Odisha.</li>
                </ol>

                <h5>11. Contact Information</h5>
                <p>For questions, support, or clarification regarding these Terms &amp; Conditions, please contact:</p>
                <p>
                    <strong>Brand:</strong> FreeBazar<br>
                    <strong>Company:</strong> Satshree Marketing Pvt. Ltd.<br>
                    <strong>Location:</strong> Bhubaneswar, Odisha, India<br>
                    <strong>Email:</strong> <a href="mailto:freebazaretail@gmail.com">freebazaretail@gmail.com</a><br>
                    <strong>Helpline:</strong> 9090660606<br>
                    <strong>Website:</strong> freebazar.co.in
                </p>

                <p><strong>Important Notice:</strong> FreeBazar provides a consumer savings and rewards program linked to eligible transactions and participation in its network.</p>
                <p>Rewards, payback, referral benefits, and POS partner incentives are governed by their respective eligibility requirements and program policies. Advertised maximum benefits do not guarantee that any particular member will receive a specific amount.</p>
                <p>Members should read the applicable program rules carefully before participating.</p>

                <div class="terms-declaration">
                    <strong>Declaration:</strong> I hereby confirm that I have read, understood, and agree to abide by the above Terms &amp; Conditions of FreeBazar.
                </div>
            </div>
        </div>
    </div>
@endsection

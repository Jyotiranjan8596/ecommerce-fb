@extends('ui.layout.master')

@section('content')
    <style>
        .faq-page {
            background: linear-gradient(180deg, #eef5ff 0%, #f8fafc 30%, #f5f7fb 100%);
            min-height: 70vh;
        }

        .faq-hero {
            background: linear-gradient(135deg, #17324d 0%, #1d4e7d 45%, #0c8b84 100%);
            border-radius: 26px;
            padding: 2.2rem 1.5rem 1.5rem;
            box-shadow: 0 1.25rem 2.5rem rgba(14, 52, 84, 0.18);
            margin-bottom: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .faq-hero::before {
            content: "";
            position: absolute;
            inset: -30% auto auto -10%;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
        }

        .faq-hero::after {
            content: "";
            position: absolute;
            inset: auto -8% -35% auto;
            width: 240px;
            height: 240px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.06);
        }

        .faq-title-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.18);
            color: #edf7ff;
            padding: 0.45rem 0.9rem;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 0.8rem;
            position: relative;
            z-index: 1;
        }

        .faq-hero h1 {
            position: relative;
            z-index: 1;
            font-weight: 900;
            color: #ffffff;
            margin-bottom: 0.65rem;
            letter-spacing: -0.03em;
        }

        .faq-hero p {
            position: relative;
            z-index: 1;
            color: rgba(255, 255, 255, 0.85);
            font-size: 1.05rem;
            margin-bottom: 0;
        }

        .faq-badges {
            position: relative;
            z-index: 1;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 0.7rem;
            margin-top: 1.2rem;
        }

        .faq-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 0.9rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.12);
            color: #f4fbff;
            font-size: 0.8rem;
            border: 1px solid rgba(255, 255, 255, 0.18);
            font-weight: 600;
        }

        .faq-stat-card {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(23, 50, 77, 0.08);
            border-radius: 20px;
            padding: 1.1rem 1rem;
            box-shadow: 0 0.8rem 1.6rem rgba(23, 32, 51, 0.04);
            height: 100%;
        }

        .faq-stat-card .icon {
            width: 42px;
            height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: linear-gradient(135deg, #17324d, #1d7a8a);
            color: #fff;
            font-size: 1.1rem;
            margin-bottom: 0.7rem;
        }

        .faq-stat-card h4 {
            color: #17324d;
            font-size: 1rem;
            font-weight: 800;
            margin-bottom: 0.25rem;
        }

        .faq-stat-card p {
            margin: 0;
            color: #56657a;
            font-size: 0.92rem;
            line-height: 1.6;
        }

        .faq-accordion {
            border-radius: 22px;
            overflow: hidden;
            box-shadow: 0 1rem 2rem rgba(23, 32, 51, 0.08);
            border: 1px solid rgba(23, 50, 77, 0.08);
            background: transparent;
        }

        .faq-accordion .accordion-item {
            border: 0 !important;
            border-bottom: 1px solid #e7edf5 !important;
            background: rgba(255, 255, 255, 0.92);
        }

        .faq-accordion .accordion-item:last-child {
            border-bottom: 0 !important;
        }

        .faq-accordion .accordion-button {
            font-weight: 800;
            color: #17324d;
            background: #ffffff;
            padding: 1.15rem 1.25rem;
            border: 0;
            box-shadow: none;
            letter-spacing: -0.01em;
        }

        .faq-accordion .accordion-button:focus {
            box-shadow: none;
            border: 0;
        }

        .faq-accordion .accordion-button::after {
            flex-shrink: 0;
            width: 1.15rem;
            height: 1.15rem;
            margin-left: auto;
            content: "";
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%2317324d'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-size: 1.15rem;
            transition: transform 0.2s ease-in-out;
        }

        .faq-accordion .accordion-button:not(.collapsed) {
            background: linear-gradient(135deg, #f5f9ff 0%, #edf7f6 100%);
            color: #17324d;
            box-shadow: inset 0 -1px 0 rgba(23, 50, 77, 0.04);
        }

        .faq-accordion .accordion-button:not(.collapsed)::after {
            transform: rotate(180deg);
        }

        .faq-accordion .accordion-body {
            background: linear-gradient(180deg, #f9fbff 0%, #f4f8ff 100%);
            color: #263449;
            line-height: 1.8;
            font-size: 0.98rem;
            padding: 1.25rem 1.25rem 1.4rem;
        }

        .faq-disclosure {
            margin-top: 2rem;
            background: linear-gradient(135deg, #ffffff 0%, #f3f8ff 100%);
            border: 1px solid #e0eaf6;
            border-left: 5px solid #0c8b84;
            border-radius: 20px;
            box-shadow: 0 0.8rem 1.7rem rgba(23, 32, 51, 0.06);
            padding: 1.5rem 1.35rem;
        }

        .faq-disclosure h3 {
            font-weight: 900;
            color: #17324d;
            margin-bottom: 1rem;
        }

        .faq-disclosure p {
            line-height: 1.8;
            color: #263449;
            margin-bottom: 0.9rem;
        }

        .faq-disclosure .faq-callout {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(12, 139, 132, 0.08);
            color: #0c7a73;
            border: 1px solid rgba(12, 139, 132, 0.15);
            border-radius: 999px;
            padding: 0.5rem 0.85rem;
            font-weight: 700;
            margin-top: 0.25rem;
        }

        @media (max-width: 767.98px) {
            .faq-hero {
                padding: 1.7rem 1rem 1.25rem;
            }

            .faq-hero h1 {
                font-size: 2rem;
            }

            .faq-accordion .accordion-button {
                padding-right: 2.8rem;
            }
        }
    </style>

    <section class="faq-page py-5">
        <div class="container" style="max-width: 1100px;">
            <div class="faq-hero text-center">
                <span class="faq-title-badge">FreeBazar Support</span>
                <h1 class="mb-2">FREQUENTLY ASKED QUESTIONS</h1>
                <p>Everything You Need to Know About FreeBazar</p>
                <div class="faq-badges">
                    <span class="faq-badge">Smart Savings</span>
                    <span class="faq-badge">Reward Community</span>
                    <span class="faq-badge">Local Growth</span>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="faq-stat-card">
                        <div class="icon">₹</div>
                        <h4>Smart Spending</h4>
                        <p>Explore how everyday purchases can unlock savings and rewards.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="faq-stat-card">
                        <div class="icon">◎</div>
                        <h4>Community Rewards</h4>
                        <p>Understand referrals, payback, and how the Free Economy Loop works.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="faq-stat-card">
                        <div class="icon">✓</div>
                        <h4>Transparent Rules</h4>
                        <p>Know the terms, benefit structure, and participation disclosures clearly.</p>
                    </div>
                </div>
            </div>

            <div class="accordion faq-accordion" id="faqAccordion">
                <div class="accordion-item border-0">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1" aria-expanded="true" aria-controls="faq1" style="font-weight: 700; color: #17324d; background: #fff;">
                            Q1. What is FreeBazar?
                        </button>
                    </h2>
                    <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                        <div class="accordion-body" style="background: #f6f8fb; color: #263449; line-height: 1.8;">
                            FreeBazar is a community-powered retail savings and rewards platform that connects customers with local businesses. Through our Free-commerce model, eligible purchases create opportunities for payback and rewards.<br><br>
                            <strong>Our motto: Your Spending Today. Your Savings Tomorrow. Your Legacy for Generations.</strong><br><br>
                            Free-commerce is our vision of making everyday spending more rewarding.
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2" aria-expanded="false" aria-controls="faq2" style="font-weight: 700; color: #17324d; background: #fff;">
                            Q2. How can I join FreeBazar?
                        </button>
                    </h2>
                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body" style="background: #f6f8fb; color: #263449; line-height: 1.8;">
                            Click <strong>Register Now</strong>, complete the registration form, and accept our Terms &amp; Conditions. Registration as a Smart Customer is free, subject to eligibility and program rules.
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3" aria-expanded="false" aria-controls="faq3" style="font-weight: 700; color: #17324d; background: #fff;">
                            Q3. Who can join FreeBazar?
                        </button>
                    </h2>
                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body" style="background: #f6f8fb; color: #263449; line-height: 1.8;">
                            Any Indian resident aged 18 or above who meets our registration requirements can join as a customer. You'll need a smartphone to scan the FreeBazar QR code at participating POS locations.
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4" aria-expanded="false" aria-controls="faq4" style="font-weight: 700; color: #17324d; background: #fff;">
                            Q4. What is the minimum spending requirement?
                        </button>
                    </h2>
                    <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body" style="background: #f6f8fb; color: #263449; line-height: 1.8;">
                            There is no fixed spending target beyond the program's minimum eligibility requirement. Eligible customers who spend at least <strong>₹500 per month</strong> at participating FreeBazar POS locations can qualify for the 10% payback opportunity, subject to program terms.<br><br>
                            You can also plan your purchases over a year, provided you meet the applicable monthly eligibility conditions.
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5" aria-expanded="false" aria-controls="faq5" style="font-weight: 700; color: #17324d; background: #fff;">
                            Q5. What is a POS?
                        </button>
                    </h2>
                    <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body" style="background: #f6f8fb; color: #263449; line-height: 1.8;">
                            POS stands for <strong>Point of Sale</strong>. It's a participating shop or retail outlet registered with FreeBazar. Look for the FreeBazar QR code at the shop, scan it, and follow the payment instructions.
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq6" aria-expanded="false" aria-controls="faq6" style="font-weight: 700; color: #17324d; background: #fff;">
                            Q6. How does the 10% payback opportunity work?
                        </button>
                    </h2>
                    <div id="faq6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body" style="background: #f6f8fb; color: #263449; line-height: 1.8;">
                            Eligible customers who spend at least ₹500 in a qualifying month at participating FreeBazar POS locations may qualify for a <strong>10% payback opportunity</strong>.<br><br>
                            Eligible payback is calculated according to the program rules and credited to your designated wallet or reward balance on a monthly basis.<br><br>
                            <em>Actual benefits depend on eligible transactions and applicable program conditions.</em>
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq7" aria-expanded="false" aria-controls="faq7" style="font-weight: 700; color: #17324d; background: #fff;">
                            Q7. Are FreeBazar products more expensive than market prices?
                        </button>
                    </h2>
                    <div id="faq7" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body" style="background: #f6f8fb; color: #263449; line-height: 1.8;">
                            FreeBazar aims to connect customers with quality products and services at competitive prices. Our rewards model is designed to provide additional savings opportunities on eligible spending.<br><br>
                            We encourage customers to compare prices and choose products that offer them genuine value.
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq8" aria-expanded="false" aria-controls="faq8" style="font-weight: 700; color: #17324d; background: #fff;">
                            Q8. What is the difference between Wallet Balance and Reward Balance?
                        </button>
                    </h2>
                    <div id="faq8" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body" style="background: #f6f8fb; color: #263449; line-height: 1.8;">
                            <strong>Wallet Balance:</strong> The balance available for eligible payments or purchases through supported FreeBazar services.<br><br>
                            <strong>Reward Balance:</strong> Rewards credited under the program that may be used for eligible discounts or purchases at participating FreeBazar POS locations.<br><br>
                            Usage restrictions, redemption conditions, and expiry rules, if any, are governed by the applicable program terms.
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq9" aria-expanded="false" aria-controls="faq9" style="font-weight: 700; color: #17324d; background: #fff;">
                            Q9. When will I receive my payback?
                        </button>
                    </h2>
                    <div id="faq9" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body" style="background: #f6f8fb; color: #263449; line-height: 1.8;">
                            Eligible customer payback and rewards are processed monthly, subject to transaction verification, eligibility requirements, and the applicable program schedule.<br><br>
                            You can check your wallet or account dashboard for your available balance and reward details.
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq10" aria-expanded="false" aria-controls="faq10" style="font-weight: 700; color: #17324d; background: #fff;">
                            Q10. What should I do if my transaction is missing?
                        </button>
                    </h2>
                    <div id="faq10" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body" style="background: #f6f8fb; color: #263449; line-height: 1.8;">
                            Keep your payment confirmation, transaction screenshot, or UTR/reference number.<br><br>
                            Contact the POS partner or FreeBazar customer support at <strong>9090660606</strong> with your transaction details. Our team will investigate and assist with verification.<br><br>
                            Where a transaction is verified, we aim to resolve the issue within 48 hours, subject to the complexity of the case.
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq11" aria-expanded="false" aria-controls="faq11" style="font-weight: 700; color: #17324d; background: #fff;">
                            Q11. What is the Free Economy Loop?
                        </button>
                    </h2>
                    <div id="faq11" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body" style="background: #f6f8fb; color: #263449; line-height: 1.8;">
                            The Free Economy Loop describes how spending, rewards, and repeat shopping work together:<br><br>
                            <strong>SPEND → EARN REWARDS → SHOP AGAIN → REPEAT</strong><br><br>
                            You make eligible purchases at participating POS locations, qualify for applicable rewards, and use your available benefits toward eligible future purchases. This can reduce your out-of-pocket spending and encourage continued shopping within the community.<br><br>
                            That's the idea behind FreeBazar: making everyday spending work harder for you.
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq12" aria-expanded="false" aria-controls="faq12" style="font-weight: 700; color: #17324d; background: #fff;">
                            Q12. What is Prime Membership, and how can I qualify for up to 100% shopping benefits?
                        </button>
                    </h2>
                    <div id="faq12" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body" style="background: #f6f8fb; color: #263449; line-height: 1.8;">
                            Prime Membership is designed for customers who help expand the FreeBazar savings community.<br><br>
                            <strong>Eligibility:</strong> Refer at least 10 qualifying members who meet the applicable spending requirements.<br><br>
                            <strong>Benefits:</strong> Eligible Prime Members may qualify for additional shopping rewards based on their referral network, qualifying community transactions, and the company's applicable reward-sharing policy.<br><br>
                            The program offers an <strong>opportunity for up to 100% shopping benefits</strong>, subject to eligibility, actual transactions, reward calculations, and program limits.<br><br>
                            <strong>Important:</strong> Up to 100% is a potential maximum benefit, not a guaranteed return or fixed income. Actual rewards may vary and are not assured.
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq13" aria-expanded="false" aria-controls="faq13" style="font-weight: 700; color: #17324d; background: #fff;">
                            Q13. What do IPO and SIP mean at FreeBazar?
                        </button>
                    </h2>
                    <div id="faq13" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body" style="background: #f6f8fb; color: #263449; line-height: 1.8;">
                            These are the names of FreeBazar's savings and rewards concepts.<br><br>
                            <strong>IPO — Inflation Protection Option</strong><br>
                            A customer-focused savings concept designed to help offset everyday expenses through eligible shopping rewards.<br><br>
                            <em>In simple words: Mehengai se bachao!</em><br><br>
                            <strong>SIP — Savings &amp; Incentive Program</strong><br>
                            Our community-based rewards concept, built around two principles:<br>
                            - <strong>Spend More to Save More</strong><br>
                            - <strong>Help More to Earn More</strong><br><br>
                            Benefits depend on the applicable program rules and qualifying transactions.
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq14" aria-expanded="false" aria-controls="faq14" style="font-weight: 700; color: #17324d; background: #fff;">
                            Q14. Why does FreeBazar call it a legacy that can continue across generations?
                        </button>
                    </h2>
                    <div id="faq14" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body" style="background: #f6f8fb; color: #263449; line-height: 1.8;">
                            FreeBazar aims to create lasting value through everyday savings and community participation.<br><br>
                            As you introduce friends and family to the platform, your savings community may grow. Those members can also introduce others, helping expand the network of participating customers and local businesses.<br><br>
                            Like a banyan tree, the community can grow and create new opportunities for shared savings.<br><br>
                            Our vision is to make everyday spending more rewarding today while building a stronger savings culture for tomorrow.<br><br>
                            <strong>Your Daily Kharcha. Your Savings Opportunity. Your Legacy.</strong><br><br>
                            <em>Jo aaj bachane ki shuruaat karoge, woh kal aapke parivaar ke kaam aa sakta hai.</em><br><br>
                            Any continuation of benefits across generations is subject to the program rules, account eligibility, and applicable nominee or succession procedures.
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq15" aria-expanded="false" aria-controls="faq15" style="font-weight: 700; color: #17324d; background: #fff;">
                            Q15. Can I leave FreeBazar if I no longer wish to participate?
                        </button>
                    </h2>
                    <div id="faq15" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body" style="background: #f6f8fb; color: #263449; line-height: 1.8;">
                            Yes. You can discontinue participation according to the account closure procedure and applicable Terms &amp; Conditions.<br><br>
                            After discontinuation, you may no longer qualify for future rewards. Any remaining balance or eligible benefits will be handled according to the applicable withdrawal, redemption, and account closure rules.
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq16" aria-expanded="false" aria-controls="faq16" style="font-weight: 700; color: #17324d; background: #fff;">
                            Q16. What benefits do I receive as a FreeBazar POS Partner?
                        </button>
                    </h2>
                    <div id="faq16" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body" style="background: #f6f8fb; color: #263449; line-height: 1.8;">
                            As a POS Partner, you can access opportunities to:<br>
                            - Connect with FreeBazar customers.<br>
                            - Build customer loyalty and encourage repeat visits.<br>
                            - Access digital QR-based transaction facilities.<br>
                            - Receive business and trade support under applicable company policies.<br>
                            - Qualify for service-related benefits linked to eligible transactions.<br><br>
                            Our goal is to help local retailers strengthen their customer relationships and grow their businesses.
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq17" aria-expanded="false" aria-controls="faq17" style="font-weight: 700; color: #17324d; background: #fff;">
                            Q17. Is there any investment required to become a POS Partner?
                        </button>
                    </h2>
                    <div id="faq17" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body" style="background: #f6f8fb; color: #263449; line-height: 1.8;">
                            Registration may be available without an upfront registration fee, subject to the applicable partnership terms.<br><br>
                            Any equipment, service, transaction, or other charges will be disclosed under the applicable agreement.<br><br>
                            Business support and service-related benefits depend on transaction performance and company policy.
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq18" aria-expanded="false" aria-controls="faq18" style="font-weight: 700; color: #17324d; background: #fff;">
                            Q18. Do I have to sell products or recruit people to earn rewards?
                        </button>
                    </h2>
                    <div id="faq18" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body" style="background: #f6f8fb; color: #263449; line-height: 1.8;">
                            Customers can participate in the regular savings program through eligible purchases.<br><br>
                            Prime Membership involves referral-based participation, subject to the program rules. Participation should always be voluntary, and no one should make unrealistic promises or force participation.
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq19" aria-expanded="false" aria-controls="faq19" style="font-weight: 700; color: #17324d; background: #fff;">
                            Q19. Are FreeBazar rewards taxable?
                        </button>
                    </h2>
                    <div id="faq19" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body" style="background: #f6f8fb; color: #263449; line-height: 1.8;">
                            Rewards and other benefits may have tax implications depending on their nature and the applicable laws.<br><br>
                            GST, income tax, TDS, or other statutory requirements may apply where relevant. Members and partners should consult a qualified tax professional regarding their individual circumstances.
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq20" aria-expanded="false" aria-controls="faq20" style="font-weight: 700; color: #17324d; background: #fff;">
                            Q20. What happens to my eligible benefits if I pass away?
                        </button>
                    </h2>
                    <div id="faq20" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body" style="background: #f6f8fb; color: #263449; line-height: 1.8;">
                            Eligible balances or benefits may be considered for transfer to a registered nominee or legal heir, subject to the nature of the benefit, applicable law, valid supporting documents, and FreeBazar's verification procedures.<br><br>
                            Some rewards or membership benefits may be personal and non-transferable. The applicable program terms will determine what can be transferred.
                        </div>
                    </div>
                </div>

                <div class="accordion-item border-0">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq21" aria-expanded="false" aria-controls="faq21" style="font-weight: 700; color: #17324d; background: #fff;">
                            Q21. Where is FreeBazar available, and how can I contact you?
                        </button>
                    </h2>
                    <div id="faq21" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body" style="background: #f6f8fb; color: #263449; line-height: 1.8;">
                            FreeBazar started in Bhubaneswar, Odisha, and aims to expand its network across Odisha and other parts of India.<br><br>
                            <strong>Website:</strong> freebazar.co.in<br>
                            <strong>Customer Support:</strong> 9090660606<br><br>
                            For registration assistance, transaction issues, or POS partnership enquiries, please contact our support team.
                        </div>
                    </div>
                </div>
            </div>

            <div class="faq-disclosure">
                <h3>IMPORTANT PROGRAM DISCLOSURE</h3>
                <p>
                    FreeBazar operates a consumer savings and rewards program linked to eligible purchases and participating community transactions.
                </p>
                <p>
                    All payback and reward benefits are subject to applicable eligibility criteria, transaction verification, program rules, and company policies. <strong>No fixed income, guaranteed return, or guaranteed recovery of spending is promised.</strong>
                </p>
                <p>
                    FreeBazar's program should not be interpreted as an investment offering merely because it uses the terms IPO or SIP. The legal nature of the program depends on its actual structure and operation, not just its terminology or disclaimers.
                </p>
                <p class="mb-0">
                    Members should review the applicable Terms &amp; Conditions before participating.<br><br>
                    <span class="faq-callout">FreeBazar — Your Spending Today. Your Savings Tomorrow. Your Legacy for Generations.</span>
                </p>
            </div>
        </div>
    </section>
@endsection

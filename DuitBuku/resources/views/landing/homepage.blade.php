<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-theme="default" data-theme-colors="default">

<head>

    <meta charset="utf-8" />
    <title>Home | DuitBuku</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="DuitBuku — Simple business finance management for small businesses" name="description" />
    <meta content="DuitBuku" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!--Swiper slider css-->
    <link href="assets/libs/swiper/swiper-bundle.min.css" rel="stylesheet" type="text/css" />

    <!-- Layout config Js -->
    <script src="assets/js/layout.js"></script>
    <!-- Bootstrap Css -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="assets/css/custom.min.css" rel="stylesheet" type="text/css" />

</head>

<body data-bs-spy="scroll" data-bs-target="#navbar-example">

    <!-- Begin page -->
    <div class="layout-wrapper landing">
        <nav class="navbar navbar-expand-lg navbar-landing fixed-top" id="navbar">
            <div class="container">
                <a class="navbar-brand" href="{{ route('homepage') }}">
                    <img src="assets/images/logo-sm.png" class="card-logo card-logo-dark" alt="DuitBuku" height="30">
                    <img src="assets/images/logo-sm.png" class="card-logo card-logo-light" alt="DuitBuku" height="30">
                </a>
                <button class="navbar-toggler py-0 fs-20 text-body" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="mdi mdi-menu"></i>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mx-auto mt-2 mt-lg-0" id="navbar-example">
                        <li class="nav-item">
                            <a class="nav-link active" href="#hero">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#features">Features</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#howitworks">How It Works</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#plans">Plans</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#faq">FAQ</a>
                        </li>
                    </ul>

                    <div class="">
                        <a href="{{ route('login') }}" class="btn btn-link fw-medium text-decoration-none text-body">Sign in</a>
                        <a href="{{ route('login') }}" class="btn btn-primary">Get Started</a>
                    </div>
                </div>

            </div>
        </nav>
        <!-- end navbar -->
        <div class="vertical-overlay" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent.show"></div>

        <!-- start hero section -->
        <section class="section pb-0 hero-section" id="hero">
            <div class="bg-overlay bg-overlay-pattern"></div>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8 col-sm-10">
                        <div class="text-center mt-lg-5 pt-5">
                            <h1 class="display-6 fw-semibold mb-3 lh-base">Smart Finance Tracking for <span class="text-success">Your Business</span></h1>
                            <p class="lead text-muted lh-base">DuitBuku helps small business owners track income, expenses, invoices, and cash flow — all in one simple dashboard.</p>

                            <div class="d-flex gap-2 justify-content-center mt-4">
                                <a href="{{ route('login') }}" class="btn btn-primary">Get Started Free <i class="ri-arrow-right-line align-middle ms-1"></i></a>
                                <a href="#features" class="btn btn-outline-secondary">See Features <i class="ri-eye-line align-middle ms-1"></i></a>
                            </div>
                        </div>

                        <div class="mt-4 mt-sm-5 pt-sm-5 mb-sm-n5 demo-carousel">
                            <div class="demo-img-patten-top d-none d-sm-block">
                                <img src="assets/images/landing/img-pattern.png" class="d-block img-fluid" alt="">
                            </div>
                            <div class="demo-img-patten-bottom d-none d-sm-block">
                                <img src="assets/images/landing/img-pattern.png" class="d-block img-fluid" alt="">
                            </div>
                            <div class="carousel slide carousel-fade" data-bs-ride="carousel">
                                <div class="carousel-inner shadow-lg p-2 bg-white rounded">
                                    <div class="carousel-item active" data-bs-interval="2000">
                                        <img src="assets/images/demos/default.png" class="d-block w-100" alt="">
                                    </div>
                                    <div class="carousel-item" data-bs-interval="2000">
                                        <img src="assets/images/demos/saas.png" class="d-block w-100" alt="">
                                    </div>
                                    <div class="carousel-item" data-bs-interval="2000">
                                        <img src="assets/images/demos/material.png" class="d-block w-100" alt="">
                                    </div>
                                    <div class="carousel-item" data-bs-interval="2000">
                                        <img src="assets/images/demos/minimal.png" class="d-block w-100" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="position-absolute start-0 end-0 bottom-0 hero-shape-svg">
                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1440 120">
                    <g mask="url(&quot;#SvgjsMask1003&quot;)" fill="none">
                        <path d="M 0,118 C 288,98.6 1152,40.4 1440,21L1440 140L0 140z"></path>
                    </g>
                </svg>
            </div>
        </section>
        <!-- end hero section -->

        <!-- start features -->
        <section class="section" id="features">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="text-center mb-5">
                            <h1 class="mb-3 ff-secondary fw-semibold lh-base">Everything you need to manage your business finances</h1>
                            <p class="text-muted">From daily transactions to yearly P&L reports — DuitBuku covers it all.</p>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-lg-4">
                        <div class="d-flex p-3">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-sm icon-effect">
                                    <div class="avatar-title bg-transparent text-success rounded-circle">
                                        <i class="ti ti-receipt fs-36"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fs-18">Transaction Tracking</h5>
                                <p class="text-muted my-3 ff-secondary">Record income and expenses by category. Keep your books clean with recurring entry support.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="d-flex p-3">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-sm icon-effect">
                                    <div class="avatar-title bg-transparent text-success rounded-circle">
                                        <i class="ti ti-file-invoice fs-36"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fs-18">Invoice Management</h5>
                                <p class="text-muted my-3 ff-secondary">Create and send invoices to customers, track payment status, and manage your client list.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="d-flex p-3">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-sm icon-effect">
                                    <div class="avatar-title bg-transparent text-success rounded-circle">
                                        <i class="ti ti-user-dollar fs-36"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fs-18">Owner Drawings</h5>
                                <p class="text-muted my-3 ff-secondary">Track salary withdrawals and personal drawings from the business separately from expenses.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="d-flex p-3">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-sm icon-effect">
                                    <div class="avatar-title bg-transparent text-success rounded-circle">
                                        <i class="ti ti-calendar-stats fs-36"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fs-18">Cashflow Forecast</h5>
                                <p class="text-muted my-3 ff-secondary">Plan ahead with a 30-day calendar view of upcoming bills, expected income, and cash runway.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="d-flex p-3">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-sm icon-effect">
                                    <div class="avatar-title bg-transparent text-success rounded-circle">
                                        <i class="ti ti-chart-bar fs-36"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fs-18">P&amp;L Reports</h5>
                                <p class="text-muted my-3 ff-secondary">Generate monthly, quarterly, and yearly profit &amp; loss snapshots with category breakdowns.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="d-flex p-3">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-sm icon-effect">
                                    <div class="avatar-title bg-transparent text-success rounded-circle">
                                        <i class="ti ti-heart-rate-monitor fs-36"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fs-18">Business Health Score</h5>
                                <p class="text-muted my-3 ff-secondary">Get a live health score based on your profit margin, expense ratio, and cash runway.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- end features -->

        <!-- start how it works -->
        <section class="section bg-light py-5" id="howitworks">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="text-center mb-5">
                            <h3 class="mb-3 fw-semibold">Get started in 3 simple steps</h3>
                            <p class="text-muted mb-4 ff-secondary">No accounting degree required. DuitBuku is designed to be simple and intuitive from day one.</p>
                        </div>
                    </div>
                </div>

                <div class="row text-center">
                    <div class="col-lg-4">
                        <div class="process-card mt-4">
                            <div class="process-arrow-img d-none d-lg-block">
                                <img src="assets/images/landing/process-arrow-img.png" alt="" class="img-fluid">
                            </div>
                            <div class="avatar-sm icon-effect mx-auto mb-4">
                                <div class="avatar-title bg-transparent text-success rounded-circle h1">
                                    <i class="ti ti-user-plus"></i>
                                </div>
                            </div>
                            <h5>Create Your Account</h5>
                            <p class="text-muted ff-secondary">Sign up and set up your business profile in under a minute. No credit card required to start.</p>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="process-card mt-4">
                            <div class="process-arrow-img d-none d-lg-block">
                                <img src="assets/images/landing/process-arrow-img.png" alt="" class="img-fluid">
                            </div>
                            <div class="avatar-sm icon-effect mx-auto mb-4">
                                <div class="avatar-title bg-transparent text-success rounded-circle h1">
                                    <i class="ti ti-category"></i>
                                </div>
                            </div>
                            <h5>Set Up Categories</h5>
                            <p class="text-muted ff-secondary">Customise your income and expense categories to match how your business actually operates.</p>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="process-card mt-4">
                            <div class="avatar-sm icon-effect mx-auto mb-4">
                                <div class="avatar-title bg-transparent text-success rounded-circle h1">
                                    <i class="ti ti-trending-up"></i>
                                </div>
                            </div>
                            <h5>Start Tracking</h5>
                            <p class="text-muted ff-secondary">Record transactions, generate invoices, and watch your financial dashboard come alive with real data.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- end how it works -->

        <!-- start cta -->
        <section class="py-5 bg-primary position-relative">
            <div class="bg-overlay bg-overlay-pattern opacity-50"></div>
            <div class="container">
                <div class="row align-items-center gy-4">
                    <div class="col-sm">
                        <div>
                            <h4 class="text-white mb-1 fw-semibold">Take control of your business finances today.</h4>
                            <p class="text-white-50 mb-0">Join DuitBuku and start making sense of your money.</p>
                        </div>
                    </div>
                    <div class="col-sm-auto">
                        <div>
                            <a href="{{ route('login') }}" class="btn btn-light fw-semibold"><i class="ti ti-login align-middle me-1"></i> Sign In to DuitBuku</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- end cta -->

        <!-- start plans -->
        <section class="section bg-light" id="plans">
            <div class="bg-overlay bg-overlay-pattern"></div>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="text-center mb-5">
                            <h3 class="mb-3 fw-semibold">Simple, transparent pricing</h3>
                            <p class="text-muted mb-4">Start free, upgrade when you're ready. No hidden fees.</p>

                            <div class="d-flex justify-content-center align-items-center">
                                <div>
                                    <h5 class="fs-14 mb-0">Monthly</h5>
                                </div>
                                <div class="form-check form-switch fs-20 ms-3" onclick="check()">
                                    <input class="form-check-input" type="checkbox" id="plan-switch">
                                    <label class="form-check-label" for="plan-switch"></label>
                                </div>
                                <div>
                                    <h5 class="fs-14 mb-0">Annual <span class="badge bg-success-subtle text-success">Save 20%</span></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row gy-4">
                    <!-- Starter -->
                    <div class="col-lg-4">
                        <div class="card plan-box mb-0">
                            <div class="card-body p-4 m-2">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h5 class="mb-1 fw-semibold">Starter</h5>
                                        <p class="text-muted mb-0">For individuals &amp; freelancers</p>
                                    </div>
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-light rounded-circle text-primary">
                                            <i class="ri-book-mark-line fs-20"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="py-4 text-center">
                                    <h1 class="month"><span class="ff-secondary fw-bold">Free</span></h1>
                                    <h1 class="annual"><span class="ff-secondary fw-bold">Free</span></h1>
                                </div>
                                <div>
                                    <ul class="list-unstyled text-muted vstack gap-3 ff-secondary">
                                        <li>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 text-success me-1"><i class="ri-checkbox-circle-fill fs-15 align-middle"></i></div>
                                                <div class="flex-grow-1">Up to <b>50</b> transactions/month</div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 text-success me-1"><i class="ri-checkbox-circle-fill fs-15 align-middle"></i></div>
                                                <div class="flex-grow-1">Invoice generation (up to <b>5</b>/month)</div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 text-success me-1"><i class="ri-checkbox-circle-fill fs-15 align-middle"></i></div>
                                                <div class="flex-grow-1">Basic P&amp;L reports</div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 text-danger me-1"><i class="ri-close-circle-fill fs-15 align-middle"></i></div>
                                                <div class="flex-grow-1">Cashflow Forecast</div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 text-danger me-1"><i class="ri-close-circle-fill fs-15 align-middle"></i></div>
                                                <div class="flex-grow-1">Business Health Score</div>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="mt-4">
                                        <a href="{{ route('login') }}" class="btn btn-soft-success w-100">Get Started Free</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pro -->
                    <div class="col-lg-4">
                        <div class="card plan-box mb-0 ribbon-box right">
                            <div class="card-body p-4 m-2">
                                <div class="ribbon-two ribbon-two-danger"><span>Popular</span></div>
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h5 class="mb-1 fw-semibold">Pro</h5>
                                        <p class="text-muted mb-0">For growing businesses</p>
                                    </div>
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-light rounded-circle text-primary">
                                            <i class="ri-medal-fill fs-20"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="py-4 text-center">
                                    <h1 class="month"><sup><small>RM</small></sup><span class="ff-secondary fw-bold">19</span> <span class="fs-13 text-muted">/Month</span></h1>
                                    <h1 class="annual"><sup><small>RM</small></sup><span class="ff-secondary fw-bold">171</span> <span class="fs-13 text-muted">/Year</span></h1>
                                </div>
                                <div>
                                    <ul class="list-unstyled text-muted vstack gap-3 ff-secondary">
                                        <li>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 text-success me-1"><i class="ri-checkbox-circle-fill fs-15 align-middle"></i></div>
                                                <div class="flex-grow-1"><b>Unlimited</b> transactions</div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 text-success me-1"><i class="ri-checkbox-circle-fill fs-15 align-middle"></i></div>
                                                <div class="flex-grow-1"><b>Unlimited</b> invoices</div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 text-success me-1"><i class="ri-checkbox-circle-fill fs-15 align-middle"></i></div>
                                                <div class="flex-grow-1">Full P&amp;L + Cashflow Forecast</div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 text-success me-1"><i class="ri-checkbox-circle-fill fs-15 align-middle"></i></div>
                                                <div class="flex-grow-1">Business Health Score</div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 text-danger me-1"><i class="ri-close-circle-fill fs-15 align-middle"></i></div>
                                                <div class="flex-grow-1">Multi-user access</div>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="mt-4">
                                        <a href="{{ route('login') }}" class="btn btn-soft-success w-100">Get Started</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Business -->
                    <div class="col-lg-4">
                        <div class="card plan-box mb-0">
                            <div class="card-body p-4 m-2">
                                <div class="d-flex align-items-center">
                                    <div class="flex-grow-1">
                                        <h5 class="mb-1 fw-semibold">Business</h5>
                                        <p class="text-muted mb-0">For teams &amp; enterprises</p>
                                    </div>
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-light rounded-circle text-primary">
                                            <i class="ri-stack-fill fs-20"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="py-4 text-center">
                                    <h1 class="month"><sup><small>RM</small></sup><span class="ff-secondary fw-bold">39</span> <span class="fs-13 text-muted">/Month</span></h1>
                                    <h1 class="annual"><sup><small>RM</small></sup><span class="ff-secondary fw-bold">351</span> <span class="fs-13 text-muted">/Year</span></h1>
                                </div>
                                <div>
                                    <ul class="list-unstyled text-muted vstack gap-3 ff-secondary">
                                        <li>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 text-success me-1"><i class="ri-checkbox-circle-fill fs-15 align-middle"></i></div>
                                                <div class="flex-grow-1">Everything in Pro</div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 text-success me-1"><i class="ri-checkbox-circle-fill fs-15 align-middle"></i></div>
                                                <div class="flex-grow-1">Multi-user access</div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 text-success me-1"><i class="ri-checkbox-circle-fill fs-15 align-middle"></i></div>
                                                <div class="flex-grow-1">Priority support</div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 text-success me-1"><i class="ri-checkbox-circle-fill fs-15 align-middle"></i></div>
                                                <div class="flex-grow-1">Data export (CSV / PDF)</div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 text-success me-1"><i class="ri-checkbox-circle-fill fs-15 align-middle"></i></div>
                                                <div class="flex-grow-1">Custom categories</div>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="mt-4">
                                        <a href="{{ route('login') }}" class="btn btn-soft-success w-100">Get Started</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- end plans -->

        <!-- start faq -->
        <section class="section" id="faq">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="text-center mb-5">
                            <h3 class="mb-3 fw-semibold">Frequently Asked Questions</h3>
                            <p class="text-muted mb-4 ff-secondary">Have questions? We've got answers. Can't find what you're looking for? <a href="{{ route('login') }}">Sign in</a> and reach out to us.</p>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div class="accordion custom-accordionwithicon custom-accordion-border accordion-border-box" id="faq-accordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="faq-headingOne">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq-collapseOne" aria-expanded="true" aria-controls="faq-collapseOne">
                                        What is DuitBuku?
                                    </button>
                                </h2>
                                <div id="faq-collapseOne" class="accordion-collapse collapse show" aria-labelledby="faq-headingOne" data-bs-parent="#faq-accordion">
                                    <div class="accordion-body ff-secondary">
                                        DuitBuku is a web-based business finance management tool designed for small business owners and sole traders. It helps you track income, expenses, invoices, owner drawings, cashflow, and generate P&L reports — all in one simple dashboard.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="faq-headingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-collapseTwo" aria-expanded="false" aria-controls="faq-collapseTwo">
                                        Do I need accounting knowledge to use DuitBuku?
                                    </button>
                                </h2>
                                <div id="faq-collapseTwo" class="accordion-collapse collapse" aria-labelledby="faq-headingTwo" data-bs-parent="#faq-accordion">
                                    <div class="accordion-body ff-secondary">
                                        Not at all. DuitBuku is designed to be simple and intuitive. Just record your transactions, create invoices, and the system automatically generates your financial reports and health score. No accounting degree required.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="faq-headingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-collapseThree" aria-expanded="false" aria-controls="faq-collapseThree">
                                        Is my financial data secure?
                                    </button>
                                </h2>
                                <div id="faq-collapseThree" class="accordion-collapse collapse" aria-labelledby="faq-headingThree" data-bs-parent="#faq-accordion">
                                    <div class="accordion-body ff-secondary">
                                        Yes. All your data is stored securely and is only accessible with your login credentials. We take data privacy seriously and your financial information is never shared with third parties.
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="faq-headingFour">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq-collapseFour" aria-expanded="false" aria-controls="faq-collapseFour">
                                        Can I export my financial reports?
                                    </button>
                                </h2>
                                <div id="faq-collapseFour" class="accordion-collapse collapse" aria-labelledby="faq-headingFour" data-bs-parent="#faq-accordion">
                                    <div class="accordion-body ff-secondary">
                                        Data export (CSV and PDF) is available on the Business plan. Starter and Pro users can view all reports within the dashboard at any time.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- end faq -->

        <!-- Start footer -->
        <footer class="custom-footer bg-dark py-5 position-relative">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 mt-4">
                        <div>
                            <div>
                                <img src="assets/images/logo-light.png" alt="DuitBuku" height="17">
                            </div>
                            <div class="mt-4 fs-13">
                                <p class="ff-secondary">DuitBuku — Simple business finance tracking for small businesses and sole traders in Malaysia.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-7 ms-lg-auto">
                        <div class="row">
                            <div class="col-sm-4 mt-4">
                                <h5 class="text-white mb-0">Product</h5>
                                <div class="text-muted mt-3">
                                    <ul class="list-unstyled ff-secondary footer-list">
                                        <li><a href="#features">Features</a></li>
                                        <li><a href="#howitworks">How It Works</a></li>
                                        <li><a href="#plans">Pricing</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-sm-4 mt-4">
                                <h5 class="text-white mb-0">Account</h5>
                                <div class="text-muted mt-3">
                                    <ul class="list-unstyled ff-secondary footer-list">
                                        <li><a href="{{ route('login') }}">Sign In</a></li>
                                        <li><a href="{{ route('login') }}">Sign Up</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-sm-4 mt-4">
                                <h5 class="text-white mb-0">Support</h5>
                                <div class="text-muted mt-3">
                                    <ul class="list-unstyled ff-secondary footer-list">
                                        <li><a href="#faq">FAQ</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row text-center text-sm-start align-items-center mt-5">
                    <div class="col-sm-6">
                        <div>
                            <p class="copy-rights mb-0">
                                <script>document.write(new Date().getFullYear())</script> &copy; DuitBuku. All rights reserved.
                            </p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="text-sm-end mt-3 mt-sm-0">
                            <ul class="list-inline mb-0 footer-social-link">
                                <li class="list-inline-item">
                                    <a href="#" class="avatar-xs d-block">
                                        <div class="avatar-title rounded-circle">
                                            <i class="ri-facebook-fill"></i>
                                        </div>
                                    </a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="#" class="avatar-xs d-block">
                                        <div class="avatar-title rounded-circle">
                                            <i class="ri-instagram-line"></i>
                                        </div>
                                    </a>
                                </li>
                                <li class="list-inline-item">
                                    <a href="#" class="avatar-xs d-block">
                                        <div class="avatar-title rounded-circle">
                                            <i class="ri-linkedin-fill"></i>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end footer -->

        <!--start back-to-top-->
        <button onclick="topFunction()" class="btn btn-danger btn-icon landing-back-top" id="back-to-top">
            <i class="ri-arrow-up-line"></i>
        </button>
        <!--end back-to-top-->

    </div>
    <!-- end layout wrapper -->

    <!-- JAVASCRIPT -->
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/node-waves/waves.min.js"></script>
    <script src="assets/libs/feather-icons/feather.min.js"></script>
    <script src="assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
    <script src="assets/js/plugins.js"></script>

    <!--Swiper slider js-->
    <script src="assets/libs/swiper/swiper-bundle.min.js"></script>

    <!-- landing init -->
    <script src="assets/js/pages/landing.init.js"></script>
</body>

</html>

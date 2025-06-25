@extends('layouts.dashboard')

@section('title', $title)

@section('content')
    <div class="container-fluid px-0">
        <div class="widget shadow-sm mb-0 border-0 rounded-3">
            <!-- Card Header -->
            <div class="card-header text-white d-flex justify-content-between align-items-center py-3 px-4 rounded-top"
                style="background-color: #159ed5;">
                <h4 class="mb-0 d-flex align-items-center">
                    <i class="fas fa-search me-2"></i>{{ $title }}
                </h4>
            </div>

            <!-- Search Form -->
            <div class="p-3 bg-light border-bottom">
                <form id="search-form" class="mb-0">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-6">
                            <label for="search" class="form-label small fw-semibold text-muted">
                                <i class="fas fa-search me-1"></i>Search Patient
                            </label>
                            <input type="text" name="search" id="search" class="form-control form-control-sm shadow-sm"
                                value="{{ request('search') }}"
                                placeholder="Search by name, patient number, email, or phone">
                        </div>
                        <div class="col-md-6 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-sm flex-grow-1 shadow-sm"
                                style="background-color: #159ed5; color: white;">
                                <i class="fas fa-search me-1"></i>Search
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary shadow-sm" id="reset-search">
                                <i class="fas fa-undo me-1"></i>Reset
                            </button>
                            <button type="button" id="export-csv-btn" class="btn btn-secondary btn-sm shadow-sm">
                                <i class="fas fa-file-csv me-1"></i>CSV
                            </button>
                            <button type="button" id="print-btn" class="btn btn-info btn-sm shadow-sm">
                                <i class="fas fa-print me-1"></i>Print
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Alerts -->
            @if (session('success'))
                <div class="alert alert-success m-3 rounded-0">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger m-3 rounded-0">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                </div>
            @endif

            <!-- Print Container -->
            <div id="print-container">
                <div class="table-responsive">
                    @include('booking.tables.search', ['appointments' => $appointments, 'specializations' => $specializations ?? []])
                </div>
            </div>
        </div>
    </div>

    <style>
        .btn-outline-secondary {
            border-color: #d1e9f5;
            color: #0d6a9f;
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        .btn-outline-secondary:hover {
            background-color: #e6f4fa;
            color: #094d7a;
        }

        .print-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .print-header h2 {
            font-size: 18pt;
            margin: 0;
        }

        .print-header hr {
            border-top: 1px solid #000;
            margin: 10px 0;
        }

        .empty-state {
            text-align: center;
            padding: 20px;
            font-size: 12pt;
            color: #555;
        }

        @media print {
            body * {
                visibility: hidden !important;
            }

            #print-container,
            #print-container * {
                visibility: visible !important;
            }

            #print-container {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
            }

            .print-header {
                display: block !important;
                visibility: visible !important;
                font-size: 14pt !important;
                text-align: center !important;
                margin-bottom: 10mm !important;
            }

            .table-responsive {
                display: block !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                overflow: visible !important;
            }

            #search-results-table {
                display: table !important;
                width: 100% !important;
                font-size: 10pt !important;
                border-collapse: collapse !important;
            }

            #search-results-table thead {
                display: table-header-group !important;
            }

            #search-results-table tbody {
                display: table-row-group !important;
            }

            #search-results-table tr {
                display: table-row !important;
            }

            #search-results-table th,
            #search-results-table td {
                display: table-cell !important;
                padding: 4px !important;
                border: 1px solid #000 !important;
                word-wrap: break-word !important;
                max-width: 75mm !important;
                font-size: 10pt !important;
                vertical-align: top !important;
            }

            #search-results-table th {
                background-color: #f0f0f0 !important;
                font-weight: bold !important;
            }

            .empty-state {
                display: block !important;
                visibility: visible !important;
                font-size: 12pt !important;
                text-align: center !important;
                margin: 20px 0 !important;
            }

            .no-print,
            .no-print * {
                display: none !important;
                visibility: hidden !important;
            }

            @page {
                size: A4;
                margin: 15mm;
            }
        }
    </style>

    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function () {
            // Load table data via AJAX
            function loadTableData(callback) {
                const formData = $('#search-form').serialize();
                $.ajax({
                    url: "{{ route('booking.search') }}",
                    method: 'GET',
                    data: formData + '&ajax=1',
                    beforeSend: function () {
                        $('#loaderOverlay').addClass('active');
                        $('.table-responsive').removeClass('loaded');
                    },
                    success: function (response) {
                        $('#print-container .table-responsive').html(response.table);
                        $('#loaderOverlay').removeClass('active');
                        $('.table-responsive').addClass('loaded');
                        if (callback) callback();
                    },
                    error: function (xhr) {
                        alert('Error loading search results: ' + xhr.statusText);
                        $('#loaderOverlay').removeClass('active');
                        if (callback) callback();
                    }
                });
            }

            // Handle form submission
            $('#search-form').on('submit', function (e) {
                e.preventDefault();
                loadTableData();
            });

            // Handle reset search
            $('#reset-search').on('click', function () {
                $('#search-form')[0].reset();
                loadTableData();
            });

            // Handle CSV export
            $('#export-csv-btn').on('click', function () {
                const formData = $('#search-form').serialize();
                window.location.href = "{{ route('booking.search') }}?" + formData + "&export_csv=1";
            });

            // Handle print with delay
            $('#print-btn').on('click', function () {
                if ($('#search-results-table').length && $('#search-results-table tbody tr:not(.empty-state)').length > 0) {
                    console.log('Initiating print for table with ' + $('#search-results-table tbody tr:not(.empty-state)').length + ' rows');
                    loadTableData(function () {
                        setTimeout(function () {
                            window.print();
                        }, 300);
                    });
                } else {
                    console.log('No table or empty table, attempting to print empty state');
                    loadTableData(function () {
                        setTimeout(function () {
                            if ($('.empty-state').length) {
                                window.print();
                            } else {
                                alert('No search results to print. Please perform a search first.');
                            }
                        }, 300);
                    });
                }
            });
        });
    </script>
@endsection
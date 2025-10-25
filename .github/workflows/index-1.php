<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock R Fact Calculator - Live Analysis</title>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.25;
        }
        
        html {
            scroll-behavior: smooth;
        }
        
        a {
            text-decoration: none;
            color: inherit;
        }
        
        /* Responsive Table Styles */
        table {
            border-collapse: collapse;
            width: 100%;
        }
        
        table th,
        table td {
            padding: .625em;
            text-align: center;
        }
        
        table th {
            font-size: .85em;
            letter-spacing: .05em;
            text-transform: uppercase;
        }
        
        /* Smooth row transitions */
        table tbody tr {
            transition: all 0.3s ease;
        }
        
        /* Sector Link Hover Effect */
        .sector-link:hover {
            background-color: #e3f2fd !important;
            border-radius: 5px;
            color: #1976d2 !important;
            transition: all 0.3s ease;
        }
        
        .sector-link:hover i {
            transform: translateY(2px);
            transition: transform 0.3s ease;
        }
        
        /* Back to Top Button */
        #backToTop {
            position: fixed;
            bottom: 40px;
            right: 40px;
            z-index: 1000;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            cursor: pointer;
            font-size: 24px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        #backToTop.show {
            opacity: 1;
            visibility: visible;
        }
        
        #backToTop:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }
        
        #backToTop:active {
            transform: translateY(-2px);
        }
        
        @media screen and (max-width: 768px) {
            #backToTop {
                bottom: 20px;
                right: 20px;
                width: 45px;
                height: 45px;
                font-size: 20px;
            }
        }
        
        /* Progress Indicator - Bottom Left */
        #progressIndicator {
            position: fixed;
            bottom: 20px;
            left: 20px;
            z-index: 1000;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            min-width: 280px;
            max-width: 350px;
        }
        
        #progressIndicator.show {
            opacity: 1;
            visibility: visible;
        }
        
        #progressIndicator .progress-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        
        #progressIndicator .progress-title {
            font-weight: bold;
            font-size: 14px;
        }
        
        #progressIndicator .progress-counter {
            font-size: 12px;
            background: rgba(255, 255, 255, 0.2);
            padding: 2px 8px;
            border-radius: 5px;
        }
        
        #progressIndicator .progress-sector {
            font-size: 13px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }
        
        #progressIndicator .progress-sector .spinner-border {
            width: 16px;
            height: 16px;
            border-width: 2px;
            margin-right: 8px;
        }
        
        #progressIndicator .progress-bar-container {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 5px;
            height: 6px;
            overflow: hidden;
        }
        
        #progressIndicator .progress-bar-fill {
            background: white;
            height: 100%;
            border-radius: 5px;
            transition: width 0.3s ease;
        }
        
        @media screen and (max-width: 768px) {
            #progressIndicator {
                left: 10px;
                bottom: 80px;
                min-width: 250px;
                max-width: calc(100% - 20px);
            }
        }
        
        /* Mobile Responsive Table */
        @media screen and (max-width: 767px) {
            .table-responsive table {
                border: 0;
            }
            
            .table-responsive table thead {
                border: none;
                clip: rect(0 0 0 0);
                height: 1px;
                margin: -1px;
                overflow: hidden;
                padding: 0;
                position: absolute;
                width: 1px;
            }
            
            .table-responsive table tr {
                border-bottom: 3px solid #ddd;
                display: block;
                margin-bottom: .625em;
                background-color: #f8f8f8;
            }
            
            .table-responsive table td {
                border-bottom: 1px solid #ddd;
                display: block;
                font-size: .9em;
                text-align: right;
                padding: .625em .625em .625em 50%;
                position: relative;
            }
            
            .table-responsive table td::before {
                content: attr(data-label);
                position: absolute;
                left: 10px;
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
                font-weight: bold;
                text-transform: uppercase;
                text-align: left;
                font-size: 0.85em;
            }
            
            .table-responsive table td:last-child {
                border-bottom: 0;
            }
            
            /* Hide symbol column completely on mobile */
            .table-responsive table td.d-none {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid my-3 px-lg-5">
        <div class="row justify-content-center align-items-center g-5">
            <div class="col-12 col-lg-6">
                <div class="text-center">
                    <h1 class="display-5 fw-bold mb-1">Stock R Fact Calculator</h1>
                    <p class="lead mb-0">Live Real-Time Stock Analysis</p>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="rounded-3 p-4 bg-white border shadow-lg">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label for="avgVolume" class="form-label fw-bold">Avg Volume (20d)</label>
                            <input type="number" class="form-control" id="avgVolume" value="20000000" placeholder="20000000">
                        </div>

                        <div class="col-md-4">
                            <label for="sortBy" class="form-label fw-bold">Sort Sectors</label>
                            <select id="sortBy" class="form-select">
                                <optgroup label="% Change">
                                    <option value="percent-high">% Change (High to Low)</option>
                                    <option value="percent-low">% Change (Low to High)</option>
                                </optgroup>
                                <optgroup label="R Fact">
                                    <option value="rfact-high" selected>R Fact (High to Low)</option>
                                    <option value="rfact-low">R Fact (Low to High)</option>
                                </optgroup>
                                <optgroup label="Volume Ratio">
                                    <option value="volume-high">Volume Ratio (High to Low)</option>
                                    <option value="volume-low">Volume Ratio (Low to High)</option>
                                </optgroup>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div class="btn-group w-100 rounded-5 overflow-hidden" role="group">
                                <button class="btn btn-success w-50" id="startBtn" onclick="startBothSectors()" title="Start Auto-Update (1 min)">
                                    Start (1 min)
                                </button>
                                <button class="btn btn-danger w-50 d-none" id="stopBtn" onclick="stopBothSectors()" title="Stop Auto-Update">
                                    Stop
                                </button>
                                <button class="btn btn-primary w-50" onclick="refreshBothSectors()" title="Refresh All Sectors (Alt+R)">
                                    Refresh <small class="d-none d-lg-inline">(Alt+R)</small>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sector Scope Table -->
        <div class="row g-5 mt-0">
            <div class="col-12">
                <div class="rounded-3 p-4 bg-white h-100 shadow-lg border">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th colspan="7" style="font-size: 24px;">Sector Scope - Live Analysis</th>
                                </tr>
                                <tr>
                                    <th width="50" class="text-center">#</th>
                                    <th class="text-start">Sector Name</th>
                                    <th width="150" class="text-center">Avg R Fact</th>
                                    <th width="150" class="text-center">Total Stocks</th>
                                    <th width="150" class="text-center">Positive</th>
                                    <th width="150" class="text-center">Negative</th>
                                    <th width="150" class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody id="sectorScopeTableBody">
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mt-3 text-muted">Waiting for sector data...</p>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2" class="text-end fw-bold">Grand Total:</th>
                                    <th class="text-center">-</th>
                                    <th class="text-center fw-bold"><span id="grandTotalStocks">0</span></th>
                                    <th class="text-center text-success fw-bold"><span id="grandTotalPositive">0</span></th>
                                    <th class="text-center text-danger fw-bold"><span id="grandTotalNegative">0</span></th>
                                    <th class="text-center">-</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="rounded-3 p-4 bg-white h-100 shadow-lg border">
                    <div class="table-responsive">
                     <table class="table table-bordered mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th colspan="12" style="font-size: 24px;">Top 10 High R Fact Stocks</th>
                                </tr>
                                <tr>
                                    <th width="50" class="text-center">#</th>
                                    <th width="140" class="text-start">Sector Name</th>
                                    <th class="text-start">Stock Name</th>
                                    <th width="100" class="text-center">Prev Close</th>
                                    <th width="100" class="text-center">LTP</th>
                                    <th width="100" class="text-center">Today Low</th>
                                    <th width="100" class="text-center">Today High</th>
                                    <th width="100" class="text-center">Current Diff</th>
                                    <th width="100" class="text-center">% Change</th>
                                    <th width="100" class="text-center">Volume</th>
                                    <th width="100" class="text-center">Vol Ratio</th>
                                    <th width="100" class="text-center">R Fact</th>
                                </tr>
                            </thead>
                            <tbody id="topStocksTableBody">
                                <tr>
                                    <td colspan="12" class="text-center py-5">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mt-3 text-muted">Waiting for stock data...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-5 mt-0">
            <div class="col-12 col-xl-6">
                <div class="rounded-3 p-4 bg-white h-100 shadow-lg border">
                    <div class="alert alert-info p-2" id="statusBar" role="alert">
                        <span class="status-badge">🔴</span>
                        <strong>LIVE:</strong> <span id="statusText">Updating...</span>
                        <small id="lastUpdate" class="ms-3"></small>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th colspan="8" style="font-size: 24px;">Bank Sector - Live Analysis</th>
                                </tr>
                                <tr>
                                    <th class="text-nowrap text-start">Stock Name</th>
                                    <th class="d-none">Symbol</th>
                                    <th class="text-center text-nowrap">Prev Close</th>
                                    <th class="text-center text-nowrap">
                                        LTP
                                        <span class="live-pulse"></span>
                                    </th>
                                    <th class="text-center text-nowrap">% Change</th>
                                    <th class="text-center text-nowrap">Volume</th>
                                    <th class="text-center text-nowrap">Vol Ratio</th>
                                    <th class="text-center text-nowrap">R Fact</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mt-3 text-muted">Initializing bank data...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <table class="table table-bordered mb-0 mt-2 align-middle" id="bankStatsTable">
                            <thead>
                                <tr>
                                    <th width="33.33%" class="text-center">Total Stocks: <span id="bankTotal">0</span></th>
                                    <th width="33.33%" class="text-success">Positive: <span id="bankPositive">0</span></th>
                                    <th width="33.33%" class="text-danger">Negative: <span id="bankNegative">0</span></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-6">
                <div class="rounded-3 p-4 bg-white h-100 shadow-lg border">
                    <div class="alert alert-info p-2" id="statusBarPSU" role="alert">
                        <span class="status-badge">🔴</span>
                        <strong>LIVE:</strong> <span id="statusTextPSU">Updating...</span>
                        <small id="lastUpdatePSU" class="ms-3"></small>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th colspan="8" style="font-size: 24px;"> PSU Bank Sector - Live Analysis</th>
                                </tr>
                                <tr>
                                    <th class="text-nowrap text-start">Stock Name</th>
                                    <th class="d-none">Symbol</th>
                                    <th class="text-center text-nowrap">Prev Close</th>
                                    <th class="text-center text-nowrap">
                                        LTP
                                        <span class="live-pulse"></span>
                                    </th>
                                    <th class="text-center text-nowrap">% Change</th>
                                    <th class="text-center text-nowrap">Volume</th>
                                    <th class="text-center text-nowrap">Vol Ratio</th>
                                    <th class="text-center text-nowrap">R Fact</th>
                                </tr>
                            </thead>
                            <tbody id="tableBodyPSU">
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="spinner-border text-warning" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mt-3 text-muted">Initializing PSU bank data...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <table class="table table-bordered mb-0 mt-2 align-middle" id="psuStatsTable">
                            <thead>
                                <tr>
                                    <th width="33.33%" class="text-center">Total Stocks: <span id="psuTotal">0</span></th>
                                    <th width="33.33%" class="text-success">Positive: <span id="psuPositive">0</span></th>
                                    <th width="33.33%" class="text-danger">Negative: <span id="psuNegative">0</span></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-6">
                <div class="rounded-3 p-4 bg-white h-100 shadow-lg border">
                    <div class="alert alert-info p-2" id="statusBarIT" role="alert">
                        <span class="status-badge">🔴</span>
                        <strong>LIVE:</strong> <span id="statusTextIT">Updating...</span>
                        <small id="lastUpdateIT" class="ms-3"></small>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th colspan="8" style="font-size: 24px;"> IT Sector - Live Analysis</th>
                                </tr>
                                <tr>
                                    <th class="text-nowrap text-start">Stock Name</th>
                                    <th class="d-none">Symbol</th>
                                    <th class="text-center text-nowrap">Prev Close</th>
                                    <th class="text-center text-nowrap">
                                        LTP
                                        <span class="live-pulse"></span>
                                    </th>
                                    <th class="text-center text-nowrap">% Change</th>
                                    <th class="text-center text-nowrap">Volume</th>
                                    <th class="text-center text-nowrap">Vol Ratio</th>
                                    <th class="text-center text-nowrap">R Fact</th>
                                </tr>
                            </thead>
                            <tbody id="tableBodyIT">
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="spinner-border text-warning" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mt-3 text-muted">Initializing IT sector data...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <table class="table table-bordered mb-0 mt-2 align-middle" id="itStatsTable">
                            <thead>
                                <tr>
                                    <th width="33.33%" class="text-center">Total Stocks: <span id="itTotal">0</span></th>
                                    <th width="33.33%" class="text-success">Positive: <span id="itPositive">0</span></th>
                                    <th width="33.33%" class="text-danger">Negative: <span id="itNegative">0</span></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-6">
                <div class="rounded-3 p-4 bg-white h-100 shadow-lg border">
                    <div class="alert alert-info p-2" id="statusBarAuto" role="alert">
                        <span class="status-badge">🔴</span>
                        <strong>LIVE:</strong> <span id="statusTextAuto">Updating...</span>
                        <small id="lastUpdateAuto" class="ms-3"></small>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th colspan="8" style="font-size: 24px;"> Auto Sector - Live Analysis</th>
                                </tr>
                                <tr>
                                    <th class="text-nowrap text-start">Stock Name</th>
                                    <th class="d-none">Symbol</th>
                                    <th class="text-center text-nowrap">Prev Close</th>
                                    <th class="text-center text-nowrap">
                                        LTP
                                        <span class="live-pulse"></span>
                                    </th>
                                    <th class="text-center text-nowrap">% Change</th>
                                    <th class="text-center text-nowrap">Volume</th>
                                    <th class="text-center text-nowrap">Vol Ratio</th>
                                    <th class="text-center text-nowrap">R Fact</th>
                                </tr>
                            </thead>
                            <tbody id="tableBodyAuto">
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="spinner-border text-warning" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mt-3 text-muted">Initializing Auto sector data...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <table class="table table-bordered mb-0 mt-2 align-middle" id="autoStatsTable">
                            <thead>
                                <tr>
                                    <th width="33.33%" class="text-center">Total Stocks: <span id="autoTotal">0</span></th>
                                    <th width="33.33%" class="text-success">Positive: <span id="autoPositive">0</span></th>
                                    <th width="33.33%" class="text-danger">Negative: <span id="autoNegative">0</span></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-6">
                <div class="rounded-3 p-4 bg-white h-100 shadow-lg border">
                    <div class="alert alert-info p-2" id="statusBarEnergy" role="alert">
                        <span class="status-badge">🔴</span>
                        <strong>LIVE:</strong> <span id="statusTextEnergy">Updating...</span>
                        <small id="lastUpdateEnergy" class="ms-3"></small>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th colspan="8" style="font-size: 24px;"> Energy Sector - Live Analysis</th>
                                </tr>
                                <tr>
                                    <th class="text-nowrap text-start">Stock Name</th>
                                    <th class="d-none">Symbol</th>
                                    <th class="text-center text-nowrap">Prev Close</th>
                                    <th class="text-center text-nowrap">
                                        LTP
                                        <span class="live-pulse"></span>
                                    </th>
                                    <th class="text-center text-nowrap">% Change</th>
                                    <th class="text-center text-nowrap">Volume</th>
                                    <th class="text-center text-nowrap">Vol Ratio</th>
                                    <th class="text-center text-nowrap">R Fact</th>
                                </tr>
                            </thead>
                            <tbody id="tableBodyEnergy">
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="spinner-border text-warning" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mt-3 text-muted">Initializing Energy sector data...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <table class="table table-bordered mb-0 mt-2 align-middle" id="energyStatsTable">
                            <thead>
                                <tr>
                                    <th width="33.33%" class="text-center">Total Stocks: <span id="energyTotal">0</span></th>
                                    <th width="33.33%" class="text-success">Positive: <span id="energyPositive">0</span></th>
                                    <th width="33.33%" class="text-danger">Negative: <span id="energyNegative">0</span></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-6">
                <div class="rounded-3 p-4 bg-white h-100 shadow-lg border">
                    <div class="alert alert-info p-2" id="statusBarFinService" role="alert">
                        <span class="status-badge">🔴</span>
                        <strong>LIVE:</strong> <span id="statusTextFinService">Updating...</span>
                        <small id="lastUpdateFinService" class="ms-3"></small>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th colspan="8" style="font-size: 24px;"> Financial Services Sector - Live Analysis</th>
                                </tr>
                                <tr>
                                    <th class="text-nowrap text-start">Stock Name</th>
                                    <th class="d-none">Symbol</th>
                                    <th class="text-center text-nowrap">Prev Close</th>
                                    <th class="text-center text-nowrap">
                                        LTP
                                        <span class="live-pulse"></span>
                                    </th>
                                    <th class="text-center text-nowrap">% Change</th>
                                    <th class="text-center text-nowrap">Volume</th>
                                    <th class="text-center text-nowrap">Vol Ratio</th>
                                    <th class="text-center text-nowrap">R Fact</th>
                                </tr>
                            </thead>
                            <tbody id="tableBodyFinService">
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="spinner-border text-warning" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mt-3 text-muted">Initializing Financial Services sector data...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <table class="table table-bordered mb-0 mt-2 align-middle" id="finServiceStatsTable">
                            <thead>
                                <tr>
                                    <th width="33.33%" class="text-center">Total Stocks: <span id="finServiceTotal">0</span></th>
                                    <th width="33.33%" class="text-success">Positive: <span id="finServicePositive">0</span></th>
                                    <th width="33.33%" class="text-danger">Negative: <span id="finServiceNegative">0</span></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-6">
                <div class="rounded-3 p-4 bg-white h-100 shadow-lg border">
                    <div class="alert alert-info p-2" id="statusBarPvtBank" role="alert">
                        <span class="status-badge">🔴</span>
                        <strong>LIVE:</strong> <span id="statusTextPvtBank">Updating...</span>
                        <small id="lastUpdatePvtBank" class="ms-3"></small>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th colspan="8" style="font-size: 24px;"> Private Bank Sector - Live Analysis</th>
                                </tr>
                                <tr>
                                    <th class="text-nowrap text-start">Stock Name</th>
                                    <th class="d-none">Symbol</th>
                                    <th class="text-center text-nowrap">Prev Close</th>
                                    <th class="text-center text-nowrap">
                                        LTP
                                        <span class="live-pulse"></span>
                                    </th>
                                    <th class="text-center text-nowrap">% Change</th>
                                    <th class="text-center text-nowrap">Volume</th>
                                    <th class="text-center text-nowrap">Vol Ratio</th>
                                    <th class="text-center text-nowrap">R Fact</th>
                                </tr>
                            </thead>
                            <tbody id="tableBodyPvtBank">
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="spinner-border text-warning" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mt-3 text-muted">Initializing Private Bank sector data...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <table class="table table-bordered mb-0 mt-2 align-middle" id="pvtBankStatsTable">
                            <thead>
                                <tr>
                                    <th width="33.33%" class="text-center">Total Stocks: <span id="pvtBankTotal">0</span></th>
                                    <th width="33.33%" class="text-success">Positive: <span id="pvtBankPositive">0</span></th>
                                    <th width="33.33%" class="text-danger">Negative: <span id="pvtBankNegative">0</span></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-6">
                <div class="rounded-3 p-4 bg-white h-100 shadow-lg border">
                    <div class="alert alert-info p-2" id="statusBarNiftyMidSelect" role="alert">
                        <span class="status-badge">🔴</span>
                        <strong>LIVE:</strong> <span id="statusTextNiftyMidSelect">Updating...</span>
                        <small id="lastUpdateNiftyMidSelect" class="ms-3"></small>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th colspan="8" style="font-size: 24px;"> Nifty Mid Select Sector - Live Analysis</th>
                                </tr>
                                <tr>
                                    <th class="text-nowrap text-start">Stock Name</th>
                                    <th class="d-none">Symbol</th>
                                    <th class="text-center text-nowrap">Prev Close</th>
                                    <th class="text-center text-nowrap">
                                        LTP
                                        <span class="live-pulse"></span>
                                    </th>
                                    <th class="text-center text-nowrap">% Change</th>
                                    <th class="text-center text-nowrap">Volume</th>
                                    <th class="text-center text-nowrap">Vol Ratio</th>
                                    <th class="text-center text-nowrap">R Fact</th>
                                </tr>
                            </thead>
                            <tbody id="tableBodyNiftyMidSelect">
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="spinner-border text-warning" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mt-3 text-muted">Initializing Nifty Mid Select sector data...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <table class="table table-bordered mb-0 mt-2 align-middle" id="niftyMidSelectStatsTable">
                            <thead>
                                <tr>
                                    <th width="33.33%" class="text-center">Total Stocks: <span id="niftyMidSelectTotal">0</span></th>
                                    <th width="33.33%" class="text-success">Positive: <span id="niftyMidSelectPositive">0</span></th>
                                    <th width="33.33%" class="text-danger">Negative: <span id="niftyMidSelectNegative">0</span></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-6">
                <div class="rounded-3 p-4 bg-white h-100 shadow-lg border">
                    <div class="alert alert-info p-2" id="statusBarRealty" role="alert">
                        <span class="status-badge">🔴</span>
                        <strong>LIVE:</strong> <span id="statusTextRealty">Updating...</span>
                        <small id="lastUpdateRealty" class="ms-3"></small>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th colspan="8" style="font-size: 24px;"> Realty Sector - Live Analysis</th>
                                </tr>
                                <tr>
                                    <th class="text-nowrap text-start">Stock Name</th>
                                    <th class="d-none">Symbol</th>
                                    <th class="text-center text-nowrap">Prev Close</th>
                                    <th class="text-center text-nowrap">
                                        LTP
                                        <span class="live-pulse"></span>
                                    </th>
                                    <th class="text-center text-nowrap">% Change</th>
                                    <th class="text-center text-nowrap">Volume</th>
                                    <th class="text-center text-nowrap">Vol Ratio</th>
                                    <th class="text-center text-nowrap">R Fact</th>
                                </tr>
                            </thead>
                            <tbody id="tableBodyRealty">
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="spinner-border text-warning" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mt-3 text-muted">Initializing Realty sector data...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <table class="table table-bordered mb-0 mt-2 align-middle" id="realtyStatsTable">
                            <thead>
                                <tr>
                                    <th width="33.33%" class="text-center">Total Stocks: <span id="realtyTotal">0</span></th>
                                    <th width="33.33%" class="text-success">Positive: <span id="realtyPositive">0</span></th>
                                    <th width="33.33%" class="text-danger">Negative: <span id="realtyNegative">0</span></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-6">
                <div class="rounded-3 p-4 bg-white h-100 shadow-lg border">
                    <div class="alert alert-info p-2" id="statusBarMetal" role="alert">
                        <span class="status-badge">🔴</span>
                        <strong>LIVE:</strong> <span id="statusTextMetal">Updating...</span>
                        <small id="lastUpdateMetal" class="ms-3"></small>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th colspan="8" style="font-size: 24px;"> Metal Sector - Live Analysis</th>
                                </tr>
                                <tr>
                                    <th class="text-nowrap text-start">Stock Name</th>
                                    <th class="d-none">Symbol</th>
                                    <th class="text-center text-nowrap">Prev Close</th>
                                    <th class="text-center text-nowrap">
                                        LTP
                                        <span class="live-pulse"></span>
                                    </th>
                                    <th class="text-center text-nowrap">% Change</th>
                                    <th class="text-center text-nowrap">Volume</th>
                                    <th class="text-center text-nowrap">Vol Ratio</th>
                                    <th class="text-center text-nowrap">R Fact</th>
                                </tr>
                            </thead>
                            <tbody id="tableBodyMetal">
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="spinner-border text-warning" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mt-3 text-muted">Initializing Metal sector data...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <table class="table table-bordered mb-0 mt-2 align-middle" id="metalStatsTable">
                            <thead>
                                <tr>
                                    <th width="33.33%" class="text-center">Total Stocks: <span id="metalTotal">0</span></th>
                                    <th width="33.33%" class="text-success">Positive: <span id="metalPositive">0</span></th>
                                    <th width="33.33%" class="text-danger">Negative: <span id="metalNegative">0</span></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-6">
                <div class="rounded-3 p-4 bg-white h-100 shadow-lg border">
                    <div class="alert alert-info p-2" id="statusBarFMCG" role="alert">
                        <span class="status-badge">🔴</span>
                        <strong>LIVE:</strong> <span id="statusTextFMCG">Updating...</span>
                        <small id="lastUpdateFMCG" class="ms-3"></small>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th colspan="8" style="font-size: 24px;"> FMCG Sector - Live Analysis</th>
                                </tr>
                                <tr>
                                    <th class="text-nowrap text-start">Stock Name</th>
                                    <th class="d-none">Symbol</th>
                                    <th class="text-center text-nowrap">Prev Close</th>
                                    <th class="text-center text-nowrap">
                                        LTP
                                        <span class="live-pulse"></span>
                                    </th>
                                    <th class="text-center text-nowrap">% Change</th>
                                    <th class="text-center text-nowrap">Volume</th>
                                    <th class="text-center text-nowrap">Vol Ratio</th>
                                    <th class="text-center text-nowrap">R Fact</th>
                                </tr>
                            </thead>
                            <tbody id="tableBodyFMCG">
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="spinner-border text-warning" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mt-3 text-muted">Initializing FMCG sector data...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <table class="table table-bordered mb-0 mt-2 align-middle" id="fmcgStatsTable">
                            <thead>
                                <tr>
                                    <th width="33.33%" class="text-center">Total Stocks: <span id="fmcgTotal">0</span></th>
                                    <th width="33.33%" class="text-success">Positive: <span id="fmcgPositive">0</span></th>
                                    <th width="33.33%" class="text-danger">Negative: <span id="fmcgNegative">0</span></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-6">
                <div class="rounded-3 p-4 bg-white h-100 shadow-lg border">
                    <div class="alert alert-info p-2" id="statusBarPharma" role="alert">
                        <span class="status-badge">🔴</span>
                        <strong>LIVE:</strong> <span id="statusTextPharma">Updating...</span>
                        <small id="lastUpdatePharma" class="ms-3"></small>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th colspan="8" style="font-size: 24px;"> Pharma Sector - Live Analysis</th>
                                </tr>
                                <tr>
                                    <th class="text-nowrap text-start">Stock Name</th>
                                    <th class="d-none">Symbol</th>
                                    <th class="text-center text-nowrap">Prev Close</th>
                                    <th class="text-center text-nowrap">
                                        LTP
                                        <span class="live-pulse"></span>
                                    </th>
                                    <th class="text-center text-nowrap">% Change</th>
                                    <th class="text-center text-nowrap">Volume</th>
                                    <th class="text-center text-nowrap">Vol Ratio</th>
                                    <th class="text-center text-nowrap">R Fact</th>
                                </tr>
                            </thead>
                            <tbody id="tableBodyPharma">
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="spinner-border text-warning" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mt-3 text-muted">Initializing Pharma sector data...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <table class="table table-bordered mb-0 mt-2 align-middle" id="pharmaStatsTable">
                            <thead>
                                <tr>
                                    <th width="33.33%" class="text-center">Total Stocks: <span id="pharmaTotal">0</span></th>
                                    <th width="33.33%" class="text-success">Positive: <span id="pharmaPositive">0</span></th>
                                    <th width="33.33%" class="text-danger">Negative: <span id="pharmaNegative">0</span></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-6">
                <div class="rounded-3 p-4 bg-white h-100 shadow-lg border">
                    <div class="alert alert-info p-2" id="statusBarSensex" role="alert">
                        <span class="status-badge">🔴</span>
                        <strong>LIVE:</strong> <span id="statusTextSensex">Updating...</span>
                        <small id="lastUpdateSensex" class="ms-3"></small>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th colspan="8" style="font-size: 24px;"> Sensex 30 - Live Analysis</th>
                                </tr>
                                <tr>
                                    <th class="text-nowrap text-start">Stock Name</th>
                                    <th class="d-none">Symbol</th>
                                    <th class="text-center text-nowrap">Prev Close</th>
                                    <th class="text-center text-nowrap">
                                        LTP
                                        <span class="live-pulse"></span>
                                    </th>
                                    <th class="text-center text-nowrap">% Change</th>
                                    <th class="text-center text-nowrap">Volume</th>
                                    <th class="text-center text-nowrap">Vol Ratio</th>
                                    <th class="text-center text-nowrap">R Fact</th>
                                </tr>
                            </thead>
                            <tbody id="tableBodySensex">
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="spinner-border text-warning" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mt-3 text-muted">Initializing Sensex 30 data...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <table class="table table-bordered mb-0 mt-2 align-middle" id="sensexStatsTable">
                            <thead>
                                <tr>
                                    <th width="33.33%" class="text-center">Total Stocks: <span id="sensexTotal">0</span></th>
                                    <th width="33.33%" class="text-success">Positive: <span id="sensexPositive">0</span></th>
                                    <th width="33.33%" class="text-danger">Negative: <span id="sensexNegative">0</span></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-6">
                <div class="rounded-3 p-4 bg-white h-100 shadow-lg border">
                    <div class="alert alert-info p-2" id="statusBarNifty50" role="alert">
                        <span class="status-badge">🔴</span>
                        <strong>LIVE:</strong> <span id="statusTextNifty50">Updating...</span>
                        <small id="lastUpdateNifty50" class="ms-3"></small>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th colspan="8" style="font-size: 24px;"> Nifty 50 - Live Analysis</th>
                                </tr>
                                <tr>
                                    <th class="text-nowrap text-start">Stock Name</th>
                                    <th class="d-none">Symbol</th>
                                    <th class="text-center text-nowrap">Prev Close</th>
                                    <th class="text-center text-nowrap">
                                        LTP
                                        <span class="live-pulse"></span>
                                    </th>
                                    <th class="text-center text-nowrap">% Change</th>
                                    <th class="text-center text-nowrap">Volume</th>
                                    <th class="text-center text-nowrap">Vol Ratio</th>
                                    <th class="text-center text-nowrap">R Fact</th>
                                </tr>
                            </thead>
                            <tbody id="tableBodyNifty50">
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="spinner-border text-warning" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mt-3 text-muted">Initializing Nifty 50 data...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <table class="table table-bordered mb-0 mt-2 align-middle" id="nifty50StatsTable">
                            <thead>
                                <tr>
                                    <th width="33.33%" class="text-center">Total Stocks: <span id="nifty50Total">0</span></th>
                                    <th width="33.33%" class="text-success">Positive: <span id="nifty50Positive">0</span></th>
                                    <th width="33.33%" class="text-danger">Negative: <span id="nifty50Negative">0</span></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-6">
                <div class="rounded-3 p-4 bg-white h-100 shadow-lg border">
                    <div class="alert alert-info p-2" id="statusBarCement" role="alert">
                        <span class="status-badge">🔴</span>
                        <strong>LIVE:</strong> <span id="statusTextCement">Updating...</span>
                        <small id="lastUpdateCement" class="ms-3"></small>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th colspan="8" style="font-size: 24px;"> Cement Sector - Live Analysis</th>
                                </tr>
                                <tr>
                                    <th class="text-nowrap text-start">Stock Name</th>
                                    <th class="d-none">Symbol</th>
                                    <th class="text-center text-nowrap">Prev Close</th>
                                    <th class="text-center text-nowrap">
                                        LTP
                                        <span class="live-pulse"></span>
                                    </th>
                                    <th class="text-center text-nowrap">% Change</th>
                                    <th class="text-center text-nowrap">Volume</th>
                                    <th class="text-center text-nowrap">Vol Ratio</th>
                                    <th class="text-center text-nowrap">R Fact</th>
                                </tr>
                            </thead>
                            <tbody id="tableBodyCement">
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="spinner-border text-warning" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mt-3 text-muted">Initializing Cement sector data...</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <table class="table table-bordered mb-0 mt-2 align-middle" id="cementStatsTable">
                            <thead>
                                <tr>
                                    <th width="33.33%" class="text-center">Total Stocks: <span id="cementTotal">0</span></th>
                                    <th width="33.33%" class="text-success">Positive: <span id="cementPositive">0</span></th>
                                    <th width="33.33%" class="text-danger">Negative: <span id="cementNegative">0</span></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center g-5 mt-0">
            <div class="col-12">
                <div class="rounded-3 p-4 bg-white h-100 shadow-lg border">
                    Powered by Sandip Kalariya <br>
                    <small class="text-muted">Disclaimer: This is a tool for educational purposes only. The information provided is not financial advice and should not be relied upon for investment decisions.</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Back to Top Button -->
    <button id="backToTop" title="Back to Top">
        <i class="bi bi-arrow-up"></i>
    </button>

    <!-- Progress Indicator - Bottom Left -->
    <div id="progressIndicator">
        <div class="progress-header">
            <div class="progress-title">
                Refreshing Sectors
            </div>
            <div class="progress-counter">
                <span id="progressCurrent">0</span>/<span id="progressTotal">0</span>
            </div>
        </div>
        <div class="progress-sector">
            <div class="spinner-border text-light" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <span id="progressSectorName">Starting...</span>
        </div>
        <div class="progress-bar-container">
            <div class="progress-bar-fill" id="progressBarFill" style="width: 0%"></div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <script>
        // All Banking stocks data
        const banks = [
            { name: "HDFC Bank", symbol: "HDFCBANK.NS", basePrice: 1742.80, prevClose: 1735.40, baseVolume: 31000000 },
            { name: "ICICI Bank", symbol: "ICICIBANK.NS", basePrice: 1238.60, prevClose: 1232.15, baseVolume: 42000000 },
            { name: "State Bank of India", symbol: "SBIN.NS", basePrice: 912.90, prevClose: 907.85, baseVolume: 52000000 },
            { name: "Kotak Mahindra Bank", symbol: "KOTAKBANK.NS", basePrice: 1765.90, prevClose: 1759.25, baseVolume: 22000000 },
            { name: "Axis Bank", symbol: "AXISBANK.NS", basePrice: 1165.40, prevClose: 1158.70, baseVolume: 28000000 },
            { name: "Bank of Baroda", symbol: "BANKBARODA.NS", basePrice: 258.65, prevClose: 255.80, baseVolume: 58000000 },
            { name: "Punjab National Bank", symbol: "PNB.NS", basePrice: 108.75, prevClose: 107.20, baseVolume: 75000000 },
            { name: "Canara Bank", symbol: "CANBK.NS", basePrice: 112.30, prevClose: 110.90, baseVolume: 45000000 },
            { name: "AU Small Finance Bank", symbol: "AUBANK.NS", basePrice: 185.40, prevClose: 183.75, baseVolume: 35000000 },
            { name: "IDFC First Bank", symbol: "IDFCFIRSTB.NS", basePrice: 72.50, prevClose: 71.35, baseVolume: 62000000 },
            { name: "IndusInd Bank", symbol: "INDUSINDBK.NS", basePrice: 1485.30, prevClose: 1478.50, baseVolume: 18000000 },
            { name: "Federal Bank", symbol: "FEDERALBNK.NS", basePrice: 185.40, prevClose: 183.75, baseVolume: 35000000 },
        ];

        // PSU Banking stocks data
        const psuBanks = [
            { name: "State Bank of India", symbol: "SBIN.NS", basePrice: 912.90, prevClose: 907.85, baseVolume: 52000000 },
            { name: "Bank of Baroda", symbol: "BANKBARODA.NS", basePrice: 258.65, prevClose: 255.80, baseVolume: 58000000 },
            { name: "Punjab National Bank", symbol: "PNB.NS", basePrice: 108.75, prevClose: 107.20, baseVolume: 75000000 },
            { name: "Canara Bank", symbol: "CANBK.NS", basePrice: 112.30, prevClose: 110.90, baseVolume: 45000000 },
            { name: "Indian Bank", symbol: "INDIANB.NS", basePrice: 542.80, prevClose: 538.20, baseVolume: 15000000 },
            { name: "Union Bank of India", symbol: "UNIONBANK.NS", basePrice: 125.60, prevClose: 123.45, baseVolume: 38000000 },
            { name: "Indian Overseas Bank", symbol: "IOB.NS", basePrice: 102.50, prevClose: 101.25, baseVolume: 22000000 },
            { name: "Bank of India", symbol: "BANKINDIA.NS", basePrice: 115.40, prevClose: 113.85, baseVolume: 32000000 },
            { name: "Bank of Maharashtra", symbol: "MAHABANK.NS", basePrice: 52.35, prevClose: 51.20, baseVolume: 25000000 },
            { name: "UCO Bank", symbol: "UCOBANK.NS", basePrice: 46.25, prevClose: 45.40, baseVolume: 42000000 },
            { name: "Central Bank of India", symbol: "CENTRALBK.NS", basePrice: 58.90, prevClose: 57.65, baseVolume: 28000000 },
        ];

        // IT sector stocks data
        const itStocks = [
            { name: "HCL Technologies", symbol: "HCLTECH.NS", basePrice: 658.40, prevClose: 652.75, baseVolume: 15000000 },
            { name: "Tata Consultancy Services", symbol: "TCS.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Infosys", symbol: "INFY.NS", basePrice: 825.30, prevClose: 819.50, baseVolume: 22000000 },
            { name: "Cams", symbol: "CAMS.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Wipro", symbol: "WIPRO.NS", basePrice: 325.20, prevClose: 322.85, baseVolume: 28000000 },
            { name: "Tata Elxsi", symbol: "TATAELXSI.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Mphasis", symbol: "MPHASIS.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Cyient", symbol: "CYIENT.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Tata Technologies", symbol: "TATATECH.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Tech Mahindra", symbol: "TECHM.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "LTIMindtree Ltd", symbol: "LTIM.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Persistent", symbol: "PERSISTENT.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "KPIT Technologies Ltd", symbol: "KPITTECH.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Kaynes Technology India Ltd", symbol: "KAYNES.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Oracle Financial Services Software Ltd", symbol: "OFSS.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Coforge Ltd", symbol: "COFORGE.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "HFCL Limited", symbol: "HFCL.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
        ];

        // Energy sector stocks data
        const energyStocks = [
            { name: "IGL", symbol: "IGL.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Reliance Industries", symbol: "RELIANCE.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "INOX Wind Energy", symbol: "INOXWIND.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "JSW Energy", symbol: "JSWENERGY.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Oil India Ltd", symbol: "OIL.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Tornpowr", symbol: "TORNTPOWER.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Tata Power", symbol: "TATAPOWER.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "BPCL", symbol: "BPCL.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Oil and Natural Gas Corporation Ltd", symbol: "ONGC.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Coal India Ltd", symbol: "COALINDIA.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Solar Industries India Ltd", symbol: "SOLARINDS.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Blue Star Limited", symbol: "BLUESTARCO.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Petronet LNG Ltd", symbol: "PETRONET.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Indian Oil Corporation Ltd", symbol: "IOC.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "GMR Airports Ltd", symbol: "GMRAIRPORT.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Hitachi Energy India Ltd", symbol: "POWERINDIA.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Adani Green Energy Ltd", symbol: "ADANIGREEN.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Power Grid Corporation of India Ltd", symbol: "POWERGRID.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "CGPower", symbol: "CGPOWER.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Adani Energy Solutions Ltd", symbol: "ADANIENSOL.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Bharat Dynamics Ltd", symbol: "BDL.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "NHPC Ltd", symbol: "NHPC.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Indian Renewable Energy Dev Agency Ltd", symbol: "IREDA.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "NTPC Ltd", symbol: "NTPC.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Suzlon Energy Ltd", symbol: "SUZLON.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Mazagon Dock Shipbuilders Ltd", symbol: "MAZDOCK.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },            
        ];

        // Auto sector stocks data
        const autoStocks = [
            { name: "Hero MotoCorp", symbol: "HEROMOTOCO.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Sonacoms", symbol: "SONACOMS.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Tiindia", symbol: "TIINDIA.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Motherson", symbol: "MOTHERSON.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Exide Industries", symbol: "EXIDEIND.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Bharat Forge", symbol: "BHARATFORG.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "TVS Motor", symbol: "TVSMOTOR.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Ashok Leyland", symbol: "ASHOKLEY.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Eicher Motors", symbol: "EICHERMOT.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Bajaj Auto", symbol: "BAJAJ-AUTO.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Bosch Ltd", symbol: "BOSCHLTD.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Titagarh Rail Systems", symbol: "TITAGARH.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Maruti Suzuki", symbol: "MARUTI.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Tata Motors", symbol: "TATAMOTORS.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "M&M", symbol: "M&M.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Uno Minda", symbol: "UNOMINDA.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
        ];

        // Financial Services sector stocks data
        const finServiceStocks = [
            { name: "ICICI Prudential Life", symbol: "ICICIPRULI.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "ICICI Bank", symbol: "ICICIBANK.NS", basePrice: 1238.60, prevClose: 1232.15, baseVolume: 42000000 },
            { name: "Shriram Finance", symbol: "SHRIRAMFIN.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "CDSL", symbol: "CDSL.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "LIC", symbol: "LICI.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "HDFC Bank", symbol: "HDFCBANK.NS", basePrice: 1742.80, prevClose: 1735.40, baseVolume: 31000000 },
            { name: "SBI Cards", symbol: "SBICARD.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Axis Bank", symbol: "AXISBANK.NS", basePrice: 1165.40, prevClose: 1158.70, baseVolume: 28000000 },
            { name: "Paytm", symbol: "PAYTM.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "SBI", symbol: "SBIN.NS", basePrice: 912.90, prevClose: 907.85, baseVolume: 52000000 },
            { name: "Angel One", symbol: "ANGELONE.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "PNB Housing Finance", symbol: "PNBHOUSING.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "LIC Housing Finance", symbol: "LICHSGFIN.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Policy Bazaar", symbol: "POLICYBZR.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Bajaj Finance", symbol: "BAJAJFINSV.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Kotak Bank", symbol: "KOTAKBANK.NS", basePrice: 1765.90, prevClose: 1759.25, baseVolume: 22000000 },
            { name: "Muthoot Finance", symbol: "MUTHOOTFIN.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Jio Finance", symbol: "JIOFIN.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Nuvama Wealth", symbol: "NUVAMA.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "SBI Life", symbol: "SBILIFE.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "REC Ltd", symbol: "RECLTD.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "BSE", symbol: "BSE.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Bajaj Finance", symbol: "BAJFINANCE.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Cholamandalam", symbol: "CHOLAFIN.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "HDFC AMC", symbol: "HDFCAMC.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Max Health", symbol: "MAXHEALTH.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "PFC", symbol: "PFC.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Samunnati Capital", symbol: "SAMMAANCAP.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "IIFL Finance", symbol: "IIFL.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "IRFC", symbol: "IRFC.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "HUDCO", symbol: "HUDCO.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "HDFC Life", symbol: "HDFCLIFE.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "ICICI GI", symbol: "ICICIGI.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
        ];

        // Private Bank sector stocks data
        const pvtBanks = [
            { name: "IDFC First Bank", symbol: "IDFCFIRSTB.NS", basePrice: 72.50, prevClose: 71.35, baseVolume: 62000000 },
            { name: "ICICI Bank", symbol: "ICICIBANK.NS", basePrice: 1238.60, prevClose: 1232.15, baseVolume: 42000000 },
            { name: "Bandhan Bank", symbol: "BANDHANBNK.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "RBL Bank", symbol: "RBLBANK.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "HDFC Bank", symbol: "HDFCBANK.NS", basePrice: 1742.80, prevClose: 1735.40, baseVolume: 31000000 },
            { name: "Axis Bank", symbol: "AXISBANK.NS", basePrice: 1165.40, prevClose: 1158.70, baseVolume: 28000000 },
            { name: "Federal Bank", symbol: "FEDERALBNK.NS", basePrice: 185.40, prevClose: 183.75, baseVolume: 35000000 },
            { name: "IndusInd Bank", symbol: "INDUSINDBK.NS", basePrice: 1485.30, prevClose: 1478.50, baseVolume: 18000000 },
            { name: "Kotak Mahindra Bank", symbol: "KOTAKBANK.NS", basePrice: 1765.90, prevClose: 1759.25, baseVolume: 22000000 },
        ];

        // Nifty Mid Select sector stocks data
        const niftyMidSelectStocks = [
            { name: "IDFC First Bank", symbol: "IDFCFIRSTB.NS", basePrice: 72.50, prevClose: 71.35, baseVolume: 62000000 },
            { name: "Hind Petro", symbol: "HINDPETRO.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Astral Ltd", symbol: "ASTRAL.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Polycab", symbol: "POLYCAB.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Federal Bank", symbol: "FEDERALBNK.NS", basePrice: 185.40, prevClose: 183.75, baseVolume: 35000000 },
            { name: "Jubilant Foodworks", symbol: "JUBLFOOD.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Cummins India", symbol: "CUMMINSIND.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "PIIND", symbol: "PIIND.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Aurobindo Pharma", symbol: "AUROPHARMA.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "UPL", symbol: "UPL.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Bharat Forge", symbol: "BHARATFORG.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Mphasis", symbol: "MPHASIS.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Ashok Leyland", symbol: "ASHOKLEY.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Godrej Properties", symbol: "GODREJPROP.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Voltas", symbol: "VOLTAS.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Indotel", symbol: "INDHOTEL.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Concor", symbol: "CONCOR.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Lupin", symbol: "LUPIN.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Persistent Systems", symbol: "PERSISTENT.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Page Industries", symbol: "PAGEIND.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "AU Small Finance Bank", symbol: "AUBANK.NS", basePrice: 185.40, prevClose: 183.75, baseVolume: 35000000 },
            { name: "HDFC AMC", symbol: "HDFCAMC.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Coforge Ltd", symbol: "COFORGE.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "RVNL", symbol: "RVNL.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
        ];

        // Realty sector stocks data
        const realtyStocks = [
            { name: "Phoenix Ltd", symbol: "PHOENIXLTD.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Godrej Properties", symbol: "GODREJPROP.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Prestige", symbol: "PRESTIGE.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "NCC", symbol: "NCC.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Oberoi Realty", symbol: "OBEROIRLTY.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "NBCC", symbol: "NBCC.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "DLF", symbol: "DLF.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Lodha", symbol: "LODHA.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
        ];

        // Metal sector stocks data
        const metalStocks = [
            { name: "Hindalco", symbol: "HINDALCO.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "VEDL", symbol: "VEDL.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "JSW Steel", symbol: "JSWSTEEL.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Hindzinc", symbol: "HINDZINC.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "National Aluminium", symbol: "NATIONALUM.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Adani Enterprises", symbol: "ADANIENT.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "NMDC", symbol: "NMDC.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Jindal Steel", symbol: "JINDALSTEL.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "APL Apollo", symbol: "APLAPOLLO.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "SAIL", symbol: "SAIL.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Tata Steel", symbol: "TATASTEEL.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
        ];

        // FMCG sector stocks data
        const fmcgStocks = [
            { name: "Eternal", symbol: "ETERNALLTD.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Patanjali", symbol: "PATANJALI.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Hindustan Unilever", symbol: "HINDUNILVR.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Colpal", symbol: "COLPAL.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Marico", symbol: "MARICO.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Nestle India", symbol: "NESTLEIND.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Dabur", symbol: "DABUR.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "ITC", symbol: "ITC.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Nykaa", symbol: "NYKAA.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Kalyan Jewellers", symbol: "KALYANKJIL.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "United Spirits", symbol: "UNITDSPR.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Tata Consumer", symbol: "TATACONSUM.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Britannia", symbol: "BRITANNIA.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "VBL", symbol: "VBL.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "DMart", symbol: "DMART.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Godrej Consumer", symbol: "GODREJCP.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Supreme Industries", symbol: "SUPREMEIND.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
        ];

        // Pharma sector stocks data
        const pharmaStocks = [
            { name: "Fortis Healthcare", symbol: "FORTIS.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "IPCA Laboratories", symbol: "IPCALAB.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Sun Pharma", symbol: "SUNPHARMA.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Alkem Laboratories", symbol: "ALKEM.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Mankind Pharma", symbol: "MANKIND.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Laurus Labs", symbol: "LAURUSLABS.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Torrent Pharma", symbol: "TORNTPHARM.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Dr Reddy's", symbol: "DRREDDY.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Aurobindo Pharma", symbol: "AUROPHARMA.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Cipla", symbol: "CIPLA.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Lupin", symbol: "LUPIN.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Zydus Lifesciences", symbol: "ZYDUSLIFE.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Divi's Laboratories", symbol: "DIVISLAB.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Glenmark Pharma", symbol: "GLENMARK.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Biocon", symbol: "BIOCON.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
        ];

        // Cement sector stocks data
        const cementStocks = [
            { name: "Dalmia Bharat", symbol: "DALBHARAT.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "UltraTech Cement", symbol: "ULTRACEMCO.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Shree Cement", symbol: "SHREECEM.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Ambuja Cements", symbol: "AMBUJACEM.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
        ];

        // Nifty 50 sector stocks data
        const nifty50Stocks = [
            { name: "Reliance Industries", symbol: "RELIANCE.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Bharti Airtel", symbol: "BHARTIARTL.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "UltraTech Cement", symbol: "ULTRACEMCO.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Hindalco", symbol: "HINDALCO.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Hero MotoCorp", symbol: "HEROMOTOCO.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "ICICI Bank", symbol: "ICICIBANK.NS", basePrice: 1238.60, prevClose: 1232.15, baseVolume: 42000000 },
            { name: "Hindustan Unilever", symbol: "HINDUNILVR.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "ONGC", symbol: "ONGC.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Sun Pharma", symbol: "SUNPHARMA.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "LT", symbol: "LT.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "HCL Technologies", symbol: "HCLTECH.NS", basePrice: 658.40, prevClose: 652.75, baseVolume: 15000000 },
            { name: "Adani Ports", symbol: "ADANIPORTS.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "BPCL", symbol: "BPCL.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Shriram Finance", symbol: "SHRIRAMFIN.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "HDFC Bank", symbol: "HDFCBANK.NS", basePrice: 1742.80, prevClose: 1735.40, baseVolume: 31000000 },
            { name: "Axis Bank", symbol: "AXISBANK.NS", basePrice: 1165.40, prevClose: 1158.70, baseVolume: 28000000 },
            { name: "TCS", symbol: "TCS.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Infosys", symbol: "INFY.NS", basePrice: 825.30, prevClose: 819.50, baseVolume: 22000000 },
            { name: "Grasim Industries", symbol: "GRASIM.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Nestle India", symbol: "NESTLEIND.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "SBI", symbol: "SBIN.NS", basePrice: 912.90, prevClose: 907.85, baseVolume: 52000000 },
            { name: "JSW Steel", symbol: "JSWSTEEL.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Dr Reddy's", symbol: "DRREDDY.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Asian Paints", symbol: "ASIANPAINT.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Bajaj Finserv", symbol: "BAJAJFINSV.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Coal India", symbol: "COALINDIA.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "IndusInd Bank", symbol: "INDUSINDBK.NS", basePrice: 1485.30, prevClose: 1478.50, baseVolume: 18000000 },
            { name: "Kotak Mahindra Bank", symbol: "KOTAKBANK.NS", basePrice: 1765.90, prevClose: 1759.25, baseVolume: 22000000 },
            { name: "ITC", symbol: "ITC.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Wipro", symbol: "WIPRO.NS", basePrice: 325.20, prevClose: 322.85, baseVolume: 28000000 },
            { name: "Eicher Motors", symbol: "EICHERMOT.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Tata Consumer", symbol: "TATACONSUM.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Apollo Hospitals", symbol: "APOLLOHOSP.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Britannia", symbol: "BRITANNIA.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "SBI Life", symbol: "SBILIFE.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Cipla", symbol: "CIPLA.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Tech Mahindra", symbol: "TECHM.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Titan Company", symbol: "TITAN.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Adani Enterprises", symbol: "ADANIENT.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Bajaj Auto", symbol: "BAJAJ-AUTO.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Power Grid", symbol: "POWERGRID.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Bajaj Finance", symbol: "BAJFINANCE.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Trent", symbol: "TRENT.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Maruti Suzuki", symbol: "MARUTI.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Tata Steel", symbol: "TATASTEEL.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "NTPC", symbol: "NTPC.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Tata Motors", symbol: "TATAMOTORS.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "BEL", symbol: "BEL.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "HDFC Life", symbol: "HDFCLIFE.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "M&M", symbol: "M&M.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
        ];

        // Sensex 30 sector stocks data
        const sensexStocks = [
            { name: "Reliance Industries", symbol: "RELIANCE.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Bharti Airtel", symbol: "BHARTIARTL.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "UltraTech Cement", symbol: "ULTRACEMCO.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "ICICI Bank", symbol: "ICICIBANK.NS", basePrice: 1238.60, prevClose: 1232.15, baseVolume: 42000000 },
            { name: "Hindustan Unilever", symbol: "HINDUNILVR.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Sun Pharma", symbol: "SUNPHARMA.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "LT", symbol: "LT.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "HCL Technologies", symbol: "HCLTECH.NS", basePrice: 658.40, prevClose: 652.75, baseVolume: 15000000 },
            { name: "Adani Ports", symbol: "ADANIPORTS.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "HDFC Bank", symbol: "HDFCBANK.NS", basePrice: 1742.80, prevClose: 1735.40, baseVolume: 31000000 },
            { name: "Axis Bank", symbol: "AXISBANK.NS", basePrice: 1165.40, prevClose: 1158.70, baseVolume: 28000000 },
            { name: "TCS", symbol: "TCS.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Infosys", symbol: "INFY.NS", basePrice: 825.30, prevClose: 819.50, baseVolume: 22000000 },
            { name: "Nestle India", symbol: "NESTLEIND.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "SBI", symbol: "SBIN.NS", basePrice: 912.90, prevClose: 907.85, baseVolume: 52000000 },
            { name: "JSW Steel", symbol: "JSWSTEEL.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Asian Paints", symbol: "ASIANPAINT.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Bajaj Finserv", symbol: "BAJAJFINSV.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "IndusInd Bank", symbol: "INDUSINDBK.NS", basePrice: 1485.30, prevClose: 1478.50, baseVolume: 18000000 },
            { name: "Kotak Mahindra Bank", symbol: "KOTAKBANK.NS", basePrice: 1765.90, prevClose: 1759.25, baseVolume: 22000000 },
            { name: "ITC", symbol: "ITC.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Tech Mahindra", symbol: "TECHM.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Titan Company", symbol: "TITAN.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Power Grid", symbol: "POWERGRID.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Bajaj Finance", symbol: "BAJFINANCE.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Maruti Suzuki", symbol: "MARUTI.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Tata Steel", symbol: "TATASTEEL.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "NTPC", symbol: "NTPC.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "Tata Motors", symbol: "TATAMOTORS.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
            { name: "M&M", symbol: "M&M.NS", basePrice: 1025.40, prevClose: 1018.75, baseVolume: 18000000 },
        ];

        // Global variables
        let priceMemory = {};
        let countdownInterval = null;
        let nextUpdateTime = null;
        let autoRefreshInterval = null;
        
        // Sector configuration - Add new sectors here
        const sectors = {
            bank: {
                name: 'Bank',
                data: banks,
                tableBodyId: 'tableBody',
                statusTextId: 'statusText',
                lastUpdateId: 'lastUpdate',
                rowPrefix: 'row',
                statsIds: {
                    total: 'bankTotal',
                    positive: 'bankPositive',
                    negative: 'bankNegative'
                },
                sectorData: [],
                updateInterval: null
            },
            psu: {
                name: 'PSU Bank',
                data: psuBanks,
                tableBodyId: 'tableBodyPSU',
                statusTextId: 'statusTextPSU',
                lastUpdateId: 'lastUpdatePSU',
                rowPrefix: 'row-psu',
                statsIds: {
                    total: 'psuTotal',
                    positive: 'psuPositive',
                    negative: 'psuNegative'
                },
                sectorData: [],
                updateInterval: null
            },
            it: {
                name: 'IT',
                data: itStocks,
                tableBodyId: 'tableBodyIT',
                statusTextId: 'statusTextIT',
                lastUpdateId: 'lastUpdateIT',
                rowPrefix: 'row-it',
                statsIds: {
                    total: 'itTotal',
                    positive: 'itPositive',
                    negative: 'itNegative'
                },
                sectorData: [],
                updateInterval: null
            },
            energy: {
                name: 'Energy',
                data: energyStocks,
                tableBodyId: 'tableBodyEnergy',
                statusTextId: 'statusTextEnergy',
                lastUpdateId: 'lastUpdateEnergy',
                rowPrefix: 'row-energy',
                statsIds: {
                    total: 'energyTotal',
                    positive: 'energyPositive',
                    negative: 'energyNegative'
                },
                sectorData: [],
                updateInterval: null
            },
            auto: {
                name: 'Auto',
                data: autoStocks,
                tableBodyId: 'tableBodyAuto',
                statusTextId: 'statusTextAuto',
                lastUpdateId: 'lastUpdateAuto',
                rowPrefix: 'row-auto',
                statsIds: {
                    total: 'autoTotal',
                    positive: 'autoPositive',
                    negative: 'autoNegative'
                },
                sectorData: [],
                updateInterval: null
            },
            finservice: {
                name: 'Financial Services',
                data: finServiceStocks,
                tableBodyId: 'tableBodyFinService',
                statusTextId: 'statusTextFinService',
                lastUpdateId: 'lastUpdateFinService',
                rowPrefix: 'row-finservice',
                statsIds: {
                    total: 'finServiceTotal',
                    positive: 'finServicePositive',
                    negative: 'finServiceNegative'
                },
                sectorData: [],
                updateInterval: null
            },
            pvtbank: {
                name: 'Private Bank',
                data: pvtBanks,
                tableBodyId: 'tableBodyPvtBank',
                statusTextId: 'statusTextPvtBank',
                lastUpdateId: 'lastUpdatePvtBank',
                rowPrefix: 'row-pvtbank',
                statsIds: {
                    total: 'pvtBankTotal',
                    positive: 'pvtBankPositive',
                    negative: 'pvtBankNegative'
                },
                sectorData: [],
                updateInterval: null
            },
            niftymidselect: {
                name: 'Nifty Mid Select',
                data: niftyMidSelectStocks,
                tableBodyId: 'tableBodyNiftyMidSelect',
                statusTextId: 'statusTextNiftyMidSelect',
                lastUpdateId: 'lastUpdateNiftyMidSelect',
                rowPrefix: 'row-niftymidselect',
                statsIds: {
                    total: 'niftyMidSelectTotal',
                    positive: 'niftyMidSelectPositive',
                    negative: 'niftyMidSelectNegative'
                },
                sectorData: [],
                updateInterval: null
            },
            realty: {
                name: 'Realty',
                data: realtyStocks,
                tableBodyId: 'tableBodyRealty',
                statusTextId: 'statusTextRealty',
                lastUpdateId: 'lastUpdateRealty',
                rowPrefix: 'row-realty',
                statsIds: {
                    total: 'realtyTotal',
                    positive: 'realtyPositive',
                    negative: 'realtyNegative'
                },
                sectorData: [],
                updateInterval: null
            },
            metal: {
                name: 'Metal',
                data: metalStocks,
                tableBodyId: 'tableBodyMetal',
                statusTextId: 'statusTextMetal',
                lastUpdateId: 'lastUpdateMetal',
                rowPrefix: 'row-metal',
                statsIds: {
                    total: 'metalTotal',
                    positive: 'metalPositive',
                    negative: 'metalNegative'
                },
                sectorData: [],
                updateInterval: null
            },
            fmcg: {
                name: 'FMCG',
                data: fmcgStocks,
                tableBodyId: 'tableBodyFMCG',
                statusTextId: 'statusTextFMCG',
                lastUpdateId: 'lastUpdateFMCG',
                rowPrefix: 'row-fmcg',
                statsIds: {
                    total: 'fmcgTotal',
                    positive: 'fmcgPositive',
                    negative: 'fmcgNegative'
                },
                sectorData: [],
                updateInterval: null
            },
            pharma: {
                name: 'Pharma',
                data: pharmaStocks,
                tableBodyId: 'tableBodyPharma',
                statusTextId: 'statusTextPharma',
                lastUpdateId: 'lastUpdatePharma',
                rowPrefix: 'row-pharma',
                statsIds: {
                    total: 'pharmaTotal',
                    positive: 'pharmaPositive',
                    negative: 'pharmaNegative'
                },
                sectorData: [],
                updateInterval: null
            },
            cement: {
                name: 'Cement',
                data: cementStocks,
                tableBodyId: 'tableBodyCement',
                statusTextId: 'statusTextCement',
                lastUpdateId: 'lastUpdateCement',
                rowPrefix: 'row-cement',
                statsIds: {
                    total: 'cementTotal',
                    positive: 'cementPositive',
                    negative: 'cementNegative'
                },
                sectorData: [],
                updateInterval: null
            },
            nifty50: {
                name: 'Nifty 50',
                data: nifty50Stocks,
                tableBodyId: 'tableBodyNifty50',
                statusTextId: 'statusTextNifty50',
                lastUpdateId: 'lastUpdateNifty50',
                rowPrefix: 'row-nifty50',
                statsIds: {
                    total: 'nifty50Total',
                    positive: 'nifty50Positive',
                    negative: 'nifty50Negative'
                },
                sectorData: [],
                updateInterval: null
            },
            sensex: {
                name: 'Sensex 30',
                data: sensexStocks,
                tableBodyId: 'tableBodySensex',
                statusTextId: 'statusTextSensex',
                lastUpdateId: 'lastUpdateSensex',
                rowPrefix: 'row-sensex',
                statsIds: {
                    total: 'sensexTotal',
                    positive: 'sensexPositive',
                    negative: 'sensexNegative'
                },
                sectorData: [],
                updateInterval: null
            }
        };

        // ============================================
        // REUSABLE SECTOR FUNCTIONS - Use for any sector
        // ============================================

        /**
         * Initialize table for a sector
         * @param {string} sectorKey - Key from sectors object (e.g., 'bank', 'psu')
         */
        function initSectorTable(sectorKey) {
            const sector = sectors[sectorKey];
            const tbody = document.getElementById(sector.tableBodyId);
            tbody.innerHTML = '';
            
            sector.data.forEach((stock, index) => {
                const row = document.createElement('tr');
                row.id = `${sector.rowPrefix}-${index}`;
                
                const tvSymbol = stock.symbol.replace('.NS', '');
                const tradingViewUrl = `https://www.tradingview.com/chart/?symbol=NSE%3A${tvSymbol}`;
                
                row.innerHTML = `
                    <td class="text-lg-start text-center" data-label="Stock Name">
                        <a href="${tradingViewUrl}" target="_blank" title="${stock.name}" class="text-uppercase" style="font-size: 14px;">
                            ${stock.name}
                            <div class="text-muted" style="font-size: 10px;">${stock.symbol}</div>
                        </a>
                    </td>
                    <td class="d-none"><code class="text-muted">${stock.symbol}</code></td>
                    <td class="prev-close text-center" data-label="Prev Close">-</td>
                    <td class="ltp fw-bold text-center" data-label="LTP">-</td>
                    <td class="percent-change text-center" data-label="% Change">-</td>
                    <td class="current-volume text-center" data-label="Volume">-</td>
                    <td class="volume-ratio text-center" data-label="Vol Ratio">-</td>
                    <td class="r-fact text-center" data-label="R Fact">-</td>
                `;
                tbody.appendChild(row);
                priceMemory[stock.symbol] = stock.basePrice;
            });
        }

        // Fetch live data
        async function getLiveData(bank) {
            try {
                const response = await fetch(`fetch_stock.php?symbol=${bank.symbol}&_=${Date.now()}`);
                
                if (response.ok) {
                    const data = await response.json();
                    
                    if (data.chart && data.chart.result && data.chart.result.length > 0) {
                        const result = data.chart.result[0];
                        const meta = result.meta;
                        
                        const ltp = meta.regularMarketPrice || meta.chartPreviousClose;
                        const prevClose = meta.chartPreviousClose || meta.previousClose;
                        const volume = meta.regularMarketVolume || 0;
                        const avgVolume20d = meta.avgVolume20d || 0;
                        const dayLow = meta.regularMarketDayLow || 0;
                        const dayHigh = meta.regularMarketDayHigh || 0;
                        
                        if (ltp && prevClose) {
                            const change = ((ltp - prevClose) / prevClose * 100);
                            // console.log(`✓ ${bank.symbol}: LTP=₹${ltp.toFixed(2)} (${change >= 0 ? '+' : ''}${change.toFixed(2)}%), Vol=${volume.toLocaleString()}, AvgVol=${avgVolume20d.toLocaleString()}`);
                            
                            priceMemory[bank.symbol] = ltp;
                            
                            return { ltp, prevClose, volume, avgVolume20d, dayLow, dayHigh };
                        }
                    }
                }
            } catch (error) {
                console.log(`⚠ API failed for ${bank.symbol}`);
            }
            
            return getSimulatedData(bank);
        }

        // Simulated data fallback
        function getSimulatedData(bank) {
            const lastPrice = priceMemory[bank.symbol] || bank.basePrice;
            const priceChange = (Math.random() - 0.5) * 0.006;
            const ltp = Math.max(bank.basePrice * 0.98, Math.min(bank.basePrice * 1.02, lastPrice * (1 + priceChange)));
            priceMemory[bank.symbol] = ltp;
            const volume = Math.round(bank.baseVolume * (0.85 + Math.random() * 0.3));
            
            // Simulate day low and high (realistic range)
            const dayLow = ltp * (0.98 + Math.random() * 0.01);  // 1-2% below LTP
            const dayHigh = ltp * (1.01 + Math.random() * 0.01); // 1-2% above LTP

            return { ltp, prevClose: bank.prevClose, volume, avgVolume20d: 0, dayLow, dayHigh };
        }

        // Format number
        function formatNumber(num, decimals = 2) {
            return num.toLocaleString('en-IN', { minimumFractionDigits: decimals, maximumFractionDigits: decimals });
        }

        /**
         * Update all stocks in a sector (OPTIMIZED - Parallel API calls)
         * @param {string} sectorKey - Key from sectors object
         */
        async function updateSector(sectorKey) {
            const sector = sectors[sectorKey];
            let manualAvgVolume = parseFloat(document.getElementById('avgVolume').value) || 20000000;
            const statusText = document.getElementById(sector.statusTextId);
            let positiveCount = 0;
            let negativeCount = 0;
            sector.sectorData = [];

            // Mark all rows as updating
            sector.data.forEach((stock, i) => {
                const row = document.getElementById(`${sector.rowPrefix}-${i}`);
                row.classList.add('updating-row');
            });

            if (sector.updateInterval) {
                statusText.textContent = `Updating all ${sector.data.length} stocks...`;
            }

            // Fetch all stock data in parallel (MUCH FASTER!)
            const dataPromises = sector.data.map(stock => getLiveData(stock));
            const allData = await Promise.all(dataPromises);

            // Process all results
            allData.forEach((data, i) => {
                const stock = sector.data[i];
                const row = document.getElementById(`${sector.rowPrefix}-${i}`);
                
                if (data) {
                    const avgVolumeToUse = data.avgVolume20d > 0 ? data.avgVolume20d : (stock.baseVolume || manualAvgVolume);
                    const percentChange = ((data.ltp - data.prevClose) / data.prevClose) * 100;
                    const volumeRatio = data.volume / avgVolumeToUse;
                    const rFact = Math.abs(percentChange) * volumeRatio;

                    // Count positive and negative
                    if (percentChange >= 0) {
                        positiveCount++;
                    } else {
                        negativeCount++;
                    }

                    sector.sectorData.push({ index: i, stock, data, percentChange, volumeRatio, rFact, dayLow: data.dayLow, dayHigh: data.dayHigh });

                    row.querySelector('.prev-close').textContent = '₹' + formatNumber(data.prevClose);
                    row.querySelector('.ltp').innerHTML = '₹' + formatNumber(data.ltp);
                    
                    const changeCell = row.querySelector('.percent-change');
                    const changeBadge = percentChange >= 0 ? 'text-bg-success' : 'text-bg-danger';
                    
                    // Calculate price difference (Prev Close - LTP)
                    const priceDiff = data.prevClose - data.ltp;
                    const diffColor = priceDiff > 0 ? 'text-danger' : 'text-success';
                    const diffSign = priceDiff > 0 ? '-' : '+';
                    
                    changeCell.innerHTML = `
                        <span class="badge ${changeBadge}">${percentChange >= 0 ? '+' : ''}${formatNumber(percentChange)}%</span>
                        <br>
                        <small class="${diffColor} fw-bold">${diffSign}₹${formatNumber(Math.abs(priceDiff))}</small>
                    `;
                    
                    row.querySelector('.current-volume').textContent = formatNumber(data.volume, 0);
                    row.querySelector('.volume-ratio').textContent = formatNumber(volumeRatio);
                    row.querySelector('.r-fact').innerHTML = `<span class="r-fact-value">${formatNumber(rFact)}</span>`;
                }

                // Remove updating class
                setTimeout(() => row.classList.remove('updating-row'), 300);
            });

            // Update stats
            updateSectorStats(sectorKey, sector.data.length, positiveCount, negativeCount);

            // Sort after a small delay to ensure DOM has updated
            setTimeout(() => {
                sortSectorTable(sectorKey);
            }, 100);

            const now = new Date().toLocaleTimeString();
            const lastUpdateEl = document.getElementById(sector.lastUpdateId);
            
            if (sector.updateInterval) {
                statusText.textContent = `✅ Updated ${allData.length}/${sector.data.length} stocks - Next in 1 min`;
                lastUpdateEl.textContent = `Last: ${now}`;
            } else {
                lastUpdateEl.textContent = `Updated: ${now}`;
            }

            // Update Sector Scope Chart
            updateSectorChart();
        }

        /**
         * Update sector statistics
         * @param {string} sectorKey - Key from sectors object
         * @param {number} total - Total stocks
         * @param {number} positive - Positive stocks count
         * @param {number} negative - Negative stocks count
         */
        function updateSectorStats(sectorKey, total, positive, negative) {
            const sector = sectors[sectorKey];
            if (sector.statsIds) {
                document.getElementById(sector.statsIds.total).textContent = total;
                document.getElementById(sector.statsIds.positive).textContent = positive;
                document.getElementById(sector.statsIds.negative).textContent = negative;
            }
        }

        /**
         * Sort a sector table
         * @param {string} sectorKey - Key from sectors object
         */
        function sortSectorTable(sectorKey) {
            const sector = sectors[sectorKey];
            const sortBy = document.getElementById('sortBy').value;
            
            if (sector.sectorData.length === 0) {
                return;
            }

            let sortedData = [...sector.sectorData];
            
            switch(sortBy) {
                case 'percent-high':
                    sortedData.sort((a, b) => b.percentChange - a.percentChange);
                    break;
                case 'percent-low':
                    sortedData.sort((a, b) => a.percentChange - b.percentChange);
                    break;
                case 'rfact-high':
                    sortedData.sort((a, b) => b.rFact - a.rFact);
                    break;
                case 'rfact-low':
                    sortedData.sort((a, b) => a.rFact - b.rFact);
                    break;
                case 'volume-high':
                    sortedData.sort((a, b) => b.volumeRatio - a.volumeRatio);
                    break;
                case 'volume-low':
                    sortedData.sort((a, b) => a.volumeRatio - b.volumeRatio);
                    break;
                default:
                    return;
            }
            
            const tbody = document.getElementById(sector.tableBodyId);
            // Use DocumentFragment for better performance and avoid flickering
            const fragment = document.createDocumentFragment();
            sortedData.forEach(item => {
                const row = document.getElementById(`${sector.rowPrefix}-${item.index}`);
                if (row) {
                    fragment.appendChild(row);
                }
            });
            tbody.appendChild(fragment);
        }

        /**
         * Sort all sectors
         */
        function sortAllSectors() {
            Object.keys(sectors).forEach(sectorKey => {
                sortSectorTable(sectorKey);
            });
        }

        // ============================================
        // CONTROL FUNCTIONS - Start/Stop/Refresh
        // ============================================

        /**
         * Update all sectors with progress indicator
         */
        async function updateAllSectorsWithProgress() {
            const sectorKeys = Object.keys(sectors);
            const totalSectors = sectorKeys.length;
            let completedSectors = 0;
            
            // Show progress indicator
            showProgressIndicator(totalSectors);
            
            // Update all sectors with progress tracking
            const updatePromises = sectorKeys.map(sectorKey => {
                const sector = sectors[sectorKey];
                return updateSector(sectorKey).then(() => {
                    completedSectors++;
                    updateProgressIndicator(completedSectors, totalSectors, sector.name);
                    
                    // Hide when all complete
                    if (completedSectors === totalSectors) {
                        hideProgressIndicator();
                    }
                });
            });
            
            // Wait for all updates to complete
            await Promise.all(updatePromises);
        }

        /**
         * Start auto-update for all sectors (with progress indicator and countdown)
         */
        async function startBothSectors() {
            document.getElementById('startBtn').classList.add('d-none');
            document.getElementById('stopBtn').classList.remove('d-none');
            
            // Initial update with progress
            await updateAllSectorsWithProgress();
            
            // Start countdown timer
            startCountdown();
            
            // Set up single auto-refresh interval every 1 minute (60000ms)
            autoRefreshInterval = setInterval(async () => {
                // Update all sectors with progress indicator
                await updateAllSectorsWithProgress();
                
                // Reset countdown for next cycle
                startCountdown();
            }, 60000);
        }

        /**
         * Stop auto-update for all sectors
         */
        function stopBothSectors() {
            document.getElementById('startBtn').classList.remove('d-none');
            document.getElementById('stopBtn').classList.add('d-none');

            // Stop countdown timer
            stopCountdown();

            // Stop auto-refresh interval
            if (autoRefreshInterval) {
                clearInterval(autoRefreshInterval);
                autoRefreshInterval = null;
            }

            // Stop each sector (cleanup old intervals if any)
            Object.keys(sectors).forEach(sectorKey => {
                const sector = sectors[sectorKey];
                if (sector.updateInterval) {
                    clearInterval(sector.updateInterval);
                    sector.updateInterval = null;
                }
            });
        }

        /**
         * Refresh all sectors immediately with progress indicator (parallel updates)
         */
        function refreshBothSectors() {
            const sectorKeys = Object.keys(sectors);
            const totalSectors = sectorKeys.length;
            let completedSectors = 0;
            
            // Show progress indicator
            showProgressIndicator(totalSectors);
            
            // Update all sectors in parallel
            sectorKeys.forEach(sectorKey => {
                const sector = sectors[sectorKey];
                
                // Update sector and track completion
                updateSector(sectorKey).then(() => {
                    completedSectors++;
                    
                    // Update progress
                    updateProgressIndicator(completedSectors, totalSectors, sector.name);
                    
                    // Hide when all complete
                    if (completedSectors === totalSectors) {
                        hideProgressIndicator();
                    }
                });
            });
        }

        // ============================================
        // COUNTDOWN TIMER FUNCTIONS
        // ============================================
        
        function startCountdown() {
            // Clear any existing countdown
            if (countdownInterval) {
                clearInterval(countdownInterval);
            }
            
            // Set next update time (60 seconds from now)
            nextUpdateTime = Date.now() + 60000;
            
            // Update countdown immediately - directly update button
            updateCountdownDisplay(60);
            
            // Update countdown every second
            countdownInterval = setInterval(() => {
                const timeLeft = Math.max(0, Math.floor((nextUpdateTime - Date.now()) / 1000));
                updateCountdownDisplay(timeLeft);
                
                if (timeLeft <= 0) {
                    // Reset for next cycle
                    nextUpdateTime = Date.now() + 60000;
                }
            }, 1000);
        }
        
        function updateCountdownDisplay(seconds) {
            const stopBtn = document.getElementById('stopBtn');
            if (stopBtn) {
                stopBtn.innerHTML = `Stop (${seconds}s)`;
            }
        }
        
        function stopCountdown() {
            if (countdownInterval) {
                clearInterval(countdownInterval);
                countdownInterval = null;
            }
            const stopBtn = document.getElementById('stopBtn');
            if (stopBtn) {
                stopBtn.innerHTML = 'Stop';
            }
        }

        // ============================================
        // INITIALIZATION
        // ============================================

        /**
         * Initialize all sectors on page load
         */
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize all sectors
            Object.keys(sectors).forEach(sectorKey => {
                initSectorTable(sectorKey);
                console.log(`✅ Initialized ${sectors[sectorKey].name} Sector: ${sectors[sectorKey].data.length} stocks`);
            });

            // Add sort event listener
            document.getElementById('sortBy').addEventListener('change', sortAllSectors);
            
            console.log('🏦 Stock R Fact Calculator - Modular Architecture');
            console.log('🎛️ Unified Controls: All sectors controlled together');
            console.log('💻 Server: PHP <?php echo phpversion(); ?>');
            console.log('🚀 Ready! Add new sectors easily by extending the sectors object.');
        });

        // ============================================
        // PROGRESS INDICATOR FUNCTIONS
        // ============================================
        
        function showProgressIndicator(totalSectors) {
            const progressIndicator = document.getElementById('progressIndicator');
            const progressTotal = document.getElementById('progressTotal');
            const progressCurrent = document.getElementById('progressCurrent');
            const progressBarFill = document.getElementById('progressBarFill');
            
            progressTotal.textContent = totalSectors;
            progressCurrent.textContent = '0';
            progressBarFill.style.width = '0%';
            
            progressIndicator.classList.add('show');
        }
        
        function updateProgressIndicator(current, total, sectorName) {
            const progressCurrent = document.getElementById('progressCurrent');
            const progressSectorName = document.getElementById('progressSectorName');
            const progressBarFill = document.getElementById('progressBarFill');
            
            progressCurrent.textContent = current;
            progressSectorName.textContent = sectorName;
            
            const percentage = (current / total) * 100;
            progressBarFill.style.width = percentage + '%';
        }
        
        function hideProgressIndicator() {
            const progressIndicator = document.getElementById('progressIndicator');
            setTimeout(() => {
                progressIndicator.classList.remove('show');
            }, 1000);
        }

        // ============================================
        // BACK TO TOP BUTTON
        // ============================================
        
        const backToTopButton = document.getElementById('backToTop');
        
        // Show/hide button based on scroll position
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTopButton.classList.add('show');
            } else {
                backToTopButton.classList.remove('show');
            }
        });
        
        // Smooth scroll to top when clicked
        backToTopButton.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // ============================================
        // KEYBOARD SHORTCUTS
        // ============================================
        
        // Helper function to scroll to sector with highlight effect
        function scrollToSector(sectorId) {
            if (!sectorId) return;
            
            const element = document.getElementById(sectorId);
            if (element) {
                element.scrollIntoView({ behavior: 'smooth', block: 'start' });
                
                // Add a brief highlight effect
                element.style.transition = 'background-color 0.5s';
                const originalBg = element.style.backgroundColor;
                element.style.backgroundColor = '#fff3cd';
                setTimeout(() => {
                    element.style.backgroundColor = originalBg;
                }, 1000);
            }
        }
        
        document.addEventListener('keydown', function(event) {
            // Alt + R for Refresh
            if (event.altKey && event.key.toLowerCase() === 'r') {
                event.preventDefault(); // Prevent browser default behavior
                refreshBothSectors();
            }
            
            // Alt + Number for Sector Navigation (1-9, then use letters for 10+)
            if (event.altKey && event.key >= '1' && event.key <= '9') {
                event.preventDefault();
                const sectorNumber = parseInt(event.key);
                
                // Map numbers to sector status bar IDs
                const sectorMap = {
                    1: 'statusBar',              // Bank
                    2: 'statusBarPSU',            // PSU Bank
                    3: 'statusBarIT',             // IT
                    4: 'statusBarEnergy',         // Energy
                    5: 'statusBarAuto',           // Auto
                    6: 'statusBarFinService',     // Financial Services
                    7: 'statusBarPvtBank',        // Private Bank
                    8: 'statusBarNiftyMidSelect', // Nifty Mid Select
                    9: 'statusBarRealty'          // Realty
                };
                
                scrollToSector(sectorMap[sectorNumber]);
            }
            
            // Alt + M for Metal
            if (event.altKey && event.key.toLowerCase() === 'm') {
                event.preventDefault();
                scrollToSector('statusBarMetal');
            }
            
            // Alt + F for FMCG
            if (event.altKey && event.key.toLowerCase() === 'f') {
                event.preventDefault();
                scrollToSector('statusBarFMCG');
            }
            
            // Alt + P for Pharma
            if (event.altKey && event.key.toLowerCase() === 'p') {
                event.preventDefault();
                scrollToSector('statusBarPharma');
            }
            
            // Alt + C for Cement
            if (event.altKey && event.key.toLowerCase() === 'c') {
                event.preventDefault();
                scrollToSector('statusBarCement');
            }
            
            // Alt + N for Nifty 50
            if (event.altKey && event.key.toLowerCase() === 'n') {
                event.preventDefault();
                scrollToSector('statusBarNifty50');
            }
            
            // Alt + S for Sensex 30
            if (event.altKey && event.key.toLowerCase() === 's') {
                event.preventDefault();
                scrollToSector('statusBarSensex');
            }
            
            // Alt + X for Top (Scroll to Top / Sector Scope)
            if (event.altKey && event.key.toLowerCase() === 'x') {
                event.preventDefault();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        });

        // ============================================
        // TOP STOCKS TABLE
        // ============================================
        
        function updateTopStocksTable() {
            const tbody = document.getElementById('topStocksTableBody');
            tbody.innerHTML = '';
            
            // Calculate sector average R Facts to determine "Strong" status
            const sectorAvgRFacts = {};
            Object.keys(sectors).forEach(sectorKey => {
                const sector = sectors[sectorKey];
                if (sector.sectorData && sector.sectorData.length > 0) {
                    const avgRFact = sector.sectorData.reduce((sum, item) => sum + item.rFact, 0) / sector.sectorData.length;
                    sectorAvgRFacts[sector.name] = avgRFact;
                }
            });
            
            // Collect ALL stocks from ALL sectors
            const allStocks = [];
            const seenSymbols = new Set(); // Track unique stock symbols
            
            Object.keys(sectors).forEach(sectorKey => {
                const sector = sectors[sectorKey];
                if (sector.sectorData && sector.sectorData.length > 0) {
                    sector.sectorData.forEach(stockData => {
                        const symbol = stockData.stock.symbol;
                        
                        // Only add if we haven't seen this stock symbol before
                        if (!seenSymbols.has(symbol)) {
                            seenSymbols.add(symbol);
                            allStocks.push({
                                sectorName: sector.name,
                                stockData: stockData
                            });
                        } else {
                            // If duplicate, keep the one with higher R Fact
                            const existingIndex = allStocks.findIndex(item => 
                                item.stockData.stock.symbol === symbol
                            );
                            if (existingIndex !== -1 && 
                                stockData.rFact > allStocks[existingIndex].stockData.rFact) {
                                allStocks[existingIndex] = {
                                    sectorName: sector.name,
                                    stockData: stockData
                                };
                            }
                        }
                    });
                }
            });
            
            // Sort all stocks by R Fact (descending) and get top 10
            const topStocks = allStocks
                .sort((a, b) => b.stockData.rFact - a.stockData.rFact)
                .slice(0, 10); // Get top 10 unique stocks
            
            // Now sort these top 10 stocks by their sector's average R Fact (descending)
            topStocks.sort((a, b) => {
                const sectorAvgA = sectorAvgRFacts[a.sectorName] || 0;
                const sectorAvgB = sectorAvgRFacts[b.sectorName] || 0;
                // First sort by sector average R Fact
                if (sectorAvgB !== sectorAvgA) {
                    return sectorAvgB - sectorAvgA;
                }
                // If sector avg R Fact is same, then sort by individual stock R Fact
                return b.stockData.rFact - a.stockData.rFact;
            });
            
            // Display top 10 stocks
            topStocks.forEach((item, index) => {
                const stockData = item.stockData;
                const row = document.createElement('tr');
                
                // Check if sector is "Strong" (avg R Fact > 2)
                const sectorAvgRFact = sectorAvgRFacts[item.sectorName] || 0;
                const isStrongSector = sectorAvgRFact > 2;
                
                // Set row class based on sector strength (overrides % change color)
                if (isStrongSector) {
                    row.className = 'table-success';
                } else if (stockData.percentChange > 5) {
                    row.className = 'table-success';
                } else if (stockData.percentChange < -5) {
                    row.className = 'table-danger';
                }
                
                // Create TradingView link
                const tvSymbol = stockData.stock.symbol.replace('.NS', '');
                const tradingViewUrl = `https://www.tradingview.com/chart/?symbol=NSE%3A${tvSymbol}`;
                
                // Calculate Current Diff (Prev Close - LTP)
                const currentDiff = stockData.data.prevClose - stockData.data.ltp;
                const diffColor = currentDiff > 0 ? 'text-danger' : 'text-success';
                const diffSign = currentDiff > 0 ? '-' : '+';
                
                row.innerHTML = `
                    <td class="text-center fw-bold">${index + 1}</td>
                    <td class="text-start fw-bold">${item.sectorName}</td>
                    <td class="text-start">
                        <a href="${tradingViewUrl}" target="_blank" title="${stockData.stock.name}" class="text-decoration-none text-dark" style="display: block; padding: 5px;">
                            <strong>${stockData.stock.name}</strong>
                            <br>
                            <small class="text-muted">${stockData.stock.symbol}</small>
                        </a>
                    </td>
                    <td class="text-center">₹${stockData.data.prevClose.toFixed(2)}</td>
                    <td class="text-center fw-bold">₹${stockData.data.ltp.toFixed(2)}</td>
                    <td class="text-center text-danger">₹${stockData.dayLow ? stockData.dayLow.toFixed(2) : '-'}</td>
                    <td class="text-center text-success">₹${stockData.dayHigh ? stockData.dayHigh.toFixed(2) : '-'}</td>
                    <td class="text-center fw-bold ${diffColor}">
                        ${diffSign}₹${Math.abs(currentDiff).toFixed(2)}
                    </td>
                    <td class="text-center">
                        <span class="badge ${stockData.percentChange >= 0 ? 'text-bg-success' : 'text-bg-danger'}">
                            ${stockData.percentChange >= 0 ? '+' : ''}${stockData.percentChange.toFixed(2)}%
                        </span>
                    </td>
                    <td class="text-center">${stockData.data.volume.toLocaleString('en-IN')}</td>
                    <td class="text-center">
                        <span class="badge bg-primary">
                            ${stockData.volumeRatio.toFixed(2)}x
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-warning text-dark" style="font-size: 14px;">
                            ${stockData.rFact.toFixed(2)}x
                        </span>
                    </td>
                `;
                
                tbody.appendChild(row);
            });
        }

        // ============================================
        // SECTOR SCOPE TABLE
        // ============================================
        
        function updateSectorScopeTable() {
            const tbody = document.getElementById('sectorScopeTableBody');
            const sectorData = [];
            
            // Variables for grand totals
            let grandTotalStocks = 0;
            let grandTotalPositive = 0;
            let grandTotalNegative = 0;
            
            // Calculate average R Fact and get stats for each sector
            Object.keys(sectors).forEach(sectorKey => {
                const sector = sectors[sectorKey];
                if (sector.sectorData && sector.sectorData.length > 0) {
                    const avgRFact = sector.sectorData.reduce((sum, item) => sum + item.rFact, 0) / sector.sectorData.length;
                    
                    // Get positive and negative counts from statsIds
                    const positiveCount = parseInt(document.getElementById(sector.statsIds.positive)?.textContent || 0);
                    const negativeCount = parseInt(document.getElementById(sector.statsIds.negative)?.textContent || 0);
                    const totalCount = sector.sectorData.length;
                    
                    // Add to grand totals
                    grandTotalStocks += totalCount;
                    grandTotalPositive += positiveCount;
                    grandTotalNegative += negativeCount;
                    
                    sectorData.push({
                        name: sector.name,
                        rFact: avgRFact,
                        total: totalCount,
                        positive: positiveCount,
                        negative: negativeCount
                    });
                }
            });
            
            // Sort by R Fact descending (highest first)
            sectorData.sort((a, b) => b.rFact - a.rFact);
            
            // Clear and update table
            tbody.innerHTML = '';
            
            sectorData.forEach((sector, index) => {
                const row = document.createElement('tr');
                
                // Determine status badge and row class
                let statusBadge, rowClass;
                if (sector.rFact > 2) {
                    statusBadge = '<span class="badge text-bg-success">Strong</span>';
                    rowClass = 'table-success';
                } else if (sector.rFact > 0) {
                    statusBadge = '<span class="badge text-bg-primary">Positive</span>';
                    rowClass = '';
                } else if (sector.rFact > -2) {
                    statusBadge = '<span class="badge text-bg-warning text-dark">Weak</span>';
                    rowClass = '';
                } else {
                    statusBadge = '<span class="badge text-bg-danger">Negative</span>';
                    rowClass = 'table-danger';
                }
                
                row.className = rowClass;
                
                // Map sector names to their status bar IDs
                const sectorIdMap = {
                    'Bank': 'statusBar',
                    'PSU Bank': 'statusBarPSU',
                    'IT': 'statusBarIT',
                    'Energy': 'statusBarEnergy',
                    'Auto': 'statusBarAuto',
                    'Financial Services': 'statusBarFinService',
                    'Private Bank': 'statusBarPvtBank',
                    'Nifty Mid Select': 'statusBarNiftyMidSelect',
                    'Realty': 'statusBarRealty',
                    'Metal': 'statusBarMetal',
                    'FMCG': 'statusBarFMCG',
                    'Pharma': 'statusBarPharma',
                    'Cement': 'statusBarCement',
                    'Nifty 50': 'statusBarNifty50',
                    'Sensex 30': 'statusBarSensex'
                };
                
                const sectorStatusId = sectorIdMap[sector.name] || 'statusBar';
                
                // Map sector names to keyboard shortcuts
                const shortcutMap = {
                    'Bank': 'Alt+1',
                    'PSU Bank': 'Alt+2',
                    'IT': 'Alt+3',
                    'Energy': 'Alt+4',
                    'Auto': 'Alt+5',
                    'Financial Services': 'Alt+6',
                    'Private Bank': 'Alt+7',
                    'Nifty Mid Select': 'Alt+8',
                    'Realty': 'Alt+9',
                    'Metal': 'Alt+M',
                    'FMCG': 'Alt+F',
                    'Pharma': 'Alt+P',
                    'Cement': 'Alt+C',
                    'Nifty 50': 'Alt+N',
                    'Sensex 30': 'Alt+S',
                    'Top': 'Alt+X'
                };
                
                const shortcut = shortcutMap[sector.name] || '';
                
                row.innerHTML = `
                    <td class="text-center fw-bold">${index + 1}</td>
                    <td class="text-start fw-bold">
                        <a href="#${sectorStatusId}" class="text-decoration-none text-dark sector-link" style="cursor: pointer; display: block; padding: 5px;">
                            ${sector.name}
                            <i class="bi bi-arrow-down-circle ms-2" style="font-size: 14px;"></i>
                            ${shortcut ? `<span class="badge bg-secondary ms-2" style="font-size: 10px;">${shortcut}</span>` : ''}
                        </a>
                    </td>
                    <td class="text-center">
                        <span class="badge ${sector.rFact > 0 ? 'bg-success' : 'bg-danger'}" style="font-size: 14px;">
                            ${sector.rFact.toFixed(2)}x
                        </span>
                    </td>
                    <td class="text-center">${sector.total}</td>
                    <td class="text-center text-success fw-bold">${sector.positive}</td>
                    <td class="text-center text-danger fw-bold">${sector.negative}</td>
                    <td class="text-center">${statusBadge}</td>
                `;
                
                tbody.appendChild(row);
            });
            
            // Update grand totals in footer
            document.getElementById('grandTotalStocks').textContent = grandTotalStocks;
            document.getElementById('grandTotalPositive').textContent = grandTotalPositive;
            document.getElementById('grandTotalNegative').textContent = grandTotalNegative;
        }
        
        function updateSectorChart() {
            // Update both tables
            updateSectorScopeTable();
            updateTopStocksTable();
        }
    </script>
</body>
</html>

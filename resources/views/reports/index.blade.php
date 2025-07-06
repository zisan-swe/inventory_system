@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h5>Reports Dashboard</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title">Sales Report</h5>
                                <p class="card-text">View detailed sales information</p>
                                <a href="{{ route('reports.sales') }}" class="btn btn-primary">
                                    <i class="bi bi-graph-up"></i> View Sales Report
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title">Inventory Report</h5>
                                <p class="card-text">View product inventory status</p>
                                <a href="{{ route('reports.inventory') }}" class="btn btn-primary">
                                    <i class="bi bi-box-seam"></i> View Inventory Report
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <h5 class="card-title">Payment Report</h5>
                                <p class="card-text">View payment collections</p>
                                <a href="#" class="btn btn-secondary" disabled>
                                    <i class="bi bi-cash"></i> Coming Soon
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@extends('layouts.app')

@section('title', 'Daraja API Test')

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-primary text-white">
        <h3 class="mb-0">
            <i class="bi bi-gear me-2"></i>Daraja API Connection Test
        </h3>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            <strong>Note:</strong> This page helps diagnose Daraja API connection issues. Check the results below and follow the recommendations.
        </div>

        <h5 class="mt-4 mb-3">Configuration</h5>
        <table class="table table-bordered">
            <tr>
                <th style="width: 200px;">Environment</th>
                <td>
                    <span class="badge bg-{{ $results['environment'] === 'production' ? 'success' : 'warning' }}">
                        {{ strtoupper($results['environment']) }}
                    </span>
                </td>
            </tr>
            <tr>
                <th>Base URL</th>
                <td><code>{{ $results['base_url'] }}</code></td>
            </tr>
        </table>

        <h5 class="mt-4 mb-3">Credentials Status</h5>
        <table class="table table-bordered">
            <tr>
                <th style="width: 200px;">Consumer Key</th>
                <td>
                    @if(strpos($results['credentials']['consumer_key'], 'NOT SET') !== false)
                        <span class="badge bg-danger">NOT SET</span>
                    @else
                        <span class="badge bg-success">{{ $results['credentials']['consumer_key'] }}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Consumer Secret</th>
                <td>
                    @if(strpos($results['credentials']['consumer_secret'], 'NOT SET') !== false)
                        <span class="badge bg-danger">NOT SET</span>
                    @else
                        <span class="badge bg-success">{{ $results['credentials']['consumer_secret'] }}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Short Code</th>
                <td>
                    @if(strpos($results['credentials']['short_code'], 'NOT SET') !== false)
                        <span class="badge bg-danger">NOT SET</span>
                    @else
                        <span class="badge bg-success">{{ $results['credentials']['short_code'] }}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Passkey</th>
                <td>
                    @if(strpos($results['credentials']['passkey'], 'NOT SET') !== false)
                        <span class="badge bg-danger">NOT SET</span>
                    @else
                        <span class="badge bg-success">{{ $results['credentials']['passkey'] }}</span>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Callback URL</th>
                <td><code>{{ $results['credentials']['callback_url'] }}</code></td>
            </tr>
        </table>

        <h5 class="mt-4 mb-3">Connection Test</h5>
        @if($results['token_test'])
            @if($results['token_test']['status'] === 'success')
                <div class="alert alert-success">
                    <i class="bi bi-check-circle me-2"></i>
                    <strong>Success!</strong> {{ $results['token_test']['message'] }}
                    @if(isset($results['token_test']['token_preview']))
                        <br><small>Token: <code>{{ $results['token_test']['token_preview'] }}</code></small>
                    @endif
                </div>
            @else
                <div class="alert alert-danger">
                    <i class="bi bi-x-circle me-2"></i>
                    <strong>Failed!</strong> {{ $results['token_test']['message'] }}
                </div>
            @endif
        @endif

        @if($results['error'])
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <strong>Error:</strong> {{ $results['error'] }}
            </div>
        @endif

        <h5 class="mt-4 mb-3">Troubleshooting Steps</h5>
        <div class="card bg-light">
            <div class="card-body">
                <ol>
                    <li class="mb-2">
                        <strong>Check .env file:</strong> Ensure all Daraja credentials are set:
                        <pre class="bg-dark text-light p-2 rounded mt-2"><code>DARAJJA_ENVIRONMENT=sandbox
DARAJJA_CONSUMER_KEY=your_consumer_key
DARAJJA_CONSUMER_SECRET=your_consumer_secret
DARAJJA_SHORT_CODE=your_short_code
DARAJJA_PASSKEY=your_passkey
DARAJJA_CALLBACK_URL={{ url('/api/mpesa/callback') }}</code></pre>
                    </li>
                    <li class="mb-2">
                        <strong>Clear config cache:</strong> Run <code>php artisan config:clear</code> after updating .env
                    </li>
                    <li class="mb-2">
                        <strong>Verify credentials:</strong> Double-check your credentials from the 
                        <a href="https://developer.safaricom.co.ke/" target="_blank">Safaricom Developer Portal</a>
                    </li>
                    <li class="mb-2">
                        <strong>Check logs:</strong> Review <code>storage/logs/laravel.log</code> for detailed error messages
                    </li>
                    <li class="mb-2">
                        <strong>Sandbox vs Production:</strong> Make sure you're using sandbox credentials when 
                        <code>DARAJJA_ENVIRONMENT=sandbox</code>
                    </li>
                    <li class="mb-2">
                        <strong>Network/Firewall:</strong> Ensure your server can make outbound HTTPS requests to 
                        <code>{{ $results['base_url'] }}</code>
                    </li>
                </ol>
            </div>
        </div>

        <div class="mt-4">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
            </a>
            <a href="{{ route('daraja.test') }}" class="btn btn-primary">
                <i class="bi bi-arrow-clockwise me-1"></i>Test Again
            </a>
        </div>
    </div>
</div>
@endsection


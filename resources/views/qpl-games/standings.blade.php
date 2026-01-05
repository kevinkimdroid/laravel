@extends('layouts.app')

@section('title', 'QBASH Pool League Standings')

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h3 class="mb-1 fw-bold text-primary">
                    <i class="bi bi-trophy me-2"></i>QBASH Pool League Standings
                </h3>
                <p class="text-muted mb-0">
                    Table calculated automatically from all recorded QBASH Pool League games.
                </p>
            </div>
            <a href="{{ route('qpl-games.index') }}" class="btn btn-outline-secondary mt-3 mt-md-0">
                <i class="bi bi-arrow-left me-1"></i>Back to Games
            </a>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <tr>
                        <th class="text-white">Pos</th>
                        <th class="text-white">Member</th>
                        <th class="text-white text-center">P</th>
                        <th class="text-white text-center">W</th>
                        <th class="text-white text-center">D</th>
                        <th class="text-white text-center">L</th>
                        <th class="text-white text-center">GF</th>
                        <th class="text-white text-center">GA</th>
                        <th class="text-white text-center">GD</th>
                        <th class="text-white text-center">Pts</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($standings as $index => $row)
                        <tr>
                            <td class="fw-semibold text-center">{{ $index + 1 }}</td>
                            <td class="fw-semibold">{{ $row['name'] }}</td>
                            <td class="text-center">{{ $row['played'] }}</td>
                            <td class="text-center">{{ $row['won'] }}</td>
                            <td class="text-center">{{ $row['drawn'] }}</td>
                            <td class="text-center">{{ $row['lost'] }}</td>
                            <td class="text-center">{{ $row['goals_for'] }}</td>
                            <td class="text-center">{{ $row['goals_against'] }}</td>
                            <td class="text-center">
                                @php $gd = $row['goal_diff']; @endphp
                                <span class="{{ $gd > 0 ? 'text-success' : ($gd < 0 ? 'text-danger' : '') }}">
                                    {{ $gd }}
                                </span>
                            </td>
                            <td class="text-center fw-bold">{{ $row['points'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <i class="bi bi-trophy display-4 text-muted d-block mb-3"></i>
                                <p class="text-muted">No games recorded yet. Standings will appear here once games are played.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection



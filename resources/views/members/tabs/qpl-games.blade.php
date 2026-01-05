<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-warning bg-opacity-10">
        <h5 class="mb-0 fw-bold">
            <i class="bi bi-trophy me-2 text-warning"></i>QBASH Pool League Games & Scores
        </h5>
    </div>
    <div class="card-body">
        <p class="text-muted mb-0">View QBASH Pool League game results and scores.</p>
    </div>
</div>

@if($qplGames && $qplGames->count() > 0)
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light" style="background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); color: white;">
                    <tr>
                        <th class="text-white">Date</th>
                        <th class="text-white">Member A</th>
                        <th class="text-white text-center">Score</th>
                        <th class="text-white">Member B</th>
                        <th class="text-white">Venue</th>
                        <th class="text-white">Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($qplGames as $game)
                        <tr>
                            <td>
                                <i class="bi bi-calendar3 me-1 text-muted"></i>
                                {{ $game->game_date->format('M d, Y') }}
                            </td>
                            <td class="fw-semibold">{{ $game->home_team }}</td>
                            <td class="text-center">
                                <span class="badge bg-primary fs-6 px-3 py-2">
                                    {{ $game->home_score }} - {{ $game->away_score }}
                                </span>
                            </td>
                            <td class="fw-semibold">{{ $game->away_team }}</td>
                            <td>
                                @if($game->venue)
                                    <i class="bi bi-geo-alt me-1 text-muted"></i>{{ $game->venue }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($game->notes)
                                    <small class="text-muted">{{ Str::limit($game->notes, 50) }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@else
<div class="card border-0 shadow-sm">
    <div class="card-body text-center py-5">
        <i class="bi bi-trophy display-4 text-muted d-block mb-3"></i>
        <h5 class="mb-2">No QBASH Pool League Games Yet</h5>
        <p class="text-muted">Game scores will appear here once they are recorded by the admin.</p>
    </div>
</div>
@endif


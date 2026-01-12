<div class="card border-0 shadow-sm">
    <div class="card-header bg-light">
        <h5 class="mb-0 fw-semibold">
            <i class="bi bi-controller me-2"></i>{{ $title ?? 'Games' }}
        </h5>
        @if(isset($description))
            <p class="mb-0 mt-1 text-muted small">{{ $description }}</p>
        @endif
    </div>
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
                        @auth
                            @if(auth()->user() && auth()->user()->role === 'admin')
                                <th class="text-white text-center">Actions</th>
                            @endif
                        @endauth
                    </tr>
                </thead>
                <tbody>
                    @forelse($games as $game)
                        @php
                            $scoreDiff = abs($game->home_score - $game->away_score);
                            $totalGoals = $game->home_score + $game->away_score;
                            $isClose = $scoreDiff <= 1;
                            $isHighScoring = $totalGoals >= 8;
                        @endphp
                        <tr class="{{ ($isClose || $isHighScoring) ? 'table-warning' : '' }}" style="{{ ($isClose || $isHighScoring) ? 'background-color: rgba(255, 193, 7, 0.1) !important;' : '' }}">
                            <td>
                                <i class="bi bi-calendar3 me-1 text-muted"></i>
                                {{ $game->game_date->format('M d, Y') }}
                                @if($isClose && $isHighScoring)
                                    <i class="bi bi-star-fill text-warning ms-1" title="Exciting Game!"></i>
                                @endif
                            </td>
                            <td class="fw-semibold">{{ $game->home_team }}</td>
                            <td class="text-center">
                                @php
                                    $badgeClass = 'bg-primary';
                                    if ($isHighScoring) $badgeClass = 'bg-danger';
                                    elseif ($isClose) $badgeClass = 'bg-warning text-dark';
                                @endphp
                                <span class="badge {{ $badgeClass }} fs-6 px-3 py-2 fw-bold">
                                    {{ $game->home_score }} - {{ $game->away_score }}
                                </span>
                                @if($isClose)
                                    <br><small class="text-danger fw-semibold"><i class="bi bi-lightning-charge"></i> Close!</small>
                                @endif
                                @if($isHighScoring)
                                    <br><small class="text-warning fw-semibold"><i class="bi bi-fire"></i> {{ $totalGoals }} Goals!</small>
                                @endif
                            </td>
                            <td class="fw-semibold">{{ $game->away_team }}</td>
                            <td>
                                @if($game->venue)
                                    <i class="bi bi-geo-alt me-1 text-muted"></i>{{ $game->venue }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            @auth
                                @if(auth()->user() && auth()->user()->role === 'admin')
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('qpl-games.edit', $game->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('qpl-games.destroy', $game->id) }}" method="POST" class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this game?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                @endif
                            @endauth
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user() && auth()->user()->role === 'admin' ? '6' : '5' }}" class="text-center py-5">
                                <i class="bi bi-inbox display-4 text-muted d-block mb-3"></i>
                                <p class="text-muted">No games found in this category.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>


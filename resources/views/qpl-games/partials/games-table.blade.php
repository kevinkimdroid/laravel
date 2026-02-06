<div class="card border-0 shadow-sm">
    <div class="card-header bg-light">
        <h5 class="mb-0 fw-semibold">
            <!-- Pool Table Icon -->
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="display: inline-block; vertical-align: middle; margin-right: 8px;">
                <!-- Table outline -->
                <rect x="3" y="6" width="18" height="12" rx="1" fill="none" stroke="currentColor"/>
                <!-- Pockets -->
                <circle cx="3" cy="6" r="1.5" fill="currentColor"/>
                <circle cx="21" cy="6" r="1.5" fill="currentColor"/>
                <circle cx="3" cy="18" r="1.5" fill="currentColor"/>
                <circle cx="21" cy="18" r="1.5" fill="currentColor"/>
                <!-- Center line -->
                <line x1="12" y1="6" x2="12" y2="18" stroke="currentColor" stroke-width="0.8"/>
                <!-- Pool balls -->
                <circle cx="8" cy="12" r="1.2" fill="currentColor"/>
                <circle cx="16" cy="12" r="1.2" fill="currentColor"/>
            </svg>{{ $title ?? 'Games' }}
        </h5>
        @if(isset($description))
            <p class="mb-0 mt-1 text-muted small">{{ $description }}</p>
        @endif
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light" style="background: linear-gradient(135deg, #FFD700 0%, #c5a030 100%); color: white;">
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
                                    <i class="bi bi-star-fill ms-1" style="color: #FFD700;" title="Exciting Game!"></i>
                                @endif
                            </td>
                            <td class="fw-semibold">{{ $game->home_team }}</td>
                            <td class="text-center">
                                @php
                                    $badgeClass = 'bg-primary';
                                    $badgeStyle = '';
                                    if ($isHighScoring) $badgeClass = 'bg-danger';
                                    elseif ($isClose) {
                                        $badgeClass = '';
                                        $badgeStyle = 'background: #FFD700; color: #000000;';
                                    }
                                @endphp
                                <span class="badge {{ $badgeClass }} fs-6 px-3 py-2 fw-bold" style="{{ $badgeStyle }}">
                                    {{ $game->home_score }} - {{ $game->away_score }}
                                </span>
                                @if($isClose)
                                    <br><small class="fw-semibold" style="color: #FFD700;"><i class="bi bi-lightning-charge"></i> Close!</small>
                                @endif
                                @if($isHighScoring)
                                    <br><small class="fw-semibold" style="color: #FFD700;"><i class="bi bi-fire"></i> {{ $totalGoals }} Goals!</small>
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


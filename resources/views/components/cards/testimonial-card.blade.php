{{-- resources/views/components/cards/testimonial-card.blade.php --}}

@props([
    'testimonial' => null,
    'name' => '',
    'position' => '',
    'company' => '',
    'content' => '',
    'rating' => 5,
    'avatar' => null,
    'date' => null,
])

<div class="testimonial-card">
    <div class="card h-100 shadow-sm">
        <div class="card-body">
            {{-- Rating Stars --}}
            @if($rating)
                <div class="mb-3">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $rating)
                            <i class="fas fa-star text-warning"></i>
                        @else
                            <i class="fas fa-star text-muted"></i>
                        @endif
                    @endfor
                </div>
            @endif

            {{-- Content --}}
            @if($testimonial && is_object($testimonial))
                <p class="testimonial-content mb-3">
                    "{{ e($testimonial->content ?? '') }}"
                </p>
                
                <div class="d-flex align-items-center">
                    @if($testimonial->avatar)
                        <img src="{{ asset($testimonial->avatar) }}" 
                             alt="{{ e($testimonial->name ?? '') }}" 
                             class="rounded-circle me-3" 
                             width="50" 
                             height="50">
                    @else
                        <div class="avatar-placeholder rounded-circle me-3 bg-primary text-white d-flex align-items-center justify-content-center" 
                             style="width: 50px; height: 50px;">
                            {{ strtoupper(substr(e($testimonial->name ?? ''), 0, 1)) }}
                        </div>
                    @endif
                    
                    <div>
                        <h6 class="mb-0">{{ e($testimonial->name ?? 'Anonymous') }}</h6>
                        @if($testimonial->position || $testimonial->company)
                            <small class="text-muted">
                                {{ e($testimonial->position ?? '') }}
                                @if($testimonial->position && $testimonial->company)
                                    , 
                                @endif
                                {{ e($testimonial->company ?? '') }}
                            </small>
                        @endif
                    </div>
                </div>
            @else
                {{-- Fallback for direct props --}}
                <p class="testimonial-content mb-3">
                    "{{ e($content) }}"
                </p>
                
                <div class="d-flex align-items-center">
                    @if($avatar)
                        <img src="{{ asset($avatar) }}" 
                             alt="{{ e($name) }}" 
                             class="rounded-circle me-3" 
                             width="50" 
                             height="50">
                    @else
                        <div class="avatar-placeholder rounded-circle me-3 bg-primary text-white d-flex align-items-center justify-content-center" 
                             style="width: 50px; height: 50px;">
                            {{ strtoupper(substr(e($name), 0, 1)) }}
                        </div>
                    @endif
                    
                    <div>
                        <h6 class="mb-0">{{ e($name) }}</h6>
                        @if($position || $company)
                            <small class="text-muted">
                                {{ e($position) }}
                                @if($position && $company)
                                    , 
                                @endif
                                {{ e($company) }}
                            </small>
                        @endif
                    </div>
                </div>
            @endif
        </div>
        
        @if($date)
            <div class="card-footer bg-transparent">
                <small class="text-muted">{{ e($date) }}</small>
            </div>
        @endif
    </div>
</div>
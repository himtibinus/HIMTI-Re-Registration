<?php
    // Import this module so we can get date/time formatter
    use Carbon\Carbon;
?>

<div class="card card-auto mb-4" style="background-color: @if(isset($event->theme_color_background)) {{$event->theme_color_background}} @else #4159a7 @endif; color: @if(isset($event->theme_color_foreground)) {{$event->theme_color_foreground}} @else #ffffff @endif;">
    <img class="card-img-auto" src="{{ $event->cover_image }}" alt="Card image cap">
    <div class="card-body">
        <h6 id="card_{{ $event->id }}_{{ $type }}" data-adjust-date="true">{{ Carbon::parse($event->date) }}</h6>
        <script>adjustDate('card_{{ $event->id }}_{{ $type }}')</script>
        @if(strlen($event->kicker) > 0)
            <h5 class="card-title">{{ $event->kicker }}</h5>
            <h5 class="card-title fw-normal">{{ $event->name }}</h5>
        @else
            <h5 class="card-title">{{ $event->name }} @if($event->private)<span class="badge rounded-pill bg-dark text-warning">Private</span>@endif</h5>
        @endif
        <p class="card-text h6">{{ $event->location }}</p>
        <div class="btn-toolbar" role="toolbar">
            <div class="btn-group" role="group">
            <a href="/events/{{ $event->id }}" class="btn btn-light" style="color: @if(isset($event->theme_color_background)) {{$event->theme_color_background}} @else #4159a7 @endif; background-color: @if(isset($event->theme_color_foreground)) {{$event->theme_color_foreground}} @else #ffffff @endif; border-color: @if(isset($event->theme_color_foreground)) {{$event->theme_color_foreground}} @else #ffffff @endif;">Details</a>
            </div>
        </div>
    </div>
</div>

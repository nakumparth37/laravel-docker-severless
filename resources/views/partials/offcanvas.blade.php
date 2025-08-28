<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNotifications"
    aria-labelledby="offcanvasNotificationsLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasNotificationsLabel">Notifications</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="list-group list-unstyled" id="notificationList">
            @if(auth()->check())
                @if (auth()->user()->unreadNotifications->count() > 0)
                @foreach (auth()->user()->unreadNotifications as $unreadNotification)
                <li class="list-group-item border-0 p-0 m-0">
                    <div class="card border-primary mb-3 notification-card" id="notification-{{ $unreadNotification->id }}">
                        <div class="card-header d-flex justify-content-between m-0">
                            <div>
                                New Product Add!
                            </div>
                            <div class="d-flex align-self-center">
                                <small class="text-muted">{{ $unreadNotification->created_at->diffForHumans() }}</small>
                                <button type="button" class="btn-close me-2 ps-1" aria-label="Close"
                                    onclick="markAsRead('{{ $unreadNotification->id }}')"></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title text-primary">{{ $unreadNotification->data['title'] }}</h6>
                            <p class="color">
                                {{ \Illuminate\Support\Str::limit($unreadNotification->data['description'], 50) }}</p>
                            <a class="btn btn-primary float-end"
                                href="{{env("BASE_URL")}}product/{{$unreadNotification->data['id']}}">Check it Now!!</a>
                        </div>
                    </div>
                </li>
                @endforeach
                @else
                <li class="list-group-item">No notifications yet</li>
                @endif
            @endif
        </ul>
    </div>
    <button type="button" name="" id="make-all-read-btn" class="btn btn-primary m-1 @if (Auth::user()->unreadNotifications()->count() == 0) disabled @endif" onclick="makeAllRead()">
        Make ad Read all
    </button>
</div>

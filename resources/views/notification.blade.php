@extends('layout_home')

@section('content_homePage')
<style>  .notification-transparent {
    background-color:rgba(116, 15, 16, 0.7);  /* Red with 50% transparency */
    color:rgb(246, 245, 231);
}
.is-read{
    background-color:rgba(116, 15, 16, 0.7);
    color: rgb(255, 226, 188); /* White text for contrast */

}
.notification-transparent:hover {
    background-color: rgba(116, 15, 16, 0.6);  /* Darker red on hover with 70% transparency */
    color: rgb(255, 226, 188); /* White text for contrast */

}
h1 {
    text-align: center;
}
</style>
<br> <br> <br> <br> <br>
<div class="container mt-4">
    <h1>Thông báo</h1>

    @if($notifications->isEmpty())
        <p>Không có thông báo nào.</p>
    @else
        <div class="list-group">
            @foreach($notifications as $notification)
                <a href="#" class="list-group-item list-group-item-action flex-column align-items-start 
        notification-transparent @if($notification->users->contains(auth()->user()) && $notification->users->firstWhere('id', auth()->id())->pivot->is_read)
            is-read
        @endif">

                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">{{ $notification->title }}</h5>
                        <small>{{ $notification->created_at->diffForHumans() }}</small>
                    </div>
                    <p class="mb-1">{{ $notification->content }}</p>
                    <small>{{ $notification->sender }} - {{ $notification->created_at->toFormattedDateString() }}</small>
                </a>
                <br>
            @endforeach
        </div>
    @endif
</div>

<br><br> <br><br> <br> <br> <br>
@endsection
<?php
if(!Session::has('messages')) {
    return;
}
$messages = Session::get('messages');
?>

@if (isset($messages['success']) && count($messages['success']))
    <div class="flash-message">
        <div class="alert alert-success">
            <ul>
                @foreach($messages['success'] as $message)
                    <li>{{ $message }}</li>   
                @endforeach
            </ul>
        </div>
    </div>
@endif

@extends('template.app')
@section('contents')
    {{__('messages.welcome')}}
    <div class="d-flex justify-content-center">
        <h1 class="font-weight-bold">Main Page</h1>
    </div>
    <hr>
    <form class="w-25">
        <select name="lang" class="form-control">
            <option value="kr">한국어</option>
            <option value="en">English</option>
        </select>
        <button class="btn btn-primary mt-3" type="submit">바꾸기</button>
    </form>
@endsection



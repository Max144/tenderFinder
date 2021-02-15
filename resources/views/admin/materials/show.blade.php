@extends('layouts.app')

@section('content')
<div id="app">
    <form action="{{route('admin.materials.update-thicknesses', $material->id)}}" method="post">
        @csrf
        <material-thickness-form :material='{{json_encode($material)}}'>
        </material-thickness-form>
    </form>
</div>
@endsection

@section('scripts')
    <script src="https://kit.fontawesome.com/f1311e8f41.js" crossorigin="anonymous"></script>
@endsection

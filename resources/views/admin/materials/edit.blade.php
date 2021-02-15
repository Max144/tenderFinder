@extends('layouts.app')

@section('content')
<div class="row mt-5">
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <form method="post" action="{{route('admin.materials.update', $material->id)}}">
            <input type="hidden" name="_method" value="put" />
            @csrf
            <div class="form-group">
                <label for="name">Материал</label>
                <input type="text" class="form-control" id="name" name="name" value="{{$material->name}}">
            </div>
            <div class="form-group">
                <label for="density">Плотность</label>
                <input type="number" class="form-control" id="density"  name="density" step="0.01" value="{{$material->density}}">
            </div>
            <button type="submit" class="btn btn-primary">Редактировать</button>
        </form>
    </div>
</div>
@endsection

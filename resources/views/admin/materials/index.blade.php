@extends('layouts.app')

@section('content')
<table class="table h4 text-center table-striped table-bordered">
    <thead>
        <tr>
            <th scope="col" style="width: 5%">ID</th>
            <th scope="col" style="width: 65%">Материал</th>
            <th scope="col" style="width: 15%">Плотность</th>
            <th scope="col" style="width: 15%">#</th>
        </tr>
    </thead>
    <tbody>
        @foreach($materials as $material)
            <tr>
                <th scope="row">{{$material->id}}</th>
                <td>{{$material->name}}</td>
                <td>{{$material->density}}</td>
                <td>
                    <a href="{{route('admin.materials.show', $material->id)}}">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{route('admin.materials.edit', $material->id)}}">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form style=" display:inline!important;" method="post" action="{{route('admin.materials.destroy', $material->id)}}">
                        @csrf
                        <a href="#">
                            <i class="fas fa-trash-alt" onclick="$(this).parents('form').submit()"></i>
                        </a>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<div class="row mt-4">
    <div class="col-md-11"></div>
    <div class="col-md-1">
        <a href="{{route('admin.materials.create')}}">
            <i class="fas fa-3x fa-plus-circle"></i>
        </a>
    </div>
</div>
@endsection


@section('scripts')
    <script src="https://kit.fontawesome.com/f1311e8f41.js" crossorigin="anonymous"></script>
@endsection

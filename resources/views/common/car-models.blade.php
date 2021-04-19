@foreach($carModels as $model)
    <option value="{{$model->modelCode}}" >
        {{$model->modelName}}
    </option>
@endforeach

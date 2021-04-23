<option value="">Select Model</option>
@foreach($carModels as $model)
    <option value="{{$model->modelCode}}" >
        {{$model->modelName}}
    </option>
@endforeach

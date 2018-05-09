cacooota

<form name='forms' id='forms' method='post' action="">
    
Nombre: <input type='text' id='name' name='name' value='{{old('name')}}' > @if($errors->has('name')) error: {{$errors->get('name')[0]}} @endif  <br>

Apellidos: <input type='text' id='surname' name='surname' value="{{old('surname')}}"> @if($errors->has('surname')) error: {{$errors->get('surname')[0]}} @endif<br>
Age: <input type='text' id='age' name='age' value='{{old('age')}}'> @if($errors->has('age')) error: {{$errors->get('age')[0]}} @endif<br>
Gender:<select id='gender' name='gender'>
        <option value=''>Selecciona</option>
        <option value='male' @if (old('gender') == 'male') selected @endif>Hombre</option>
        <option value='female' @if (old('gender') == 'female') selected @endif>Mujer</option>
    </select>@if($errors->has('gender')) error: {{$errors->get('gender')[0]}} @endif <br><br>
Políticas:<select id='policy_id' name='policy_id'>
    @foreach ($policies as $policy)
    <option value='{{$policy->id}}'>{{$policy->policy}}</option>
    @endforeach
</select>@if($errors->has('policy_id')) error: {{$errors->get('policy_id')[0]}} @endif<br><br>
    {{ csrf_field() }}
<input type="submit" id="btnSubmit" name="btnSubmit" value="Guardar">
</form>
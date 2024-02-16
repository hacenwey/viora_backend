@extends('backend.layouts.master')

@section('title',settings('app_name').' | '. trans('global.edit') . trans('cruds.user.title_singular'))

@section('main-content')

<div class="card">
  <div class="row">
    <div class="col-md-12">
       @include('backend.layouts.notification')
    </div>
</div>
    <h5 class="card-header">@lang('global.edit') @lang('cruds.user.title_singular')</h5>
    <div class="card-body">
      <form class="row" method="post" action="{{route('backend.sellers.update', ['seller' => $user->id])}}">
        @csrf
        @method("PUT")
        <div class="form-group col-md-4">
          <label for="inputTitle" class="col-form-label">@lang('cruds.user.fields.name')</label>
        <input id="inputTitle" type="text" name="name" placeholder="@lang('global.enter') @lang('cruds.user.fields.name')"  value="{{ $user->name }}" class="form-control" disabled>
        @error('name')
        <span class="text-danger">{{$message}}</span>
        @enderror
        </div>

        <div class="form-group col-md-4">
            <label for="inputEmail" class="col-form-label">Age</label>
          <input id="inputEmail" type="email" name="text" placeholder="Age"  value="{{ $user->age }}" class="form-control" disabled>
          @error('email')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group col-md-4">
            <label for="inputPassword" class="col-form-label">Occupation</label>
          <input id="inputPassword" type="password" name="text" placeholder="Occupation"  value="" class="form-control" disabled>
          @error('password')
            <span class="text-danger">{{$message}}</span>
          @enderror
        </div>

        <div class="form-group col-md-4">
            <label for="phone" class="col-form-label">@lang('cruds.user.fields.phone_number')</label>
            <input id="phone" type="text" name="phone_number" placeholder="@lang('global.enter') @lang('cruds.user.fields.phone_number')"  value="{{ old('phone_number', $user->phone_number) }}" class="form-control" disabled>
            @error('phone_number')
              <span class="text-danger">{{$message}}</span>
            @enderror
        </div>

        <div class="form-group col-md-4">
          <label for="phone" class="col-form-label">Address</label>
          <input id="phone" type="text" name="phone_number" placeholder="Address"  value="{{ old('adress', $user->adress) }}" class="form-control" disabled>
          @error('phone_number')
            <span class="text-danger">{{$message}}</span>
          @enderror
      </div>
        <div class="form-group col-md-4">
          <label for="phone" class="col-form-label">Number des commande livre</label>
          <input id="phone" type="text" name="phone_number" placeholder="0"  value="{{ old('order_delivered', $user->order_delivered) }}" class="form-control" disabled>
          @error('phone_number')
            <span class="text-danger">{{$message}}</span>
          @enderror
      </div>
      <div class="form-group col-md-3">
        <label for="discount_start" class="col-form-label">Commission pour le vendeur (%).</label>
        <input id="commission" type="number" name="commission"
            placeholder="Par défaut {{ settings('commission_global') }}%"
            value="{{ old('commission', $user->commission) }}" class="form-control" min="0" max="50">
        @error('discount_start')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>
        <div class="form-group col-md-4">
          <label for="phone" class="col-form-label">Number des commande encours</label>
          <input id="phone" type="text" name="solde" placeholder="@lang('global.enter') @lang('cruds.user.fields.phone_number')"  value="{{ old('order_in_delivered', $user->order_in_delivered) }}" class="form-control" disabled>
          @error('phone_number')
            <span class="text-danger">{{$message}}</span>
          @enderror
      </div>

        <div class="form-group col-md-4">
          <label for="phone" class="col-form-label">Solde du compte</label>
          <input id="phone" type="text" name="phone_number" placeholder="@lang('global.enter') @lang('cruds.user.fields.phone_number')"  value="{{ old('solde', $user->solde) }} MRU" class="form-control" disabled>
          @error('phone_number')
            <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
        <div class="form-group col-md-4">
          <label for="phone" class="col-form-label">Enter un montant pour payer le vendeur en MRU</label>
          <input id="phone" type="number" name="avance" placeholder="Enter un montant pour payer le vendeur en MRU" class="form-control" oninput="validateInput()">
          @error('phone_number')
            <span class="text-danger">{{$message}}</span>
          @enderror
          
        </div>
          <div class="form-group col-md-6">
            <label for="phone" class="text-danger">Suite à l'activation du statut du vendeur, son solde augmentera immédiatement de 100 MRU.</label>
            <select name="status" class="form-control" >
                <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>@lang('global.active')</option>
                <option value="inactive" {{ $user->status == 'inactive' ? 'selected' : '' }} disabled = '{{$user->status === 'active'}}'>@lang('global.inactive')</option>
            </select>
          @error('status')
          <span class="text-danger">{{$message}}</span>
          @enderror
          </div>
          <div class="form-group col-md-6">

            <span class="btn badge-warning col-md-12" data-toggle="modal" data-target=".bd-example-modal-lg">Voir Historique des transactions</span>
         </div>
        <div class="form-group col-md-12">
           <button class="btn btn-success col-md-12" type="submit">Sauvegarder</button>
        </div>
      </form>
    </div>


    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="card-body">
            <div class="table-responsive">
              @if(count($transactions) == 0)
                    <p style="float:center"></p>
                    <h6 class="text-center">Aucune transaction pour ce vendeur.</h6>

                @else
              <table class="table table-bordered" id="user-dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <tr>
                      <th>@lang('cruds.user.fields.id')</th>
                      <th>Nom du vendeur au vendeuse</th>
                      <th>Numero du téléphone</th>
                      <th>Date de transaction</th>
                      <th>Montant</th>
                      <th>Type</th>
                    </tr>
                  </tr>
                </thead>
                <tfoot>
                  <tr>
                      <th>@lang('cruds.user.fields.id')</th>
                      <th>Nom</th>
                      <th>Numero du téléphone</th>
                      <th>Date d'inscription</th>
                      <th>Montant</th>
                      <th>Type</th>

                    </tr>
                </tfoot>
                <tbody>
                  @foreach($transactions as $transaction)
                      <tr>
                          <td>{{$transaction->reference}}</td>
                          <td>{{$transaction->seller->name}}</td>
                          <td>{{$transaction->seller->phone_number}}</td>
                          <td>{{(($transaction->created_at)? $transaction->created_at->diffForHumans() : '')}}</td>
                          <td>{{$transaction->solde}} MRU</td>

                          <td>

                            @if($transaction->type=='IN')
                            <span class="badge badge-success">{{$transaction->type}}</span>
                        @else
                            <span class="badge badge-warning">{{$transaction->type}}</span>
                        @endif
                          </td>
                      </tr>
                  @endforeach
              </tbody>

              </table>
              @endif

              <span style="float:center">{{$transactions->links()}}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>

<script>
    $('.select2').select2();
    $('#lfm').filemanager('file');


    function validateInput() {
            var inputElement = document.getElementById('avance');
            var errorMessageElement = document.getElementById('error-message');
            var inputValue = inputElement.value;

            if (inputValue < 0) {
                errorMessageElement.textContent = 'Please enter a non-negative number.';
                inputElement.setCustomValidity('Please enter a non-negative number.');
            } else {
                errorMessageElement.textContent = '';
                inputElement.setCustomValidity('');
            }
        }
</script>
@endpush

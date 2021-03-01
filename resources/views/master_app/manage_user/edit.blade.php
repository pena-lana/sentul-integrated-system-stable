<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <form id="edit-user-form" method="post">
            <div class="form-group">
                <label for="fullname">Fullname</label>
                <input type="hidden" class="form-control" id="user_id" name="user_id" value="{{ $user->encrypt_id }}">
                <input type="text" class="form-control" id="fullname" name="fullname" value="{{ $user->employee->fullname }}">
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="{{ $user->username }}">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ $user->employee->email }}">
            </div>
            <div class="form-group">
                <label for="Departement">Departement</label>
                <select name="departement_id" id="departement_id" class="select2 form-control">
                    @foreach ($departements as $departement)
                        <option value="{{ $departement->encrypt_id }}" @if ($departement->encrypt_id === $user->employee->encrypt_departement_id) selected  @endif>{{$departement->departement}}</option>
                    @endforeach
                </select>
            </div>
        </form>
        <div class="row">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                <button class="btn btn-outline-secondary form-control" data-dismiss="modal" onclick="resetModalSize()">Cancel</button>
            </div>

            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                <button class="btn btn-primary form-control" onclick="updateUser()">Update</button>
            </div>

        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.select2').select2({
                'theme':'bootstrap4'
        });
    });
</script>

<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <form id="analisa-sampel-pi-form">
            <div class="card">
                <div class="card-header bg-secondary" data-toggle="collapse" data-target="#detail_sampel_pi">
                    Detail Sampel PI
                </div>
                <div class="card-body collapse show" id="detail_sampel_pi">
                    <div class="form-group">
                        <label for="filling_sampel_code">
                            Kode Sampel Filling
                        </label>
                        <input type="hidden" name="rpd_filling_detail_id" id="rpd_filling_detail_id" value="{{ $rpd_filling_detail->encrypt_id }}">
                        <input type="hidden" name="filling_machine_id" id="filling_machine_id" value="{{ $rpd_filling_detail->encrypt_id }}">
                        <input type="text" name="filling_sampel_code" id="filling_sampel_code" class="form-control" value="{{ $rpd_filling_detail->filling_sampel_code->filling_sampel_code.' - '.$rpd_filling_detail->filling_sampel_code->filling_sampel_event }}" readonly>
                        <input type="hidden" class="form-control datetimepicker-input" name="filling_date_old" id="filling_date_old"  value="{{ $rpd_filling_detail->filling_date }}" />
                        <input type="hidden" class="form-control datetimepicker-input" name="filling_time_old" id="filling_time_old"  value="{{ $rpd_filling_detail->filling_time }}" autocomplete="off" required/>
                    </div>
                    <div class="form-group">
                        <label for="product_name">Nama Produk</label>
                        <input type="text" name="product_name" id="product_name" class="form-control" value="{{ $rpd_filling_detail->product->product_name }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="filling_machine">Mesin Filling</label>
                        <input type="text" name="filling_machine" id="filling_machine" class="form-control" value="{{ $rpd_filling_detail->filling_machine->filling_machine_code }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="filling_date">Filling Date</label>
                        <div class="input-group date mb-2 datepickernya" data-target-input="nearest">
                            <div class="input-group-append" onclick="$('#filling_date').click()">
                                <div class="input-group-text"><i class="fas fa-calendar"></i></div>
                            </div>
                            <input type="text" class="form-control datetimepicker-input" name="filling_date" id="filling_date" data-target=".datepickernya" value="{{ $rpd_filling_detail->filling_date }}" data-target=".datepickernya" data-toggle="datetimepicker"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="filling_time">Filling Time</label>
                        <div class="input-group date mb-2 timepickernya" data-target-input="nearest">
                            <div class="input-group-append" onclick="$('#filling_time').click()">
                                <div class="input-group-text"><i class="fas fa-clock"></i></div>
                            </div>
                            <input type="text" class="form-control datetimepicker-input" name="filling_time" id="filling_time" data-target=".timepickernya" data-toggle="datetimepicker" value="{{ $rpd_filling_detail->filling_time }}"/ autocomplete="off" required/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header bg-primary" data-toggle="collapse" data-target="#parameter_analisa_pi">
                    Parameter Analisa Sampel PI
                </div>
                <div class="card-body collapse show" id="parameter_analisa_pi">
                    <div class="form-group">
                        <label for="overlap">Overlap</label>
                        <input type="text" name="overlap" id="overlap" class="form-control" inputmode="decimal" maxlength="4" autocomplete="off" placeholder="Ex. : 4.5" onfocusout="overlap_change()">
                        <small id="overlap_help_text" class="form-text text-muted">
                            Batas overlap @if ($rpd_filling_detail->filling_machine->filling_machine_code == 'TPA A') Min. 4.5 Batas Max. 6.0 @else Batas Min. 3.5 Batas Max. 4.5 @endif
                            <br> Menggunaan titik (.) sebagai desimal
                        </small>
                    </div>
                    <div class="form-group">
                        <label for="ls_sa_proportion">LS/SA Proportion</label>
                        <input type="text" class="form-control" inputmode="decimal" id="ls_sa_proportion" name="ls_sa_proportion" maxlength="5" placeholder="Ex. : 40-60" autocomplete="off" onfocusout="ls_sa_proportion_change()">
                        <small id="ls_sa_proportion_help_text" class="form-text text-muted">
                            Di isi hanya dengan angka dengan format XX-XX
                        </small>
                    </div>
                    <div class="form-group">
                        <label for="volume_kanan"> Volume Kanan </label>
                        <input type="text" inputmode="numeric" class=" form-control" name="volume_kanan" id="volume_kanan" maxlength="3" placeholder="Ex : 200" onkeypress="return event.charCode >= 48 && event.charCode <= 57" autocomplete="off" onfocusout="set_status_analisa()">
                        <small id="volume_kanan_help_text" class="form-text text-muted">
                            Batas Min. 198 Batas Max. 202 <br>
                            Di isi hanya dengan format angka dalam satuan Ml
                        </small>
                    </div>
                    <div class="form-group">
                        <label for="volume_kiri"> Volume Kiri </label>
                        <input type="text" inputmode="numeric" class=" form-control" name="volume_kiri" id="volume_kiri" maxlength="3" placeholder="Ex : 200" onkeypress="return event.charCode >= 48 && event.charCode <= 57" autocomplete="off" onfocusout="set_status_analisa()">
                        <small id="volume_kiri_help_text" class="form-text text-muted">
                            Batas Min. 198 Batas Max. 202 <br>
                            Di isi hanya dengan format angka dalam satuan Ml
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="airgap"> Airgap </label>
                        <select name="airgap" id="airgap" class="select2 form-control"  required="true" onchange="set_status_analisa()">
                            <option value="OK" selected>OK</option>
                            <option value="#OK">#OK</option>
                            <option value="-">-</option>
                        </select>
                        <small id="airgap_help_text" class="form-text text-muted">
                            Max. 1mm
                        </small>
                    </div>
                    <div class="form-group">
                        <label for="ts_accurate_kiri"> TS Accurate Kiri </label>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12" id="ts_accurate_kiri_div">
                                <select name="ts_accurate_kiri" id="ts_accurate_kiri" class="select2 form-control" required="true" onchange="select_not_ok_change(this)">
                                    <option value="OK" selected>OK</option>
                                    <option value="#OK">#OK</option>
                                    <option value="-">-</option>
                                </select>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-6 col-6" id="ts_accurate_kiri_not_ok_div">
                                <select name="ts_accurate_kiri_not_ok" id="ts_accurate_kiri_not_ok" class="select2 form-control">
                                    <option value="not_ok" selected disabled>Pilih Kategori #OK</option>
                                    <option value="Block Seal">Block Seal</option>
                                    <option value="Crack">Crack</option>
                                    <option value="Plastic Lump">Plastic Lump</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="ts_accurate_kanan"> TS Accurate Kanan </label>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12" id="ts_accurate_kanan_div">
                                <select name="ts_accurate_kanan" id="ts_accurate_kanan" class="select2 form-control"  required="true" onchange="select_not_ok_change(this)">
                                    <option value="OK" selected>OK</option>
                                    <option value="#OK">#OK</option>
                                    <option value="-">-</option>
                                </select>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-6 col-6" id="ts_accurate_kanan_not_ok_div">
                                <select name="ts_accurate_kanan_not_ok" id="ts_accurate_kanan_not_ok" class="select2 form-control" >
                                    <option value="#OK" selected disabled>Pilih Kategori #OK</option>
                                    <option value="Block Seal">Block Seal</option>
                                    <option value="Crack">Crack</option>
                                    <option value="Plastic Lump">Plastic Lump</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="ls_accurate"> LS Accurate </label>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12" id="ls_accurate_div" >
                                <select name="ls_accurate" id="ls_accurate" class="select2 form-control"  required="true" onchange="select_not_ok_change(this)">
                                    <option value="OK" selected>OK</option>
                                    <option value="#OK">#OK</option>
                                    <option value="-">-</option>
                                </select>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-6 col-6 hidden" id="ls_accurate_not_ok_div">
                                <select name="ls_accurate_not_ok" id="ls_accurate_not_ok" class="select2 form-control" >
                                    <option value="#OK" selected disabled>Pilih Kategori #OK</option>
                                    <option value="Block Seal">Block Seal</option>
                                    <option value="Strip Wrinkle">Strip Wrinkle</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="sa_accurate"> SA Accurate </label>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12" id="sa_accurate_div" >
                                <select name="sa_accurate" id="sa_accurate" class="select2 form-control"  required="true" onchange="select_not_ok_change(this)">
                                    <option value="OK" selected>OK</option>
                                    <option value="#OK">#OK</option>
                                    <option value="-">-</option>
                                </select>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-6 col-6 hidden" id="sa_accurate_not_ok_div">
                                <select name="sa_accurate_not_ok" id="sa_accurate_not_ok" class="select2 form-control" >
                                    <option value="#OK" selected disabled>Pilih Kategori #OK</option>
                                    <option value="Block Seal">Block Seal</option>
                                    <option value="Strip Wrinkle">Strip Wrinkle</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="surface_check"> Surface Check </label>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12" id="surface_check_div">
                                <select name="surface_check" id="surface_check" class="select2 form-control"  required="true" onchange="select_not_ok_change(this)">
                                    <option value="OK" selected>OK</option>
                                    <option value="#OK">#OK</option>
                                    <option value="-">-</option>
                                </select>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-6 col-6 hidden" id="surface_check_not_ok_div">
                                <select name="surface_check_not_ok" id="surface_check_not_ok" class="select2 form-control">
                                    <option value="#OK" selected disabled>Pilih Kategori #OK</option>
                                    <option value="Block Seal">Block Seal</option>
                                    <option value="Strip Wrinkle">Strip Wrinkle</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="pinching"> Pinching </label>
                        <select name="pinching" id="pinching" class="select2 form-control"  required="true">
                            <option value="OK" selected>OK</option>
                            <option value="#OK">#OK</option>
                            <option value="-">-</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="strip_folding"> Strip Folding </label>
                        <select name="strip_folding" id="strip_folding" class="select2 form-control"  required="true">
                            <option value="OK" selected>OK</option>
                            <option value="#OK">#OK</option>
                            <option value="-">-</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="konduktivity_kiri"> Konduktivity Kiri </label>
                        <select name="konduktivity_kiri" id="konduktivity_kiri" class="select2 form-control"  required="true">
                            <option value="OK" selected>OK</option>
                            <option value="#OK">#OK</option>
                            <option value="-">-</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="konduktivity_kanan"> Konduktivity Kanan </label>
                        <select name="konduktivity_kanan" id="konduktivity_kanan" class="select2 form-control"  required="true">
                            <option value="OK" selected>OK</option>
                            <option value="#OK">#OK</option>
                            <option value="-">-</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="design_kiri"> Design Kiri </label>
                        <select name="design_kiri" id="design_kiri" class="select2 form-control"  required="true">
                            <option value="OK" selected>OK</option>
                            <option value="#OK">#OK</option>
                            <option value="-">-</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="design_kanan"> Design Kanan </label>
                        <select name="design_kanan" id="design_kanan" class="select2 form-control"  required="true">
                            <option value="OK" selected>OK</option>
                            <option value="#OK">#OK</option>
                            <option value="-">-</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="dye_test"> Dye Test </label>
                        <select name="dye_test" id="dye_test" class="select2 form-control"  required="true">
                            <option value="OK" selected>OK</option>
                            <option value="#OK">#OK</option>
                            <option value="-">-</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="residu_h2o2"> Residu H2O2 (Maks. 0,5 ppm) </label>
                        <select name="residu_h2o2" id="residu_h2o2" class="select2 form-control"  required="true">
                            <option value="OK" selected>OK</option>
                            <option value="#OK">#OK</option>
                            <option value="-">-</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="prod_code_no_md"> Prod Code & No Md </label>
                        <select name="prod_code_no_md" id="prod_code_no_md" class="select2 form-control"  required="true">
                            <option value="OK" selected>OK</option>
                            <option value="#OK">#OK</option>
                            <option value="-">-</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="correction"> Correction </label>
                        <input type="hidden" name="status_akhir" id="status_akhir">
                        <textarea name="correction" id="correction" cols="30" rows="5" class="form-control">-</textarea>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row mt-2">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 mt-2 order-2 order-sm-1">
                        <button class="btn btn-outline-secondary form-control" onclick="$('.close').click()">
                            Batal
                        </button>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 mt-2 order-1 order-sm-1">
                        <button class="btn btn-primary form-control">
                            Submit
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
<script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<script>

    $('.timepickernya').datetimepicker({
        format: 'HH:mm:ss',
        locale:'en',
        // date: new Date()
    });
    $('.datepickernya').datetimepicker({
        format: 'YYYY-MM-DD',
        locale:'en',
        // date: new Date()
    });

    $('.datepicker').datetimepicker({
        format: 'YYYY-MM-DD',
        locale:'en'
    });
    $('.datetimepickernya').datetimepicker({
        format: 'YYYY-MM-DD HH:mm:ss',
    });
    $('.select2').select2({
            'theme':'bootstrap4'
    });
</script>

<script>
    $('#ts_accurate_kanan_not_ok_div').hide();
    $('#ts_accurate_kiri_not_ok_div').hide();
    function overlap_change()
    {
        var overlap     = $('#overlap').val();
        if(!(!overlap) || overlap !== '')
        {
            if (!overlap.includes('.'))
            {
                Swal.fire({
                    title   : 'Process Error !',
                    text    : "Overlap hanya di isi angka dengan format desimal dua angka dibelakang koma X.XX dengan Titik sebagai koma pemisah",
                    icon    : 'error'
                });
                return false;
            }
            else
            {
                overlap_split   = overlap.toString().split('.');
                if (overlap_split[0].length !== 1 || overlap_split[1].length == 0  || overlap_split[1].length > 2)
                {
                    Swal.fire({
                        title   : 'Process Error !',
                        text    : "Overlap hanya di isi angka dengan format desimal dua angka dibelakang koma X.XX",
                        icon    : 'error'
                    });
                    return false;
                }
            }
        }
        set_status_analisa()
    }
    function ls_sa_proportion_change()
    {
        var ls_sa_proportion = $('#ls_sa_proportion').val();
        if ( !(!ls_sa_proportion) || ls_sa_proportion !== '')
        {
            if (!ls_sa_proportion.includes('-') && !ls_sa_proportion.includes(':'))
            {
                Swal.fire({
                    title   : 'Process Error !',
                    text    : "LS/SA Proportion hanya di isi angka dengan format XX-XX",
                    icon    : 'error'
                });
                return false;
            }
            else
            {
                if (ls_sa_proportion.includes(':'))
                {
                    if (ls_sa_proportion.toString().split(':')[0].length !== 2 || ls_sa_proportion.toString().split(':')[1].length !== 2 )
                    {
                        Swal.fire({
                            title   : 'Process Error !',
                            text    : "LS/SA Proportion hanya di isi angka dengan format XX-XX",
                            icon    : 'error'
                        });
                    }
                    return false;
                }
                else if(ls_sa_proportion.includes('-'))
                {
                    if (ls_sa_proportion.toString().split('-')[0].length !== 2 || ls_sa_proportion.toString().split('-')[1].length !== 2 )
                    {
                        Swal.fire({
                            title   : 'Process Error !',
                            text    : "LS/SA Proportion hanya di isi angka dengan format XX-XX",
                            icon    : 'error'
                        });
                        return false;
                    }
                    var change_strip    = ls_sa_proportion.replace('-',':');
                    $('#ls_sa_proportion').val(change_strip);
                }
            }
        }
        set_status_analisa()
    }

    function select_not_ok_change(params)
    {
        ts_accurate_id      = params.id;
        ts_accurate_value   = params.value;
        switch (ts_accurate_value)
        {
            case "OK":
                $('#'+ts_accurate_id+'_div').removeAttr('class');
                $('#'+ts_accurate_id+'_div').addClass('col-lg-12 col-md-12 col-sm-12 col-12');
                $('#'+ts_accurate_id+'_not_ok_div').removeAttr("required");
                $('#'+ts_accurate_id+'_not_ok_div').hide();
            break;

            case "#OK":
                $('#'+ts_accurate_id+'_div').removeAttr('class');
                $('#'+ts_accurate_id+'_div').addClass('col-lg-6 col-md-6 col-sm-6 col-6');
                $('#'+ts_accurate_id+'_not_ok_div').attr("required",true);
                $('#'+ts_accurate_id+'_not_ok_div').show();
            break;

            case "-":
                $('#'+ts_accurate_id+'_div').removeAttr('class');
                $('#'+ts_accurate_id+'_div').addClass('col-lg-12 col-md-12 col-sm-12 col-12');
                $('#'+ts_accurate_id+'_not_ok_div').hide();
                $('#'+ts_accurate_id+'_not_ok_div').removeAttr("required");
            break;
        }
        set_status_analisa()
    }

    function set_status_analisa()
    {
        overlap                     = $('#overlap').val();
        ls_sa_proportion            = $('#ls_sa_proportion').val();
        volume_kanan                = $('#volume_kanan').val();
        volume_kiri                 = $('#volume_kiri').val();
        airgap                      = $('#airgap').val();
        ts_accurate_kiri            = $('#ts_accurate_kiri').val();
        ts_accurate_kanan           = $('#ts_accurate_kanan').val();
        ts_accurate_kanan_not_ok    = $('#ts_accurate_kanan_not_ok').val();
        ls_accurate                 = $('#ls_accurate').val();
        ls_accurate_not_ok          = $('#ls_accurate_not_ok').val();
        sa_accurate                 = $('#sa_accurate').val();
        sa_accurate_not_ok          = $('#sa_accurate_not_ok').val();
        surface_check               = $('#surface_check').val();
        surface_check_not_ok        = $('#surface_check_not_ok').val();
        pinching                    = $('#pinching').val();
        strip_folding               = $('#strip_folding').val();
        konduktivity_kiri           = $('#konduktivity_kiri').val();
        konduktivity_kanan          = $('#konduktivity_kanan').val();
        design_kiri                 = $('#design_kiri').val();
        design_kanan                = $('#design_kanan').val();
        dye_test                    = $('#dye_test').val();
        residu_h2o2                 = $('#residu_h2o2').val();
        prod_code_no_md             = $('#prod_code_no_md').val();
        if
        (
            volume_kanan >= 198                                                     &&
            volume_kiri >= 198                                                      &&
            (airgap == 'OK' || airgap == '-')                                       &&
            (ts_accurate_kiri == 'OK' || ts_accurate_kiri == '-')                   &&
            (ts_accurate_kanan == 'OK' || ts_accurate_kanan == '-')                 &&
            (ls_accurate == 'OK' || ls_accurate == '-')                             &&
            (sa_accurate == 'OK' || sa_accurate == '-')                             &&
            (surface_check == 'OK' || surface_check == '-')                         &&
            (pinching == 'OK' || pinching == '-')                                   &&
            (strip_folding == 'OK' || strip_folding == '-')                         &&
            (konduktivity_kiri == 'OK' || konduktivity_kiri == '-')                 &&
            (konduktivity_kanan == 'OK' || konduktivity_kanan == '-')               &&
            (design_kiri == 'OK' || design_kiri == '-')                             &&
            (design_kanan == 'OK' || design_kanan == '-')                           &&
            (dye_test == 'OK' || dye_test == '-')                                   &&
            (residu_h2o2 == 'OK' || residu_h2o2 == '-')                             &&
            (prod_code_no_md == 'OK' || prod_code_no_md == '-')                     &&
            (
                ls_sa_proportion !== '10:90' &&
                ls_sa_proportion !== '90:10' &&
                ls_sa_proportion !== '80:20' &&
                ls_sa_proportion !== '70:30'
            )
        )
        {

            $('#status_akhir').val('OK');
            if ($('#correction').val() == '')
            {
                $('#correction').val('-');
            }
        }
        else
        {
            $('#status_akhir').val('#OK');

            if ($('#correction').val() == '-')
            {
                $('#correction').val('');
            }
        }


    }

    $(document).ready(function()
    {
        var form        = $('#analisa-sampel-pi-form');
        form.validate({
            rules:
            {
                overlap : {
                    required : true,
                    number:true
                },

                ls_sa_proportion    : {
                    required : true
                },

                volume_kanan    : {
                    required : true
                },

                volume_kiri : {
                    required : true
                },

                airgap  : {
                    required : true
                },

                ts_accurate_kiri    : {
                    required : true
                },

                ts_accurate_kiri_not_ok    : {
                    required : function () {
                        return $('#ts_accurate_kiri').val() == '#OK';
                    }
                },

                ts_accurate_kanan   : {
                    required : true
                },

                ts_accurate_kanan_not_ok    : {
                    required : function () {
                        return $('#ts_accurate_kanan').val() == '#OK';
                    }
                },

                ls_accurate : {
                    required : true
                },

                ls_accurate_not_ok  : {
                    required : function () {
                        return $('#ls_accurate').val() == '#OK';
                    }
                },

                sa_accurate : {
                    required : true
                },

                sa_accurate_not_ok  : {
                    required : function () {
                        return $('#sa_accurate').val() == '#OK';
                    }
                },

                surface_check   : {
                    required : true
                },

                surface_check_not_ok    : {
                    required : function () {
                        return $('#surface_check').val() == '#OK';
                    }
                },

                pinching    : {
                    required : true
                },

                strip_folding   : {
                    required : true
                },

                konduktivity_kiri   : {
                    required : true
                },

                konduktivity_kanan  : {
                    required : true
                },

                design_kiri : {
                    required : true
                },

                design_kanan    : {
                    required : true
                },

                dye_test    : {
                    required : true
                },

                residu_h2o2 : {
                    required : true
                },

                prod_code_no_md : {
                    required : true
                },
                correction:{
                    required : true
                },
            },
            messages:
            {
                overlap : {
                    required : "Data analisa wajib di isi ",
                    number:"Hanya di isi oleh angka"
                },

                ls_sa_proportion    : {
                    required : "Data analisa wajib di isi "
                },

                volume_kanan    : {
                    required : "Data analisa wajib di isi "
                },

                volume_kiri : {
                    required : "Data analisa wajib di isi "
                },

                airgap  : {
                    required : "Data analisa wajib di isi "
                },

                ts_accurate_kiri    : {
                    required : "Data analisa wajib di isi "
                },

                ts_accurate_kanan   : {
                    required : "Data analisa wajib di isi "
                },

                ts_accurate_kanan_not_ok    : {
                    required : "Data analisa wajib di isi "
                },

                ls_accurate : {
                    required : "Data analisa wajib di isi "
                },

                ls_accurate_not_ok  : {
                    required : "Data analisa wajib di isi "
                },

                sa_accurate : {
                    required : "Data analisa wajib di isi "
                },

                sa_accurate_not_ok  : {
                    required : "Data analisa wajib di isi "
                },

                surface_check   : {
                    required : "Data analisa wajib di isi "
                },

                surface_check_not_ok    : {
                    required : "Data analisa wajib di isi "
                },

                pinching    : {
                    required : "Data analisa wajib di isi "
                },

                strip_folding   : {
                    required : "Data analisa wajib di isi "
                },

                konduktivity_kiri   : {
                    required : "Data analisa wajib di isi "
                },

                konduktivity_kanan  : {
                    required : "Data analisa wajib di isi "
                },

                design_kiri : {
                    required : "Data analisa wajib di isi "
                },

                design_kanan    : {
                    required : "Data analisa wajib di isi "
                },

                dye_test    : {
                    required : "Data analisa wajib di isi "
                },

                residu_h2o2 : {
                    required : "Data analisa wajib di isi "
                },

                prod_code_no_md : {
                    required : "Data analisa wajib di isi "
                },

                correction:{
                    required : "Data analisa wajib di isi "
                },
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            },
            submitHandler: function(form) {
                overlap_change();
                ls_sa_proportion_change();
                if ($('#status_akhir').val() == 'OK')
                {
                    Swal.fire({
                        icon:'question',
                        title: 'Konfirmasi Hasil Analisa',
                        showConfirmButton: true,
                        showDenyButton: false,
                        showCancelButton: true,
                        html: 'Apa benar hasil semua pengecekan OK?',
                        confirmButtonText: '<i class="fas fa-check"></i> Ya, Submit Analisa!',
                        cancelButtonText: 'Tidak, Revisi Analisa',
                    }).then((result) => {
                        /* Read more about isConfirmed, isDenied below */
                        if(result.isConfirmed)
                        {
                            submitForm();
                        }
                    });
                }
                else
                {
                    submitForm();
                }
            }


        });
    });
    function submitForm()
    {
        data            = $('#analisa-sampel-pi-form').serialize();
        $.ajax({
            url:"{{url('rollie/rpd-filling/analisa-filling-sampel')}}",
            type:'POST',
            beforeSend:function()
            {
                $('.loading-bar').removeClass('hidden');
            },
            headers: {
                'X-CSRF-TOKEN'  : $('meta[name="csrf-token"]').attr('content')
            },
            data:data,
            success: function(data)
            {
                switch (data.status)
                {
                    case '00':
                        if (data.ppq)
                        {
                            table.ajax.reload(null,false);
                            table_done.ajax.reload(null,false);
                            Swal.fire({
                                title: 'Perhatian',
                                text: data.message,
                                icon: 'info'
                            });
                            $('#modal').modal('show');
                            $('.close').hide();
                            $('#modal-title').html('Form Ketidaksesuaian Package Integrity');
                            $('#modal-size').addClass('modal-xl');
                            $('#modal .modal-body').html(data.ppq_view);
                        }
                        else
                        {
                            $('.close').click();
                            table.ajax.reload(null,false);
                            table_done.ajax.reload(null,false);
                        }
                    break;
                    case '01':
                        Swal.fire({
                            title: 'Process Error ! ',
                            text: data.message,
                            icon: 'error'
                        });
                    break;

                    case '02':
                        Swal.fire({
                            title: 'Process Error !',
                            text: data.message,
                            icon: 'error'
                        });
                        $('.close').click();

                        table.ajax.reload(null,false);
                        table_done.reload(null,false);
                    break;
                }
            },
            complete: function (data) {
                $('.loading-bar').addClass('hidden');
            }
        });
    }
</script>

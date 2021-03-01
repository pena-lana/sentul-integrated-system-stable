<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <form id="analisa-sampel-pi-at-event-form">
            <div class="card">
                <div class="card-header bg-secondary" data-toggle="collapse" data-target="#detail_sampel_pi">
                    Detail Sampel PI At Event
                </div>
                <div class="card-body collapse show" id="detail_sampel_pi">
                    <div class="form-group">
                        <label for="filling_sampel_code">
                            Kode Sampel Filling
                        </label>
                        <input type="hidden" name="rpd_filling_detail_id" id="rpd_filling_detail_id" value="{{ $rpd_filling_detail->encrypt_id }}">
                        <input type="hidden" name="filling_machine_id" id="filling_machine_id" value="{{ $rpd_filling_detail->encrypt_id }}">
                        <input type="hidden" name="params" id="params" value="{{ $rpd_filling_detail->params }}">
                        <input type="text" name="filling_sampel_code" id="filling_sampel_code" class="form-control" value="{{ $rpd_filling_detail->filling_sampel_code->filling_sampel_code.' - '.$rpd_filling_detail->filling_sampel_code->filling_sampel_event.' (Event)' }}" readonly>
                        <input type="hidden" class="form-control " name="filling_date_old" id="filling_date_old" value="{{ $rpd_filling_detail->filling_date }}" />
                        <input type="hidden" class="form-control " name="filling_time_old" id="filling_time_old" value="{{ $rpd_filling_detail->filling_time }}" />
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
                        <small id="filling_date_help_text" class="form-text text-muted">
                            Merubah data tanggal filling pada event ini secara otomatis akan merubah data tanggal filling pada sampel PI yang terkait
                        </small>
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

                    <small id="filling_date_help_text" class="form-text text-muted">
                        Merubah data jam filling pada event ini secara otomatis akan merubah data jam filling pada sampel PI yang terkait
                    </small>
                </div>
            </div>
            <div class="card">
                <div class="card-header bg-primary" data-toggle="collapse" data-target="#parameter-analisa-wajib">
                    Event Point
                </div>
                <div class="card-body collapse show" id="parameter-analisa-wajib">
                    <div class="form-group">
                        <label for="ls_sa_sealing_quality">LS/SA Sealing Quality</label>
                        <select name="ls_sa_sealing_quality" id="ls_sa_sealing_quality" class="select2 form-control"  required="true" onchange="set_status_analisa()" >
                            <option disabled selected>-- Status LS/SA Sealing Quality --</option>
                            <option value="OK">OK</option>
                            <option value="#OK">#OK</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="ls_sa_proportion">LS/SA Proportion</label>
                        <input type="text" class="form-control" inputmode="decimal" id="ls_sa_proportion" name="ls_sa_proportion" maxlength="5" placeholder="Ex. : 40-60" autocomplete="off" onfocusout="ls_sa_proportion_change()" required="true">
                        <small id="ls_sa_proportion_help_text" class="form-text text-muted">
                            Di isi hanya dengan angka dengan format XX-XX
                        </small>
                    </div>
                </div>
            </div>

            <div class="card" id="paper-splicing-parameter">
                <div class="card-header bg-primary" data-toggle="collapse" data-target="#paper-splicing">
                    Analisa Paper Splicing
                </div>
                <div class="card-body collapse show" id="paper-splicing">
                    <div class="form-group">
                        <label for="sideway_sealing_alignment">
                            Sideway Sealing Alignment
                        </label>
                        <input type="text" name="sideway_sealing_alignment" id="sideway_sealing_alignment" class="form-control" inputmode="decimal" maxlength="4" autocomplete="off" placeholder="Ex. : 0.55" onfocusout="sideway_sealing_alignment_change()" required="true">
                        <small id="sideway_sealing_alignment_help_text" class="form-text text-muted">
                            Batas Min. 0 Max. 0.5
                            <br>
                            Hanya di isi angka dengan format desimal X.XX dan menggunakan titik (.) sebagai desimal
                        </small>
                    </div>
                    <div class="form-group">
                        <label for="overlap">
                            Overlap
                        </label>
                        <input type="text" name="overlap" id="overlap" class="form-control" inputmode="decimal"  maxlength="5" autocomplete="off" placeholder="Ex. : 16.34" onfocusout="overlap_change()" required="true">
                        <small id="overlap_help_text" class="form-text text-muted">
                            Batas Min. 16 Max. 17
                            <br>
                            Hanya di isi angka dengan format desimal XX.XX dan menggunakan titik (.) sebagai desimal
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="overlap">
                            Package Length
                        </label>
                        <input type="text" name="package_length" id="package_length" class="form-control" inputmode="decimal" maxlength="6" autocomplete="off" placeholder="Ex. : 118.95" onfocusout="package_length_change()" required="true">
                        <small id="package_length_help_text" class="form-text text-muted">
                            Batas Min. 118.5 Max. 119.5
                            <br>
                            Hanya di isi angka dengan format desimal XXX.XX dan menggunakan titik (.) sebagai desimal
                        </small>
                    </div>
                    <div class="form-group">
                        <label for="paper_splice_sealing_quality">Paper Splice Sealing Quality</label>
                        <select name="paper_splice_sealing_quality" id="paper_splice_sealing_quality" class="select2 form-control"  required="true" onchange="set_status_analisa()" required="true">
                            <option disabled selected>-- Status Paper Slice Sealing Quality --</option>
                            <option value="OK">OK</option>
                            <option value="#OK">#OK</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="no_kk">
                            No. KK
                        </label>
                        <input type="text" name="no_kk" id="no_kk" class="form-control" inputmode="decimal" maxlength="14" autocomplete="off" placeholder="Ex. : 26532001803" onfocusout="set_status_analisa()" required="true">
                        <small id="no_kk_help_text" class="form-text text-muted">
                            Hanya di isi maks. 14 digit angka No. KK
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="no_md">
                            No. MD
                        </label>
                        <input type="text" name="no_md" id="no_md" class="form-control" inputmode="decimal" maxlength="14" autocomplete="off" placeholder="Ex. : 401110066269" onfocusout="set_status_analisa()" required="true">
                        <small id="no_md_help_text" class="form-text text-muted">
                            Hanya di isi maks. 14 digit angka No. MD
                        </small>
                    </div>
                </div>
            </div>

            <div class="card" id="strip-splicing-parameter">
                <div class="card-header bg-primary" data-toggle="collapse" data-target="#strip-splicing">
                    Analisa Strip Splicing
                </div>
                <div class="card-body collapse show" id="strip-splicing">
                    <div class="form-group">
                        <label for="ls_sa_sealing_quality_strip">Strip LS/SA Sealing Quality</label>
                        <select name="ls_sa_sealing_quality_strip" id="ls_sa_sealing_quality_strip" class="select2 form-control"  required="true" onchange="set_status_analisa()" required="true">
                            <option disabled selected>-- Status Strip LS/SA Sealing Quality --</option>
                            <option value="OK">OK</option>
                            <option value="#OK">#OK</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="card" id="short-stop-parameter">
                <div class="card-header bg-primary" data-toggle="collapse" data-target="#short-stop">
                    Analisa Short Stop
                </div>
                <div class="card-body collapse show" id="short-stop">
                    <div class="form-group">
                        <label for="ls_sealing_quality_short_stop">Short Stop LS Sealing Quality</label>
                        <select name="ls_sealing_quality_short_stop" id="ls_sealing_quality_short_stop" class="select2 form-control"  required="true" onchange="set_status_analisa()" >
                            <option disabled selected>-- Status Short Stop LS Sealing Quality --</option>
                            <option value="OK">OK</option>
                            <option value="#OK">#OK</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="sa_sealing_quality_short_stop">Short Stop SA Sealing Quality</label>
                        <select name="sa_sealing_quality_short_stop" id="sa_sealing_quality_short_stop" class="select2 form-control"  required="true" onchange="set_status_analisa()">
                            <option disabled selected>-- Status Short Stop SA Sealing Quality --</option>
                            <option value="OK">OK</option>
                            <option value="#OK">#OK</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card" id="footer-parameter">
                <div class="card-body">
                    <div class="form-group">
                        <label for="status_akhir">Status Akhir</label>
                        <input type="text" name="status_akhir" id="status_akhir" class="form-control" required="true" readonly >
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" cols="30" rows="5" class="form-control"></textarea>
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
        $('#paper-splicing-parameter').hide();
        $('#strip-splicing-parameter').hide();
        $('#short-stop-parameter').hide();

        var params  = "{{$rpd_filling_detail->params}}";
        $('#'+params+"-parameter").show();
        $('#params').val(params);
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
        function sideway_sealing_alignment_change() {
            var sideway_sealing_alignment     = $('#sideway_sealing_alignment').val();
            if(!(!sideway_sealing_alignment) || sideway_sealing_alignment !== '')
            {
                if (!sideway_sealing_alignment.includes('.'))
                {
                    Swal.fire({
                        title   : 'Process Error !',
                        text    : "Sideway Sealing Alignment hanya di isi angka dengan format desimal dua angka dibelakang koma X.XX dengan Titik sebagai koma pemisah",
                        icon    : 'error'
                    });
                    return false;
                }
                else
                {
                    sideway_sealing_alignment_split   = sideway_sealing_alignment.toString().split('.');
                    if (sideway_sealing_alignment_split[0].length > 1 || sideway_sealing_alignment_split[1].length > 2)
                    {
                        Swal.fire({
                            title   : 'Process Error !',
                            text    : "Sideway Sealing Alignment hanya di isi angka dengan format desimal dua angka dibelakang koma X.XX",
                            icon    : 'error'
                        });
                        return false;
                    }
                }
            }
            set_status_analisa()
        }
        function overlap_change() {
            var overlap     = $('#overlap').val();
            if(!(!overlap) || overlap !== '')
            {
                if (!overlap.includes('.'))
                {
                    Swal.fire({
                        title   : 'Process Error !',
                        text    : "Overlap hanya di isi angka dengan format desimal maksimal dua angka dibelakang koma XX.XX dengan Titik sebagai koma pemisah",
                        icon    : 'error'
                    });
                    return false;
                }
                else
                {
                    overlap_split   = overlap.toString().split('.');
                    if (overlap_split[0].length > 2 || overlap_split[0].length < 2 ||  overlap_split[1].length > 2)
                    {
                        Swal.fire({
                            title   : 'Process Error !',
                            text    : "Overlap hanya di isi angka dengan format desimal maksimal dua angka dibelakang koma XX.XX",
                            icon    : 'error'
                        });
                        return false;
                    }
                }
            }
            set_status_analisa()
        }

        function package_length_change() {
            var package_length     = $('#package_length').val();
            if(!(!package_length) || package_length !== '')
            {
                if (!package_length.includes('.'))
                {
                    Swal.fire({
                        title   : 'Process Error !',
                        text    : "Package Length hanya di isi angka dengan format desimal maksimal dua angka dibelakang koma XXX.XX dengan Titik sebagai koma pemisah",
                        icon    : 'error'
                    });
                    return false;
                }
                else
                {
                    package_length_split   = package_length.toString().split('.');
                    if (package_length_split[0].length > 3 || package_length_split[0].length < 3 || package_length_split[1].length > 2)
                    {
                        Swal.fire({
                            title   : 'Process Error !',
                            text    : "Package Length hanya di isi angka dengan format desimal maksimal dua angka dibelakang koma XXX.XX",
                            icon    : 'error'
                        });
                        return false;
                    }
                }
            }
            set_status_analisa()
        }
        function set_status_analisa()
        {
            ls_sa_sealing_quality           = $('#ls_sa_sealing_quality').val();
            ls_sa_proportion                = $('#ls_sa_proportion').val();
            if
            (
                (
                    ls_sa_proportion !== '10:90' &&
                    ls_sa_proportion !== '90:10' &&
                    ls_sa_proportion !== '80:20' &&
                    ls_sa_proportion !== '70:30'
                )   &&
                ls_sa_sealing_quality =='OK'
            )
            {
                $('#status_akhir').val('OK');
            }
            else
            {
                $('#status_akhir').val('#OK');
            }
            switch (params)
            {
                case 'paper-splicing':
                    sideway_sealing_alignment       = $('#sideway_sealing_alignment').val();
                    overlap                         = $('#overlap').val();
                    package_length                  = $('#package_length').val();
                    paper_splice_sealing_quality     = $('#paper_splice_sealing_quality').val();
                    no_kk                           = $('#no_kk').val();
                    no_md                           = $('#no_md').val();

                    if (
                        paper_splice_sealing_quality == 'OK'     &&
                        (
                            sideway_sealing_alignment >= 0  &&
                            sideway_sealing_alignment < 0.6
                        )                                       &&
                        (
                            overlap >= 16     &&
                            overlap <= 17
                        )                                       &&
                        (
                            package_length >= 118.5 &&
                            package_length <= 119.5
                        )
                    )
                    {
                        $('#status_akhir').val('OK');
                    }
                    else
                    {
                        $('#status_akhir').val('#OK');
                    }
                break;

                case 'strip-splicing':
                    ls_sa_sealing_quality_strip     = $('#ls_sa_sealing_quality_strip').val();
                    if (ls_sa_sealing_quality_strip == 'OK')
                    {
                        $('#status_akhir').val('OK');
                    }
                    else
                    {
                        $('#status_akhir').val('#OK');
                    }
                break;


                case 'short-stop':
                    ls_sealing_quality_short_stop   = $('#ls_sealing_quality_short_stop').val();
                    sa_sealing_quality_short_stop   = $('#sa_sealing_quality_short_stop').val();
                    if (ls_sealing_quality_short_stop == 'OK' && sa_sealing_quality_short_stop == 'OK')
                    {
                        $('#status_akhir').val('OK');
                    }
                    else
                    {
                        $('#status_akhir').val('#OK');
                    }
                break;

            }
        }

        $(document).ready(function()
        {
            ls_sa_proportion_change();
            var form        = $('#analisa-sampel-pi-at-event-form');
            form.validate({
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
                    switch (params)
                    {
                        case 'paper-splicing':
                            sideway_sealing_alignment_change();
                            overlap_change();
                            package_length_change();
                        break;
                    }
                    data            = $('#analisa-sampel-pi-at-event-form').serialize();
                    $.ajax({
                        url:"{{url('rollie/rpd-filling/analisa-filling-sampel-event')}}",
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
                                        /* ppq is true */
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

            });
        });
    </script>

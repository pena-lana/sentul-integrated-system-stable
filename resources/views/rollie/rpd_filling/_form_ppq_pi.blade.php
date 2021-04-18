<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <form id="ppq-form">
            <div class="card">
                <div class="card-header bg-secondary" data-toggle="collapse" data-target="#detail_product_ppq">
                    Data Produk PPQ {{$ppq->nomor_ppq}}<span class="float-right"> <i class="fas fa-arrow-down"></i></span>
                </div>
                <div class="card-body collapse" id="detail_product_ppq">
                    <div class="form-group">
                        <label for="product_name">
                            Nama Produk
                        </label>
                        <input type="text" class="form-control" value="{{ $ppq->rpdFillingDetailPi->woNumber->product->product_name }}" name="product_name" id="product_name" readonly>
                    </div>
                    <div class="form-group">
                        <label for="wo_number">
                            Nomor Wo
                        </label>
                        <input type="text" class="form-control" value="{{ $ppq->rpdFillingDetailPi->woNumber->wo_number }}" name="wo_number" id="wo_number" readonly>
                    </div>
                    <div class="form-group">
                        <label for="oracle_code">
                            Kode Oracle
                        </label>
                        <input type="text" class="form-control" value="{{ $ppq->rpdFillingDetailPi->woNumber->product->oracle_code }}" name="oracle_code" id="oracle_code" readonly>
                    </div>
                    <div class="form-group">
                        <label for="filling_machine_code">
                            Mesin Filling
                        </label>
                        <input type="text" class="form-control" value="{{ $ppq->rpdFillingDetailPi->fillingMachine->filling_machine_code }}" name="filling_machine_code" id="filling_machine_code" readonly>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header bg-secondary" data-toggle="collapse" data-target="#detail_ppq">
                    Detail PPQ {{$ppq->nomor_ppq}}
                </div>
                <div class="card-body collapse show" id="detail_ppq">
                    <div class="form-group">
                        <label for="lot_filling">
                            Nomor Lot
                        </label>
                        @if (is_null($ppq->paletPpqs) || count($ppq->paletPpqs) == 0)
                            <textarea class="form-control text-white" style="background-color: red" name="lot_filling" id="lot_filling" rows="2" readonly>Palet belum tersedia, harap hubungi tim packing untuk segera mengisi form packing dan memisahkan pack PPQ</textarea>
                            <input type="hidden" name="save_type" id="save_type" value="draft">
                        @else
                            <input type="hidden" name="save_type" id="save_type" value="submit">
                            <textarea class="form-control text-white"  name="lot_filling" id="lot_filling" rows="2" readonly>@foreach ($ppq->paletPpqs as $palet_ppq) {{ $palet_ppq->palet->cppDetail->lot_number.'-'.$palet_ppq->palet->palet }} @endforeach</textarea>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="jumlah_pack">Jumlah Pack</label>
                        @if (is_null($ppq->paletPpqs) || count($ppq->paletPpqs) == 0)
                            <textarea class="form-control text-white" style="background-color: red" name="jumlah_pack" id="jumlah_pack" rows="2" readonly>Palet belum tersedia, harap hubungi tim packing untuk segera mengisi form packing dan memisahkan pack PPQ</textarea>
                        @else
                            <input class="form-control" type="text" name="jumlah_pack" id="jumlah_pack" value="{{ $ppq->jumlah_pack }} Pack">
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="jam_awal_ppq">Jam Awal PPQ</label>
                        <input class="form-control" type="text" name="jam_awal_ppq" id="jam_awal_ppq" value="{{ date('d-m-Y H:i:s',strtotime($ppq->jam_awal_ppq)) }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="jam_akhir_ppq">Jam Akhir PPQ</label>
                        <input class="form-control" type="text" name="jam_akhir_ppq" id="jam_akhir_ppq" value="{{ date('d-m-Y H:i:s',strtotime($ppq->jam_akhir_ppq)) }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="alasan">Alasan PPQ</label>
                        <textarea class="form-control" name="alasan" rows="3" required>{{$ppq->alasan}}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="detail_titik_ppq">Detail Titik PPQ</label>
                        <textarea class="form-control" name="detail_titik_ppq" rows="3" required>{{$ppq->detail_titik_ppq}}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="">Kategori PPQ : </label>
                        <select name="kategori_ppq" class="form-control" name="kategori_ppq" required>
                            <option value="0" selected disabled> Pilih Kategori PPQ </option>
                            @foreach ($kategori_ppqs as $kategori_ppq)
                                <option value="{{ $kategori_ppq->encrypt_id }}"> {{ $kategori_ppq->kategori_ppq }} </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row mt-2">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mt-2 order-1 order-sm-1">
                        <a class="btn btn-primary text-white form-control" onclick="submitPpq()">
                            Submit
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


<script>
     function submitPpq()
    {
        save_type   = $('#save_type').val();
        if (save_type == 'submit')
        {
            alasan              = $('#alasan').val();
            detail_titik_ppq    = $('#detail_titik_ppq').val();
            kategori_ppq        = $('#kategori_ppq').val();
            if (!alasan || alasan == '' || !detail_titik_ppq || detail_titik_ppq == '' || !kategori_ppq || kategori_ppq == '0' )
            {
                Swal.fire({
                    title: 'Process Error !',
                    text: "Data Detail PPQ Tidak Boleh Ada Yang Kosong",
                    icon: 'error'
                });
                return false;
            }
        }
        data            = $('#ppq-form').serialize();
        $.ajax({
            url:"{{url('rollie/rpd-filling/submit-ppq')}}",
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
                        $('.close').click();
                        Swal.fire({
                            title: 'Proccess Success',
                            text: data.message,
                            icon: 'success'
                        });
                        table.ajax.reload(null,false);
                        table_done.ajax.reload(null,false);
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

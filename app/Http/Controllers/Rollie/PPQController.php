<?php

namespace App\Http\Controllers\Rollie;

use App\Models\Master\Product;
use App\Models\Master\ProductType;
use App\Models\Master\FillingMachine;
use App\Models\Master\FillingSampelCode;
use App\Models\Master\FillingMachineGroupHead;
use App\Models\Master\FillingMachineGroupDetail;
use App\Models\Master\Brand;
use App\Models\Master\Subbrand;

use App\Models\Transaction\Rollie\WoNumber;
use App\Models\Transaction\Rollie\RPDFillingHead;
use App\Models\Transaction\Rollie\RPDFillingDetailPi;
use App\Models\Transaction\Rollie\RPDFillingDetailPiAtEvent;
use App\Models\Transaction\Rollie\PPQ;

use Illuminate\Http\Request;
use App\Http\Controllers\ResourceController;
use Maatwebsite\Excel\Facades\Excel;

use Auth;
use DB;
use DataTables;
use Session;
use Hash;
use View;
class PPQController extends ResourceController
{



    public static function createDraftPPQ($jenis_ppq,$rpd_filling_detail_pi_after,$rpd_filling_detail_pi_before)
    {
        switch ($jenis_ppq)
        {
            case 'Package Integrity':
                $nomor_ppq                      = self::getNomorPPQ();
                $rpd_filling_detail_pi_after    = $rpd_filling_detail_pi_after; /* Rpd filling detail after #OK  */
                $rpd_filling_detail_pi_before   = $rpd_filling_detail_pi_before; /* Rpd filling detail before #OK  */
                $cpp_head                       = $rpd_filling_detail_pi_before->woNumber->cppHead;
                $cpp_head_id                    = null;
                if (!is_null($cpp_head))
                {
                    $cpp_head_id                = $cpp_head->id;
                }
                $check_cpp_details              = $rpd_filling_detail_pi_before->woNumber->cppDetails;
                $jumlah_pack                    = 0;
                $palets 				        = NULL;
                if (!is_null($check_cpp_details))
                {
                    $cpp_detail                 = $check_cpp_details->where('filling_machine_id',$rpd_filling_detail_pi_before->filling_machine_id)->first();
                }

                $jam_awal_ppq                   = $rpd_filling_detail_pi_before->filling_date.' '.$rpd_filling_detail_pi_before->filling_time;
                $jam_akhir_ppq                  = $rpd_filling_detail_pi_after->filling_date.' '.$rpd_filling_detail_pi_after->filling_time;

                try {
                    DB::beginTransaction();
                    $ppq                            = PPQ::create([
                        'rpd_filling_detail_pi_id'  => $rpd_filling_detail_pi_after->id, /* RPD Filling ID OK After #OK  */
                        'cpp_head_id'               => $cpp_head_id,
                        'nomor_ppq'                 => $nomor_ppq,
                        'ppq_date'                  => date('Y-m-d'),
                        'jam_awal_ppq'              => $jam_awal_ppq,
                        'jam_akhir_ppq'             => $jam_akhir_ppq,
                        'jumlah_pack'               => $jumlah_pack,
                        'status_akhir'              => '5' /* Set As Draft PPQ */
                    ]);
                    DB::commit();
                    return [
                        'status'    => '00',
                        'message'   => 'Draft PPQ Berhasil Dibuat',
                        'data'      => $ppq
                    ];
                }
                catch (\Exception $e)
                {
                    return [
                        'status'    => '01',
                        'message'   => $e->getMessage()
                    ];
                }
            break;
        }
    }

    public static function getNomorPPQ()
    {
        $database_transaction       = DB::connection('transaction_data')->getDatabaseName();
        $sql                        =   "SELECT ppqs.nomor_ppq FROM ".$database_transaction.".ppqs WHERE SUBSTRING_INDEX(nomor_ppq,'/',-1)='2020'";
        $get_all_ppq                = DB::select($sql);
        if (count($get_all_ppq) == 0)
        {
            $last_ppq_number        = 0;
        }
        else
        {
            $get_ppq_number         = explode('/',$get_all_ppq[count($get_all_ppq)-1]->nomor_ppq);
            $last_ppq_number        = intval($get_ppq_number[0]);
        }

        $new_ppq_number     = $last_ppq_number+1;
        if (strlen($new_ppq_number) == 1)
        {
            $ppq_number         = '00'.$new_ppq_number;
        }
        elseif (strlen($new_ppq_number) == 2)
        {
            $ppq_number     = '0'.$new_ppq_number;
        }
        elseif (strlen($new_ppq_number) == 3)
        {
            $ppq_number     = $new_ppq_number;
        }
        $bulan          = ['01'=>'I','02'=>'II','03'=>'III','04'=>'IV','05'=>'V','06'=>'VI','07'=>'VII','08'=>'VIII','09'=>'IX','10'=>'X','11'=>'XI','12'=>'XII'];
        $nomor_ppq      = $ppq_number.'/PPQ/'.$bulan[date('m')].'/'.date('Y');
        return $nomor_ppq;

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction\Rollie\PPQ  $pPQ
     * @return \Illuminate\Http\Response
     */
    public function show(PPQ $pPQ)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transaction\Rollie\PPQ  $pPQ
     * @return \Illuminate\Http\Response
     */
    public function edit(PPQ $pPQ)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transaction\Rollie\PPQ  $pPQ
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PPQ $pPQ)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transaction\Rollie\PPQ  $pPQ
     * @return \Illuminate\Http\Response
     */
    public function destroy(PPQ $pPQ)
    {
        //
    }
}

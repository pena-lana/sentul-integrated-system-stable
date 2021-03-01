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



    public static function createDraftPPQ($jenis_ppq,$params_id)
    {
        switch ($jenis_ppq)
        {
            case 'Package Integrity':
                $rpd_filling_detail_pi_id       = $params_id; /* params id OK after #ok */

                $nomor_ppq                      = self::getNomorPPQ();
                $rpd_filling_detail_pi          = RPDFillingDetailPi::find($rpd_filling_detail_pi_id);
                dd($rpd_filling_detail_pi);
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

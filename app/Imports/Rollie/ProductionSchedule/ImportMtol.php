<?php

namespace App\Imports\Rollie\ProductionSchedule;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithMappedCells;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\RedirectResponse;

use App\Models\Transaction\Rollie\WoNumber;
use App\Models\Master\Product;

use Auth;
class ImportMtol implements WithMappedCells,ToModel,WithValidation
{
    use Importable;
	public function mapping(): array
	{
		//mengambil dari row 5 sampai row 50
		for ($i=5; $i <= 50 ; $i++)
		{
			$return['plan_row_'.$i]							= "B".$i; // INDEX 0 dalam hasil
			$return['production_plan_date_row_'.$i]			= "C".$i; // INDEX 1 dalam hasil
			$return['nomor_wo_row_'.$i]						= "D".$i; // INDEX 2 dalam hasil
			$return['keterangan_1_row_'.$i]					= "F".$i; // INDEX 3 dalam hasil
			$return['keterangan_2_row_'.$i]					= "G".$i; // INDEX 4 dalam hasil
			$return['keterangan_3_row_'.$i] 				= "H".$i; // INDEX 5 dalam hasil
			$return['kode_produk_row_'.$i] 					= "I".$i; // INDEX 6 dalam hasil
			$return['nama_produk_row_'.$i] 					= "J".$i; // INDEX 7 dalam hasil
			$return['status_row_'.$i] 						= "L".$i; // INDEX 8 dalam hasil
			$return['revisi_formula_row_'.$i] 				= "Z".$i; // INDEX 9 dalam hasil
			$return['plan_batch_size_row_'.$i] 				= "K".$i; // INDEX 10 dalam hasil
			$return['plan_qty_box_row_'.$i] 				= "AA".$i; // INDEX 10 dalam hasil
		}
		return $return;
    }
    public function model(array $row)
    {
        $baris_asli = 1; // untuk deklarasi baris asli yang akan digunakan datanya

    	// pengecekan row 5 sampe 50
    	for ($i=5; $i <= 50 ; $i++)
    	{
            $hasil['baris_ke_'.$baris_asli] = array(); // deklarasi baris asli yang akan di ambil sebagai array

    		foreach ($row as $key => $value) // looping semua hasil yang diambil dari row 5 sampe 50
    		{
    			$keynya = explode('_',$key);
            	$cek = end($keynya); // pengambilan row dari pengambilan data
    			if ($cek == $i)  // pengecekan apa row data yang di ambil sesuai dengan row yang akan di cek secara ascending 5-50
    			{
    				// apabila sesuai maka akan di cek apa isi baris nomor wo , nama produk , kode produknya tidak kosong
    				if ($row['nomor_wo_row_'.$i] !== "" && $row['nomor_wo_row_'.$i] !== NULL && $row['nama_produk_row_'.$i] && $row['nama_produk_row_'.$i] !== NULL && $row['kode_produk_row_'.$i] !== "" && $row['kode_produk_row_'.$i] !== NULL)
    				{
    					// apabila tidak kosong maka akan di masukan sebagai bagian dari baris yang akan di input INDEXING lihat di fungsi mapping diatas tapi sebelumnya harus di cek dulu WO nya sudah ada dijadwal atau engga
    					$cekjadwal 	= WoNumber::where('wo_number',$row['nomor_wo_row_'.$i])->count();
    					if ($cekjadwal < 1)
    					{

	    					array_push($hasil['baris_ke_'.$baris_asli], $value);
    					}
    				}
    			}
    		}
    		// pengecekan apabila array dari baris aslinya tidak hanya array kosong maka dia akan menambah baris baru
    		if ($hasil['baris_ke_'.$baris_asli] !== [])
    		{
	    		$baris_asli++;
    		}
        }
    	$jadwalinsert 	= array();
    	for ($i=1; $i < count($hasil) ; $i++)
		{
			$kode_produk 				= 	$hasil['baris_ke_'.$i][6];
			if ($kode_produk == '7500150M' || $kode_produk == '7500147M')
			{
				continue;
			}
			else
			{
				$plan 						= 	$hasil['baris_ke_'.$i][0];
				$production_plan_date 		= 	Date::excelToDateTimeObject($hasil['baris_ke_'.$i][1]);
				$keterangan_1 				= 	$hasil['baris_ke_'.$i][3];
				$keterangan_2 				= 	$hasil['baris_ke_'.$i][4];
				$keterangan_3 				= 	$hasil['baris_ke_'.$i][5];
				$nomor_wo 					= 	$hasil['baris_ke_'.$i][2];
				$nama_produk 				= 	$hasil['baris_ke_'.$i][7];
				$status 					= 	$hasil['baris_ke_'.$i][8];
				$revisi_formula 			= 	$hasil['baris_ke_'.$i][9];
                $plan_batch_size 			= 	$hasil['baris_ke_'.$i][10];
                $plan_qty_box               =   $hasil['baris_ke_'.$i][11];
				if (!is_null($plan))
				{
					if ($plan == 'PLG')
					{
						$plan = '3';
					}
				}

				if (!is_null($keterangan_1) || $keterangan_1 == "")
				{
					$keterangan_1 = "-";
				}
				if (!is_null($keterangan_2) || $keterangan_2 == "")
				{
					$keterangan_2 = "-";
				}
				if (!is_null($keterangan_3) || $keterangan_3 == "")
				{
					$keterangan_3 = "-";
				}
				//mengambil data produk dengan dengan menyamakan nama produknya
				if ($kode_produk !== "" && $nama_produk !== "" || !is_null($kode_produk) && !is_null($nama_produk))
				{
					// PENGECEKAN APABILA WO YANG DIGUNAKAN ADALAH WO kode_trial
					if (strpos($nomor_wo, '/'))
					{
						$patahkan 		= explode('/',$nomor_wo);
						$kode_trial 	= end($patahkan);
						$produk 		= Product::where('trial_code',$kode_trial)->first();
						$produk_id 		= $produk->id;
					}
					else
					{
						$produk 	= Product::where('oracle_code',$kode_produk)->first();

						$produk_id 	= $produk->id;

					}
				}
				// pengecekan status dari excel lalu di rubah sesuai indexing dalam database
				if ($status == "Pending")
				{
					$status = "0";
				}
				else if ($status == "Cancelled")
				{
					$status = "6";
				}
				else if($status == '')
				{
					$status = "0";
				}
				else if ($status == 'WIP')
				{
					$status = '1';
				}

				if ($revisi_formula == "" || is_null($revisi_formula))
				{
					$revisi_formula = "-";
				}
				$arrayrow = array();

				//memasukan value kedalam array untuk insert multiple
				$arrayrow['wo_number']					= $nomor_wo;
				$arrayrow['product_id']					= $produk_id;
				$arrayrow['plan_id']					= $plan;
				$arrayrow['production_plan_date']		= $production_plan_date;
				$arrayrow['wo_status']					= $status;
				$arrayrow['explanation_1']				= $keterangan_1;
				$arrayrow['explanation_2']				= $keterangan_2;
				$arrayrow['explanation_3']				= $keterangan_3;
				$arrayrow['plan_batch_size']			= $plan_batch_size;
				$arrayrow['plan_qty_box']			    = $plan_qty_box;
				$arrayrow['formula_revision']			= $revisi_formula;
				$arrayrow['upload_status']			    = '0';
				$arrayrow['created_by']			        = Auth::user()->id;
				// dd($arrayrow);
				// satu variabel insert di push
				array_push($jadwalinsert, $arrayrow);
			}

    	}

    	$insertjadwal 	= WoNumber::insert($jadwalinsert);
    }
	public function rules(): array
    {
        return [
        	'produk_id' => 'required|numeric'
        ];
    }
}

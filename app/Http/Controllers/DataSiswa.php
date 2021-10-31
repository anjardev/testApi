<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Raw;
use App\Models\Rapot;
use App\Models\Siswa;

class DataSiswa extends Controller
{
    //
    public function import(){
        
        \DB::beginTransaction();
        
        try{
            $raw =  Raw::with(['siswa'])->get();
            foreach($raw as $r){
                if($r->nilai < 40){
                    $nilai = 'E';
                } else if($r->nilai >= 40 && $r->nilai <= 54){
                    $nilai = 'D';
                } else if($r->nilai >= 55 && $r->nilai <= 64){
                    $nilai = 'C';
                } else if($r->nilai >= 65 && $r->nilai <= 79){
                    $nilai = 'B';
                } else if($r->nilai >= 80){
                    $nilai = 'A';
                } else {
                    $nilai = '';
                }

                $rapot = new Rapot();
                $rapot->id_siswa    = $r->siswa->id;
                $rapot->nilai       = $r->nilai;
                $rapot->nilai_huruf = $nilai;
                $rapot->save();
            }
            \DB::commit();
            return "Import Data Sukses";
        }catch(\Exception $e){
            \DB::rollback();
            return "Import Data Gagal";
        }
    }

    public function list(Request $request){
        $siswa = Siswa::join('rapot', 'rapot.id_siswa', '=', 'siswa.id');
        $siswa = $siswa->join('kelas', 'siswa.id_kelas', '=', 'kelas.id');

        if(!empty($request->kota))
        {
            $siswa = $siswa->where('kota', $request->kota);
        }

        if(!empty($request->kelas))
        {
            $siswa = $siswa->where('kelas.nama', $request->kelas);
        }

        $siswa = $siswa->get(['kelas.nama AS nama_kelas', 'siswa.*', 'rapot.nilai', 'rapot.nilai_huruf']);

        $result = array();
        $data = array();
        $dataSiswa = array();
        foreach($siswa as $s){
            $data["nis"] = $s->nis;
            $data["nama"] = $s->nama;
            $data["kota"] = $s->kota;
            $data["kelas"] = $s->nama_kelas;
            $data["nilai"] = $s->nilai;
            $data["nilai_huruf"] = $s->nilai_huruf;

            array_push($dataSiswa,$data);

        }

        if(count($dataSiswa) > 0){
            $result['status'] = true;
            $result['message'] = 'OK';
            $result["data"] = $dataSiswa;
        } else {
            $result['status'] = false;
            $result['message'] = 'Data Tidak Ditemukan';
        }

        return $result;
    }

    public function kota(){

        $kota = Siswa::select('kota')->groupBy('kota')->get();

        $dataKota = array();
        $result = array();
        foreach($kota as $k){
            $kelas = Siswa::join('kelas', 'siswa.id_kelas', '=', 'kelas.id')->select('id_kelas','kelas.nama AS nama_kelas')->where('kota',$k->kota)->groupBy('id_kelas')->get();
            $dataKelas = array();
            foreach($kelas as $kls){
                $siswa = Siswa::with(['kelas','rapot'])->where(['kota'=>$k->kota, 'id_kelas'=>$kls->id_kelas])->get()->sortByDesc('rapot.nilai');
                $dataSiswa = array();
                foreach($siswa as $s){
                    $data["nis"] = $s->nis;
                    $data["nama"] = $s->nama;
                    $data["kota"] = $s->kota;
                    $data["kelas"] = $s->kelas->nama;
                    $data["nilai"] = $s->rapot->nilai;
                    $data["nilai_huruf"] = $s->rapot->nilai_huruf;
        
                    array_push($dataSiswa,$data);
                }
                $data1['kelas'] = $kls->nama_kelas;
                $data1['data'] = $dataSiswa;
                array_push($dataKelas,$data1);
            }
            $dataKota['kota'] = $k->kota;
            $dataKota['data'] = $dataKelas;
            array_push($result,$dataKota);
        }

        if(count($result) > 0){
            $results['status'] = true;
            $results['message'] = 'OK';
            $results["data"] = $result;
        } else {
            $results['status'] = false;
            $results['message'] = 'Data Tidak Ditemukan';
        }

        return $results;
    }
}

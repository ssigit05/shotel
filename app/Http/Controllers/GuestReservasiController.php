<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kamar;
use App\Models\Pemesanan;

class GuestReservasiController extends Controller
{
    public function create()
    {
        $kamar = Kamar::select('id as value','nama_kamar as option')->get();
        $kamar->map(function($item){
            $item['option'] = ucwords($item['option']);
            return $item;
        });
        $kamar->toArray();
        return view('reservasi',['kamar'=>$kamar]);
    }
    public function store(Request $request)
    {
        $request->validate([
            'checkin'=>'required|date|after:today',
            'checkout'=>'required|date|after:checkin',
            'kamar'=>'required|numeric|integer',
            'jumlah_kamar'=>'required|numeric|integer|gt:0',
            'nama_pemesan'=>'required',
            'email'=>'required|email',
            'nomor_handphone'=>'required',
            'nama_tamu'=>'required',
        ]);

        $pemesanan = Pemesanan::create([
            'kamar_id'=>$request->kamar,
            'tgl_checkin'=>$request->checkin,
            'tgl_checkout'=>$request->checkout,
            'jum_kamar_dipesan'=>$request->jumlah_kamar,
            'nama_pemesan'=>$request->nama_pemesan,
            'email_pemesan'=>$request->email,
            'no_hp'=>$request->nomor_handphone,
            'nama_tamu'=>$request->nama_tamu,
            'status'=> 'pesan',
        ]);

        return redirect()->route('guest.reservasi.show',['pemesanan'=>$pemesanan->id]);
    }

    public function show(Pemesanan $pemesanan)
    {
        return view('reservasi-show',['item'=>$pemesanan]);
    }
}

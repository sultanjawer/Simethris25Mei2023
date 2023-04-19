<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commitment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

use App\Models\Commitmentbackdate;
use App\Models\MasterPenangkar;
use App\Models\MasterKelompok;
use App\Models\PenangkarMitra;
use App\Models\PengajuanV2;
use App\Models\verif_commitment;

class CommitmentBackdateController extends Controller
{
	// use SimeviTrait;

	// public $access_token = '';
	// public $data_user;
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		//
		$module_name = 'Komitmen';
		$page_title = 'Daftar Komitmen';
		$page_heading = 'Daftar Komitmen';
		$heading_class = 'fa fa-file-invoice';

		$masterpenangkars = MasterPenangkar::all();
		$user = Auth::user();
		$commitments = $user->commitmentbackdate()->get();

		return view('v2.commitment.index', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'user', 'commitments', 'masterpenangkars'));
	}

	public function create()
	{
		//
		$module_name = 'Komitmen';
		$page_title = 'Komitmen Baru';
		$page_heading = 'Tambah Komitmen Baru';
		$heading_class = 'fa fa-file-invoice';
		$masterpenangkars = MasterPenangkar::all();

		return view('v2.commitment.create', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'masterpenangkars'));
	}

	public function store(Request $request)
	{
		//
		$commitments = new CommitmentBackdate();
		$commitments->user_id = Auth::user()->id;
		$commitments->no_ijin = $request->input('no_ijin');
		$commitments->periodetahun = $request->input('periodetahun');
		$commitments->tgl_ijin = $request->input('tgl_ijin');
		$commitments->tgl_end = $request->input('tgl_end');
		$commitments->no_hs = $request->input('no_hs');
		$commitments->volume_riph = $request->input('volume_riph');
		$commitments->no_hs = $request->input('no_hs');
		$commitments->stok_mandiri = $request->input('stok_mandiri');
		$commitments->organik = $request->input('organik');
		$commitments->npk = $request->input('npk');
		$commitments->dolomit = $request->input('dolomit');
		$commitments->za = $request->input('za');
		$commitments->mulsa = $request->input('mulsa');
		$commitments->poktan_share = $request->input('poktan_share');

		//upload formRiph
		if ($request->hasFile('formRiph')) {
			$attch = $request->file('formRiph');
			$attchname = 'formRiph_' . $commitments->id . '_' . time() . '.' . $attch->getClientOriginalExtension();
			Storage::disk('public')->putFileAs('docs/commitmentsv2/' . $request->input('periodetahun') . '/' . 'formRiph', $attch, $attchname);
			$commitments->formRiph = $attchname;
		}

		//upload formSptjm
		if ($request->hasFile('formSptjm')) {
			$attch = $request->file('formSptjm');
			$attchname = 'formSptjm_' . $commitments->id . '_' . time() . '.' . $attch->getClientOriginalExtension();
			Storage::disk('public')->putFileAs('docs/commitmentsv2/' . $request->input('periodetahun') . '/' . 'formSptjm', $attch, $attchname);
			$commitments->formSptjm = $attchname;
		}

		//upload logbook
		if ($request->hasFile('logbook')) {
			$attch = $request->file('logbook');
			$attchname = 'logbook_' . $commitments->id . '_' . time() . '.' . $attch->getClientOriginalExtension();
			Storage::disk('public')->putFileAs('docs/commitmentsv2/' . $request->input('periodetahun') . '/' . 'logbook', $attch, $attchname);
			$commitments->logbook = $attchname;
		}

		//upload formRt
		if ($request->hasFile('formRt')) {
			$attch = $request->file('formRt');
			$attchname = 'formRt_' . $commitments->id . '_' . time() . '.' . $attch->getClientOriginalExtension();
			Storage::disk('public')->putFileAs('docs/commitmentsv2/' . $request->input('periodetahun') . '/' . 'formRt', $attch, $attchname);
			$commitments->formRt = $attchname;
		}

		//upload formRta
		if ($request->hasFile('formRta')) {
			$attch = $request->file('formRta');
			$attchname = 'formRta_' . $commitments->id . '_' . time() . '.' . $attch->getClientOriginalExtension();
			Storage::disk('public')->putFileAs('docs/commitmentsv2/' . $request->input('periodetahun') . '/' . 'formRta', $attch, $attchname);
			$commitments->formRta = $attchname;
		}

		//upload formRpo
		if ($request->hasFile('formRpo')) {
			$attch = $request->file('formRpo');
			$attchname = 'formRpo_' . $commitments->id . '_' . time() . '.' . $attch->getClientOriginalExtension();
			Storage::disk('public')->putFileAs('docs/commitmentsv2/' . $request->input('periodetahun') . '/' . 'formRpo', $attch, $attchname);
			$commitments->formRpo = $attchname;
		}

		//upload formLa
		if ($request->hasFile('formLa')) {
			$attch = $request->file('formLa');
			$attchname = 'formLa_' . $commitments->id . '_' . time() . '.' . $attch->getClientOriginalExtension();
			Storage::disk('public')->putFileAs('docs/commitmentsv2/' . $request->input('periodetahun') . '/' . 'formLa', $attch, $attchname);
			$commitments->formLa = $attchname;
		}

		// dd($commitments);
		$commitments->save();

		return redirect()->route('admin.task.commitments.index')->with('success', 'Data Commitment Saved successfully');
	}

	public function show($id)
	{

		$module_name = 'Komitmen';
		$page_title = 'Detail';
		$page_heading = 'Detail Komitmen';
		$heading_class = 'fa fa-file-invoice';

		$commitment = CommitmentBackdate::with('user', 'pksmitra.masterkelompok', 'penangkarmitra.masterpenangkar', 'pengajuanv2')
			->where('user_id', Auth::id())
			->findOrFail($id);
		$masterkelompoks = MasterKelompok::all();
		$masterpenangkars = MasterPenangkar::all();
		$pengajuanv2 = PengajuanV2::all();
		$pksmitras = $commitment->pksmitra;
		$penangkarmitras = $commitment->penangkarmitra;

		if (!$commitment->status) {
			$disabled = false; // input di-enable
		} else {
			$disabled = true; // input di-disable
		}

		// dd();
		return view('v2.commitment.show', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'masterpenangkars', 'penangkarmitras', 'commitment', 'masterkelompoks', 'pksmitras', 'pengajuanv2', 'disabled'));
	}

	public function edit($id)
	{
		//
		//load all commitments for current user
		// $commitments = CommitmentBackdate::with('user')->findOrFail($id);
		$commitments = CommitmentBackdate::with('user')
			->where('user_id', Auth::id())
			->findOrFail($id);

		//load all Master Penangkar for reference in blade view
		$masterpenangkars = MasterPenangkar::all();

		//load all Penangkar Mitra for current Commitment (commitment_backdate_id)
		$penangkarmitras = PenangkarMitra::with('commitmentbackdate')->get();

		$module_name = 'Komitmen';
		$page_title = 'Ubah Data Komitmen';
		$page_heading = 'Ubah data Komitmen: ' . $commitments->no_ijin;
		$heading_class = 'fal fa-file-edit';


		return view('v2.commitment.edit', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'commitments', 'masterpenangkars', 'penangkarmitras'));
	}

	public function update(Request $request, $id)
	{
		//
		$commitments = CommitmentBackdate::find($id);
		$commitments->user_id = Auth::user()->id;
		$commitments->no_ijin = $request->input('no_ijin');
		$commitments->periodetahun = $request->input('periodetahun');
		$commitments->tgl_ijin = $request->input('tgl_ijin');
		$commitments->tgl_end = $request->input('tgl_end');
		$commitments->no_hs = $request->input('no_hs');
		$commitments->volume_riph = $request->input('volume_riph');
		$commitments->no_hs = $request->input('no_hs');
		$commitments->stok_mandiri = $request->input('stok_mandiri');
		$commitments->organik = $request->input('organik');
		$commitments->npk = $request->input('npk');
		$commitments->dolomit = $request->input('dolomit');
		$commitments->za = $request->input('za');
		$commitments->mulsa = $request->input('mulsa');
		$commitments->poktan_share = $request->input('poktan_share');

		//upload formRiph
		if ($request->hasFile('formRiph')) {
			$attch = $request->file('formRiph');
			$attchname = 'formRiph_' . $commitments->id . '_' . time() . '.' . $attch->getClientOriginalExtension();
			Storage::disk('public')->putFileAs('docs/commitmentsv2/' . $request->input('periodetahun') . '/' . 'formRiph', $attch, $attchname);
			$commitments->formRiph = $attchname;
		}

		//upload formSptjm
		if ($request->hasFile('formSptjm')) {
			$attch = $request->file('formSptjm');
			$attchname = 'formSptjm_' . $commitments->id . '_' . time() . '.' . $attch->getClientOriginalExtension();
			Storage::disk('public')->putFileAs('docs/commitmentsv2/' . $request->input('periodetahun') . '/' . 'formSptjm', $attch, $attchname);
			$commitments->formSptjm = $attchname;
		}

		//upload logbook
		if ($request->hasFile('logbook')) {
			$attch = $request->file('logbook');
			$attchname = 'logbook_' . $commitments->id . '_' . time() . '.' . $attch->getClientOriginalExtension();
			Storage::disk('public')->putFileAs('docs/commitmentsv2/' . $request->input('periodetahun') . '/' . 'logbook', $attch, $attchname);
			$commitments->logbook = $attchname;
		}

		//upload formRt
		if ($request->hasFile('formRt')) {
			$attch = $request->file('formRt');
			$attchname = 'formRt_' . $commitments->id . '_' . time() . '.' . $attch->getClientOriginalExtension();
			Storage::disk('public')->putFileAs('docs/commitmentsv2/' . $request->input('periodetahun') . '/' . 'formRt', $attch, $attchname);
			$commitments->formRt = $attchname;
		}

		//upload formRta
		if ($request->hasFile('formRta')) {
			$attch = $request->file('formRta');
			$attchname = 'formRta_' . $commitments->id . '_' . time() . '.' . $attch->getClientOriginalExtension();
			Storage::disk('public')->putFileAs('docs/commitmentsv2/' . $request->input('periodetahun') . '/' . 'formRta', $attch, $attchname);
			$commitments->formRta = $attchname;
		}

		//upload formRpo
		if ($request->hasFile('formRpo')) {
			$attch = $request->file('formRpo');
			$attchname = 'formRpo_' . $commitments->id . '_' . time() . '.' . $attch->getClientOriginalExtension();
			Storage::disk('public')->putFileAs('docs/commitmentsv2/' . $request->input('periodetahun') . '/' . 'formRpo', $attch, $attchname);
			$commitments->formRpo = $attchname;
		}

		//upload formLa
		if ($request->hasFile('formLa')) {
			$attch = $request->file('formLa');
			$attchname = 'formLa_' . $commitments->id . '_' . time() . '.' . $attch->getClientOriginalExtension();
			Storage::disk('public')->putFileAs('docs/commitmentsv2/' . $request->input('periodetahun') . '/' . 'formLa', $attch, $attchname);
			$commitments->formLa = $attchname;
		}

		$commitments->save();
		return redirect()->route('admin.task.commitments.index')->with('success', 'Data Commitment updated successfully');
	}

	public function read($id)
	{
		//
		//load all commitments for current user
		$commitments = CommitmentBackdate::with('user')
			->where('user_id', Auth::id())
			->findOrFail($id);

		//load all Master Penangkar for reference in blade view
		$masterpenangkars = MasterPenangkar::all();

		//load all Penangkar Mitra for current Commitment (commitment_backdate_id)
		$penangkarmitras = PenangkarMitra::with('commitmentbackdate')->get();

		$module_name = 'Komitmen';
		$page_title = 'Komitmen Detail';
		$page_heading = 'Data Komitmen: ' . $commitments->no_ijin;
		$heading_class = 'fal fa-file-invoice';


		return view('v2.commitment.read', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'commitments', 'masterpenangkars', 'penangkarmitras'));
	}

	public function penangkar($id)
	{
		$module_name = 'Komitmen';
		$page_title = 'Penangkar';
		$page_heading = 'Penangkar Mitra';
		$heading_class = 'fa fa-file-invoice';

		$commitment = CommitmentBackdate::with('user')
			->where('user_id', Auth::id())
			->findOrFail($id);
		$masterpenangkars = MasterPenangkar::all();
		$commitmentbackdate = CommitmentBackdate::with('penangkarmitra.masterpenangkar')
			->findOrFail($id);
		$penangkarmitras = $commitmentbackdate->penangkarmitra;

		if (!$commitment->status) {
			$disabled = false; // input di-enable
		} else {
			$disabled = true; // input di-disable
		}

		return view('v2.commitment.penangkarmitra', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'commitment', 'masterpenangkars', 'penangkarmitras', 'commitmentbackdate', 'disabled'));
	}

	public function pksmitra($id)
	{
		$module_name = 'Komitmen';
		$page_title = 'Kerjasama';
		$page_heading = 'Perjanjian Kerjasama';
		$heading_class = 'fa fa-file-signature';

		$commitment = CommitmentBackdate::with(['user', 'pksmitra.masterkelompok'])
			->where('user_id', Auth::id())
			->findOrFail($id);

		if (!$commitment->status) {
			$disabled = false; // input di-enable
		} else {
			$disabled = true; // input di-disable
		}
		$masterkelompoks = MasterKelompok::all();
		// $commitmentbackdate = CommitmentBackdate::with('pksmitra.masterkelompok')
		// 	->findOrFail($id);
		$pksmitras = $commitment->pksmitra;

		return view('v2.commitment.pksmitra.create', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'commitment', 'masterkelompoks', 'pksmitras', 'disabled'));
	}

	//buat pengajuan verifikasi
	public function createpengajuan($id)
	{
		//load all commitments for current user
		$commitments = CommitmentBackdate::with(['user', 'pksmitra.anggotamitras'])
			->where('user_id', Auth::id())
			->findOrFail($id);

		if (!empty($commitments->status) && $commitments->status != 6) {
			return redirect()->route('admin.task.commitments.viewpengajuan', $commitments->id);
			$disabled = true;
		} else {
			$disabled = false; // input di-disable
		}


		$total_luastanam = $commitments->pksmitra->flatMap(function ($pm) {
			return $pm->anggotamitras;
		})->sum('luas_tanam');

		$total_volume = $commitments->pksmitra->flatMap(function ($pm) {
			return $pm->anggotamitras;
		})->sum('volume');

		$module_name = 'Komitmen';
		$page_title = 'Pengajuan Verifikasi';
		$page_heading = 'Pengajuan Verifikasi Realisasi';
		$heading_class = 'fal fa-file-invoice';

		return view('v2.pengajuanv2.create', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'commitments', 'total_luastanam', 'total_volume', 'disabled'));
	}

	public function viewpengajuan($id)
	{
		//load all commitments for current user
		$commitments = CommitmentBackdate::with(['user', 'pksmitra.anggotamitras'])
			->where('user_id', Auth::id())
			->findOrFail($id);

		if (!empty($commitments->status) && $commitments->status != 6) {
			$disabled = true; // input di-enable
		} else {
			$disabled = false; // input di-disable
		}

		$total_luastanam = $commitments->pksmitra->flatMap(function ($pm) {
			return $pm->anggotamitras;
		})->sum('luas_tanam');

		$total_volume = $commitments->pksmitra->flatMap(function ($pm) {
			return $pm->anggotamitras;
		})->sum('volume');

		$module_name = 'Komitmen';
		$page_title = 'Pengajuan Verifikasi';
		$page_heading = 'Pengajuan Verifikasi Realisasi';
		$heading_class = 'fal fa-file-invoice';

		return view('v2.pengajuanv2.create', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'commitments', 'total_luastanam', 'total_volume', 'disabled'));
	}

	//simpan pengajuan verifikasi
	public function storepengajuan($id, Request $request)
	{
		//validasi sebelum pengajuan di-submit
		$request->validate(
			[]
		);
		// Find commitment_backdate id
		$commitments = CommitmentBackdate::find($id);

		/**check status commitments->status:
		 * A. Cancel, yaitu
		 * 1. jika dalam tahap pengajuan verifikasi
		 * 2. jika dalam proses verifikasi
		 * 3. jika dalam proses pengajuan SKL
		 * 4. jika dalam proses verifikasi SKL
		 * 
		 * B. Lanjut, yaitu
		 * 1. jika pengajuan ulang baik verifikasi maupun SKL
		 */

		//create new pengajuan
		$pengajuan = new PengajuanV2();
		// get current month and year as 2-digit and 4-digit strings
		$month = date('m');
		$year = date('Y');
		// retrieve the latest record for the current month and year
		$latestRecord = PengajuanV2::where('no_pengajuan', 'like', "%/{$month}/{$year}")
			->orderBy('created_at', 'desc')
			->first();

		// get the current increment value for n
		$n = 1;
		if ($latestRecord) {
			$parts = explode('/', $latestRecord->no_pengajuan);
			$n = intval($parts[0]) + 1;
		}

		// mask the n part to always have 3 digits
		$nMasked = str_pad($n, 3, '0', STR_PAD_LEFT);

		// generate the new no_pengajuan value with timestamp and masked n
		$no_pengajuan = "{$nMasked}/PV." . time() . "/simethris/{$month}/{$year}";
		$pengajuan->no_pengajuan = $no_pengajuan;
		$pengajuan->status = '1';
		$pengajuan->commitmentbackdate_id = $commitments->id;
		// $pengajuan->jenis = 'verifikasi';
		$pengajuan->created_at = Carbon::now();

		$pengajuan->save();
		//set status pengajuan pada tabel commitment
		$commitments->status = '1'; //or 'verifikasi submitted'
		$commitments->pengajuan_id = $pengajuan->id;
		$commitments->save();

		$verifCommitment = new verif_commitment();
		$verifCommitment->pengajuan_id = $pengajuan->id;
		$verifCommitment->commitmentbackdate_id = $commitments->id;
		$verifCommitment->status = '1';
		$verifCommitment->verif_at = Carbon::now();
		$verifCommitment->save();

		return redirect()->route('admin.task.commitments.pengajuansuccess', $pengajuan->id)->with('success', 'Data Pengajuan submitted successfully');
	}

	//redirect sukses
	public function success($id)
	{
		$module_name = 'Komitmen';
		$page_title = 'Pengajuan Verifikasi';
		$page_heading = 'Pengajuan Verifikasi Realisasi';
		$heading_class = 'fal fa-file-invoice';

		$pengajuan = PengajuanV2::findOrFail($id);
		return view('v2.pengajuanv2.successaju', compact('module_name', 'page_title', 'page_heading', 'heading_class', 'pengajuan'));
	}

	//saat pengajuan ulang
	public function pengajuanulang($id, Request $request)
	{
		// Find commitment_backdate id

		$commitment = CommitmentBackdate::with('user')
			->where('user_id', Auth::id())
			->findOrFail($id);

		if ($commitment->status != 6) {
			return redirect()->back()->with('error', 'Halaman ini tidak dapat di akses!');
		}

		// Update pengajuanv2 status
		$pengajuan = PengajuanV2::where('no_pengajuan', $request->input('no_pengajuan'))
			->where('commitmentbackdate_id', $id)
			->firstOrFail();
		$pengajuan->status = '6';
		$pengajuan->save();

		// Update commitment_backdate
		$commitment->status = '6';
		$fileInputs = [
			'formRiph',
			'formSptjm',
			'logbook',
			'formRt',
			'formRta',
			'formRpo',
			'formLa'
		];
		$commitment_id = $commitment->id;
		$folder_name = "commitmentsv2/$commitment_id";

		foreach ($fileInputs as $fileInput) {
			if ($request->hasFile($fileInput)) {
				$file = $request->file($fileInput);
				$file_name = $fileInput . '_' . $commitment_id . '_' . date('Ymd') . '_' . time() . '.' . $file->getClientOriginalExtension();
				Storage::disk('public')->putFileAs("docs/$folder_name", $file, $file_name);
				$commitment->$fileInput = $file_name;
			}
		}

		$commitment->save();

		return redirect()->route('admin.task.commitments.show', $commitment->id)->with('success', 'Data Pengajuan submitted successfully');
	}

	public function destroy($id)
	{
		$commitments = CommitmentBackdate::withTrashed()
			->where('user_id', Auth::id())
			->findOrFail($id);
		$commitments->penangkarmitra()->delete(); //delete related object here
		$commitments->pengajuanv2()->delete(); //delete related object here
		$commitments->pksmitra()->delete();
		$commitments->delete();
		return redirect()->route('admin.task.commitments.index')->with('success', 'Data Commitment deleted successfully');
	}
}

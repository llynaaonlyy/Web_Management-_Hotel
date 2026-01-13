<?php

namespace App\Controllers;

class Home extends BaseController
{
    protected $akomodasiModel;
    protected $fotoModel;
    
    public function __construct()
    {
        $this->akomodasiModel = new \App\Models\AkomodasiModel();
        $this->fotoModel = new \App\Models\FotoAkomodasiModel();
    }
    
   public function index()
        {
            $tipe = $this->request->getGet('tipe');
            $keyword = $this->request->getGet('search');

            $builder = $this->akomodasiModel;

            // filter tipe
            if ($tipe && $tipe !== 'semua') {
                $builder->where('tipe', $tipe);
            }

            // filter keyword
            if ($keyword) {
                $akomodasi = $this->akomodasiModel->searchAkomodasi($keyword);
            } else {
                $akomodasi = $builder->findAll();
            }

            $data = [
                'akomodasi' => $akomodasi,
                'tipe_aktif' => $tipe ?? 'semua',
                'keyword' => $keyword ?? ''
            ];

            return view('/home', $data);
        }
    
    public function detail($id)
    {
        $akomodasi = $this->akomodasiModel->getDetailAkomodasi($id);
        
        if (!$akomodasi) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        
        $data = [
            'akomodasi' => $akomodasi,
            'foto' => $this->fotoModel->where('akomodasi_id', $id)->orderBy('urutan')->findAll(),
            'fasilitas' => (new \App\Models\FasilitasModel())->where('akomodasi_id', $id)->findAll(),
            'highlights' => (new \App\Models\HighlightModel())->where('akomodasi_id', $id)->findAll(),
            'kebijakan' => (new \App\Models\KebijakanModel())->where('akomodasi_id', $id)->first(),
            'tipe_kamar' => (new \App\Models\TipeKamarModel())->where('akomodasi_id', $id)->findAll()
        ];
        
        return view('detail', $data);
    }
    
    public function search()
    {
        $keyword = $this->request->getPost('keyword');
       return redirect()->to(uri: base_url('/home?search=' . urlencode($keyword)));
    }
}

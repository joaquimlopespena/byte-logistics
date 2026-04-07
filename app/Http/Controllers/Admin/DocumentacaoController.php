<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DocumentacaoController extends Controller
{
    public function index()
    {
        return view('admin.documentacao.index');
    }
}

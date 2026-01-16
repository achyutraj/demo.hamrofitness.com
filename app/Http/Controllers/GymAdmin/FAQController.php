<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faq;
use Illuminate\Support\Facades\App;
use DataTables;

class FAQController extends GymAdminBaseController
{
    public function __construct() {
        parent::__construct();
        $this->data['manageMenu'] = 'active';
        $this->data['faqsMenu'] = 'active';
        $this->data['title'] = "FAQs";

    }

    public function index()
    {
        $this->data['faqs'] = Faq::get();
        return view('gym-admin.faqs.index', $this->data);

    }

    public function ajaxData()
    {
        $faqs = Faq::withoutGlobalScopes()->get();
        return DataTables::of($faqs)
                ->addIndexColumn()
                ->editColumn('description',function($row){
                    return substr($row->description,0,50).'....';
                })
                ->addColumn('action', function($row){
                    $edit = route('admin.faqs.edit',$row->id) ;
                    $delete = route('admin.faqs.destroy',$row->id);
                    $actionBtn = '<div class="flex space-x-3 rtl:space-x-reverse">
                                    <a href="'.$edit.'" class="action-btn" type="button">
                                    <iconify-icon icon="heroicons:pencil-square"></iconify-icon>
                                    </a>
                                    <button class="action-btn deleteBtn" type="button" data-route="'.$delete.'">
                                    <iconify-icon icon="heroicons:trash"></iconify-icon>
                                    </button>
                                </div>';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);   
    }

    public function create()
    {
        return view('gym-admin.faqs.create',$this->data);
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'title' => 'required|string',
            'description' => 'required|string',
        ]);
        Faq::create([
            'title' => $request->get('title'),
            'description' => $request->get('description'),
        ]);
        return Reply::redirect(route('gym-admin.faqs.index'), "FAQs added successfully");
    }

    public function edit($id)
    {
        if ($this->data['user']->is_admin !== 1) {
            return App::abort(401);
        }
        $this->data['faq'] = Faq::findOrFail($id);
        return view('gym-admin.faqs.edit',$this->data);
    }

    public function update(Request $request, string $id)
    {
        $this->validate($request,[
            'title' => 'required|string'
        ]);
        $faq = Faq::findOrFail($id);
        $faq->update([
            'title' => $request->get('title'),
            'description' => $request->get('description'),
        ]);
        return Reply::redirect(route('gym-admin.faqs.index'), "FAQs updated successfully");
    }

    public function destroy($id)
    {
        if ($this->data['user']->is_admin !== 1) {
            return App::abort(401);
        }
        Faq::findOrFail($id)->delete();
        return Reply::redirect(route('gym-admin.faqs.index'), "FAQs deleted successfully");
    }
}

<?php

namespace App\Http\Controllers\AdminController;

use Illuminate\Http\Request;
use App\Category;
use App\User;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::orderBy('created_at' , 'desc')->get();
        return view('admin.categories.index' , compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request , [
            'ar_name'  => 'required|string',
            'en_name'  => 'required|string',
            'hi_name'  => 'required|string',
            'photo'    => 'required|mimes:jpeg,bmp,png,jpg|max:5000',

        ]);
        // create a new category
        $category= Category::create([
            'ar_name'            => $request->ar_name,
            'en_name'            => $request->en_name,
            'hi_name'            => $request->hi_name,
            'photo'              => $request->file('photo') == null ? null : UploadImage($request->file('photo'), 'photo', '/uploads/categories'),
            
        ]);       
        flash('تم أنشاء  القسم  بنجاح')->success();
        return redirect()->route('Category');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit' , compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request , [
            'ar_name'  => 'required|string',
            'en_name'  => 'required|string',
            'hi_name'  => 'required|string',
            'photo'    => 'sometimes|mimes:jpeg,bmp,png,jpg|max:5000',


        ]);
        // update  a  category
        $category = Category::findOrFail($id);

        $category->update([
            'ar_name'  => $request->ar_name == null ? $category->ar_name : $request->ar_name,
            'en_name'  => $request->en_name == null ? $category->en_name : $request->en_name,
            'hi_name'  => $request->hi_name == null ? $category->hi_name : $request->hi_name,
            'photo'              => $request->file('photo') == null ? $category->photo : UploadImage($request->file('photo'), 'photo', '/uploads/categories'),
            

        ]);
        flash('تم تعديل بيانات  القسم  بنجاح')->success();
        return redirect()->route('Category');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $users = User::whereCategory_id($id)->get();
        if ($users->count() > 0)
        {
            flash('لا يمكن  الحذف')->error();
            return redirect()->route('Category');
        }else{
            $city->delete();
            flash('تم الحذف  بنجاح')->success();
            return redirect()->route('Category');
        }
        
    }
}
